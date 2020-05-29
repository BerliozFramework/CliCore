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

namespace Berlioz\CliCore\Tests\Command;

use Berlioz\CliCore\Command\AbstractCommand;
use GetOpt\GetOpt;
use PHPUnit\Framework\TestCase;

class AbstractCommandTest extends TestCase
{
    public function test()
    {
        $command = new class extends AbstractCommand {
            public function run(GetOpt $getOpt): int
            {
                return 0;
            }
        };

        $this->assertNull($command::getDescription());
        $this->assertNull($command::getShortDescription());
        $this->assertEmpty($command::getOptions());
        $this->assertEmpty($command::getOperands());
        $this->assertEquals(0, $command->run(new GetOpt()));
    }
}
