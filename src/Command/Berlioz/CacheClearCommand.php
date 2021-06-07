<?php
/*
 * This file is part of Berlioz framework.
 *
 * @license   https://opensource.org/licenses/MIT MIT License
 * @copyright 2021 Ronan GIRON
 * @author    Ronan GIRON <https://github.com/ElGigi>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code, to the root.
 */

declare(strict_types=1);

namespace Berlioz\Cli\Core\Command\Berlioz;

use Berlioz\Cli\Core\Command\AbstractCommand;
use Berlioz\Cli\Core\Console\Environment;

/**
 * Class CacheClearCommand.
 */
class CacheClearCommand extends AbstractCommand
{
    /**
     * @inheritDoc
     */
    public static function getDescription(): ?string
    {
        return 'Clear cache of Berlioz Framework';
    }

    /**
     * Clear cache.
     *
     * @return bool
     */
    public function clearCache(): bool
    {
        return $this->getApp()->getCore()->getCache()->clear();
    }

    /**
     * @inheritDoc
     */
    public function run(Environment $env): int
    {
        $env->console()->inline('Cache clear... ');
        $env->console()->spinner();

        if (true === $this->clearCache()) {
            $env->console()->green('done!');
            return 0;
        }

        $env->console()->red('failed!');
        return 1;
    }
}