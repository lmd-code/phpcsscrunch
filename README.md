# LMD Crunch CSS

Take an array of source files and combine them into a single minified CSS file *on-the-fly* with an optional level of "crunch" (minification).

## How does it work?

Include the `LmdCrunchCss` code somewhere in your header (before HTML output) and tell it what your source files are and what the output file is.

Add an output method to your template (inline CSS or stylesheet &lt;link&gt; markup).

Then, when make a change in one of your source files and reload the page, it will automatically re-minify the result.

> The optional `$devMode` setting allows you to still link to your separate source files while in development (so you can easily locate the source of problems) - the minification process still works in the background.

## Who should use this?

- Devs who build sites and without using a CSS preprocessor any framework with inbuilt CSS processing.
- Devs who keep their CSS compartmentalised into separate files on the development site, but need them combined on the live site.
- Devs who like maintenance between 'dev' and 'live' sites to be as easy as possible (changing one line is easier than changing several!).

## Installation / Usage

**Minimum PHP Version:** 7.4

1. Download the latest release and unpack the `lmdcrunchcss` folder to you dev project.
2. Include the necessary code somewhere before your site content (or at least your stylesheet) outputs to screen. (see [Initialise](#initialise))
3. Output the result of the minification to your template, either as a link to a minified file, or directly as inline CSS (see [Methods](#methods)).

For a fully working example, see the `demo/example.phps` file.

## Initialise

### `new LmdCrunchCss($srcFiles, $outFile, $docRoot, $devMode = false)`

The class has three required parameters and one optional parameter.

#### `$srcFiles` *&lt;array&gt;* - *required*

Paths (from document root) to CSS source files.

- You must provide absolute paths from the document root, not the URI or file-system path.
  For example, for `http://example.com/assets/style.css` use `/assets/mystyle.css`.
- Files must have a `.css` extension.
- Files must be properly formatted standard CSS (no SCSS/SASS/LESS).

**Important:** the order in which you add source files to the array will be the order in which they are added to the output file, so keep the *cascade* in mind!

#### `$outFile` *&lt;string&gt;* - *required*

Path (from document root) to CSS output file.

- You must provide absolute paths (see `$srcFiles` above).
- The directory must already exist (and be writable).
- The file must have a `.css` extension.
- The file does not need to exist yet (it will be created).

#### `$docRoot` *&lt;string&gt;* - *required*

Absolute file-system path to the document root (for example, `http://example.com/` might be `/home/site/public_html`). Most often just providing `$_SERVER['DOCUMENT_ROOT']` works.

#### `$devMode` *&lt;boolean&gt;* - *optional*

Enable development mode. Defaults to `false`.

#### Example 1

**Note:** Setup variables used in this example will be used in all method examples.

```php
use lmdcode\lmdcrunchcss\LmdCrunchCss;

// Include the class (unless using an autoloader)
include '/path/to/vendor/lmdcrunchcss/src/LmdCrunchCss.php';

/*** Constructor parameter values */

// Source files - will be combined in array order!
$sourceFiles = ['/assets/fileA.css', '/assets/fileB.css', '/assets/fileC.css'];

// Output file for minified CSS
$outputFile = '/assets/crunched.min.css';

// Development mode - however you determine this
$devMode = false; // if never using devMode, you can omit this param

/**
 * Initialise class with param values
 * @var LmdCrunchCss $crunch
 */
$crunch = new LmdCrunchCss($sourceFiles, $outputFile, $_SERVER['DOCUMENT_ROOT'], $devMode);
```

## Methods

### `process($level = 0, $force = false)`

This method processes the source files if:

- No source files have been processed and no output file has yet been saved.
- The most recently modified source file is newer/fresher than the previously saved output file.
- The `$level` param  value is different to the last applied minification level.
- The `$force` param value is `true`.

**Returns:** *self*.

The method accepts two parameters.

#### `$level` *&lt;integer&gt;* - *optional*

Use an integer or one of the minify constants:

- **`0` / `MINIFY_NONE`** - combines multiple source files into one without any minification. (*Default*)
- **`1` / `MINIFY_LOW`** - only unnecessary/excess whitespace removed (blank lines, multiple spaces/tabs, empty rulesets etc).
- **`2` / `MINIFY_MEDIUM`** - most whitespace removed, but with each ruleset on a new line (including media queries/animation keyframes)
- **`3` / `MINIFY_HIGH`** - almost zero whitespace, with only necessary whitespace remaining (e.g., between style values, such as margin declarations)

Defaults to `0` / `MINIFY_NONE` if an out of range integer is provided.

#### `$force` *&lt;boolean&gt;* - *optional*

Force the recreation of minified CSS output from the source files even when it would not otherwise.

Defaults to `false`.

#### Example 2

```php
$crunch->process(LmdCrunchCss::MINIFY_HIGH, true);

// -- or --

$crunch->process(3, true); // produces the same as above
```

### `toString()`

Returns the minified CSS string without saving it to a file.

The string is the result of the last `process()` call. The method can be chained to the `process()` method.

Calling `toString()` before any `process()` method has been run results in a blank string.

**Returns:** *string*.

#### Example 3

```php
// Process CSS
$crunch->process(LmdCrunchCss::MINIFY_HIGH);

$css1 = $crunch->toString(); // return result of process()

// Chained method (note, each call to 'process' replaces any previous call)
$css2 = $crunch->process(LmdCrunchCss::MINIFY_LOW)->toString();
```

### `toFile($cacheBuster = false)`

Save the minified CSS to the output file and returns the stylesheet `<link>` markup, the content of which depends on whether development mode was enabled at set-up or not:

- **Enabled:** returns the source files, saving the minified file in the background.
- **Not enabled:** returns the saved minified file (default).

Only saves the file if the minified source has changed.

The CSS saved is the result of the last `process()` call. The method can be chained to the `process()` method.

Calling `toFile()` before any `process()` results in a blank string.

**Returns:** *string*.

The method accepts one parameter.

#### `$cacheBuster` *&lt;boolean&gt;* - *optional*

Add a cache buster (a timestsamp) to the markup. Defaults to false.

#### Example 4.1: `$devMode` not enabled (default)

```php
// No $devMode param set (false by default)
$crunchLive = new LmdCrunchCss($sourceFiles, $outputFile, $_SERVER['DOCUMENT_ROOT']);

$outputLive = $crunch->process(LmdCrunchCss::MINIFY_HIGH)->toFile(true); // cache buster enabled
```

Returns markup (*"t=000..." is the cache buster*):

```html
<link href="/assets/crunched.min.css?t=0000000000" rel="stylesheet">
```

#### Example 4.2: `$devMode` enabled

```php
 // $devMode param set to true
$crunchDev = new LmdCrunchCss($sourceFiles, $outputFile, $_SERVER['DOCUMENT_ROOT'], true);

$outputDev = $crunch->process(LmdCrunchCss::MINIFY_HIGH)->toFile(); // no cache buster
```

Returns markup:

```html
<link href="/assets/fileA.css" rel="stylesheet">
<link href="/assets/fileB.css" rel="stylesheet">
<link href="/assets/fileC.css" rel="stylesheet">
```

See [Advanced](#advanced) for how to change the stylesheet link markup.

### `minify($css, $level)`

The minification method itself can be called statically, useful if you just want to crunch some inline CSS code.

**Returns:** *string*.

The method accepts two parameters.

#### `$css` *&lt;string&gt;* - *required*

The CSS content string itself, not a file reference.

#### `$level` *&lt;integer&gt;* - *required*

The minification level (see `process()` method above).

Defaults to `0` / `MINIFY_NONE` if an out of range integer is provided.

#### Example 5

```php
$css = "
  body {
      background: black;
      color: white;
  }
  p {
      margin: 2rem 0;
  }
";

echo LmdCrunchCss::minify($css, LmdCrunchCss::MINIFY_HIGH);
```

Results in:

```css
body{background:black;color:white}p{margin:2rem 0}
```

## Advanced

You can modify the stylesheet link markup returned by `toFile()` by extending the class and writing your own `getMarkup()` method.

### `getMarkup($styles, $cacheBuster = false)`

Generates markup from a list of stylesheet files.

**Returns:** *string*.

The method must accept the following two parameters:

#### `$styles` *&lt;array&gt;*

An array of paths to stylesheets (will either be source files or a single minified output file).

#### `$cacheBuster` *&lt;boolean&gt;*

Whether to add a cache buster to the output.

### Example extended class

An example class extension, along with an example usage, is provided in `demo/ExtendLmdCrunchCss.phps` and `demo/extend-example.phps`.

## Demo

A demonstration of the different minification levels is available.

<https://lmd-code.github.io/lmdcrunchcss/>

The code that generated the demo is provided in `demo/demo.phps`, which can be run once LMD Crunch CSS is installed.
