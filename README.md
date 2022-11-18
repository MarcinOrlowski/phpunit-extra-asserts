# PhpUnit Extras Asserts #

[![Latest Stable Version](https://poser.pugx.org/marcin-orlowski/phpunit-extra-asserts/v/stable)](https://packagist.org/packages/marcin-orlowski/phpunit-extra-asserts)
[![License](https://poser.pugx.org/marcin-orlowski/phpunit-extra-asserts/license)](https://packagist.org/packages/marcin-orlowski/phpunit-extra-asserts)

Collection of additional asserts to be used with [PHP Unit](https://phpunit.de) testing framework.
Helpers are split into dedicated namespaces:

* `ExtraAsserts`: various asserts to help testing your code,
* `Generator`: various helper methods producing random values for your tests,

## Installation ##

```bash
composer require-dev marcin-orlowski/phpunit-extra-asserts
```

## Usage ##

As ExtraAsserts come as set of static methods so you just need to add related `use` to your test
class and all
the methods should be simply available via static reference `ExtraAsserts::...`. For example:

```php
use \MarcinOrlowski\PhpunitExtraAsserts\ExtraAsserts;

class MyBaseTestClass extends ... {

    use \MarcinOrlowski\PhpunitExtraAsserts\ExtraAsserts;
    use \MarcinOrlowski\PhpunitExtraAsserts\Type;

    [...]

    public function testSomething(): void
    {
        [...]

        ExtraAsserts::assertIsType($val, [Type::STRING, Type::BOOL]);
        ExtraAsserts::assertRFC3339($stamp);
    }
}
```

## Available asserts ##

| Assert                                                                               | Description                                                                                                     | Added  |
|--------------------------------------------------------------------------------------|-----------------------------------------------------------------------------------------------------------------|--------|
| assertArrayElement(string $key, array $array, string $expected_value)                | Asserts given array has specified key and it's value is according to expectations.                              | v1.0.0 |
| assertArrayEquals(array $array_a, array $array_b)                                    | Asserts two arrays are equivalent.                                                                              | v1.0.0 |
| assertArrayHasKeys(array $required_keys, array $array)                               | Asserts array has ALL the required keys.                                                                        | v1.0.0 |
| assertArraysHaveDifferences(int $diff_count, array $array_a, array $array_b)         | Asserts two arrays differ by exactly given number of elements (default is 0).                                   | v1.0.0 |
| assertRFC3339(string $stamp)                                                         | Asserts provided string is valid RFC3339 timestamp string.                                                      | v1.0.0 |
| assertRFC3339OrNull(string $stamp)                                                   | Asserts $stamp string is valid RFC3339 timestamp string or @null.                                               | v1.0.0 |
| massAssertEquals(array $array_a, array $array_b, array $ignored_keys)                | Assert if keys from response have the same values as in original array. Keys listed in $skip_keys are ignored.  | v1.0.0 |
| assertIsArray(mixed $value, ?string $var_name = null)                                | Asserts given $value (which the value of variable named $var_name) is an array.                                 | v3.0.0 |
| assertIsBool(mixed $value, ?string $var_name = null)                                 | Asserts given $value (which the value of variable named $var_name) is a boolean.                                | v3.0.0 |
| assertIsFloat(mixed $value, ?string $var_name = null)                                | Asserts given $value (which the value of variable named $var_name) is an float.                                 | v3.0.0 |
| assertIsInteger(mixed $value, ?string $var_name = null)                              | Asserts given $value (which the value of variable named $var_name) is an integer.                               | v3.0.0 |
| assertIsInteger(mixed $value, ?string $var_name = null)                              | Asserts given $value (which the value of variable named $var_name) is an integer.                               | v3.0.0 |
| assertIsObject(mixed $value, ?string $var_name = null)                               | Asserts given $value (which the value of variable named $var_name) is an object (of any class).                 | v3.0.0 |
| assertIsObjectOrExistingClass(string OR object $cls_or_obj, ?string $var_nam = null) | Asserts given $value (which the value of variable named $var_name) is an object or name name of existing class. | v3.0.0 |
| assertIsString(mixed $value, ?string $var_name = null)                               | Asserts given $value (which the value of variable named $var_name) is an string.                                | v3.0.0 |

## Helper methods ##

| Method                                | Description                                      |
|---------------------------------------|--------------------------------------------------|
| printArray(array $array, int $indent) | Prints content of given array in compacted form. |

## Generator methods ##

| Method                                                                                                                      | Description                                                                                                                                                                                                                                  |
|-----------------------------------------------------------------------------------------------------------------------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| getRandomString(?string $prefix = null, int $length = 24, string $separator = '_')                                          | Returns string of total length equal to specified limit (incl. optional prefix or separator strings)                                                                                                                                         |
| getRandomStringOrNull(?string $prefix = null, int $length = 24, string $separator = '_', float $trueValueProbability = 0.5) | Returns either random `string` or `NULL`. Probability (float value in range 0-1) specifies the chance of drawing the `string` value. The higher the value of this argument, the higher the chance for `string` being returned.               |
| getRandomFloat(float $min, float $max, int $fractionalDigits = 0)                                                           | Draws random `float` from given range. If given `max` is lower than `min`, these two will be swapped.  If number of fractional digits is non-zero, value will be rounded to given number of fractional digits. Value `0` means not rounding. |
| getRandomInt(int $min = 0, int $max = 100)                                                                                  | Draws random `int` from given range. If given `max` is lower than `min`, these two will be swapped.                                                                                                                                          |
| getRandomBool(float $trueValueProbability = 0.5)                                                                            | Draws random `boolean` value. Float value (in 0-1 range) defining the chances of drawing the value of `TRUE`. The higher the of this argument, the higher the chances to draw `TRUE`.                                                        |
| getRandomLatitude(float $min, float $max, int $round=0)                                                                     | Draws random latitude from given range (default is max allowed range). If given `max` is lower than `min`, these two will be swapped.                                                                                                        |
| getRandomLongitude(float $min, float $max, int $round=0)                                                                    | Draws random longitude from given range (default is max allowed range). If given `max` is lower than `min`, these two will be swapped.                                                                                                       |

----

## License ##

* Written and copyrighted &copy;2014-2022 by Marcin Orlowski
* Open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
