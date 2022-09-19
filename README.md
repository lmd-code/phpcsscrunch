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
$crunch->process(3); // see Methods below for explanation of arguments
```

#### File Paths/Names

- You must provide the full (absolute) server path to the source and output files, not the URIs.
- Source files must be standard CSS files (no SCSS/SASS/LESS etc) and have a '.css' extension.
- Source files must be properly formatted CSS (any formatting errors may cause issues).
- Output file must not start with a dot (".") and must have a '.css' extension.
- Output file *does not need* to exist yet (it will be created if the output directory is writable).
- The output directory path must exist (and be writable), you will need to create it manually if it does not.

### 3. Use the minified CSS file

You can link directly to the crunched/minified file in your HTML head section.

```html
<link href="/css/output.min.css" rel="stylesheet">
```

### 4. Remove on live site

Remember to remove the 'cruncher' code from the live version of your site.

## Methods

### process(*$strictness, $force = false, $nosave = false*)

This method processes the source files, but only if the most recently modified source file time is more recent than the last output saved (modified) time (or if `$force` is `true`, see method arguments below).

The `process` method accepts three arguments.

#### `$strictness` (*integer*) - optional

There are three levels of strictness indicated by an integer (1-3):

**`1` (Low)** - only unnecessary/excess whitespace removed (blank lines, multiple spaces/tabs, empty rulesets etc).

**`2` (Medium)** - most whitespace removed, but with each ruleset on a new line (including media queries/animation keyframes)

**`3` (High)** - almost zero whitespace, with only necessary whitespace remaining (e.g., between style values, such as margin declarations)

If an integer other than 1-3 is provided, it will default to `3` (high).

#### `$force` (*boolean*) - optional

Force the recreation of the output CSS file, ignoring any modified dates. Useful for when you want to change the strictness level but haven't modified any of the source files.

Defaults to `false`.

#### `$nosave` (*boolean*) - optional

Outputs the processed CSS as a string without saving it to the output file. Useful for checking output with committing to it (or for inline CSS).

When enabling this option, you need to echo/print the results.

```php
echo $crunch->process(3, false, true);
```

Defaults to `false`.

### minify(*$css, $strictness*)

The minification method itself can be called statically, useful if you just want to crunch some inline CSS code.

#### `$css` (*string*) - *required*

The CSS content string itself, not a file reference.

#### `$strictness` (*integer*) - *required*

The strictness level of minification (see `process()` method above). If an integer other than 1-3 is provided, it will default to `3` (high).

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
