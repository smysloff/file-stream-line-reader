<?php

declare(strict_types=1);

require_once '../src/FileStreamLineReader.php';

use Smysloff\IO\FileStreamLineReader;


/**
 * 02:
 * Gets array of lines from file or stream.
 * File handler passed through processing method.
 */
$reader = new FileStreamLineReader();
foreach ($reader->toArray($argv[1] ?? STDIN) as $key => $value) {
   fprintf(STDOUT, '%3d. %s' . PHP_EOL, $key + 1, $value);
}

