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

namespace Berlioz\CliCore\Command\Berlioz;

use Berlioz\CliCore\App\CliArgs;
use Berlioz\CliCore\Command\AbstractCommand;
use Berlioz\CliCore\Command\CommandArg;
use Berlioz\Core\Core;
use Berlioz\Core\CoreAwareInterface;
use Berlioz\Core\CoreAwareTrait;

/**
 * Class HelpCommand.
 *
 * @package Berlioz\CliCore\Command\Berlioz
 */
class HelpCommand extends AbstractCommand implements CoreAwareInterface
{
    use CoreAwareTrait;

    /**
     * CacheClearCommand constructor.
     *
     * @param \Berlioz\Core\Core $core
     */
    public function __construct(Core $core)
    {
        $this->setCore($core);
    }

    /**
     * @inheritdoc
     */
    public static function getDescription(): ?string
    {
        return 'This help';
    }

    /**
     * @inheritdoc
     * @throws \Berlioz\Config\Exception\ConfigException
     * @throws \Berlioz\Core\Exception\BerliozException
     */
    public function run(CliArgs $args)
    {
        $commands = $this->getCore()->getConfig()->get('commands');

        print "Available commands:" . PHP_EOL;

        $commandsLength = array_map(function ($value) {
            return mb_strlen($value);
        }, array_keys($commands));
        $maxCommandLength = max($commandsLength);

        /** @var \Berlioz\CliCore\Command\CommandInterface $class */
        foreach ($commands as $command => $class) {
            print sprintf('    %s    %s' . PHP_EOL,
                          str_pad($command, $maxCommandLength),
                          $class::getDescription());
        }
    }
}