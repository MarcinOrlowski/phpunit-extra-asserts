# PhpUnit Extras Asserts #

[![Latest Stable Version](https://poser.pugx.org/marcin-orlowski/phpunit-extra-asserts/v/stable)](https://packagist.org/packages/marcin-orlowski/phpunit-extra-asserts)
[![License](https://poser.pugx.org/marcin-orlowski/phpunit-extra-asserts/license)](https://packagist.org/packages/marcin-orlowski/phpunit-extra-asserts)

Collection of additional asserts to be used with [PHP Unit](https://phpunit.de) testing framework.

## Installation ##

    composer require-dev marcin-orlowski/phpunit-extra-asserts

## Usage ##

As ExtraAsserts come as trait, you just need to add related `use` to your base test class and all
the methods should be simply available as `$this->assertName()`:

    class MyBaseTestClass extends ... {

        use \MarcinOrlowski\PhpunitExtraAsserts\Traits\ExtraAsserts;


        [...]

        public function testSuccessWithExplicitNull(): void
        {
            [...]

            $this->assertRFC3339($stamp);
        }
    }

## Available asserts ##

| Assert                                                                       | Description                                                                                                    | Added  |
|------------------------------------------------------------------------------|----------------------------------------------------------------------------------------------------------------|--------|
| assertArrayElement(string $key, array $array, string $expected_value)        | Asserts given array has specified key and it's value is according to expectations.                             | v1.0.0 |
| assertArrayEquals(array $array_a, array $array_b)                            | Asserts two arrays are equivalent.                                                                             | v1.0.0 |
| assertArrayHasKeys(array $required_keys, array $array)                       | Asserts array has ALL the required keys.                                                                       | v1.0.0 |
| assertArraysHaveDifferences(int $diff_count, array $array_a, array $array_b) | Asserts two arrays differ by exactly given number of elements (default is 0).                                  | v1.0.0 |
| assertRFC3339(string $stamp)                                                 | Asserts provided string is valid RFC3339 timestamp string.                                                     | v1.0.0 |
| assertRFC3339OrNull(string $stamp)                                           | Asserts $stamp string is valid RFC3339 timestamp string or @null.                                              | v1.0.0 |
| massAssertEquals(array $array_a, array $array_b, array $ignored_keys)        | Assert if keys from response have the same values as in original array. Keys listed in $skip_keys are ignored. | v1.0.0 |
| [DEPRECATED] assertArrayContains(array $array, $item)                        | Asserts $array contains specified $item.                                                                       | v1.3.0 |
| [DEPRECATED] assertArrayNotContain(array $array, $item)                      | Asserts $array does NOT contain specified $item.                                                               | v1.3.0 |

## Helper methods ##

|Method|Description|
|---|---|
|printArray(array $array, int $indent)|Prints content of given array in compacted form.|

----

## License ##

* Written and copyrighted &copy;2014-2022 by Marcin Orlowski
* PhpUnit-Extra-Asserts is open-sourced software licensed under
  the [MIT license](http://opensource.org/licenses/MIT)
