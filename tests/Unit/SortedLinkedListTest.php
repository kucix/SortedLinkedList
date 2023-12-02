<?php

declare(strict_types=1);

namespace SortedLinkedList\Tests\Unit;

use PHPUnit\Framework\TestCase;
use SortedLinkedList\Enum\DataType;
use SortedLinkedList\Enum\Sort;
use SortedLinkedList\SortedLikedList;

class SortedLinkedListTest extends TestCase
{
    public function testSuccessOutputSimpleInt(): void
    {
        $sortedList = new SortedLikedList();
        $sortedList->add(8)
            ->add(3)
            ->add(10)
            ->add(1)
            ->add(5);

        $this->assertSame('1 > 3 > 5 > 8 > 10', (string)$sortedList);
    }

    public function testSuccessOutputSimpleIntDesc(): void
    {
        $sortedList = new SortedLikedList(sort: Sort::DESC);
        $sortedList->add(8)
            ->add(3)
            ->add(10)
            ->add(1)
            ->add(5);

        $this->assertSame('10 > 8 > 5 > 3 > 1', (string)$sortedList);
    }

    public function testSuccessOutputSimpleString(): void
    {
        $sortedList = new SortedLikedList();
        $sortedList->add('b')
            ->add('abc')
            ->add('acb')
            ->add('ačb')
            ->add('z')
            ->add('h');

        $this->assertSame('abc > acb > ačb > b > h > z', (string)$sortedList);
    }

    public function testSuccessOutputSimpleStringDesc(): void
    {
        $sortedList = new SortedLikedList(sort: Sort::DESC);
        $sortedList->add('b')
            ->add('abc')
            ->add('acb')
            ->add('ačb')
            ->add('z')
            ->add('h');

        $this->assertSame('z > h > b > ačb > acb > abc', (string)$sortedList);
    }

    public function testIterator(): void
    {
        $values = [
            1,
            3,
            5,
            8,
            10
        ];
        $sortedList = SortedLikedList::createFromArray($values);

        $i = 0;
        foreach ($sortedList as $value) {
            $this->assertSame($values[$i], $value);
            $i++;
        }
    }

    public function testWrongTypeStringExpected(): void
    {
        $sortedList = new SortedLikedList(dataType: DataType::STRING);
        $this->expectExceptionMessage('Argument has wrong type, string expected');
        $sortedList->add(1);
    }

    public function testWrongTypeIntExpected(): void
    {
        $sortedList = new SortedLikedList(dataType: DataType::INT);
        $this->expectExceptionMessage('Argument has wrong type, int expected');
        $sortedList->add('1');
    }

    public function testExists(): void
    {
        $sortedList = new SortedLikedList();
        $sortedList->add('b')
            ->add('abc')
            ->add('acb')
            ->add('ačb')
            ->add('z')
            ->add('h');

        $this->assertSame('z', $sortedList->getNode('z')?->getValue());
        $this->assertSame(null, $sortedList->getNode('x'));
    }

    public function testRemove(): void
    {
        $sortedList = new SortedLikedList();
        $sortedList->add('b')
            ->add('abc')
            ->add('acb')
            ->add('ačb')
            ->add('z')
            ->add('h')
            ->remove('h');

        $this->assertSame('abc > acb > ačb > b > z', (string)$sortedList);

        $sortedList->remove('z');
        $this->assertSame('abc > acb > ačb > b', (string)$sortedList);
    }

}
