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

namespace Berlioz\CliCore\App;

use Berlioz\CliCore\Command\CommandInterface;
use Berlioz\CliCore\Exception\CommandException;
use Berlioz\Config\ConfigInterface;
use Berlioz\Config\Exception\ConfigException;
use Berlioz\Core\App\AbstractApp;
use Berlioz\Core\Debug;
use Berlioz\Core\Exception\BerliozException;
use GetOpt\Argument;
use GetOpt\ArgumentException;
use GetOpt\Command;
use GetOpt\GetOpt;
use GetOpt\Option;
use Psr\SimpleCache\InvalidArgumentException;
use Throwable;

/**
 * Class CliApp.
 *
 * @package Berlioz\CliCore\App
 */
class CliApp extends AbstractApp
{
    /**
     * Create commands from config.
     *
     * @param ConfigInterface $config
     *
     * @return array
     * @throws BerliozException
     * @throws CommandException
     * @throws ConfigException
     */
    private function createCommandsFromConfig(ConfigInterface $config)
    {
        $commands = $config->get('commands', []);

        if (!is_array($commands)) {
            throw new BerliozException('Bad configuration format, "commands" key must be an array');
        }

        $commandsObjects = [];

        /**
         * @var string $name
         * @var CommandInterface $command
         */
        foreach ($commands as $name => $command) {
            if (!is_string($command)) {
                throw new CommandException(sprintf('Command declaration must be a class name'));
            }
            if (!class_exists($command)) {
                throw new CommandException(sprintf('Command class "%s" not found', $command));
            }
            if (!is_a($command, CommandInterface::class, true)) {
                throw new CommandException(
                    sprintf('Command class "%s" must be implement "%s" interface', $command, CommandInterface::class)
                );
            }

            $commandsObjects[] =
                (new Command($name, $command))
                    ->setShortDescription($command::getShortDescription() ?? '')
                    ->setDescription($command::getDescription() ?? $command::getShortDescription() ?? '')
                    ->addOptions($command::getOptions())
                    ->addOperands($command::getOperands());
        }

        return $commandsObjects;
    }

    /**
     * Get commands.
     *
     * @return Command[]
     * @throws CommandException
     * @throws ConfigException
     * @throws BerliozException
     * @throws InvalidArgumentException
     */
    private function getCommands(): array
    {
        // Get from cache if exists
        if (null !== ($commandsList = $this->getCore()->getCacheManager()->get('berlioz-clicore-commands'))) {
            return $commandsList;
        }

        $commandsList = $this->createCommandsFromConfig($this->getCore()->getConfig());

        // Save commands list in cache
        $this->getCore()->getCacheManager()->set('berlioz-clicore-commands', $commandsList);

        return $commandsList;
    }

    ///////////////
    /// HANDLER ///
    ///////////////

    /**
     * Handle.
     *
     * @param array|string|Argument|null $arguments
     *
     * @return int
     * @throws CommandException
     * @throws ArgumentException
     * @throws InvalidArgumentException
     * @throws Throwable
     */
    public function handle($arguments = null): int
    {
        $getOpt = new GetOpt();
        $getOpt->addOption(Option::create(null, 'help')->setDescription('Shows this help'));
        $getOpt->addCommands($this->getCommands());
        $getOpt->process($arguments);

        if ($getOpt->getOption('help') || is_null($command = $getOpt->getCommand())) {
            print $getOpt->getHelpText();
            return 0;
        }

        // Create instance of command and invoke method
        try {
            $commandActivity = (new Debug\Activity('Command'))->start();

            // Create instance of command
            /** @var CommandInterface $commandObj */
            $commandObj =
                $this
                    ->getCore()
                    ->getServiceContainer()
                    ->getInstantiator()
                    ->newInstanceOf($command->getHandler());

            // Run command
            return $commandObj->run($getOpt);
        } finally {
            $this->getCore()->getDebug()->getTimeLine()->addActivity($commandActivity->end());
        }
    }
}