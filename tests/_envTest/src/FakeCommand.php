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

namespace Berlioz\CliCore\TestProject;

use Berlioz\CliCore\Command\AbstractCommand;
use GetOpt\GetOpt;
use GetOpt\Operand;
use GetOpt\Option;

class FakeCommand extends AbstractCommand
{
    public static function getShortDescription(): ?string
    {
        return 'Short description';
    }

    public static function getDescription(): ?string
    {
        return 'Long description';
    }

    public static function getOptions(): array
    {
        return [
            (new Option('f', 'foo', GetOpt::OPTIONAL_ARGUMENT))
                ->setDescription('Foo')
                ->setValidation('is_string'),
            (new Option('b', 'bar', GetOpt::REQUIRED_ARGUMENT))
                ->setDescription('Bar')
                ->setValidation('is_numeric'),
        ];
    }

    public static function getOperands(): array
    {
        return [
            new Operand('qux', Operand::OPTIONAL),
            new Operand('quux', Operand::MULTIPLE),
        ];
    }

    public function run(GetOpt $getOpt): int
    {
        return 1234;
    }
}