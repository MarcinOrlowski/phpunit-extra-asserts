# PhpUnit Extra Asserts #

## CHANGELOG ##

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
