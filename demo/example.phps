<?php

/**
 * LMD Crunch CSS
 * (c) LMD, 2022
 * https://github.com/lmd-code/lmdcrunchcss
 *
 * This is an example/demo of how to use LMD Crunch CSS.
 * 
 * You can run this demo from your local copy of LmdCrunchCss
 *
 * 1. Rename the file from 'example.phps' => 'example.php'
 *
 * 2. In your browser, go to:
 *    http://host.name/path/to/lmdcrunchcss/demo/example.php
 *
 *    For example, in a dev environment:
 *    http://project.locahost/vendor/lmdcrunchcss/demo/example.php
 *
 * In your own project, the following PHP code would probably go in a header/init file.
 */

use lmdcode\lmdcrunchcss\LmdCrunchCss;

include '../LmdCrunchCss.php'; // include LmdCrunchCss

$devMode = false; // however you determine dev mode

// Path to current directory from document root
$dirPath = LmdCrunchCss::normalisePath(dirname($_SERVER['PHP_SELF']), true); 

// Source files
$sourceFiles = [
    $dirPath . '/css-input-1.css',
    $dirPath . '/css-input-2.css',
    $dirPath . '/css-input-3.css',
];

// Output file
$minifiedFile = $dirPath . '/css-output.min.css';

// Init LmdCrunchCss
$crunch = new LmdCrunchCss($sourceFiles, $minifiedFile, $_SERVER['DOCUMENT_ROOT'], $devMode);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMD Crunch CSS Dev Mode Demo</title>
    <?php echo $crunch->process(LmdCrunchCss::MINIFY_HIGH)->toFile(true); ?>
</head>
<body id="top">
<header>
    <h1>LMD Crunch CSS Dev Mode Demo</h1>
</header>
<main>
    <p>Make some changes to the style sheets and reload the page. Then change <code class="highlight">$devMode</code> to "false" and reload again. Your changes should be reflected in the generate minified file.</p>
    
    <div class="container">
        <div class="item">
            <p>Dolor sea dolores dolores accusam justo. Sed sea eos est sit eirmod amet rebum aliquyam lorem. Gubergren dolore et dolores et invidunt accusam stet voluptua, magna kasd voluptua sed eos voluptua dolore, clita aliquyam dolores sit justo consetetur. Et no at ipsum dolor rebum nonumy. Lorem consetetur takimata eirmod justo.</p>
        </div>
        <div class="item">
            <p>Sed eirmod ipsum diam ipsum nonumy ipsum, stet et clita consetetur ea sit stet, clita sed et ipsum duo stet. Amet ipsum tempor sit sanctus, rebum takimata tempor ipsum et. Sed rebum justo et sit et accusam at dolor. Et diam est erat sea nonumy duo nonumy et, diam ea et at accusam lorem dolor gubergren sadipscing.</p>
        </div>
    </div>
    
    <p><a href="#top">Test link</a></p>
</main> 
<footer>
    <p>
        LMD Crunch CSS is licensed under the 
        <a href="https://github.com/lmd-code/lmdcrunchcss/blob/main/LICENSE">MIT License</a>.
    </p>
</footer>
</body>
</html>