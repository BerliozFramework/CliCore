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

namespace Berlioz\CliCore\App;

use Berlioz\CliCore\Command\CommandInterface;

/**
 * Class CliArgs.
 *
 * @package Berlioz\CliCore\App
 */
class CliArgs
{
    /** @var array CLI args */
    private $argv = [];
    /** @var array Options */
    private $options = [];
    /** @var array Values */
    private $values = [];

    public function __construct(array $argv)
    {
        $this->argv = $argv;
    }

    public function getCommandName(): ?string
    {
        return $this->argv[1] ?? null;
    }

    public function handle(CommandInterface $command)
    {
        $cliOptions = [];
        $cliValues = [];

        // Get options and values from CLI arguments list
        $nbArgs = count($this->argv);
        for ($i = 2; $i < $nbArgs; $i++) {
            if (substr($this->argv[$i], 0, 2) == '--') {
                $cliOptions[substr($this->argv[$i], 2)] = $i;
                continue;
            }

            if (substr($this->argv[$i], 0, 1) == '-') {
                $cliOptions[substr($this->argv[$i], 1)] = $i;
                continue;
            }

            $cliValues[$i] = $this->argv[$i];
        }

        // Check for command args
        $commandArgs = $command->getArgs();
        foreach ($commandArgs as $commandArg) {
            $argName = $commandArg->getLongName() ?? $commandArg->getShortName();
            $argPos = $cliOptions[$commandArg->getShortName()] ?? $cliOptions[$commandArg->getLongName()] ?? null;

            if ($commandArg->isRequired() && is_null($argPos)) {
                throw new \ArgumentCountError(sprintf('Missing option "%s"', $argName));
            }

            if (!is_null($argPos)) {
                if ($commandArg->hasValue() && !isset($cliValues[$argPos + 1])) {
                    throw new \ArgumentCountError(sprintf('Missing value for option "%s"', $argName));
                }

                $argValue = true;
                if ($commandArg->hasValue()) {
                    $argValue = $cliValues[$argPos + 1];
                    unset($cliValues[$argPos + 1]);
                }

                $this->options[$argName][] = $argValue;
            }
        }

        // Get values
        $this->values = array_values($cliValues);
    }

    ////////////////////////
    /// OPTIONS & VALUES ///
    ////////////////////////

    /**
     * Has option?
     *
     * @param string ...$name
     *
     * @return bool
     */
    public function hasOption(string ...$name): bool
    {
        foreach ($name as $aName) {
            if (isset($this->options[$aName])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get value of option.
     *
     * @param string ...$name Name of option
     *
     * @return string|true|null
     */
    public function getOptionValue(string ...$name)
    {
        foreach ($name as $aName) {
            if (isset($this->options[$aName])) {
                if (count($this->options[$aName]) == 1) {
                    return reset($this->options[$aName]);
                }

                return $this->options[$aName];
            }
        }

        return null;
    }

    /**
     * Get value (who are not value of option).
     *
     * @param int $i Value position
     *
     * @return null|string
     */
    public function getValue(int $i): ?string
    {
        return $this->values[$i] ?? null;
    }

    /**
     * Get values (who are not values of option).
     *
     * @return string[]
     */
    public function getValues(): array
    {
        return $this->values;
    }
}