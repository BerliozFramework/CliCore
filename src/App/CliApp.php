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
use Berlioz\CliCore\Exception\CommandException;
use Berlioz\Core\App\AbstractApp;
use Berlioz\Core\Debug;
use GetOpt\ArgumentException;
use GetOpt\Command;
use GetOpt\GetOpt;
use GetOpt\Option;

/**
 * Class CliApp.
 *
 * @package Berlioz\CliCore\App
 */
class CliApp extends AbstractApp
{
    /**
     * Get commands.
     *
     * @return \GetOpt\Command[]
     * @throws \Berlioz\CliCore\Exception\CommandException
     * @throws \Berlioz\Config\Exception\ConfigException
     * @throws \Berlioz\Core\Exception\BerliozException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    private function getCommands(): array
    {
        // Get from cache if exists
        if (!is_null($commandsList = $this->getCore()->getCacheManager()->get('berlioz-clicore-commands'))) {
            return $commandsList;
        }

        $commandsList = [];
        $commands = $this->getCore()->getConfig()->get('commands', []);

        /**
         * @var string                                    $name
         * @var \Berlioz\CliCore\Command\CommandInterface $command
         */
        foreach ($commands as $name => $command) {
            if (!is_string($command)) {
                throw new CommandException(sprintf('Command declaration must be a class name'));
            }
            if (!is_a($command, CommandInterface::class, true)) {
                throw new CommandException(sprintf('Command class "%s" must be implement "%s" interface', $command, CommandInterface::class));
            }

            $commandsList[] =
                (new Command($name, $command))
                    ->setShortDescription($command::getShortDescription() ?? '')
                    ->setDescription($command::getDescription() ?? $command::getShortDescription() ?? '')
                    ->addOptions($command::getOptions())
                    ->addOperands($command::getOperands());
        }

        // Save commands list in cache
        $this->getCore()->getCacheManager()->set('berlioz-clicore-commands', $commandsList);

        return $commandsList;
    }

    ///////////////
    /// HANDLER ///
    ///////////////

    /**
     * Handle.
     */
    public function handle()
    {
        try {
            $getOpt = new GetOpt();
            $getOpt->addOption(Option::create(null, 'help')->setDescription('Shows this help'));
            $getOpt->addCommands($this->getCommands());
            $getOpt->process();

            if ($getOpt->getOption('help') || is_null($command = $getOpt->getCommand())) {
                print $getOpt->getHelpText();
                exit(0);
            }

            // Create instance of command and invoke method
            try {
                $commandActivity = (new Debug\Activity('Command'))->start();

                // Create instance of command
                /** @var \Berlioz\CliCore\Command\CommandInterface $commandObj */
                $commandObj =
                    $this->getCore()
                         ->getServiceContainer()
                         ->getInstantiator()
                         ->newInstanceOf($command->getHandler());

                // Run command
                $commandObj->run($getOpt);
            } finally {
                $this->getCore()->getDebug()->getTimeLine()->addActivity($commandActivity->end());
            }
        } catch (ArgumentException $e) {
            fwrite(STDERR, $e->getMessage() . PHP_EOL);
            exit(1);
        } catch (CommandException $e) {
            fwrite(STDERR, $e->getMessage() . PHP_EOL);
            exit(1);
        } catch (\Throwable $e) {
            fwrite(STDERR, (string) $e . PHP_EOL);
            exit(1);
        }
    }
}