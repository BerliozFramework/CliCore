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

namespace Berlioz\CliCore;

use Berlioz\Core\Package\AbstractPackage;

/**
 * Class CliCorePackage.
 *
 * @package Berlioz\CliCore
 */
class CliCorePackage extends AbstractPackage
{
    /**
     * @inheritdoc
     * @throws \Berlioz\Core\Exception\BerliozException
     */
    public function register()
    {
        // Merge configuration
        $this->mergeConfig(implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'resources', 'config.default.json']));
    }
}