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

class Bridge
{
    /**
     * Calls protected method of $object, passing optional array of arguments.
     *
     * @param object|string $cls_or_obj  Object to call $methodName on or name of the class.
     * @param string        $method_name Name of method to call.
     * @param array         $args        Optional array of arguments (empty array for no args).
     *
     * @return mixed
     *
     * @throws \ReflectionException
     * @throws \RuntimeException
     */
    public function callProtectedMethod(      $cls_or_obj, string $method_name,
                                           array $args = []): mixed
    {
        ExtraAsserts::assertIsObjectOrExistingClass($cls_or_obj, 'objectOrClass');

        /**
         * At this point $objectOrClass is either object or string but some static analyzers
         * got problems figuring that out, so this (partially correct) var declaration is
         * to make them believe.
         *
         * @var class-string|object $cls_or_obj
         */
        $reflection = new \ReflectionClass($cls_or_obj);
        $method = $reflection->getMethod($method_name);
        $method->setAccessible(true);

        return $method->invokeArgs(\is_object($cls_or_obj) ? $cls_or_obj : null, $args);
    }

    /**
     * Returns value of otherwise non-public property of the class
     *
     * @param string|object $cls_or_obj Class name to get property from, or instance of that class
     * @param string        $name       Property name to grab (i.e. `maxLength`)
     *
     * @return mixed
     *
     * @throws \ReflectionException
     */
    public function getProtectedProperty($cls_or_obj, string $name): mixed
    {
        ExtraAsserts::assertIsObjectOrExistingClass($cls_or_obj, 'objectOrClass');

        /**
         * At this point $objectOrClass is either object or string but some static analyzers
         * got problems figuring that out, so this (partially correct) var declaration is
         * to make them believe.
         *
         * @var class-string|object $cls_or_obj
         */
        $reflection = new \ReflectionClass($cls_or_obj);
        $property = $reflection->getProperty($name);
        $property->setAccessible(true);

        return $property->getValue(\is_object($cls_or_obj) ? $cls_or_obj : null);
    }

    /**
     * Returns value of otherwise non-public member of the class
     *
     * @param string|object $cls_or_obj Class name to get member from, or instance of that class
     * @param string        $name       Name of constant to grab (i.e. `FOO`)
     *
     * @return mixed
     */
    public function getProtectedConstant($cls_or_obj, string $name): mixed
    {
        ExtraAsserts::assertIsObjectOrExistingClass($cls_or_obj, 'objectOrClass');

        /**
         * At this point $obj_or_cls is either object or string but some static analyzers
         * got problems figuring that out, so this (partially correct) var declaration is
         * to make them believe.
         *
         * @var class-string|object $cls_or_obj
         */
        return (new \ReflectionClass($cls_or_obj))->getConstant($name);
    }

    /* **************************************************************************************************** */

} // end of trait
