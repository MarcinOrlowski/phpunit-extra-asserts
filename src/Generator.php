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

class Generator
{

    /**
     * Generates random string, with optional prefix
     *
     * @param string|null $prefix    Optional prefix to be added to generated string.
     * @param int         $length    Length of the string to be generated.
     * @param string      $separator Optional prefix separator.
     */
    public static function getRandomString(?string $prefix = null, int $length = 24,
                                           string  $separator = '_'): string
    {
        if ($length < 1) {
            throw new \InvalidArgumentException('Length must be greater than 0');
        }
        if ($prefix !== null) {
            $margin = 3;
            if ($length < (\strlen($prefix) + $margin)) {
                $msg = "String length cannot be smaller than prefix length + {$margin} chars";
                throw new \InvalidArgumentException($msg);
            }
            $prefix .= $separator;
        }

        return \substr(($prefix ?? '') . \md5(\uniqid('', true)), 0, $length);
    }

    /**
     * Generates random string, with optional prefix
     *
     * @param string|null $prefix      Optional prefix to be added to generated string.
     * @param int         $length      Length of the string to be generated.
     * @param string      $separator   Optional prefix separator.
     * @param float       $probability Probability (float value in range 0-1) specifying of string
     *                                 being returned in the drawing. Drawings are done with three
     *                                 digits precision. Default value is 0.5 (50%).
     *
     * @throws \Exception
     */
    public static function getRandomStringOrNull(?string $prefix = null, int $length = 24,
                                                 string  $separator = '_',
                                                 float   $probability = 0.5): ?string
    {
        /** @var float $rand */
        $rand = \random_int(0, 999) / 1000;
        if ($rand >= $probability) {
            return static::getRandomString($prefix, $length, $separator);
        }
        return null;
    }

    /**
     * Generate Random float value
     *
     * @param float $min    Lowest allowed value.
     * @param float $max    Highest allowed value.
     * @param int   $digits The optional number of decimal digits to round to.
     *                      Default 0 means not rounding.
     *
     * @return float
     */
    public static static function getRandomFloat(float $min, float $max, int $digits = 0): float
    {
        $result = $min + \mt_rand() / \mt_getrandmax() * ($max - $min);
        if ($digits > 0) {
            $result = \round($result, $digits);
        }

        return $result;
    }

    /**
     * Generates random integer value from withing specified range.
     *
     * @param int $min Min allowed value (inclusive)
     * @param int $max Max allowed value (inclusive)
     *
     * @return int
     *
     * @throws \Exception
     */
    public static function getRandomInt(int $min = 0, int $max = 100): int
    {
        return \random_int($min, $max);
    }

    /**
     * Draws random boolean value.
     *
     * @throws \Exception
     */
    public static function getRandomBool(float $probability = 0.5): bool
    {
        $rand = \random_int(0, 999) / 1000;
        return $rand > $probability;
    }


    /* **************************************************************************************************** */

} // end of trait
