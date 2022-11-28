<?php
declare(strict_types=1);

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

namespace MarcinOrlowski\PhpunitExtraAsserts;

use MarcinOrlowski\TypeAsserts\Exception as Ex;
use MarcinOrlowski\TypeAsserts\Validator;
use MarcinOrlowski\TypeAsserts\Type;
use PHPUnit\Framework\Assert;

class ExtraAsserts
{
    /**
     * Asserts array has specified key and it's value is according to expectations.
     *
     * @param string $key
     * @param array  $array
     * @param string $expected_value
     */
    public static function assertArrayElement(string $key, array $array, string $expected_value): void
    {
        $msg = "Key not found: {$key}";
        Assert::assertArrayHasKey($key, $array, $msg);
        $msg = "Value for key '{$key}' is not as expected: {$expected_value}";
        Assert::assertEquals($expected_value, $array[ $key ], $msg);
    }

    /**s
     * Asserts array has ALL the required keys
     *
     * @param array $required_keys list of required array keys
     * @param array $array         array to check
     */
    public static function assertArrayHasKeys(array $required_keys, array $array): void
    {
        foreach ($required_keys as $key) {
            Assert::assertArrayHasKey($key, $array);
        }
    }

    /**
     * Asserts $arrayA equals $arrayB which means both array contain the same content, yet the order of data
     * is not taken into account. For example ['foo','bar'] equals ['bar','foo'] as content is the same
     * ['key1'=>'foo','key2'=>'bar'] differs from ['key1'=>'bar','key2'=>'foo'].
     *
     * @param array $array_a Array A to compare content of
     * @param array $array_b Array B to compare content of
     */
    public static function assertArrayEquals(array $array_a, array $array_b): void
    {
        static::assertArraysHaveDifferences(0, $array_a, $array_b);
    }

