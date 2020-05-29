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

/**
 * Class AbstractCommand.
 *
 * @package Berlioz\CliCore\Command
 */
abstract class AbstractCommand implements CommandInterface
{
    /**
     * @inheritdoc
     */
    public static function getShortDescription(): ?string
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public static function getDescription(): ?string
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public static function getOptions(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function getOperands(): array
    {
        return [];
    }
}