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

use Berlioz\CliCore\App\CliApp;
use Berlioz\CliCore\App\CliAppAwareInterface;
use Berlioz\CliCore\App\CliAppAwareTrait;
use Berlioz\CliCore\App\CliArgs;
use Berlioz\CliCore\Command\AbstractCommand;
use Berlioz\CliCore\Command\CommandArg;

class ConfigCommand extends AbstractCommand implements CliAppAwareInterface
{
    use CliAppAwareTrait;

    /**
     * CacheClearCommand constructor.
     *
     * @param \Berlioz\CliCore\App\CliApp $app
     */
    public function __construct(CliApp $app)
    {
        $this->setApp($app);
    }

    public function getArgs(): array
    {
        return [new CommandArg('f', 'filter', 'Filter', true)];
    }

    /**
     * @inheritdoc
     * @throws \Berlioz\Config\Exception\ConfigException
     * @throws \Berlioz\Core\Exception\BerliozException
     */
    public function run(CliArgs $args)
    {
        if (!is_string($filter = $args->getOptionValue('f', 'filter')) || empty($filter)) {
            $filter = null;
        }

        print json_encode($this->getApp()->getConfig()->get($filter), JSON_PRETTY_PRINT);
    }
}