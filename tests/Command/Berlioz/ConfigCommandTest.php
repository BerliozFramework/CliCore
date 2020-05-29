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

use Berlioz\CliCore\Command\Berlioz\ConfigCommand;
use Berlioz\CliCore\Tests\FakeDefaultDirectories;
use Berlioz\Core\Core;
use GetOpt\GetOpt;
use PHPUnit\Framework\TestCase;

class ConfigCommandTest extends TestCase
{
    public function test()
    {
        $core = new Core(new FakeDefaultDirectories(), false);

        $command = new ConfigCommand($core);

        $this->assertNotNull($command::getShortDescription());

        $getOpt = new GetOpt();
        $getOpt->addOptions($command::getOptions());

        ob_start();
        $result = $command->run($getOpt);
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertEquals(0, $result);
        $this->assertStringContainsString('"berlioz": {', $output);


        ob_start();
        $getOpt->process('-f "berlioz.debug.enable"');
        $result = $command->run($getOpt);
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertEquals(0, $result);
        $this->assertEquals('false', $output);
    }
}
