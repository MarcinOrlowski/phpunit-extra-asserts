<?php

namespace MarcinOrlowski\PhpunitExtraAsserts\Traits;

/**
 * PhpUnit Extra Asserts
 *
 * @package   MarcinOrlowski\PhpunitExtraAsserts
 *
 * @author    Marcin Orlowski <mail (#) marcinOrlowski (.) com>
 * @copyright 2014-2022 Marcin Orlowski
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      https://github.com/MarcinOrlowski/phpunit-extra-asserts
 */
trait ExtraAsserts
{
    /**
     * Asserts array contains specified item.
     *
     * @param array       $array Array to inspect.
     * @param mixed       $item  Array item to look for.
     * @param string|null $msg   Custom error message to show if assertion fails.
     *
     * @deprecated Use arrayContains() instead.
     */
    public function assertArrayContains(array $array, $item, string $msg = null): void
    {
        $this->deprecated('assertArrayContains', 'arrayContains');

        if ($msg === null) {
            $msg = "Array does not contain element '$item'";
        }
        $this->assertContains($item, $array, $msg);
    }

    /**
     * Asserts array does NOT contain given element.
     *
     * @param array       $array Array to inspect.
     * @param mixed       $item  Array item to look for.
     * @param string|null $msg   Custom error message to show if assertion fails.
     *
     * @deprecated Use arrayNotContains() instead.
     */
    public function assertArrayNotContain(array $array, $item, string $msg = null): void
    {
        $this->deprecated('assertArrayNotContain', 'arrayNotContains');

        if ($msg === null) {
            $msg = "Array does contain element '$item'";
        }
        $this->assertNotContains($item, $array, $msg);
    }

    /**
     * Asserts array has specified key and it's value is according to expectations.
     *
     * @param string $key
     * @param array  $array
     * @param string $expected_value
     */
    public function assertArrayElement(string $key, array $array, string $expected_value): void
    {
        $msg = "Key not found: {$key}";
        $this->assertArrayHasKey($key, $array, $msg);
        $msg = "Value for key '{$key}' is not as expected: {$expected_value}";
        $this->assertEquals($expected_value, $array[ $key ], $msg);
    }

    /**s
     * Asserts array has ALL the required keys
     *
     * @param array $required_keys list of required array keys
     * @param array $array         array to check
     */
    public function assertArrayHasKeys(array $required_keys, array $array): void
    {
        foreach ($required_keys as $key) {
            $this->assertArrayHasKey($key, $array);
        }
    }

    /**
     * Asserts two arrays are equivalent.
     *
     * @deprecated Please use assertArrayEquals() instead
     */
    public function assertArraysEquals(array $array_a, array $array_b): void
    {
        $this->deprecated('assertArraysEquals', 'assertArrayEquals');

        $this->assertArrayEquals($array_a, $array_b);
    }

    /**
     * Asserts $arrayA equals $arrayB which means both array contain the
     * same content, yet the order of data is not taken into account.
     * For example ['foo','bar'] equals ['bar','foo'] as content is the same
     * ['key1'=>'foo','key2'=>'bar'] differs from ['key1'=>'bar','key2'=>'foo'].
     *
     * @param array $array_a Array A to compare content of
     * @param array $array_b Array B to compare content of
     */
    public function assertArrayEquals(array $array_a, array $array_b): void
    {
        $this->assertArraysHaveDifferences(0, $array_a, $array_b);
    }

    /**
     * Asserts two arrays differ by exactly given number of elements.
     *
     * Asserts $arrayA equals $arrayB which means both array contain the
     * same content, yet the order of data is not taken into account.
     * For example ['foo','bar'] equals ['bar','foo'] as content is the same
     * ['key1'=>'foo','key2'=>'bar'] differs from ['key1'=>'bar','key2'=>'foo'].
     *
     * @param int   $expected Expected number of differences between arrays
     * @param array $array_a  Array A to compare content of
     * @param array $array_b  Array B to compare content of
     */
    protected function assertArraysHaveDifferences(int   $expected,
                                                   array $array_a, array $array_b): void
    {
        $diff_array_count = $this->arrayRecursiveDiffCount($array_a, $array_b);
        $msg = "Expected {$expected} differences, found {$diff_array_count}";
        $this->assertEquals($expected, $diff_array_count, $msg);
    }

