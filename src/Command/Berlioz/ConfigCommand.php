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

namespace Berlioz\CliCore\Command\Berlioz;

use Berlioz\CliCore\Command\AbstractCommand;
use Berlioz\Config\Exception\ConfigException;
use Berlioz\Core\Core;
use Berlioz\Core\CoreAwareInterface;
use Berlioz\Core\CoreAwareTrait;
use Berlioz\Core\Exception\BerliozException;
use GetOpt\GetOpt;
use GetOpt\Option;

/**
 * Class ConfigCommand.
 *
 * @package Berlioz\CliCore\Command\Berlioz
 */
class ConfigCommand extends AbstractCommand implements CoreAwareInterface
{
    use CoreAwareTrait;

    /**
     * CacheClearCommand constructor.
     *
     * @param Core $core
     */
    public function __construct(Core $core)
    {
        $this->setCore($core);
    }

    /**
     * @inheritdoc
     */
    public static function getShortDescription(): ?string
    {
        return 'Show merged JSON configuration';
    }

    /**
     * @inheritdoc
     */
    public static function getOptions(): array
    {
        return [
            (new Option('f', 'filter', GetOpt::OPTIONAL_ARGUMENT))
                ->setDescription('Filter')
                ->setValidation('is_string'),
        ];
    }

    /**
     * @inheritdoc
     * @throws ConfigException
     */
    public function run(GetOpt $getOpt): int
    {
        if (empty($filter = $getOpt->getOption('f'))) {
            $filter = null;
        }

        print json_encode($this->getCore()->getConfig()->get($filter), JSON_PRETTY_PRINT);

        return 0;
    }
}