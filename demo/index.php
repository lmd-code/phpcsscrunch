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

// Save file with maximum minification and get filename
$cssFile = $crunch->process(3)->toFile();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMD Crunch CSS Demo</title>
    <style type="text/css">.wrapper{width: 100%;max-width:800px;margin: 0 auto}textarea{width: 100%;max-width: 800px;tab-size:4;white-space: pre;overflow-wrap: normal;overflow-x: scroll}</style>
</head>
<body>
<div class="wrapper">
    <h1>LMD Crunch CSS Demo</h1>

    <h2>CSS File (set to <code>$level = 3</code>):</h2>
    <p>Go to: <a href="<?=$cssFile?>"><?=$cssFile?></a></p>

    <!-- String outputs with different minification levels applied -->

    <h2><code>$level = 0</code></h2>
    <p><textarea id="cssout0" cols="80" rows="15"><?=$crunch->process(0)->toString()?></textarea></p>

    <h2><code>$level = 1</code></h2>
    <p><textarea id="cssout1" cols="80" rows="15"><?=$crunch->process(1)->toString()?></textarea></p>

    <h2><code>$level = 2</code></h2>
    <p><textarea id="cssout2" cols="80" rows="15"><?=$crunch->process(2)->toString()?></textarea></p>

    <h2><code>$level = 3</code></h2>
    <p><textarea id="cssout3" cols="80" rows="5"><?=$crunch->process(3)->toString()?></textarea></p>
</div>
</body>
</html>