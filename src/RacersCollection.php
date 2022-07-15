<?php declare(strict_types=1);

namespace MonacoReport;

class RacersCollection implements \Iterator, \Countable
{
    protected int $position = 0;
    protected $container = [];

    public function __construct(array $array = null)
    {
        $this->position = 0;
        if (!is_null($array)) {
            foreach ($array as $item) {
                //В данную колекцию можно записать только обьекты класса Racer
                if (!($item instanceof Racer)) {
                    throw new InvalidArgumentException("RacersCollection must contains only Racer type objects");
                }
            }
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

    public function add(Racer $racer): void
    {
        $this->container[] = $racer;
    }

    public function delete(int $offset): void
    {
        unset($this->container[$offset]);
    }

    public function count(): int
    {
        return count($this->container);
    }
}
