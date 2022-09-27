<?php

/**
 * LMD Crunch CSS
 * (c) LMD, 2022
 * https://github.com/lmd-code/lmdcrunchcss
 *
 * @version 2.0.1
 */

declare(strict_types=1);

namespace lmdcode\lmdcrunchcss;

/**
 * LMD Crunch CSS
 *
 * Take an array of source files and combine them into a single minified CSS file with an
 * optional minification level.
 *
 * The source CSS must be properly formatted.
 */
class LmdCrunchCss
{
    /**
     * No minification (only combines source files)
     */
    public const MINIFY_LEVEL_NONE = 0;

    /**
     * Low level minification (only excess whitespace removed)
     */
    public const MINIFY_LEVEL_LOW = 1;

    /**
     * Medium level minification (most whitespace removed)
     */
    public const MINIFY_LEVEL_MEDIUM = 2;

    /**
     * Highest level of minification (almost zero whitespace)
     */
    public const MINIFY_LEVEL_HIGH = 3;

    /**
     * An error occured
     * @var boolean
     */
    private $hasError = false;

    /**
     * Document root path
     * @var string
     */
    private $root = '';

    /**
     * List of source CSS files
     * @var string[]
     */
    private $srcFiles = [];

    /**
     * Output (minified) CSS file
     * @var string
     */
    private $outFile = '';

    /**
     * The specified output file already exists
     * @var boolean
     */
    private $outFileExists = false;

    /**
     * Modified timestamp of most recently modified source file
     * @var integer
     */
    private $srcLastModified = 0;

    /**
     * Modified timestamp of output file (if it exists)
     * @var integer
     */
    private $outLastModified = 0;

    /**
     * Raw/unminfied CSS from combined source files
     * @var string
     */
    private $rawCss = '';

    /**
     * Output CSS after processing
     * @var string
     */
    private $outCss = '';

    /**
     * A source file has been modified more recently than the output file
     * @var bool
     */
    private $updatedCss = false;

    /**
     * The last minification level applied
     * @var int
     */
    private $lastMinify = -1; // -1 if not yet applied to allow for level 0

    /**
     * Valid mime-types for CSS files
     * @var string[]
     */
    private static $validMimetypes = [
        'text/css',
        'text/plain'
    ];

    /**
     * Options for validating minification level
     * @var array
     */
    private static $filterOpts = [
        'options' => [
            'min_range' => self::MINIFY_LEVEL_NONE,
            'max_range' => self::MINIFY_LEVEL_HIGH
        ]
    ];

