# LMD Crunch CSS

Take an array of source files and combine them into a single minified CSS file with an optional level of "crunch" (minification).

## Usage

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
$crunch->process(3);
```

#### File Paths/Names
- You must provide the full (absolute) server path to the source and output files, not the URIs.
- Source files must be CSS files (no 'scss'/'sass' etc) and have a '.css' extension.
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

## Minification Options

The `LmdCrunchCss::process()` method accepts three arguments.

### `$strictness` (integer) - *required*

There are three levels of strictness, from 1 to 3.

**1. Low** - only unnecessary/excess whitespace removed (blank lines, multiple spaces/tabs, empty rulesets etc).

**2. Medium** - most whitespace removed, but with each ruleset on a new line (including media queries/animation keyframes)

**3. High** - almost zero whitespace, with only neccessary whitespace remaining (e.g., between style values, such as margin declarations)

If any other integer is provided, it will default to `3` (high).

### `$force` (boolean) - optional

Force the recreation of the output CSS file, ignoring any modified dates. Useful for when you want to change the strictness level but havenvt modified any of the source files.

Defaults to `false`.

### `$nosave` (boolean) - optional

Outputs the processed CSS as a string without saving it to the output file. Useful for checking output with committing to it (or for inline CSS).

When enabling this option, you need to echo/print the results.

```php
echo $crunch->process(3, false, true);
```

Defaults to `false`.
