<?php

declare(strict_types=1);

namespace SortedLinkedList;

use SortedLinkedList\Enum\DataType;
use SortedLinkedList\Enum\Sort;

/**
 * @implements \Iterator<null, int|string>
 */
class SortedLikedList implements \Iterator
{
    private DataType $dataType;
    private ?Node $headNode = null;
    private ?Node $currentNode = null;

    /**
     * @throws \InvalidArgumentException
     */
    public function __construct(
        string|int|null $value = null,
        ?DataType $dataType = null,
        private readonly Sort $sort = Sort::ASC
    ) {
        if ($dataType) {
            $this->dataType = $dataType;
        }

        if ($value) {
            $this->add($value);
        }
    }

    /**
     * @param array<int|string> $values
     */
    public static function createFromArray(array $values, ?DataType $dataType = null, Sort $sort = Sort::ASC): self
    {
        $instance = new self(dataType: $dataType, sort: $sort);

        foreach ($values as $value) {
            $instance->add($value);
        }

        return $instance;
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function add(int|string $value): self
    {
        $this->validate($value);

        $newValueNode = new Node($value);

        // list is empty, set head
        if (!$this->headNode) {
            $this->headNode = $newValueNode;

            return $this;
        }

        // add new value before head
        if ($this->isBefore($this->headNode->getValue(), $value)) {
            $newValueNode->setNext($this->headNode);
            $this->headNode = $newValueNode;

            return $this;
        }

        // find right position for new value and insert
        $carry = $this->headNode;

        while ($carry->getNext() !== null && $this->isAfter($carry->getNext()->getValue(), $value)) {
            $carry = $carry->getNext();
        }

        $newValueNode->setNext($carry->getNext());
        $carry->setNext($newValueNode);

        return $this;
    }

    public function getNode(int|string $value): ?Node
    {
        if (!$this->headNode) {
            return null;
        }

        $current = $this->headNode;
        do {
            if ($current->getValue() === $value) {
                return $current;
            }
        } while ($current = $current->getNext());

        return null;
    }

    public function remove(int|string $value): self
    {
        if (!$this->headNode) {
            return $this;
        }

        if ($this->headNode->getValue() === $value) {
            $this->headNode = $this->headNode->getNext();

            return $this;
        }

        $current = $this->headNode;
        do {
            if ($current->getNext()?->getValue() === $value) {
                $current->setNext($current->getNext()->getNext());

                return $this;
            }
        } while ($current = $current->getNext());

        return $this;
    }

    // iterable
    public function current(): int|string
    {
        if (!$this->currentNode) {
            $this->rewind();
        }

        return $this->currentNode->getValue();
    }

    public function next(): void
    {
        if (!$this->currentNode) {
            $this->rewind();
        }

        $this->currentNode = $this->currentNode->getNext();
    }

    public function key(): null
    {
        return null;
    }

    public function valid(): bool
    {
        return $this->currentNode !== null;
    }

    /** @phpstan-assert !null $this->currentNode */
    public function rewind(): void
    {
        if (!$this->headNode) {
            throw new \LogicException('Calling iterator before adding items');
        }

        $this->currentNode = $this->headNode;
    }

    // iterable end

    public function __toString(): string
    {
        return (string)$this->headNode;
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function validate(int|string $value): void
    {
        if (!isset($this->dataType)) {
            $this->dataType = $this->detectDataType($value);
        }

        if ($this->dataType === DataType::INT && !is_int($value)) {
            throw new \InvalidArgumentException('Argument has wrong type, int expected');
        }

        if ($this->dataType === DataType::STRING && !is_string($value)) {
            throw new \InvalidArgumentException('Argument has wrong type, string expected');
        }
    }

    private function detectDataType(int|string $value): DataType
    {
        if (is_int($value)) {
            return DataType::INT;
        }

        return DataType::STRING;
    }

    private function isBefore(int|string $base, int|string $value): bool
    {
        if ($this->sort === Sort::ASC) {
            return $base >= $value;
        }

        return $base <= $value;
    }

    private function isAfter(int|string $base, int|string $value): bool
    {
        if ($this->sort === Sort::ASC) {
            return $base <= $value;
        }

        return $base >= $value;
    }
}