    /**
     * Constructor
     *
     * **Important:** the order in which you add source files to the array will be the order
     * in which they are added to the output file, so keep the *cascade* in mind!
     *
     * @param array  $srcFiles Paths (from document root) to CSS source files.
     *                         - You must provide absolute paths from the document root,
     *                           not the URI or file-system path. For example, for
     *                           `http://example.com/assets/style.css` use `/assets/mystyle.css`.
     *                         - Files must have a `.css` extension.
     *                         - Files must be properly formatted standard CSS (no SCSS/SASS/LESS).
     *
     * @param string $outFile  Path (from document root) to CSS output file.
     *                         - You must provide absolute paths (see `$srcFiles` above).
     *                         - The directory must already exist (and be writable).
     *                         - The file must have a `.css` extension.
     *                         - The file does not need to exist yet (it will be created).
     *
     * @param string $docRoot  Absolute file-system path to the document root (for example,
     *                         `http://example.com/` might be `/home/site/public_html`).
     *                         Most often just providing `$_SERVER['DOCUMENT_ROOT']` works.
     *
     * @return void;
     */
    public function __construct(array $srcFiles, string $outFile, string $docRoot)
    {
        try {
            // Validate document root path
            $docRoot = self::normalisePath($docRoot);
            if ($docRoot === '' || !is_dir($docRoot)) {
                throw new \Exception('The provided document root was either not provided or does not appear to exist.');
            }

            $this->root = $docRoot;

            // Source Files
            if (!is_array($srcFiles) || count($srcFiles) < 1) {
                throw new \Exception('Array of source files is empty or invalid.');
            }

            $srcFiles = array_unique($srcFiles); // remove duplicates

            $srcInvalid = []; // capture any missing or invalid (not CSS) files

            foreach ($srcFiles as &$srcFile) {
                // normalise and enforce leading forward slash
                $srcFile = '/' . ltrim(self::normalisePath($srcFile), '/');
                $absPath = $this->root . $srcFile; // absolute path to file

                $spl = new \SplFileInfo($absPath);

                $hasCssExtn = $spl->getExtension() === 'css';

                if ($spl->isReadable()) {
                    if (
                        $hasCssExtn
                        && $spl->getType() === 'file'
                        && in_array(mime_content_type($absPath), self::$validMimetypes)
                    ) {
                        // Find the most recently modified source file
                        // (used to determine whether to run the processor)
                        $modified = $spl->getMTime();
                        if ($modified > $this->srcLastModified) {
                            $this->srcLastModified = $modified;
                        }

                        $this->srcFiles[] = $srcFile;
                    } else {
                        $srcInvalid[] = 'Invalid: ' . $srcFile;
                    }
                } else {
                    $srcInvalid[] = ($hasCssExtn ? 'Not Found' : 'Invalid') . ': ' . $srcFile;
                }
            }

            // Throw an exception if any of the source files do not exist or are invalid.
            if (count($srcInvalid) > 0) {
                throw new \Exception(
                    'One or more source files could not be found/opened or are otherwise invalid,
                    please check that the following paths/filenames are correct<br>- '
                        . implode('<br>- ', $srcInvalid)
                );
            }

            // Output File
            // normalise and enforce leading forward slash
            $outFile = '/' . ltrim(self::normalisePath($outFile), '/');
            $absOut = $this->root . $outFile; // absolute path to file
            $pathInfo = pathinfo($absOut); // break into path components

            // Do not overwrite a source file!
            if (in_array($outFile, $srcFiles)) {
                throw new \Exception('Output file location matches a source file location.');
            }

            // Check if provided directory exists
            if (!is_dir($pathInfo['dirname'])) {
                throw new \Exception(
                    'The provided output directory path does not exist, please create it:<br>'
                    . $pathInfo['dirname']
                );
            }

            // Trim output file name
            $outFileName = isset($pathInfo['filename']) ? trim($pathInfo['filename']) : '';

            // Output file name is required
            if ($outFileName === '') {
                throw new \Exception('The output filename was not provided.');
            }

            // Output file must have 'css' extension
            if (!isset($pathInfo['extension']) || $pathInfo['extension'] !== 'css') {
                throw new \Exception('The output file must have a "css" extension.');
            }

            // If it is an existing output file, get its modified time for later comparison
            if (file_exists($absOut)) {
                $this->outLastModified = filemtime($absOut);
                $this->outFileExists = true;
            }

            $this->outFile = $outFile;
        } catch (\Exception $e) {
            $this->hasError = true;
            self::error($e->getMessage());
        }
    }

