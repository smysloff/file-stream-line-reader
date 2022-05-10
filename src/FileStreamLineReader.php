<?php

declare(strict_types=1);

namespace Smysloff\IO;

/**
 * Simple and unified interface for working with files and streams.
 * Allows to read files line by line optimized lazy way.
 */
class FileStreamLineReader
{
    protected $stream;
    protected ?string $file;

    protected const ERROR_TYPE_MSG = 'Wrong type was passed as an argument, '
                                   . 'a resource or string was expected, '
                                   . 'but %s was passed';

    protected const ERROR_FILE_MSG = 'Unable to open file %s';

    protected const ERROR_READ_MSG = 'Unable to read stream %s';

    public function __construct($fileOrStream = null)
    {
        if (isset($fileOrStream)) {
            $this->setFile($fileOrStream);
        }
    }

    public function setFile($fileOrStream): static
    {
        switch ($type = gettype($fileOrStream)) {

            case 'resource':
                $this->file = null;
                $this->stream = $fileOrStream;
                break;

            case 'string':
                $this->file = $fileOrStream;
                $this->stream = $this->openFile($fileOrStream);
                break;

            default:
                throw new \Exception(sprintf(static::ERROR_TYPE_MSG, $type));
        }

        return $this;
    }

    public function getLines($fileOrStream = null): \Generator
    {
       if (isset($fileOrStream)) {
           $this->setFile($fileOrStream);
       }

       while ($line = fgets($this->stream)) {
           yield preg_replace('#[\r\n]#', '', $line);
       }
       if (!feof($this->stream)) {
           throw new \Exception(sprintf(static::ERROR_READ_MSG, $this->file));
       }

       if (isset($this->file)) {
           fclose($this->stream);
       }
    }

    public function toArray($fileOrStream = null): array
    {
        $lines = [];
        foreach ($this->getLines($fileOrStream) as $line) {
            $lines[] = $line;
        }

        return $lines;
    }

    public function getOneLine($stream = null): string|false
    {
        $line = fgets($stream ?? $this->stream);
        if (!isset($stream) && feof($this->stream)) {
            fclose($this->stream);
            $this->stream = null;
            $this->file = null;
        }

        if ($line !== false) {
            $line = preg_replace('#[\r\n]#', '', $line);
        }

        return $line;
    }

    protected function openFile(string $file): mixed
    {
        if (
            !is_file($file)
            || !is_readable($file)
            || !($stream = fopen($file, 'r'))
        ) {
            throw new \Exception(sprintf(static::ERROR_FILE_MSG, $file));
        }

        return $stream;
    }
}

