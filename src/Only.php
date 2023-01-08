<?php

declare(strict_types=1);

namespace ArrayLookup;

use Webmozart\Assert\Assert;

final class Only
{
    /**
     * @param mixed[]        $data
     * @param callable(mixed $datum): bool $filter
     */
    public static function once(array $data, callable $filter): bool
    {
        return self::onlyFoundTimes($data, $filter, 1);
    }

    /**
     * @param mixed[]        $data
     * @param callable(mixed $datum): bool $filter
     */
    public static function twice(array $data, callable $filter): bool
    {
        return self::onlyFoundTimes($data, $filter, 2);
    }

    /**
     * @param mixed[]        $data
     * @param callable(mixed $datum): bool $filter
     */
    public static function times(array $data, callable $filter, int $count): bool
    {
        return self::onlyFoundTimes($data, $filter, $count);
    }

    /**
     * @param mixed[]        $data
     * @param callable(mixed $datum): bool $filter
     */
    private static function onlyFoundTimes(array $data, callable $filter, int $maxCount): bool
    {
        // usage must be higher than 0
        Assert::greaterThan($maxCount, 0);

        $totalFound = 0;
        foreach ($data as $datum) {
            $isFound = $filter($datum);

            // returns of callable must be bool
            Assert::boolean($isFound);

            if (! $isFound) {
                continue;
            }

            // already passed
            if ($totalFound === $maxCount) {
                return false;
            }

            ++$totalFound;
        }

        return $totalFound === $maxCount;
    }
}