    /**
     * Process CSS source files
     *
     * Only processes files if:
     * - No source files have been processed and no output file has been saved.
     * - The most recently modified source file is fresher than the saved output file.
     * - If `$level` is different to the last applied minification level.
     * - If `$force` is `true`.
     *
     * @param int  $level  Minification level 0-3 (default: 0).
     *                     - 0 (None) - combines multiple source files without minification.
     *                     - 1 (Low) - only unnecessary/excess whitespace removed (blank lines,
     *                       multiple spaces/tabs, empty rulesets etc).
     *                     - 2 (Medium) - most whitespace removed, but with each ruleset on a new
     *                       line (including media queries/animation keyframes).
     *                     - 3 (High) - almost zero whitespace, with only necessary whitespace
     *                       remaining (e.g., between style values, such as margin declarations).
     *
     * @param bool $force  Force the recreation of minified CSS output from the source files even
     *                     when it would not otherwise.
     *
     * @return self
     */
    public function process(int $level = 0, bool $force = false): self
    {
        if (!$this->hasError) {
            // Enforce valid minification level
            if (filter_var($level, FILTER_VALIDATE_INT, self::$filterOpts) === false) {
                $level = self::MINIFY_LEVEL_NONE; // default
            }

            // Get output CSS content if it exists and is more recent than the source files.
            // We do this here instead of in the constructor method in case a file is saved
            // before a call to process() -- e.g. for output at different minification levels.
            if ($this->outFileExists && $this->outLastModified > $this->srcLastModified) {
                $this->outCss = $this->readFile($this->outFile);
                // Get minification level from file (last line comment)
                if (preg_match('/\/\*(?<level>\d)\*\/$/', $this->outCss, $match) === 1) {
                    $this->lastMinify = intval($match['level']);
                }
            }

            // Determine whether to run the minification process
            if ($force || $this->outCss === '' || $level !== $this->lastMinify) {
                // We only need to read the source files if we haven't already done so
                if ($this->rawCss === '') {
                    $combinedCSS = "";
                    foreach ($this->srcFiles as $srcFile) {
                        $srcCSS = $this->readFile($srcFile);
                        $combinedCSS .= trim($srcCSS) . "\n\n";
                    }
                    $this->rawCss = $combinedCSS;
                }

                $this->outCss = self::minify($this->rawCss, $level);
                $this->updatedCss = true;
            }
        }

        $this->lastMinify = $level;

        return $this;
    }

    /**
     * Save output CSS to output file.
     *
     * Will only save to file if the source files generated fresh output.
     *
     * Returns the output file path (from document root, as provided)
     *
     * @return string
     */
    public function toFile(): string
    {
        if ($this->hasError) {
            return ''; // stop if there was an error
        }

        // Only save file if source was updated
        if ($this->updatedCss && $this->outCss !== '') {
            $this->saveFile($this->outCss);
            $this->outLastModified = filemtime($this->root . $this->outFile); // update
            $this->outFileExists = true;
        }

        return $this->outFile;
    }

    /**
     * Return output CSS as a string
     *
     * @return string
     */
    public function toString(): string
    {
        return $this->outCss;
    }

