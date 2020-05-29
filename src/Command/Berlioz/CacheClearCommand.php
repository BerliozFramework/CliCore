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
use Berlioz\CliCore\Exception\CommandException;
use Berlioz\Core\Core;
use Berlioz\Core\CoreAwareInterface;
use Berlioz\Core\CoreAwareTrait;
use Berlioz\Core\Exception\BerliozException;
use GetOpt\GetOpt;

/**
 * Class CacheClearCommand.
 *
 * @package Berlioz\CliCore\Command\Berlioz
 */
class CacheClearCommand extends AbstractCommand implements CoreAwareInterface
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
        return 'Clear cache of Berlioz Framework';
    }

    /**
     * @inheritdoc
     * @throws BerliozException
     * @throws CommandException
     */
    public function run(GetOpt $getOpt): int
    {
        if (empty($cacheManager = $this->getCore()->getCacheManager())) {
            throw new CommandException('Missing cache service');
        }

        print "Cache clear of Berlioz...";

        $result = $cacheManager->clear();

        print sprintf(' %s!', $result ? 'done' : 'failed') . PHP_EOL;

        return (int)!$result;
    }
}