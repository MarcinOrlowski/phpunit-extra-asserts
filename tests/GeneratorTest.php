<?php

/**
 * PHP Unit Extra Asserts
 *
 * @package   MarcinOrlowski\PhpUnitExtraAsserts
 *
 * @author    Marcin Orlowski <mail (#) marcinOrlowski (.) com>
 * @copyright 2019-2022 Marcin Orlowski
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      https://github.com/MarcinOrlowski/phpunit-extra-asserts
 */

use MarcinOrlowski\PhpunitExtraAsserts\Generator;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class GeneratorTest extends TestCase
{
    public function testRandomFloatInRange(): void
    {
        $min = Generator::getRandomFloat(-100, -10);
        $max = Generator::getRandomFloat(10, 100);
        Assert::assertTrue($min < $max);

        $val = Generator::getRandomFloat($min, $max);

        Assert::assertTrue($val >= $min && $val <= $max);
    }

    public function testRandomFloatInRangeMinMaxBothNegative(): void
    {
        $min = Generator::getRandomFloat(-100, -50);
        $max = Generator::getRandomFloat(-40, -5);
        Assert::assertTrue($min < $max);

        $val = Generator::getRandomFloat($min, $max);

        Assert::assertTrue($val >= $min && $val <= $max);
    }

    public function testRandomFloatInRangeMinMaxSwapped(): void
    {
        $min = Generator::getRandomFloat(-100, -10);
        $max = Generator::getRandomFloat(10, 100);
        Assert::assertTrue($min < $max);

        $val = Generator::getRandomFloat($max, $min);

        Assert::assertTrue($val >= $min && $val <= $max);
    }

    /* ****************************************************************************************** */

    public function testRandomLatitude(): void
    {
        $min = Generator::getRandomFloat(Generator::LATITUDE_MIN, -1);
        $max = Generator::getRandomFloat(1, Generator::LATITUDE_MAX);
        Assert::assertTrue($min < $max);

        $val = Generator::getRandomLatitude($min, $max);
        Assert::assertTrue($val >= $min && $val <= $max);
    }

    public function testRandomLatitudeSwappedMinMax(): void
    {
        $min = Generator::getRandomFloat(Generator::LATITUDE_MIN, -1);
        $max = Generator::getRandomFloat(1, Generator::LATITUDE_MAX);
        Assert::assertTrue($min < $max);

        $val = Generator::getRandomLatitude($max, $min);
        Assert::assertTrue($val >= $min && $val <= $max);
    }

    /* ****************************************************************************************** */

    public function testRandomLongitude(): void
    {
        $min = Generator::getRandomFloat(Generator::LONGITUDE_MIN, -1);
        $max = Generator::getRandomFloat(1, Generator::LONGITUDE_MAX);
        Assert::assertTrue($min < $max);

        $val = Generator::getRandomLongitude($min, $max);
        Assert::assertTrue($val >= $min && $val <= $max);
    }

    public function testRandomLongitudeSwappedMinMax(): void
    {
        $min = Generator::getRandomFloat(Generator::LONGITUDE_MIN, -1);
        $max = Generator::getRandomFloat(1, Generator::LONGITUDE_MAX);
        Assert::assertTrue($min < $max);

        $val = Generator::getRandomLongitude($max, $min);
        Assert::assertTrue($val >= $min && $val <= $max);
    }

} // end of class
