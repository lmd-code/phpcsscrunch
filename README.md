# LMD Crunch CSS

Take an array of source files and combine them into a single minified CSS file with an optional level of "crunch" (minification).

## Usage

```php
$crunch = new \lmdcode\lmdcrunchcss\LmdCrunchCss(
    [
        '/path/to/css/input1.css',
        '/path/to/css/input2.css',
        '/path/to/css/input3.css',
    ],
    '/path/to/css/output.min.css'
);
$crunch->process(3);
```
