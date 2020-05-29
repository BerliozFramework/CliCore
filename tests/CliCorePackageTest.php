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

namespace Berlioz\CliCore\Tests;

use Berlioz\CliCore\CliCorePackage;
use PHPUnit\Framework\TestCase;

class CliCorePackageTest extends TestCase
{
    public function testConfig()
    {
        $config = CliCorePackage::config();

        $configContents = file_get_contents(__DIR__ . '/../resources/config.default.json');

        $this->assertNotFalse($configContents);

        $configExpected = json_decode($configContents, true);

        $this->assertIsArray($configExpected);
        $this->assertEquals($configExpected, $config->original());
    }
}
