<?php

declare(strict_types=1);

require_once '../src/FileStreamLineReader.php';

use Smysloff\IO\FileStreamLineReader;


/**
 * 01:
 * Simple reading of file or stream line by line
 * with lazy line processing / memory optimization.
 * File handler passed through constructor.
 */
$reader = new FileStreamLineReader($argv[1] ?? STDIN);
$lineNumber = 1;
foreach ($reader->getLines() as $line) {
    fprintf(STDOUT, '%3d. %s' . PHP_EOL, $lineNumber++, $line); 
}

