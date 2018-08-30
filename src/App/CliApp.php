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
use Berlioz\Core\Config;
use Berlioz\Core\Debug;

class CliApp extends AbstractApp
{
    //////////////
    /// CONFIG ///
    //////////////

    /**
     * @inheritdoc
     */
    public function getConfig(): ?Config
    {
        if (!$this->isConfigInitialized()) {
            parent::getConfig()->extendsJson(implode(DIRECTORY_SEPARATOR,
                                                     [__DIR__, '..', '..', 'resources', 'config.default.json']),
                                             true,
                                             true);
        }

        return parent::getConfig();
    }

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
            if (!array_key_exists(1, $argv)) {
                throw new CommandException('Missing command name');
            }

            $commands = $this->getConfig()->get('commands', []);
            if (!array_key_exists($argv[1], $commands)) {
                throw new CommandException(sprintf('Command "%s" not found', $argv[1]));
            }

            $command = $commands[$argv[1]];

            // Create instance of command and invoke method
            try {
                $commandActivity = (new Debug\Activity('Command'))->start();

                if (!is_a($command, CommandInterface::class, true)) {
                    throw new CommandException(sprintf('Command class "%s" must be implement "%s" interface', $command, CommandInterface::class));
                }

                // Create instance of command
                /** @var \Berlioz\CliCore\Command\CommandInterface $command */
                $command = $this->getServiceContainer()
                                ->getInstantiator()
                                ->newInstanceOf($command);

                $cliArgs = new CliArgs($argv);
                $cliArgs->handle($command);

                // Run command
                $command->run($cliArgs);
            } finally {
                $this->getDebug()->getTimeLine()->addActivity($commandActivity->end());
            }
        } catch (\Throwable $e) {
            if ($e instanceof CommandException) {
                fwrite(STDERR, $e->getMessage() . PHP_EOL);
            } else {
                fwrite(STDERR, (string) $e . PHP_EOL);
            }
        }
    }
}