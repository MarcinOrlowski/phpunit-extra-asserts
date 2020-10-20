<?php

namespace MarcinOrlowski\PhpunitExtraAsserts\Traits;

/**
 * PhpUnit Extra Asserts
 *
 * @package   MarcinOrlowski\PhpunitExtraAsserts
 *
 * @author    Marcin Orlowski <mail (#) marcinOrlowski (.) com>
 * @copyright 2014-2020 Marcin Orlowski
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      https://github.com/MarcinOrlowski/phpunit-extra-asserts
 */
trait ExtraAsserts
{
	/**
	 * Asserts array has specified key and it's value is according to expectations.
	 *
	 * @param string $key
	 * @param array  $array
	 * @param string $expected_value
	 */
	public function assertArrayElement(string $key, array $array, string $expected_value): void
	{
		$this->assertArrayHasKey($key, $array, var_export($array, true));
		$this->assertEquals($expected_value, $array[ $key ], var_export($array, true));
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
	 * @obsolete Please use assertArrayEquals() instead
	 */
	public function assertArraysEquals(array $arrayA, array $arrayB): void
	{
		$this->assertArrayEquals($arrayA, $arrayB);
	}

	/**
	 * Asserts arrayA equals $arrayB which means both array contain the
	 * same content, yet the order of data is not taken into account.
	 * For example ['foo','bar'] equals ['bar','foo'] as content is the same
	 * ['key1'=>'foo','key2'=>'bar'] differs from ['key1'=>'bar','key2'=>'foo'].
	 *
	 * @param array $arrayA Array A to compare content of
	 * @param array $arrayB Array B to compare content of
	 */
	public function assertArrayEquals(array $arrayA, array $arrayB): void
	{
		$this->assertArraysHaveDifferences($arrayA, $arrayB);
	}

	/**
	 * Asserts two arrays differ by exactly given number of elements.
	 *
	 * @param array $arrayA             Array A to compare content of
	 * @param array $arrayB             Array B to compare content of
	 * @param int   $allowed_diff_count Exact number of allowed differences to still consider arrays equal (default is 0)
	 */
	protected function assertArraysHaveDifferences(array $arrayA, array $arrayB,
	                                               int $allowed_diff_count = 0): void
	{
		$diff_array = $this->arrayRecursiveDiff($arrayA, $arrayB);
		if (count($diff_array) !== $allowed_diff_count) {
			$this->printArray($diff_array);
		}
		$this->assertEquals($allowed_diff_count, count($diff_array));
	}

	/**
	 * Assert if keys from response have the same values as in original array.
	 * Keys listed in $skip_keys are ignored.
	 *
	 * @param array $arrayA       Array A to compare content of
	 * @param array $arrayB       Array B to compare content of
	 * @param array $ignored_keys Array of keys that will be ignored during comparision (as they never existed)
	 */
	public function massAssertEquals(array $arrayA, array $arrayB, array $ignored_keys = [])
	{
		foreach ($arrayA as $key => $value) {
			if (in_array($key, $ignored_keys)) {
				continue;
			}
			if (is_array($value)) {
				$this->massAssertEquals($arrayA[ $key ], $arrayB[ $key ], $ignored_keys);
			} else {
				$orig_type = gettype($arrayA[ $key ]);
				$resp_type = gettype($arrayB[ $key ]);

				if ($orig_type !== $resp_type) {
					$msg = "Type mismatch for key '{$key}'. Expected '{$orig_type}', found '{$resp_type}'";
				} else {
					$msg = "Value mismatch for key '{$key}'. Expected '{$arrayA[$key]}', found '{$arrayB[$key]}'";
				}
				$this->assertEquals($value, $arrayB[ $key ], $msg);
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
		if (!is_string($stamp)) {
			$type = gettype($stamp);
			$this->fail("'{$type}' provided. String required");
		}

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
		if (is_null($stamp) === false) {
			if (is_string($stamp) === fals) {
				$type = gettype($stamp);
				$this->fail("'{$type}' provided. String required");
			}

			if ($this->validateRFC3339($stamp) === false) {
				$this->fail("'{$stamp}' is neither a valid RFC3339 time stamp string nor NULL");
			}
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

		return preg_match($RFC3339_REGEXP, $stamp) === 1;
	}

	/**
	 * Helper method to recursively diff two arrays
	 *
	 * @param array $arrayA
	 * @param array $arrayB
	 *
	 * @return array
	 */
	protected function arrayRecursiveDiff(array $arrayA, array $arrayB): array
	{
		$return_array = [];

		foreach ($arrayA as $m_key => $m_value) {
			if (array_key_exists($m_key, $arrayB)) {
				if (is_array($m_value)) {
					$a_recursive_diff = $this->arrayRecursiveDiff($m_value, $arrayB[ $m_key ]);
					if (count($a_recursive_diff)) {
						$return_array[ $m_key ] = $a_recursive_diff;
					}
				} else {
					if ($m_value !== $arrayB[ $m_key ]) {
						$return_array[ $m_key ] = $m_value;
					}
				}
			} else {
				$return_array[ $m_key ] = $m_value;
			}
		}

		return $return_array;
	}

	/**
	 * Search given array for the first element containing given key/value pair
	 *
	 * @param array  $search_array Array to search
	 * @param string $search_key   Key to look for
	 * @param mixed  $search_value Value of $key to match
	 *
	 * @return mixed|null
	 */
	protected function findInArrayElementContainKeyValue(array $search_array, string $search_key, $search_value)
	{
		foreach ($search_array as $array_element) {
			if ((array_key_exists($search_key, $array_element)) && ($array_element[ $search_key ] == $search_value)) {
				return $array_element;
			}
		}

		return null;
	}

	/**
	 * Prints content of given array in compacted form.
	 *
	 * @param array $array  Array to print
	 * @param int   $indent Number of indent blocks (2 spaces per block) to add for each nest level
	 */
	public function printArray(array $array, int $indent = 0): void
	{
		$indent_block = '  ';
		$i = str_repeat($indent_block, $indent + 1);

		foreach ($array as $k => $v) {
			if (is_array($v)) {
				echo "{$i}{$k}:\n";
				$this->printArray($v, $indent + 1);
			} else {
				echo "{$i}{$k}: {$v}\n";
			}
		}
	}

}
