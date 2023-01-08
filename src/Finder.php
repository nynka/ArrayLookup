<?php

declare(strict_types=1);

namespace ArrayLookup;

use Traversable;
use Webmozart\Assert\Assert;

use function current;
use function end;
use function iterator_to_array;
use function key;
use function prev;

final class Finder
{
    /**
     * @param mixed[]|iterable        $data
     * @param callable(mixed $datum): bool $filter
     */
    public static function first(iterable $data, callable $filter): mixed
    {
        foreach ($data as $datum) {
            $isFound = $filter($datum);

            // returns of callable must be bool
            Assert::boolean($isFound);

            if (! $isFound) {
                continue;
            }

            return $datum;
        }

        return null;
    }

    /**
     * @param mixed[]|iterable        $data
     * @param callable(mixed $datum): bool $filter
     */
    public static function last(iterable $data, callable $filter): mixed
    {
        // convert to array when data is Traversable instance
        if ($data instanceof Traversable) {
            $data = iterator_to_array($data);
        }

        // ensure data is array for end(), key(), current(), prev() usage
        Assert::isArray($data);

        // go to end of array
        end($data);

        // key = null means no longer current data
        while (key($data) !== null) {
            $current = current($data);
            $isFound = $filter($current);

            // returns of callable must be bool
            Assert::boolean($isFound);

            if (! $isFound) {
                // go to previous row
                prev($data);

                continue;
            }

            return $current;
        }

        return null;
    }
}
