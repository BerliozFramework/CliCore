<?php
/**
 * This file is part of Berlioz framework.
 *
 * @license   https://opensource.org/licenses/MIT MIT License
 * @copyright 2020 Ronan GIRON
 * @author    Ronan GIRON <https://github.com/ElGigi>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code, to the root.
 */

declare(strict_types=1);

namespace Berlioz\CliCore\Command;

use GetOpt\GetOpt;
use GetOpt\Operand;
use GetOpt\Option;

/**
 * Interface CommandInterface.
 *
 * @package Berlioz\CliCore\Command
 */
interface CommandInterface
{
    /**
     * Get short description.
     *
     * @return string|null
     */
    public static function getShortDescription(): ?string;

    /**
     * Get description.
     *
     * @return string|null
     */
    public static function getDescription(): ?string;

    /**
     * Get options.
     *
     * Must return an array of options.
     *
     * @return Option[]
     * @see http://getopt-php.github.io/getopt-php/options.html
     */
    public static function getOptions(): array;

    /**
     * Get operands.
     *
     * Must return an array of operands.
     *
     * @return Operand[]
     * @see http://getopt-php.github.io/getopt-php/operands.html
     */
    public static function getOperands(): array;

    /**
     * Run command.
     *
     * @param GetOpt $getOpt
     *
     * @return int
     */
    public function run(GetOpt $getOpt): int;
}