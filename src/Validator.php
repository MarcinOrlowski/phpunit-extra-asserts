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

use MarcinOrlowski\PhpunitExtraAsserts\Exception as Ex;

/**
 * Data validator helper
 */
class Validator
{
    /**
     * Checks if $item (of name $key) is of type that is include in $allowed_types (there's `OR` connection
     * between specified types).
     *
     * @param string          $varName      Label or name of the variable to use exception message.
     * @param mixed           $value        Variable to be asserted.
     * @param string|string[] $allowedTypes Array of allowed types for $value, i.e. [Type::INTEGER]
     * @param string          $exClass      Name of exception class (which implements
     *                                      Ex\InvalidTypeExceptionContract) to be used when assertion fails.
     *                                      In that case object of that class will be instantiated and
     *                                      thrown.
     *
     * @throws Ex\InvalidTypeExceptionContract
     * @throws \InvalidArgumentException
     */
    public static function assertIsType(string       $varName, mixed $value,
                                        string|array $allowedTypes,
                                        string       $exClass = Ex\InvalidTypeException::class): void
    {
        $allowedTypes = (array)$allowedTypes;

        // Type::EXISTING_CLASS is artificial type, so we need separate logic to handle it.
        $filteredAllowedTypes = $allowedTypes;
        $idx = \array_search(Type::EXISTING_CLASS, $filteredAllowedTypes, true);
        if ($idx !== false) {
            // Remove the type, so gettype() test loop won't see it.
            unset($filteredAllowedTypes[ $idx ]);
            if (\is_string($value) && \class_exists($value)) {
                // It's existing class, no need to test further.
                return;
            }
        }

        $type = \gettype($value);
        if (empty($filteredAllowedTypes)) {
            throw new \InvalidArgumentException("List of allowed types cannot be empty.}");
        }
        if (!\in_array($type, $filteredAllowedTypes, true)) {
            // FIXME we need to ensure $exClass implements Ex\InvalidTypeExceptionContract at some point.
            throw static::buildException($varName, $type, $filteredAllowedTypes);
        }
    }

    /**
     * @param string          $varName      Name of the variable (to be included in error message)
     * @param string          $type         Current type of the $value
     * @param string|string[] $allowedTypes Array of allowed types (Type::*) or single element.
     */
    protected static function buildException(string       $varName, string $type,
                                             string|array $allowedTypes): \RuntimeException
    {
        $allowedTypes = (array)$allowedTypes;
        switch (\count($allowedTypes)) {
            case 0:
                throw new \RuntimeException('allowedTypes array must not be empty.');

            case 1:
                $msg = '"%1$s" must be type(s) of %2$s but %3$s found.';
                break;

            default;
                $msg = '"%1$s" must be one of allowed types: %2$s but %3$s found.';
                break;
        }

        return new \RuntimeException(
            \sprintf($msg, $varName, \implode(', ', $allowedTypes), $type)
        );
    }


    /* **************************************************************************************************** */

} // end of class
