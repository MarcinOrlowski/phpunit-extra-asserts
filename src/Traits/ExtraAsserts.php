<?php

namespace MarcinOrlowski\PhpunitExtras\Traits;

trait AdditionalAsserts
{
    /**
     * Verify that given array has specified key and it's value is according to expectations.
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

    /**
     * Assert that array has ALL the required keys
     *
     * @param array $required_keys list of required array keys
     * @param array $array         array to check
     */
    protected function assertArrayHasKeys(array $required_keys, array $array): void
    {
        foreach ($required_keys as $key) {
            $this->assertArrayHasKey($key, $array);
        }
    }

    /**
     * Assert given arrays are equivalent
     *
     * @param array $array0
     * @param array $array1
     */
    protected function assertArraysEquals(array $array0, array $array1): void
    {
        $this->assertArraysHaveDifferences($array0, $array1);
    }

    /**
     * Assert given arrays differ in exactly given number of elements.
     *
     * @param array $array0
     * @param array $array1
     * @param int   $expected_diffs
     */
    protected function assertArraysHaveDifferences(array $array0, array $array1, int $expected_diffs = 0): void
    {
        $diff_array = $this->arrayRecursiveDiff($array0, $array1);
        if (count($diff_array) !== $expected_diffs) {
            $this->printArray($diff_array);
        }
        $this->assertCount($expected_diffs, count($diff_array));
    }

    /**
     * Assert if keys from response have the same values as in original array.
     * Keys from $skip_keys are not checked.
     *
     * @param array $original
     * @param array $response
     * @param array $skip_keys
     */
    protected function massAssertEquals(array $original, array $response, array $skip_keys = [])
    {
        foreach ($original as $key => $value) {
            if (in_array($key, $skip_keys)) {
                continue;
            }
            if (is_array($value)) {
                $this->massAssertEquals($original[ $key ], $response[ $key ], $skip_keys);
            } else {
                $orig_type = gettype($original[ $key ]);
                $resp_type = gettype($response[ $key ]);

                if ($orig_type !== $resp_type) {
                    $msg = "Type mismatch for key '{$key}'. Expected '{$orig_type}', found '{$resp_type}'";
                } else {
                    $msg = "Value mismatch for key '{$key}'. Expected '{$original[$key]}', found '{$response[$key]}'";
                }
                $this->assertEquals($value, $response[ $key ], $msg);
            }
        }
    }

    /**
     * Asserts provided string is valid RFC3339 timestamp string
     *
     * @param string $stamp_string
     */
    public function assertRFC3339(string $stamp_string): void
    {
        if (!is_string($stamp_string)) {
            $type = gettype($stamp_string);
            $this->fail("'{$type}' provided. String required");
        }

        if ($this->validateRFC3339($stamp_string) === false) {
            $this->fail("'{$stamp_string}' is not a valid RFC3339 time stamp string");
        }
    }

    /**
     * Asserts provided string is valid RFC3339 timestamp string or @null
     *
     * @param string $stamp_string
     */
    public function assertRFC3339OrNull(string $stamp_string): void
    {
        if (is_null($stamp_string) === false) {
            if (is_string($stamp_string) === fals) {
                $type = gettype($stamp_string);
                $this->fail("'{$type}' provided. String required");
            }

            if ($this->validateRFC3339($stamp_string) === false) {
                $this->fail("'{$stamp_string}' is neither a valid RFC3339 time stamp string nor NULL");
            }
        }
    }

    /**
     * Asserts provided string is valid RFC3339 timestamp string
     *
     * @param string $stamp_string
     *
     * @return bool
     */
    protected function validateRFC3339(string $stamp_string): bool
    {
        $RFC3339_REGEXP = '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(?:\.\d{1,3})?((?:[\+\-]\d{2}:\d{2})|Z)$/i';

        return preg_match($RFC3339_REGEXP, $stamp_string) === 1;
    }

    /**
     * Helper method to recursively diff two arrays
     *
     * @param $array0
     * @param $array1
     *
     * @return array
     */
    protected function arrayRecursiveDiff($array0, $array1)
    {
        $return_array = [];

        foreach ($array0 as $m_key => $m_value) {
            if (array_key_exists($m_key, $array1)) {
                if (is_array($m_value)) {
                    $a_recursive_diff = $this->arrayRecursiveDiff($m_value, $array1[ $m_key ]);
                    if (count($a_recursive_diff)) {
                        $return_array[ $m_key ] = $a_recursive_diff;
                    }
                } else {
                    if ($m_value !== $array1[ $m_key ]) {
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
     * @param $search_array
     * @param $search_key
     * @param $search_value
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
     * Print content of given array in compacted form
     *
     * @param array $array
     * @param int   $indent
     */
    public function printArray(array $array, int $indent = 0): void
    {
        $i = '  ' . substr('                        ', 0, $indent * 2);

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