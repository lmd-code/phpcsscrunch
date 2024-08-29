<?php

/**
 * PHP CSS Crunch <https://github.com/lmd-code/phpcsscrunch>
 *
 * This is an example/demo of the ExtendPhpCssCrunch class.
 *
 * You can run this demo from your local copy of PhpCssCrunch
 *
 * 1. Rename this file to 'extend-example.php' (it's just a file extension change).
 * 
 * 2. Rename the file 'ExtendPhpCssCrunch.phps' to 'ExtendPhpCssCrunch.php'.
 *
 * 3. In your browser, go to:
 *    http://host.name/path/to/phpcsscrunch/demo/extend-example.php
 *
 *    For example, in a dev environment:
 *    http://project.locahost/vendor/phpcsscrunch/demo/extend-example.php
 */

use lmdcode\phpcsscrunch\ExtendPhpCssCrunch;

// Include the classes (unless using an autoloader)
include '../src/PhpCssCrunch.php'; //  original PhpCssCrunch class
include 'ExtendPhpCssCrunch.php'; // ExtendPhpCssCrunch class

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
 * @var ExtendPhpCssCrunch $crunch
 */
$crunch = new ExtendPhpCssCrunch($sourceFiles, $minifiedFile, $_SERVER['DOCUMENT_ROOT'], $devMode);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP CSS Crunch Class Extension</title>
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
<body id="top">
<header>
    <h1>PHP CSS Crunch Class Extension</h1>
</header>
<main>
    <p>Example of a class extension to modify the output of the <code>getMarkup()</code> method.</p>
    
    <h2><label for="cssout1">Markup</label></h2>
    <p><textarea id="cssout1" cols="80" rows="6"><?php
        echo $crunch->process(ExtendPhpCssCrunch::MINIFY_HIGH)->toFile();
    ?></textarea></p>

</main> 
<footer>
    <p>
        PHP CSS Crunch by <a href="https://github.com/lmd-code/">LMD-Code</a><br>
        Licensed under the <a href="https://github.com/lmd-code/phpcsscrunch/blob/main/LICENSE">MIT License</a>
    </p>
</footer>
</body>
</html>