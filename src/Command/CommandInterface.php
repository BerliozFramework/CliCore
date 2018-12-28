<?php
/**
 * This file is part of Berlioz framework.
 *
 * @license   https://opensource.org/licenses/MIT MIT License
 * @copyright 2018 Ronan GIRON
 * @author    Ronan GIRON <https://github.com/ElGigi>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code, to the root.
 */

declare(strict_types=1);

namespace Berlioz\CliCore\Command;

use Berlioz\CliCore\App\CliArgs;

/**
 * Interface CommandInterface.
 *
 * @package Berlioz\CliCore\Command
 */
interface CommandInterface
{
    /**
     * Get description.
     *
     * @return string|null
     */
    public static function getDescription(): ?string;

    /**
     * Get args.
     *
     * Must return an array of arguments.
     *
     * @return \Berlioz\CliCore\Command\CommandArg[]
     */
    public function getArgs(): array;

    /**
     * Run command.
     *
     * @param \Berlioz\CliCore\App\CliArgs $args
     *
     * @return void
     */
    public function run(CliArgs $args);
}