<?php

/**
 * PHP CSS Crunch
 * (c) LMD-Code, 2022-2024
 * https://github.com/lmd-code/phpcsscrunch
 */

declare(strict_types=1);

namespace lmdcode\phpcsscrunch;

/**
 * Example of a class that extends PhpCssCrunch
 */
class ExtendPhpCssCrunch extends PhpCssCrunch
{
    /**
     * {@inheritDoc}
     *
     * Returns stylesheet `<link>` markup with an additional `id` attribute.
     */
    protected function getMarkup(array $styles, bool $cacheBuster = false): string
    {
        if (count($styles) < 1) {
            return '';
        }

        $bustCache = ($cacheBuster) ? '?t=' . time() : ''; // add a cache buster

        $out = '';

        foreach ($styles as $file) {
            $out .= '<link href="' . $file . $bustCache . '" rel="stylesheet" '
            . 'id="' . $this->getId($file) . '">' . PHP_EOL;
        }

        return $out;
    }

    /**
     * Get an ID from a filename
     *
     * This is a very basic id generator just to give a general idea, it does not take into
     * account spaces in filenames or any other special character other than '.'.
     *
     * @param string $file The filepath/filename
     *
     * @return string
     */
    private function getId($file): string
    {
        return str_replace('.', '-', basename($file));
    }
}
