# LMD Crunch CSS

Take an array of source files and combine them into a single minified CSS file with an optional level of "crunch" (minification).

## Installation / Usage

### 1. Download release

Download the latest release and unpack the `lmdcrunchcss` folder to you dev project.

### 2. Add 'cruncher' code

Insert the following code somewhere before your site content (or at least your stylesheet) outputs to screen.

```php
require '/path/to/lmdcrunchcss/LmdCrunchCss.php';

$crunch = new \lmdcode\lmdcrunchcss\LmdCrunchCss(
    [
        '/full/path/to/css/input1.css',
        '/full/path/to/css/input2.css',
        '/full/path/to/css/input3.css',
    ],
    '/full/path/to/css/output.min.css'
);
```

**Important:** the order in which you add source files to the array will be the order in which they are added to the output file, so keep the *cascade* in mind!

#### File Paths/Names

- You must provide the full (absolute) server path to the source and output files, not the URIs.
- Source files must be standard CSS files (no SCSS/SASS/LESS etc) and have a '.css' extension.
- Source files must be properly formatted CSS (any formatting errors may cause issues).
- Output file must not start with a dot (".") and must have a '.css' extension.
- Output file *does not need* to exist yet (it will be created if the output directory is writable).
- The output directory path must exist (and be writable), you will need to create it manually if it does not.

### 3. Use the minified CSS

See [Methods](#Methods) below for explanation of parameters.

```php
// Chain the methods
$cssFile = $crunch->process(3)->toFile(); // Save output file, get filename

$cssCode = $crunch->process(3)->toString(); // Get output string

// Without chaining toFile/toString will operate on the same process result
$crunch->process(3);

$cssFile = $crunch->toFile();
$cssCode = $crunch->toString();
```

Unless you are using the results in multiple places, it is easiest to just save and link to the minified file in your HTML head section.

```html
<link href="/css/<?=$crunch->process(3)->toFile()?>" rel="stylesheet">
```

Or using the output string.

```html
<style type="text/css">
<?=$crunch->process(3)->toString()?>
</style>
```

### 4. Remove on live site

It is highly recommended that you only use this minifier in development and that you use the saved minified output file on your live site.

## Methods

### `process($level = 0, $force = false)`

This method processes the source files if:

- No source files have been processed and no output file has been saved.
- The most recently modified source file is fresher than the saved output file.
- If `$level` is different to the last applied minification level.
- If `$force` is `true` (see method parameters below).

**Returns:** self.

The method accepts two parameters.

#### `$level` (*integer*) - optional

Minification levels are indicated by an integer (0-3):

**`0` (None)** - combines multiple source files into one without any minification. **Default**

**`1` (Low)** - only unnecessary/excess whitespace removed (blank lines, multiple spaces/tabs, empty rulesets etc).

**`2` (Medium)** - most whitespace removed, but with each ruleset on a new line (including media queries/animation keyframes)

**`3` (High)** - almost zero whitespace, with only necessary whitespace remaining (e.g., between style values, such as margin declarations)

If an integer other than 0-3 is provided, it will default to `0` (none).

#### `$force` (*boolean*) - optional

Force the recreation of minified CSS output from the source files even when it would not otherwise.

Defaults to `false`.

### `toFile()`

Save the minified CSS to the output file. Only saves the file if the minified source has changed.

When chained to the `process()` method it saves the result of that method, otherwise when called directly it saves the result of the last `process()` call.

The method returns the basename (filename without path) of the output file.

### `toString()`

Returns the minified CSS string.

When chained to the `process()` method it returns the result of that method, otherwise when called directly it returns the result of the last `process()` call.

### `minify($css, $level)`

The minification method itself can be called statically, useful if you just want to crunch some inline CSS code.

#### `$css` (*string*) - *required*

The CSS content string itself, not a file reference.

#### `$level` (*integer*) - *required*

The minification level (see `process()` method above). If an integer other than 0-3 is provided, it will default to `0` (none).

#### Example

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
echo LmdCrunchCss::minify($css, 3);
```

Results in:

```css
body{background:black;color:white}p{margin:2rem 0}
```

## Demo

A demonstration (with code) is provided in the `demo` folder, which can be run once LMD Crunch CSS is installed.
