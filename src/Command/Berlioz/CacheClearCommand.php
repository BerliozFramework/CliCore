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
use Berlioz\CliCore\Exception\CommandException;
use Berlioz\Core\Core;
use Berlioz\Core\CoreAwareInterface;
use Berlioz\Core\CoreAwareTrait;

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
        return 'Clear cache of Berlioz Framework';
    }

    /**
     * @inheritdoc
     */
    public function getArgs(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     * @throws \Berlioz\Core\Exception\BerliozException
     */
    public function run(CliArgs $args)
    {
        if (empty($cacheManager = $this->getCore()->getCacheManager())) {
            throw new CommandException('Missing cache service');
        }

        print "Cache clear...";

        try {
            $cacheManager->clear();

            print " done!" . PHP_EOL;
        } catch (\Throwable $e) {
            print " failed!" . PHP_EOL;
        }
    }
}