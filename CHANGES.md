# PhpUnit Extra Asserts #

## CHANGELOG ##

* dev
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