    /**
     * Minify CSS source
     *
     * @param string $css   CSS source to minify
     * @param int    $level Minificaiton level  (@see `process()` method)
     *
     * @return string
     */
    public static function minify(string $css, int $level): string
    {
        if (($css = trim($css)) === '') {
            return ''; // no CSS was provided
        }

        // Validate $level - if param is invalid or MINIFY_LEVEL_NONE, return original string
        if (
            filter_var($level, FILTER_VALIDATE_INT, self::$filterOpts) === false
            || $level === self::MINIFY_LEVEL_NONE
        ) {
            return $css . "/*" . self::MINIFY_LEVEL_NONE . "*/";
        }

        // Variable length space character - only include when $level is low
        $vs = $level > self::MINIFY_LEVEL_LOW ? '' : ' ';

        // Strip unnecessary stuff
        $css = preg_replace("/\/\*.*?\*\//s", "", $css); // strip comments
        $css = preg_replace("/(^|}+)[^{]+{\s*}/s", "\\1", $css); // strip empty rulesets
        $css = preg_replace("/\R+/su", "", $css); // strip all vertical whitespace
        $css = preg_replace("/\h+/s", " ", $css); // reduce/normalise horizontal whitespace
        $css = preg_replace("/ ?(\{|\}|;) ?/s", "\\1", $css); // strip whitespace around braces and semi-colons

        if ($level > self::MINIFY_LEVEL_LOW) {
            $css = str_replace(";}", "}", $css); // strip semi-colon from before closing brace
        } else {
            $css = preg_replace("/(?<!;|\})\}/s", ";}", $css); // insert missing semi-colon before closing brace
        }

        // Insert newline after single at-rules
        $css = preg_replace("/(@(charset|import|namespace)[^;]+;)/s", "\\1\n", $css);

        // Insert newline after closing braces
        $css = str_replace("}", "}\n", $css);

        // Insert newlines after opening braces of nested rulesets
        $css = preg_replace("/(?<={)(.+?\{)/m", "\n\\1", $css);

        /***
         * Iterate over CSS as an array
         */
        $lines = explode("\n", trim($css));

        $depth = 0; // nested ruleset depth counter
        $css = ""; // reset CSS string

        foreach ($lines as $line) {
            $line = trim($line);
            $line_beg = substr($line, 0, 1); // first character
            $line_end = substr($line, -1, 1); // last character

            // If the line starts with a closing brace, we are exiting a nested ruleset
            if ($line_beg === "}") {
                $depth--;
            }

            // Indent string content depends on nested ruleset depth
            $indent = str_repeat("\t", $depth);

            // Insert indent at medium/low level only
            $css .= ($level < self::MINIFY_LEVEL_HIGH) ? $indent : "";

            if ($line_end === ";" || $line_beg === "}") {
                // self-contained line (e.g. @import) or closing brace
                $css .= $line;
            } elseif ($line_end === "{") {
                // opening brace, entering nested ruleset
                $line = rtrim($line, "{"); // trim opening brace temporarily

                // Normalise spaces around conditionals
                $line = preg_replace('/\s?(\([^:\s]+)\s*:\s*([^)]+\))\s?/s', " \\1:$vs\\2 ", $line);

                $css .= trim($line) . $vs . "{"; // add opening brace back

                $depth++; // increment indentation
            } else {
                // self-contained ruleset

                // Newline/indent ruleset declarations
                $indent_dec = ($level < self::MINIFY_LEVEL_MEDIUM) ? "\n" . str_repeat("\t", $depth + 1) : "";

                // Get separate parts (selectors {declarations}), minus braces
                preg_match("/^(?<sels>[^{]+)\{(?<decs>[^}]+)\}$/", $line, $matches);

                if (isset($matches['sels']) && isset($matches['decs'])) {
                    // Selectors - normalise space around commas
                    $sels = preg_replace("/\h?,\h?/s", ",$vs", $matches['sels']);

                    // Declarations
                    // - normalise space around colons and commas
                    $decs = preg_replace("/\h?([:,])\h?/", "\\1$vs", $matches['decs']);
                    // - remove space around semi-colons and insert indentation
                    $decs = preg_replace("/\h?;\h?/s", ";$indent_dec", $decs);

                    // Build ruleset
                    $css .= trim($sels) . $vs . "{" . $indent_dec . trim($decs)
                    . (($level < self::MINIFY_LEVEL_MEDIUM) ? "\n" . $indent : "")
                    . "}";
                }
            }

            // Insert newline at medium/low level only
            $css .= ($level < self::MINIFY_LEVEL_HIGH) ? "\n" : "";
        }

        // Append minification level (is used to identify level when output file is read)
        $css .= "/*" . $level . "*/";

        return trim($css);
    }

    /**
     * Read a CSS file and return its contents
     *
     * @param string $file Full path to file
     *
     * @return string
     */
    private function readFile(string $file): string
    {
        try {
            if (!$css = @file_get_contents($this->root . $file)) {
                throw new \Exception('Could not open the file. ' . $file);
            }
            return $css;
        } catch (\Exception $e) {
            self::error($e->getMessage());
        }
    }

    /**
     * Save CSS content to the output file
     *
     * @param string $css Content to save
     *
     * @return void
     */
    private function saveFile(string $css): void
    {
        try {
            if (!@file_put_contents($this->root . $this->outFile, $css)) {
                throw new \Exception('Could not save the output CSS file.');
            }
        } catch (\Exception $e) {
            self::error($e->getMessage());
        }
    }

    /**
     * Normalise path separators - make back slashes into forward slashes
     *
     * @param string  $path The path to normalise.
     *
     * @return string
     */
    private static function normalisePath(string $path): string
    {
        return rtrim(str_replace('\\', '/', $path), '/');
    }

    /**
     * Custom error output
     *
     * Outputs directly to screen.
     *
     * @param string $msg The error message
     *
     * @return void
     */
    private static function error(string $msg): void
    {
        echo '<p><strong>' . __CLASS__ . ' Error:</strong> ' . $msg . '</p>';
    }
}
