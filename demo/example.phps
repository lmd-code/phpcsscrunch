<?php

/**
 * PHP CSS Crunch <https://github.com/lmd-code/phpcsscrunch>
 *
 * This is an example/demo of how to use PHP CSS Crunch.
 * 
 * You can run this demo from your local copy of PhpCssCrunch
 *
 * 1. Rename this file to 'example.php' (it's just a file extension change).
 *
 * 2. In your browser, go to:
 *    http://host.name/path/to/phpcsscrunch/demo/example.php
 *
 *    For example, in a dev environment:
 *    http://project.locahost/vendor/phpcsscrunch/demo/example.php
 *
 * In your own project, the following PHP code would probably go in a header/init file.
 */

use lmdcode\phpcsscrunch\PhpCssCrunch;

// Include the class (unless using an autoloader)
include '../src/PhpCssCrunch.php';

// Path to current directory from document root (normalise path)
$dirPath = '/' . trim(str_replace('\\', '/', dirname($_SERVER['PHP_SELF'])), '/');

/*** Constructor parameter values */

// Source files - will be combined in array order!
$sourceFiles = [
    $dirPath . '/input-1.css',
    $dirPath . '/input-2.css',
    $dirPath . '/input-3.css',
];

//  Output file for minified CSS
$minifiedFile = $dirPath . '/output.min.css';

// Development mode - however you determine this
$devMode = true; // change this to see different outputs

/**
 * Initialise class with param values
 * @var PhpCssCrunch $crunch
 */
$crunch = new PhpCssCrunch($sourceFiles, $minifiedFile, $_SERVER['DOCUMENT_ROOT'], $devMode);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP CSS Crunch Example Usage</title>
    <?php
        // Maximum minification and output markup with cache buster
        echo $crunch->process(PhpCssCrunch::MINIFY_HIGH)->toFile(true);
    ?>
</head>
<body id="top">
<header>
    <h1>PHP CSS Crunch Example Usage</h1>
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
        PHP CSS Crunch by <a href="https://github.com/lmd-code/">LMD-Code</a><br>
        Licensed under the <a href="https://github.com/lmd-code/phpcsscrunch/blob/main/LICENSE">MIT License</a>
    </p>
</footer>
</body>
</html>