    /**
     * Assert if keys from response have the same values as in original array.
     * Keys listed in $skip_keys are ignored.
     *
     * @param array $array_a      Array A to compare content of
     * @param array $array_b      Array B to compare content of
     * @param array $ignored_keys Array of keys that will be ignored during comparison
     *                            (as they never existed)
     */
    public function massAssertEquals(array $array_a, array $array_b, array $ignored_keys = [])
    {
        foreach ($array_a as $key => $value) {
            if (\in_array($key, $ignored_keys, true)) {
                continue;
            }
            if (\is_array($value)) {
                $this->massAssertEquals($value, $array_b[ $key ], $ignored_keys);
            } else {
                $orig_type = \gettype($value);
                $resp_type = \gettype($array_b[ $key ]);

                if ($orig_type !== $resp_type) {
                    $msg = "Type mismatch for key '{$key}'. Expected '{$orig_type}', found '{$resp_type}'";
                } else {
                    $msg = "Value mismatch for key '{$key}'. Expected '{$value}', found '{$array_b[$key]}'";
                }
                $this->assertEquals($value, $array_b[ $key ], $msg);
            }
        }
    }

    /**
     * Asserts provided string is valid RFC3339 timestamp string.
     *
     * @param string $stamp String to check against RFC3339 format
     */
    public function assertRFC3339(string $stamp): void
    {
        if ($this->validateRFC3339($stamp) === false) {
            $this->fail("'{$stamp}' is not a valid RFC3339 time stamp string");
        }
    }

    /**
     * Asserts $stamp string is valid RFC3339 timestamp string or @null.
     *
     * @param string $stamp
     */
    public function assertRFC3339OrNull(string $stamp): void
    {
        if ($this->validateRFC3339($stamp) === false) {
            $this->fail("'{$stamp}' is neither a valid RFC3339 time stamp string nor NULL");
        }
    }

    /**
     * Asserts provided string is valid RFC3339 timestamp string.
     *
     * @param string $stamp
     *
     * @return bool
     */
    protected function validateRFC3339(string $stamp): bool
    {
        $RFC3339_REGEXP = '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(?:\.\d{1,3})?((?:[\+\-]\d{2}:\d{2})|Z)$/i';

        return \preg_match($RFC3339_REGEXP, $stamp) === 1;
    }

    protected function arrayRecursiveDiffCount(array $array_a, array $array_b): int
    {
        $diff_count = 0;

        $count_a = \count($array_a);
        $count_b = \count($array_b);

        // If array_b is bigger (more keys) then we can need to count key count
        // difference separately. In case array_a is bigger, we detect missing
        // keys in the loop below.
        if ($count_b > $count_a) {
            $diff_count = \abs($count_a - $count_b);
        }

        foreach ($array_a as $a_key => $a_value) {
            if (!\array_key_exists($a_key, $array_b)) {
                $diff_count++;
                continue;
            }

            if (\is_array($a_value) && \is_array($array_b[ $a_key ])) {
                $diff_count += $this->arrayRecursiveDiffCount($a_value, $array_b[ $a_key ]);
            } elseif ($a_value !== $array_b[ $a_key ]) {
                $diff_count++;
            }
        }

        return $diff_count;
    }

    /* ****************************************************************************************** */

    /**
     * Prints content of given array in compacted form.
     *
     * @param array $array  Array to print
     * @param int   $indent Number of indent blocks (2 spaces per block) to add for each nest level
     */
    public function printArray(array $array, int $indent = 0): void
    {
        $indent_block = '  ';
        $i = \str_repeat($indent_block, $indent + 1);

        foreach ($array as $k => $v) {
            if (\is_array($v)) {
                echo "{$i}{$k}:\n";
                $this->printArray($v, $indent + 1);
            } elseif (is_object($v)) {
                try {
                    \get_class($v);
                    $v = $v->__toString();
                    echo "{$i}{$k}: {$v}\n";
                } catch (\Throwable $e) {
                    \get_class($v);
                    echo "{$i}{$k}: {$v}\n";
                }
            } else {
                echo "{$i}{$k}: {$v}\n";
            }
        }
    }

    /**
     * Shows depreciation message (for deprecated asserts).
     *
     * @param string  $method_name     Name of method being deprecatad.
     * @param ?string $new_method_name Name of method to use instead or @NULL if there's none.
     *
     * @return void
     */
    protected function deprecated(string $method_name, ?string $new_method_name): void
    {
        $msg = ["Deprecated assert {$method_name}()."];
        if ($new_method_name !== null) {
            $msg[] = "Use {$new_method_name}() instead.";
        }
        $this->addWarning(\implode(' ', $msg));

    }

} // end of trait
