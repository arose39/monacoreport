<?php

namespace MonacoReport;

class CustomIterator implements \Iterator
{
    protected $position = 0;
    protected $container = [];

    public function __construct(array $array)
    {
        $this->position = 0;
        if (!is_null($array)) {
            $this->container = $array;
        }
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function current(): mixed
    {
        return $this->container[$this->position];
    }

    public function key(): mixed
    {
        return $this->position;
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function valid(): bool
    {
        return isset($this->container[$this->position]);
    }
}
