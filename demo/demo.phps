<?php

/**
 * LMD Crunch CSS <https://github.com/lmd-code/lmdcrunchcss>
 *
 * This is a demo of the minification capabilities, and not a usage demo.
 * See example.phps for an actual usage demo.
 *
 * You can run this demo from your local copy of LmdCrunchCss
 *
 * 1. Rename this file to 'demo.php' (it's just a file extension change).
 *
 * 2. In your browser, go to:
 *    http://host.name/path/to/lmdcrunchcss/demo/demo.php
 *
 *    For example, in a dev environment:
 *    http://project.locahost/vendor/lmdcrunchcss/demo/demo.php
 */

use lmdcode\lmdcrunchcss\LmdCrunchCss;
include '../src/LmdCrunchCss.php';
$dirPath = '/' . trim(str_replace('\\', '/', dirname($_SERVER['PHP_SELF'])), '/');
$sourceFiles = [$dirPath . '/input-1.css', $dirPath . '/input-2.css', $dirPath . '/input-3.css',];
$minifiedFile = $dirPath . '/output.min.css';
$crunch = new LmdCrunchCss($sourceFiles, $minifiedFile, $_SERVER['DOCUMENT_ROOT']);
$css = $crunch->process(LmdCrunchCss::MINIFY_NONE)->toString(); // just need the combined CSS
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMD Crunch CSS Demo</title>
    <style type="text/css">
        html,*,::before,::after{box-sizing:border-box}
        html{line-height:1.4;font-size:16px;font-size-adjust:0.5;font-weight:400;text-size-adjust:none}
        body{background-color:#fff;color:#000;font-size:1rem}
        header,footer{text-align:center}
        main{width:100%;max-width:800px;margin:0 auto}
        h1{margin:1rem 0 0.5rem 0;font-size:2em}
        p{margin:1rem 0}
        textarea{width: 100%;max-width: 800px;tab-size:4;white-space: pre;overflow-wrap: normal;overflow-x: auto}
        @media screen and (min-width:640px){body{font-size:1.125rem}}
    </style>
</head>
<body>
<header>
    <h1>LMD Crunch CSS Demo</h1>
</header>
<main>
    <p>Demonstrating the different levels of minification.</p>

    <h2><label for="cssout0"><code>MINIFY_LEVEL_NONE (0)</code></label></h2>
    <p><textarea id="cssout0" cols="80" rows="15"><?php echo $css;?></textarea></p>

    <h2><label for="cssout1"><code>MINIFY_LEVEL_LOW (1)</code></label></h2>
    <p><textarea id="cssout1" cols="80" rows="15"><?php
        echo LmdCrunchCss::minify($css, LmdCrunchCss::MINIFY_LOW);
    ?></textarea></p>

    <h2><label for="cssout2"><code>MINIFY_LEVEL_MEDIUM (2)</code></label></h2>
    <p><textarea id="cssout2" cols="80" rows="15"><?php
        echo LmdCrunchCss::minify($css, LmdCrunchCss::MINIFY_MEDIUM);
    ?></textarea></p>

    <h2><label for="cssout3"><code>MINIFY_LEVEL_HIGH (3)</code></label></h2>
    <p><textarea id="cssout3" cols="80" rows="3"><?php
        echo LmdCrunchCss::minify($css, LmdCrunchCss::MINIFY_HIGH);
    ?></textarea></p>
</main>
<footer>
    <p>
        LMD Crunch CSS is licensed under the 
        <a href="https://github.com/lmd-code/lmdcrunchcss/blob/main/LICENSE">MIT License</a>.
    </p>
</footer>
</body>
</html>