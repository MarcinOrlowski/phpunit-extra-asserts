<?php

/**
 * PHP Unit Extra Asserts
 *
 * @package   MarcinOrlowski\PhpUnitExtraAsserts
 *
 * @author    Marcin Orlowski <mail (#) marcinOrlowski (.) com>
 * @copyright 2019-2022 Marcin Orlowski
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      https://github.com/MarcinOrlowski/phpunit-extra-asserts
 */

use MarcinOrlowski\PhpunitExtraAsserts\ExtraAsserts;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;

class AssertsTest extends TestCase
{
    /**
     * Generates random string, with optional prefix
     *
     * @param string|null $prefix    Optional prefix to be added to generated string.
     * @param int         $length    Length of the string to be generated.
     * @param string      $separator Optional prefix separator.
     *
     * @return string
     */
    protected function getRandomString(?string $prefix = null, int $length = 24,
                                       string  $separator = '_'): string
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

    public function testAssertArrayElementPositive(): void
    {
        $array = $this->getDataForArrayContainTests();
        $key_idx = $this->getRandomInt(0, \count($array) - 1);
        $exp_key = \array_keys($array)[ $key_idx ];
        $exp_val = $array[ $exp_key ];

        ExtraAsserts::assertArrayElement($exp_key, $array, $exp_val);
    }

    public function testAssertArrayElementNonExistingKey(): void
    {
        $array = $this->getDataForArrayContainTests();
        $idx = $this->getRandomInt(0, \count($array) - 1);

        $exp_key = $this->getRandomString('nonExistingKey');
        $exp_val = $this->getRandomString('nonExistingVal');

        $this->expectException(AssertionFailedError::class);
        ExtraAsserts::assertArrayElement($exp_key, $array, $exp_val);
    }

    public function testAssertArrayElementKeyValueMismatch(): void
    {
        $array = $this->getDataForArrayContainTests();
        $idx = $this->getRandomInt(0, \count($array) - 1);

        $key_idx = $this->getRandomInt(0, \count($array) - 1);
        $exp_key = \array_keys($array)[ $key_idx ];
        $exp_val = $this->getRandomString('nonExistingVal');

        $this->expectException(AssertionFailedError::class);
        ExtraAsserts::assertArrayElement($exp_key, $array, $exp_val);
    }

    /* ****************************************************************************************** */

    public function testAssertArrayHasKeysPositive(): void
    {
        $min = 5;
        $max = $this->getRandomInt($min * 2, $min * 4);
        $array = $this->getDataForArrayContainTests($min, $max);

        $exp_keys = [];
        $exp_keys_count = $this->getRandomInt($min, $min * 3);
        for ($i = 0; $i < $exp_keys_count; $i++) {
            $keys = \array_keys($array);
            $exp_keys = \array_slice($keys, 0, $exp_keys_count);
        }

        ExtraAsserts::assertArrayHasKeys($exp_keys, $array);
    }

    public function testAssertArrayHasKeysNegative(): void
    {
        $array = $this->getDataForArrayContainTests();

        $non_existing_keys = [
            $this->getRandomString('nonExistingKey'),
        ];

        $this->expectException(AssertionFailedError::class);
        ExtraAsserts::assertArrayHasKeys($non_existing_keys, $array);
    }

    /* ****************************************************************************************** */

    public function testAssertArrayEquals(): void
    {
        $array_a = $this->getDataForArrayContainTests(10, 20);
        $array_b = $array_a;
        \asort($array_b);
        ExtraAsserts::assertArrayEquals($array_a, $array_a);
    }

    /**
     * Ensures arrays diff is calculated correctly if $arrayB is smaller
     * in size than $arrayA.
     */
    public function testAssertArraysHaveDifferencesSubsetA(): void
    {
        $array_a = $this->getDataForArrayContainTests(10, 20);
        $array_b = \array_slice($array_a, 0, (int)(\count($array_a) / 2));
        \asort($array_b);
        $diff_count = \abs(\count($array_a) - \count($array_b));
        ExtraAsserts::assertArraysHaveDifferences($diff_count, $array_a, $array_b);
    }

    /**
     * Ensures arrays diff is calculated correctly if $arrayA is smaller
     * in size than $arrayB.
     */
    public function testAssertArraysHaveDifferencesSubsetB(): void
    {
        $array_b = $this->getDataForArrayContainTests(10, 20);
        $array_b_size = \count($array_b);
        $array_a = \array_slice($array_b, 0, (int)($array_b_size / 2));
        $array_a_size = \count($array_a);
        \asort($array_b);
        $diff_count = \abs($array_a_size - $array_b_size);
        ExtraAsserts::assertArraysHaveDifferences($diff_count, $array_a, $array_b);
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