    /**
     * Asserts two arrays differ by exactly given number of elements.
     *
     * Asserts $arrayA equals $arrayB which means both array contain the same content, yet the order of data
     * is not taken into account. For example ['foo','bar'] equals ['bar','foo'] as content is the same
     * ['key1'=>'foo','key2'=>'bar'] differs from ['key1'=>'bar','key2'=>'foo'].
     *
     * @param int   $expected Expected number of differences between arrays
     * @param array $array_a  Array A to compare content of
     * @param array $array_b  Array B to compare content of
     */
    public static function assertArraysHaveDifferences(int   $expected,
                                                       array $array_a, array $array_b): void
    {
        $diff_array_count = static::arrayRecursiveDiffCount($array_a, $array_b);
        $msg = "Expected {$expected} differences, found {$diff_array_count}";
        Assert::assertEquals($expected, $diff_array_count, $msg);
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
    public static function massAssertEquals(array $array_a, array $array_b, array $ignored_keys = []): void
    {
        foreach ($array_a as $key => $value) {
            if (\in_array($key, $ignored_keys, true)) {
                continue;
            }
            if (\is_array($value)) {
                static::massAssertEquals($value, $array_b[ $key ], $ignored_keys);
            } else {
                $orig_type = \gettype($value);
                $resp_type = \gettype($array_b[ $key ]);

                if ($orig_type !== $resp_type) {
                    $msg = "Type mismatch for key '{$key}'. Expected '{$orig_type}', found '{$resp_type}'";
                } else {
                    $msg = "Value mismatch for key '{$key}'. Expected '{$value}', found '{$array_b[$key]}'";
                }
                Assert::assertEquals($value, $array_b[ $key ], $msg);
            }
        }
    }

    /**
     * Asserts provided string is valid RFC3339 timestamp string.
     *
     * @param string $stamp String to check against RFC3339 format
     */
    public static function assertRFC3339(string $stamp): void
    {
        if (static::validateRFC3339($stamp) === false) {
            Assert::fail("'{$stamp}' is not a valid RFC3339 time stamp string");
        }
    }

    /**
     * Asserts $stamp string is valid RFC3339 timestamp string or @null.
     */
    public static function assertRFC3339OrNull(string $stamp): void
    {
        if (static::validateRFC3339($stamp) === false) {
            Assert::fail("'{$stamp}' is neither a valid RFC3339 time stamp string nor NULL");
        }
    }

    /**
     * Asserts provided string is valid RFC3339 timestamp string.
     */
    protected static function validateRFC3339(string $stamp): bool
    {
        $RFC3339_REGEXP = '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(?:\.\d{1,3})?((?:[\+\-]\d{2}:\d{2})|Z)$/i';

        return \preg_match($RFC3339_REGEXP, $stamp) === 1;
    }

    protected static function arrayRecursiveDiffCount(array $array_a, array $array_b): int
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
                $diff_count += static::arrayRecursiveDiffCount($a_value, $array_b[ $a_key ]);
            } elseif ($a_value !== $array_b[ $a_key ]) {
                $diff_count++;
            }
        }

        return $diff_count;
    }

    /* **************************************************************************************************** */

    /**
     * Prints content of given array in compacted form.
     *
     * @param array $array  Array to print
     * @param int   $indent Number of indent blocks (2 spaces per block) to add for each nest level
     */
    public static function printArray(array $array, int $indent = 0): void
    {
        $indent_block = '  ';
        $i = \str_repeat($indent_block, $indent + 1);

        foreach ($array as $k => $v) {
            if (\is_array($v)) {
                echo "{$i}{$k}:\n";
                static::printArray($v, $indent + 1);
            } elseif (is_object($v)) {
                try {
                    if ($v instanceof \Stringable) {
                        $v = $v->__toString();
                    } else {
                        $v = \get_class($v);
                    }
                    echo "{$i}{$k}: {$v}\n";
                } catch (\Throwable $ex) {
                    echo "{$i}{$k}: {$ex->getMessage()}\n";
                }
            } else {
                echo "{$i}{$k}: {$v}\n";
            }
        }
    }

    /* **************************************************************************************************** */

    /**
     * Checks if given $val is of type array
     *
     * @param mixed           $value    Variable to be asserted.
     * @param string|string[] $type     Expected type as string (single type) or array of type strings.
     * @param string|null     $var_name Optional name of the variable the content is being asserted for (used
     *                                  to build error message only).
     *
     * @throws Ex\InvalidTypeExceptionContract
     */
    public static function assertIsType(mixed $value, $type, ?string $var_name = null): void
    {
        Validator::assertIsType($value, $type, Ex\InvalidTypeException::class, $var_name);
    }

    /**
     * Checks if given $val is of type array
     *
     * @param mixed       $value    Variable to be asserted.
     * @param string|null $var_name Optional name of the variable the content is being asserted for (used to
     *                              build error message only).
     *
     * @throws Ex\InvalidTypeExceptionContract
     */
    public static function assertIsArray(mixed $value, ?string $var_name = null): void
    {
        Validator::assertIsType($value, [Type::ARRAY], Ex\NotArrayException::class, $var_name);
    }

    /**
     * Checks if given $val is of type boolean
     *
     * @param mixed       $value    Variable to be asserted.
     * @param string|null $var_name Optional name of the variable the content is being asserted for (used to
     *                              build error message only).
     *
     */
    public static function assertIsBool(mixed $value, ?string $var_name = null): void
    {
        Validator::assertIsType($value, [Type::BOOL], Ex\NotBooleanException::class, $var_name);
    }

    /**
     * Checks if given $val is of type float
     *
     * @param mixed       $value    Variable to be asserted.
     * @param string|null $var_name Optional name of the variable the content is being asserted for (used to
     *                              build error message only).
     *
     * @throws Ex\InvalidTypeExceptionContract
     */
    public static function assertIsFloat(mixed $value, ?string $var_name = null): void
    {
        Validator::assertIsType($value, [Type::FLOAT], Ex\NotFloatException::class, $var_name);
    }

    /**
     * Checks if given $val is of type integer
     *
     * @param mixed       $value    Variable to be asserted.
     * @param string|null $var_name Optional name of the variable the content is being asserted for (used to
     *                              build error message only).
     *
     * @throws Ex\InvalidTypeExceptionContract
     * @throws Ex\NotIntegerException
     */
    public static function assertIsInteger(mixed $value, ?string $var_name = null): void
    {
        Validator::assertIsType($value, [Type::INT], Ex\NotIntegerException::class, $var_name);
    }

    /**
     * Checks if given $val is an object
     *
     * @param mixed       $value    Variable to be asserted.
     * @param string|null $var_name Optional name of the variable the content is being asserted for (used to
     *                              build error message only).
     *
     * @throws Ex\InvalidTypeExceptionContract
     */
    public static function assertIsObject(mixed $value, ?string $var_name = null): void
    {
        Validator::assertIsType($value, [Type::OBJECT], Ex\NotObjectException::class, $var_name);
    }

    /**
     * Checks if given $cls_cls_or_obj is either an object or name of existing class.
     *
     * @param string|object $cls_or_obj
     * @param string|null   $var_name Optional name of the variable the content is being asserted for (used to
     *                                build error message only).
     *
     * @throws Ex\InvalidTypeExceptionContract
     */
    public static function assertIsObjectOrExistingClass($cls_or_obj, ?string $var_name = null): void
    {
        Validator::assertIsType($cls_or_obj, [Type::EXISTING_CLASS, Type::OBJECT], $var_name);
    }

    /**
     * Checks if given $val is of type string
     *
     * @param mixed       $value    Variable to be asserted.
     * @param string|null $var_name Optional name of the variable the content is being asserted for (used to
     *                              build error message only).
     *
     * @throws Ex\InvalidTypeExceptionContract
     */
    public static function assertIsString(mixed $value, ?string $var_name = null): void
    {
        Validator::assertIsType($value, [Type::STRING], Ex\NotStringException::class, $var_name);
    }

    /* **************************************************************************************************** */

} // end of trait
