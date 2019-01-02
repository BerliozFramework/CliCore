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

/**
 * Class CliApp.
 *
 * @package Berlioz\CliCore\App
 */
class CliApp extends AbstractApp
{
    ///////////////
    /// HANDLER ///
    ///////////////

    /**
     * Handle.
     *
     * @param array $argv
     */
    public function handle(array $argv)
    {
        try {
            $commandName = $argv[1] ?? 'berlioz:help';

            $commands = $this->getCore()->getConfig()->get('commands', []);
            if (!array_key_exists($commandName, $commands)) {
                throw new CommandException(sprintf('Command "%s" not found', $argv[1]));
            }

            $command = $commands[$commandName];

            // Create instance of command and invoke method
            try {
                $commandActivity = (new Debug\Activity('Command'))->start();

                if (!is_a($command, CommandInterface::class, true)) {
                    throw new CommandException(sprintf('Command class "%s" must be implement "%s" interface', $command, CommandInterface::class));
                }

                // Create instance of command
                /** @var \Berlioz\CliCore\Command\CommandInterface $command */
                $command = $this->getCore()
                                ->getServiceContainer()
                                ->getInstantiator()
                                ->newInstanceOf($command);

                $cliArgs = new CliArgs($argv);
                $cliArgs->handle($command);

                // Run command
                $command->run($cliArgs);
            } finally {
                $this->getCore()->getDebug()->getTimeLine()->addActivity($commandActivity->end());
            }
        } catch (\Throwable $e) {
            if ($e instanceof CommandException) {
                fwrite(STDERR, $e->getMessage() . PHP_EOL);

                return;
            }

            fwrite(STDERR, (string) $e . PHP_EOL);
        }
    }
}