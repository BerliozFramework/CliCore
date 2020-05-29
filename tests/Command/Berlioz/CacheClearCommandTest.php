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

namespace Berlioz\CliCore\Tests\Command\Berlioz;

use Berlioz\CliCore\Command\Berlioz\CacheClearCommand;
use Berlioz\CliCore\Tests\FakeDefaultDirectories;
use Berlioz\Core\Core;
use GetOpt\GetOpt;
use PHPUnit\Framework\TestCase;

class CacheClearCommandTest extends TestCase
{
    public function test()
    {
        $core = new Core(new FakeDefaultDirectories(), false);

        $command = new CacheClearCommand($core);

        $this->assertNotNull($command::getShortDescription());
        $this->assertEquals(0, $command->run(new GetOpt()));
    }
}
