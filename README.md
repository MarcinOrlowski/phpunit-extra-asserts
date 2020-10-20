# PhpUnit Extras Asserts #

[![Latest Stable Version](https://poser.pugx.org/marcin-orlowski/phpunit-extra-asserts/v/stable)](https://packagist.org/packages/marcin-orlowski/phpunit-extra-asserts)
[![License](https://poser.pugx.org/marcin-orlowski/phpunit-extra-asserts/license)](https://packagist.org/packages/marcin-orlowski/phpunit-extra-asserts)


Collection of additional asserts to be used with PHP Unit testing framework.

## Installation ##

    composer require-dev marcin-orlowski/phpunit-extra-asserts

## Usage ##

 As ExtraAsserts come as trait, you just need to add related `use` to your
 base test class and all the methods should be simply available as `$this->assertName()`:

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

|Assert|Description|
|---|---|
|assertArrayElement(string $key, array $array, string $expected_value)|Asserts given array has specified key and it's value is according to expectations.|
|assertArrayHasKeys(array $required_keys, array $array)|Asserts array has ALL the required keys.|
|assertArrayEquals(array $arrayA, array $arrayB)|Asserts two arrays are equivalent.|
|assertArraysHaveDifferences(array $arrayA, array $arrayB, int $diff_count)|Asserts two arrays differ by exactly given number of elements (default is 0).|
|massAssertEquals(array $arrayA, array $arrayB, array $ignored_keys)|Assert if keys from response have the same values as in original array. Keys listed in $skip_keys are ignored.|
|assertRFC3339(string $stamp)|Asserts provided string is valid RFC3339 timestamp string.|
|assertRFC3339OrNull(string $stamp)|Asserts $stamp string is valid RFC3339 timestamp string or @null.|

## Helper methods ##

|Method|Description|
|---|---|
|printArray(array $array, int $indent)|Prints content of given array in compacted form.|


----

## License ##

 * Written and copyrighted &copy;2014-2020 by Marcin Orlowski
 * PhpUnit-Extras is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

