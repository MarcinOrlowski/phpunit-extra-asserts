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

class Type
{
    public const ARRAY          = 'array';
    public const BOOL           = 'bool';
    public const FLOAT          = 'float';
    public const INTEGER        = 'integer';
    public const NULL           = 'null';
    public const OBJECT         = 'object';
    public const STRING         = 'string';
    public const EXISTING_CLASS = 'existing_class';

} // end of class
