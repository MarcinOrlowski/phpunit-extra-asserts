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

    /* **************************************************************************************************** */

    /**
     * Generates random string, with optional prefix
     *
     * @param string|null $prefix                 Optional prefix to be added to generated string.
     * @param int         $length                 Length of the string to be generated.
     * @param string      $separator              Optional prefix separator.
     * @param float       $stringValueProbability Probability (float value in range 0-1) specifying the
     *                                            chance of drawing the string value instead of `NULL`. The
     *                                            higher the value of this argument, the higher the chance
     *                                            for `string` being returned.
     *
     * @throws \Exception
     */
    public static function getRandomStringOrNull(?string $prefix = null, int $length = 24,
                                                 string  $separator = '_',
                                                 float   $stringValueProbability = 0.5): ?string
    {
        if (static::getRandomBool($stringValueProbability)) {
            return static::getRandomString($prefix, $length, $separator);
        }
        return null;
    }

    /* **************************************************************************************************** */

    /**
     * Generate Random float value
     *
     * @param float $min              Lowest allowed value.
     * @param float $max              Highest allowed value.
     * @param int   $fractionalDigits The optional number of decimal digits to round to. Default 0 means not
     *                                rounding.
     *
     * @return float
     */
    public static function getRandomFloat(float $min, float $max, int $fractionalDigits = 0): float
    {
        if ($min > $max) {
            $tmp = $min;
            $min = $max;
            $max = $tmp;
        }

        $result = $min + \mt_rand() / \mt_getrandmax() * ($max - $min);
        if ($fractionalDigits > 0) {
            $result = \round($result, $fractionalDigits);
        }

        return $result;
    }

    /* **************************************************************************************************** */

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
        if ($max < $min) {
            $tmp = $min;
            $min = $max;
            $max = $tmp;
        }
        return \random_int($min, $max);
    }

    /* **************************************************************************************************** */

    /**
     * Draws random boolean value. Returns TRUE if value is higher than specified threshold, FALSE otherwise.
     *
     * @param float $trueValueProbability Float value (in 0-1 range) defining the chances of drawing the
     *                                    value of `TRUE`. The higher the of this argument, the higher the
     *                                    chances to draw `TRUE`.
     *
     * @throws \Exception
     */
    public static function getRandomBool(float $trueValueProbability = 0.5): bool
    {
        $rand = \random_int(0, 999) / 1000;
        return $rand < $trueValueProbability;
    }

    /* **************************************************************************************************** */

    /** @var float */
    public const LATITUDE_MIN = -90.0;

    /** @var float */
    public const LATITUDE_MAX = 90.0;

    /** @var float */
    public const LONGITUDE_MIN = -180.0;

    /** @var float */
    public const LONGITUDE_MAX = 180.0;

    /**
     * Returns random latitude coordinate (WGS84)
     *
     * @param float $min              Minimal value (default: -90).
     * @param float $max              Maximal value (default: +90).
     * @param int   $fractionalDigits The optional number of fractional digits to round to. Default 0 means
     *                                not rounding.
     *
     * @return float
     */
    public static function getRandomLatitude(float $min = self::LATITUDE_MIN,
                                             float $max = self::LATITUDE_MAX,
                                             int   $fractionalDigits = 0): float
    {
        return static::getRandomFloat($min, $max, $fractionalDigits);
    }

    /**
     * Returns random longitude coordinate (WGS84)
     *
     * @param float $min    Minimal value (default: -180).
     * @param float $max    Maximal value (default: +180).
     * @param int   $digits The optional number of decimal digits to round to.Default 0 means not rounding.
     *
     * @return float
     */
    public static function getRandomLongitude(float $min = self::LONGITUDE_MIN,
                                              float $max = self::LONGITUDE_MAX,
                                              int   $digits = 0): float
    {
        return static::getRandomFloat($min, $max, $digits);
    }

} // end of Generator class
