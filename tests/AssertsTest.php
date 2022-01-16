<?php

use PHPUnit\Framework\AssertionFailedError;

class AssertsTest extends \PHPUnit\Framework\TestCase
{
    use \MarcinOrlowski\PhpunitExtraAsserts\Traits\ExtraAsserts;

    /**
     * Generates random string, with optional prefix
     *
     * @param string|null $prefix    Optional prefix to be added to generated string.
     * @param int         $length    Length of the string to be generated.
     * @param string|null $separator Optional prefix separator.
     *
     * @return string
     */
    protected function getRandomString(string $prefix = null, int $length = 24,
                                       string $separator = '_'): string
    {
        if ($length < 1) {
            throw new \RuntimeException('Length must be greater than 0');
        }

        $prefix = ($prefix !== null) ? "{$prefix}{$separator}" : '';

        return \substr($prefix . \md5(\uniqid('', true)), 0, $length);
    }

    /**
     * Generates random integer value from withing specified range.
     *
     * @param int $min Min allowed value (inclusive)
     * @param int $max Max allowed value (inclusive)
     *
     * @return int
     *
     * @throws \Exception
     */
    protected function getRandomInt(int $min = 0, int $max = 100): int
    {
        return \random_int($min, $max);
    }

    /* ****************************************************************************************** */

    public function testAssertArrayContainsPositive(): void
    {
        $array = $this->getDataForArrayContainTests();
        $expected = $this->getRandomString('this');
        $array[] = $expected;

        $this->assertArrayContains($array, $expected);
    }

    public function testAssertArrayContainsNegative(): void
    {
        $array = $this->getDataForArrayContainTests();
        $missing = $this->getRandomString('this');
        $this->expectException(AssertionFailedError::class);
        $this->assertArrayContains($array, $missing);
    }

    public function testAssertArrayNotContainPositive(): void
    {
        $array = $this->getDataForArrayContainTests();
        $missing = $this->getRandomString('this');
        $this->assertArrayNotContain($array, $missing);
    }

    public function testAssertArrayNotContainNegative(): void
    {
        $array = $this->getDataForArrayContainTests();
        $expected = $this->getRandomString('this');
        $array[] = $expected;
        $this->expectException(AssertionFailedError::class);
        $this->assertArrayNotContain($array, $expected);
    }

    /* ****************************************************************************************** */

    public function testAssertArrayElementPositive(): void
    {
        $array = $this->getDataForArrayContainTests();
        $keyIdx = $this->getRandomInt(0, \count($array) - 1);
        $expKey = \array_keys($array)[ $keyIdx ];
        $expVal = $array[ $expKey ];

        $this->assertArrayElement($expKey, $array, $expVal);
    }

    public function testAssertArrayElementNonExistingKey(): void
    {
        $array = $this->getDataForArrayContainTests();
        $idx = $this->getRandomInt(0, \count($array) - 1);

        $expKey = $this->getRandomString('nonExistingKey');
        $expVal = $this->getRandomString('nonExistingVal');

        $this->expectException(AssertionFailedError::class);
        $this->assertArrayElement($expKey, $array, $expVal);
    }

    public function testAssertArrayElementKeyValueMismatch(): void
    {
        $array = $this->getDataForArrayContainTests();
        $idx = $this->getRandomInt(0, \count($array) - 1);

        $keyIdx = $this->getRandomInt(0, \count($array) - 1);
        $expKey = \array_keys($array)[ $keyIdx ];
        $expVal = $this->getRandomString('nonExistingVal');

        $this->expectException(AssertionFailedError::class);
        $this->assertArrayElement($expKey, $array, $expVal);
    }

    /* ****************************************************************************************** */

    public function testAssertArrayHasKeysPositive(): void
    {
        $min = 5;
        $max = $this->getRandomInt($min * 2, $min * 4);
        $array = $this->getDataForArrayContainTests($min, $max);

        $expKeys = [];
        $expKeysCount = $this->getRandomInt($min, $min * 3);
        for ($i = 0; $i < $expKeysCount; $i++) {
            $keys = \array_keys($array);
            $expKeys = \array_slice($keys, 0, $expKeysCount);
        }

        $this->assertArrayHasKeys($expKeys, $array);
    }

    public function testAssertArrayHasKeysNegative(): void
    {
        $array = $this->getDataForArrayContainTests();

        $nonExistingKeys = [
            $this->getRandomString('nonExistingKey'),
        ];

        $this->expectException(AssertionFailedError::class);
        $this->assertArrayHasKeys($nonExistingKeys, $array);
    }

    /* ****************************************************************************************** */

    public function testAssertArrayEquals(): void
    {
        $arrayA = $this->getDataForArrayContainTests(10, 20);
        $arrayB = $arrayA;
        \asort($arrayB);
        $this->assertArrayEquals($arrayA, $arrayA);
    }

    /**
     * Ensures arrays diff is calculated correctly if $arrayB is smaller
     * in size than $arrayA.
     */
    public function testAssertArraysHaveDifferences_SubsetA(): void
    {
        $arrayA = $this->getDataForArrayContainTests(10, 20);
        $arrayB = \array_slice($arrayA, 0, \count($arrayA) / 2);
        \asort($arrayB);
        $diffCount = \abs(\count($arrayA) - \count($arrayB));
        $this->assertArraysHaveDifferences($diffCount, $arrayA, $arrayB);
    }

    /**
     * Ensures arrays diff is calculated correctly if $arrayA is smaller
     * in size than $arrayB.
     */
    public function testAssertArraysHaveDifferences_SubsetB(): void
    {
        $arrayB = $this->getDataForArrayContainTests(10, 20);
        $arrayBSize = \count($arrayB);
        $arrayA = \array_slice($arrayB, 0, $arrayBSize / 2);
        $arrayASize = \count($arrayA);
        \asort($arrayB);
        $diffCount = \abs($arrayASize - $arrayBSize);
        $this->assertArraysHaveDifferences($diffCount, $arrayA, $arrayB);
    }

    /* ****************************************************************************************** */

    protected function getDataForArrayContainTests(int $min = 1, int $max = 25): array
    {
        if ($max < $min) {
            $msg = "Max value ({$max}) must be greater than min value ({$min}).";
            throw new \InvalidArgumentException($msg);
        }

        $cnt = $this->getRandomInt($min, $max);
        $array = [];
        for ($i = 0; $i < $cnt; $i++) {
            $key = $this->getRandomString('key');
            $array[ $key ] = $this->getRandomString('val');
        }
        return $array;
    }

} // end of class
