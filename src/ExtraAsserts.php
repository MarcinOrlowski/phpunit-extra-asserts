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
     * @deprecated Use assertArrayHasKeyValue() instead
     */
    public static function assertArrayElement(string $key, array $array, string $expectedValue): void
    {
        $msg = "Key not found: {$key}";
        Assert::assertArrayHasKey($key, $array, $msg);
        $msg = "Value for key '{$key}' is not as expected: {$expectedValue}";
        Assert::assertEquals($expectedValue, $array[ $key ], $msg);
    }

    /**
     * Asserts array has specified key and it's value is according to expectations.
     *
     * @param string  $expectedKey   Key to look for
     * @param mixed   $expectedValue Value expected to be found ad $key element.
     * @param array   $array         Array to inspect.
     * @param ?string $message       Optional custom message to display on failure.
     */
    public static function assertArrayHasKeyValue(string  $expectedKey,
                                                  mixed   $expectedValue,
                                                  array   $array,
                                                  ?string $message = null): void
    {
        $msg = $message ?? "Key not found: {$expectedKey}";
        Assert::assertArrayHasKey($expectedKey, $array, $msg);
        $msg = $message ?? "Value for key '{$expectedKey}' is not as expected.";
        Assert::assertEquals($expectedValue, $array[ $expectedKey ], $msg);
    }

    /**s
     * Asserts array has ALL the required keys
     *
     * @param array   $requiredKeys list of required array keys
     * @param array   $array        array to check
     * @param ?string $message      Optional custom message to display on failure.
     */
    public static function assertArrayHasKeys(array   $requiredKeys,
                                              array   $array,
                                              ?string $message = null): void
    {
        foreach ($requiredKeys as $key) {
            Assert::assertArrayHasKey($key, $array, $message ?? '');
        }
    }

    /**
     * Asserts $actual equals $expected which means both array contain the same content, yet the order of data
     * is not taken into account. For example ['foo','bar'] equals ['bar','foo'] as content is the same
     * ['key1'=>'foo','key2'=>'bar'] differs from ['key1'=>'bar','key2'=>'foo'].
     *
     * @param array   $expected Expected content of the array.
     * @param array   $actual   Actual content of the array to compare with expectations.
     * @param ?string $message  Optional custom message to display on failure.
     */
    public static function assertArrayEquals(array $expected, array $actual, ?string $message = null): void
    {
        static::assertArraysHaveDifferences(0, $expected, $actual, $message);
    }

    /**
     * Asserts two arrays differ by exactly given $count number of elements.
     *
     * @param int     $count    Expected number of differences between arrays
     * @param array   $expected Expected content of the array.
     * @param array   $actual   Actual content of the array to compare with expectations.
     * @param ?string $message  Optional custom message to display on failure.
     */
    public static function assertArraysHaveDifferences(int     $count,
                                                       array   $expected,
                                                       array   $actual,
                                                       ?string $message = null): void
    {
        $diffCount = static::arrayRecursiveDiffCount($expected, $actual);
        $msg = $message ?? "Expected {$count} differences, found {$diffCount}";
        Assert::assertEquals($count, $diffCount, $msg);
    }

    /**
     * Assert if keys from response have the same values as in original array.
     * Keys listed in $skip_keys are ignored.
     *
     * @param array    $expected     Expected content of the array.
     * @param array    $actual       Actual content of the array to compare with expectations.
     * @param string[] $keysToIgnore Array of keys that will be ignored during comparison (as they never
     *                               existed).
     */
    public static function massAssertEquals(array $expected, array $actual, array $keysToIgnore = []): void
    {
        foreach ($expected as $key => $value) {
            if (\in_array($key, $keysToIgnore, true)) {
                continue;
            }
            if (\is_array($value)) {
                static::massAssertEquals($value, $actual[ $key ], $keysToIgnore);
            } else {
                $origType = \gettype($value);
                $actualType = \gettype($actual[ $key ]);

                if ($origType !== $actualType) {
                    $msg = "Type mismatch for key '{$key}'. Expected '{$origType}', found '{$actualType}'";
                } else {
                    $msg = "Value mismatch for key '{$key}'. Expected '{$value}', found '{$actual[$key]}'";
                }
                Assert::assertEquals($value, $actual[ $key ], $msg);
            }
        }
    }

    /**
     * Asserts provided string is valid RFC3339 timestamp string.
     *
     * @param string  $stamp   String to check against RFC3339 format.
     * @param ?string $message Optional custom message to display on failure.
     */
    public static function assertRFC3339(string $stamp, ?string $message = null): void
    {
        if (static::validateRFC3339($stamp) === false) {
            Assert::fail($message ?? "'{$stamp}' is not a valid RFC3339 time stamp string");
        }
    }

    /**
     * Asserts $stamp string is valid RFC3339 timestamp string or @null.
     *
     * @param ?string $stamp   String to check against RFC3339 format.
     * @param ?string $message Optional custom message to display on failure.
     */
    public static function assertRFC3339OrNull(?string $stamp, ?string $message = null): void
    {
        if ($stamp !== null && static::validateRFC3339($stamp) === false) {
            Assert::fail($message ?? "'{$stamp}' is neither a valid RFC3339 time stamp string nor NULL");
        }
    }

    /**
     * Asserts provided string is valid RFC3339 timestamp string.
     */
    protected static function validateRFC3339(string $stamp): bool
    {
        $regExp = '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(?:\.\d{1,3})?((?:[\+\-]\d{2}:\d{2})|Z)$/i';

        return \preg_match($regExp, $stamp) === 1;
    }

    protected static function arrayRecursiveDiffCount(array $arrayA, array $arrayB): int
    {
        $diff_count = 0;

        $countA = \count($arrayA);
        $countB = \count($arrayB);

        // If arrayA is bigger (more keys) then we can need to count key count difference separately.
        // In case arrayA is bigger, we detect missing keys in the loop below.
        if ($countB > $countA) {
            $diff_count = \abs($countA - $countB);
        }

        foreach ($arrayA as $KeyOfA => $valueOfA) {
            if (!\array_key_exists($KeyOfA, $arrayB)) {
                $diff_count++;
                continue;
            }

            if (\is_array($valueOfA) && \is_array($arrayB[ $KeyOfA ])) {
                $diff_count += static::arrayRecursiveDiffCount($valueOfA, $arrayB[ $KeyOfA ]);
            } elseif ($valueOfA !== $arrayB[ $KeyOfA ]) {
                $diff_count++;
            }
        }

        return $diff_count;
    }

    /* **************************************************************************************************** */

    /**
     * Prints content of given array in compacted form.
     *
     * @param array  $array       Array to print
     * @param int    $indent      Number of indent blocks to add for each nest level
     * @param string $indentBlock String to use for indenting.
     */
    public static function printArray(array $array, int $indent = 0, string $indentBlock = '  '): void
    {
        $i = \str_repeat($indentBlock, $indent + 1);

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
     * @param string|null     $varName  Optional name of the variable the content is being asserted for (used
     *                                  to build error message only).
     *
     * @throws Ex\InvalidTypeExceptionContract
     */
    public static function assertIsType(mixed $value, string|array $type, ?string $varName = null): void
    {
        Validator::assertIsType($value, $type, Ex\InvalidTypeException::class, $varName);
    }

    /**
     * Checks if given $val is of type array
     *
     * @param mixed       $value    Variable to be asserted.
     * @param string|null $varName  Optional name of the variable the content is being asserted for (used to
     *                              build error message only).
     *
     * @throws Ex\InvalidTypeExceptionContract
     */
    public static function assertIsArray(mixed $value, ?string $varName = null): void
    {
        Validator::assertIsType($value, [Type::ARRAY], Ex\NotArrayException::class, $varName);
    }

    /**
     * Checks if given $val is of type boolean
     *
     * @param mixed       $value    Variable to be asserted.
     * @param string|null $varName  Optional name of the variable the content is being asserted for (used to
     *                              build error message only).
     *
     */
    public static function assertIsBool(mixed $value, ?string $varName = null): void
    {
        Validator::assertIsType($value, [Type::BOOL], Ex\NotBooleanException::class, $varName);
    }

    /**
     * Checks if given $val is of type float
     *
     * @param mixed       $value    Variable to be asserted.
     * @param string|null $varName  Optional name of the variable the content is being asserted for (used to
     *                              build error message only).
     *
     * @throws Ex\InvalidTypeExceptionContract
     */
    public static function assertIsFloat(mixed $value, ?string $varName = null): void
    {
        Validator::assertIsType($value, [Type::FLOAT], Ex\NotFloatException::class, $varName);
    }

    /**
     * Checks if given $val is of type integer
     *
     * @param mixed       $value    Variable to be asserted.
     * @param string|null $varName  Optional name of the variable the content is being asserted for (used to
     *                              build error message only).
     *
     * @throws Ex\InvalidTypeExceptionContract
     * @throws Ex\NotIntegerException
     */
    public static function assertIsInteger(mixed $value, ?string $varName = null): void
    {
        Validator::assertIsType($value, [Type::INT], Ex\NotIntegerException::class, $varName);
    }

    /**
     * Checks if given $val is an object
     *
     * @param mixed       $value    Variable to be asserted.
     * @param string|null $varName  Optional name of the variable the content is being asserted for (used to
     *                              build error message only).
     *
     * @throws Ex\InvalidTypeExceptionContract
     */
    public static function assertIsObject(mixed $value, ?string $varName = null): void
    {
        Validator::assertIsType($value, [Type::OBJECT], Ex\NotObjectException::class, $varName);
    }

    /**
     * Checks if given $cls_cls_or_obj is either an object or name of existing class.
     *
     * @param string|object $classOrObject
     * @param string|null   $varName  Optional name of the variable the content is being asserted for (used to
     *                                build error message only).
     *
     * @throws Ex\InvalidTypeExceptionContract
     */
    public static function assertIsObjectOrExistingClass(string|object $classOrObject,
                                                         ?string       $varName = null): void
    {
        // FIXME Should throw more specific exception instead of Ex\NotObjectException::class
        Validator::assertIsType($classOrObject, [Type::EXISTING_CLASS,
                                                 Type::OBJECT,
        ], Ex\NotObjectException::class, $varName);
    }

    /**
     * Checks if given $val is of type string
     *
     * @param mixed       $value    Variable to be asserted.
     * @param string|null $varName  Optional name of the variable the content is being asserted for (used to
     *                              build error message only).
     *
     * @throws Ex\InvalidTypeExceptionContract
     */
    public static function assertIsString(mixed $value, ?string $varName = null): void
    {
        Validator::assertIsType($value, [Type::STRING], Ex\NotStringException::class, $varName);
    }

    /* **************************************************************************************************** */

} // end of trait
