<?php
/**
 * You can run this demo from your local copy of LmdCrunchCSS
 * 
 * In your browser, go to:
 * http://host.name/path/to/lmdcrunchcss/demo/
 * 
 * E.g. in a dev environment:
 * http://project.locahost/vendor/lmdcrunchcss/demo/
 */
include '../LmdCrunchCss.php';

use \lmdcode\lmdcrunchcss\LmdCrunchCss;

$dir = rtrim(str_replace('\\', '/', __DIR__), '/'); // current directory path

$sourceFiles = [
    $dir . '/css-input-1.css',
    $dir . '/css-input-2.css',
    $dir . '/css-input-3.css',
];

$crunch = new LmdCrunchCss($sourceFiles,  $dir . '/css-output.min.css');

// Save output to file with default strictness (3)
//$crunch->process();

// Get returned string with minimum strictness, without saving to file
$css = $crunch->process(1, false, true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMD Crunch CSS Demo</title>
    <style type="text/css">.wrapper{width: 100%;max-width:800px;margin: 0 auto}p{text-align: center}textarea{width: 100%;max-width: 800px;tab-size:4;white-space: pre;overflow-wrap: normal;overflow-x: scroll}</style>
</head>
<body>
<div class="wrapper">
    <h1>LMD Crunch CSS Demo</h1>

    <h2><code>$strictness = 1</code></h2>
    <p><textarea id="cssout1" cols="80" rows="15"><?=$css?></textarea></p>

    <!-- For the next two examples, we are statically calling the minify method directly on the already partially crunched output -->

    <h2><code>$strictness = 2</code></h2>
    <p><textarea id="cssout2" cols="80" rows="15"><?=LmdCrunchCss::minify($css, 2)?></textarea></p>

    <h2><code>$strictness = 3</code></h2>
    <p><textarea id="cssout3" cols="80" rows="5"><?=LmdCrunchCss::minify($css, 3)?></textarea></p>
</div>
</body>
</html>