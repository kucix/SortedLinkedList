<?php

declare(strict_types=1);

namespace SortedLinkedList;

class Node
{
    private ?Node $next = null;

    public function __construct(private readonly int|string $value)
    {
    }

    public function getValue(): int|string
    {
        return $this->value;
    }

    public function setNext(?Node $node): void
    {
        $this->next = $node;
    }

    public function getNext(): ?Node
    {
        return $this->next;
    }

    public function hasNext(): bool
    {
        return $this->next !== null;
    }

    public function __toString(): string
    {
        return "{$this->value}" . ($this->hasNext() ? " > {$this->getNext()}" : '');
    }
}
