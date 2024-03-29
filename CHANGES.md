# PhpUnit Extra Asserts #

## CHANGELOG ##

* 5.2.0 (2023-03-04)
  * Most asserts now accepts optional message string as PHPUnit's.
  * Added `assertArrayHasKeyValue()`
  * `assertArrayElement()` is now deprecated.
  * Corrected signature of `assertRFC3339OrNull()`.
  * `printArray()`'s indent block string is now exposed as optional parameter.


* 5.1.0 (2022-11-28)
  * Fixed `assertIsInteger()` referencing wrong constant.
  * Corrected the way `printaArray()` handles conversion exceptions.
  * Corrected `assertIsObjectOrExistingClass()` not passing exception to `assertIsType()`
  * Added `Generator` tests.
  * Added PHPStan Github Action.


* 5.0.0 (2022-11-18)
  * `Generator::getRandomBool()` probability now works opposite, and sets the probability of
    drawing the `TRUE` value.
  * `Generator::getRandomFloat()` now will swap min/max args if given max is lower than min.
  * `Generator::getRandomInt()` now will swap min/max args if given max is lower than min.
  * `Generator::getRandomStringOrNull()` probability now works opposite and defines the chance of
    having the `string` value returned.


* 4.0.0 (2022-11-04)
  * Dropped support for PHP 7.
  * The `Bridge` class is no longer part of the package. Use `marcin-orlowski/php-lockpick` instead.

*
* 3.4.0 (2022-09-26)
  * Internal `Validator` and `Type` are replaced by ones provided by `TypeAsserts` package.
  * Exception classes are replaced by ones provided by `TypeAsserts` package.


* 3.3.0 (2022-09-20)
  * Added `getRandomLongitude()` and `getRandomLatitude()` generators.


* 3.2.0 (2022-09-20)
  * Fixed invalid signature of `getRandomFloat()`.
  * Made `Bridge` methods `static`.
  * Added `PHPStan` to development dependencies.
  * Added default configs for `PHPStan`, `markdownlint` and `pre-commit` checkers.
  * Updated documentation.


* 3.1.0 (2022-09-11)
  * Added `Generator` class to factory random values of various types.
  * Added `Bridge` class to manipulate hidden methods/properties/constants.


* 3.0.0 (2022-09-11)
  * [BREAKING] Changed package structure and method access.
  * Requires PHP 7.4 or newer.
  * Removed all deprecated methods.
  * Extra asserts are no longer a `Trait` but regular class.
  * All asserts are now `static` methods.
  * Incorporated `TypeValidator` and related data type asserts into this package.


* 2.0.0 (2022-01-17)
  * [BREAKING] Signature of `assertArraysHaveDifferences()` changed.
  * Fixed `assertArraysHaveDifferences()` (and indirectly `assertArrayEquals()`)
    failing to correctly detect differences when second array has more keys than first one.
  * Added unit tests for all the asserts.
  * Deprecated asserts:
    * `assertArrayContains()` (use `assertContains()` instead),
    * `assertArrayNotContains()` (use `assertNotContains()` instead),
    * `assertArraysEquals()` (use `assertArrayEquals()` instead).
  * Corrected code style.


* 1.3.1 (2022-01-15)
  * Enforced global namespace for all PHP core method calls.
  * `printArray()` now properly deals with objects not implementing `__toString()`.


* 1.3.0 (2021-12-31)
  * Added `assertArrayContains()` and `assertArrayNotContains()` asserts.


* 1.2.1 (2021-01-21)
  * Code cleanup.


* 1.2.0 (2020-10-20)
  * Deprecated `assertArraysEquals()`. Use `assertArrayEquals()` instead.
  * Updated documentation.


* 1.1.0
  * Make all `assertXXX()` methods `public`.
  * Updated documentation.


* 1.0.3
  * Corrected package namespace.
  * Fixed incorrect assert in `assertArraysHaveDifferences()`.
