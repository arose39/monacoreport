<?php

namespace MonacoReport;

use http\Exception\InvalidArgumentException;

class RacersCollection extends CustomIterator implements \Countable
{
    protected $position = 0;
    protected $container = [];

    public function __construct(array $array = null)
    {
        $this->position = 0;
        if (!is_null($array)) {
            foreach ($array as $item) {
                //В данную колекцию можно записать только обьекті класса Racer
                if (!($item instanceof Racer)) {
                    throw new InvalidArgumentException("RacersCollection must contains only Racer type objects");
                }
            }
            $this->container = $array;
        }
    }

    public function add(Racer $value): void
    {
        $this->container[] = $value;
    }

    public function delete($offset): void
    {
        unset($this->container[$offset]);
    }

    public function count(): int
    {
        return count($this->container);
    }
}