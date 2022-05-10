<?php

declare(strict_types=1);

require_once '../src/FileStreamLineReader.php';

use Smysloff\IO\FileStreamLineReader;


/**
 * Gets a single line from file or stream
 */
$reader = new FileStreamLineReader($argv[1] ?? STDIN);
fprintf(STDOUT, '%s' . PHP_EOL, $reader->getOneLine());

