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

namespace Berlioz\CliCore\Tests\App;

use Berlioz\CliCore\App\CliApp;
use Berlioz\CliCore\Exception\CommandException;
use Berlioz\CliCore\Tests\FakeDefaultDirectories;
use Berlioz\Config\JsonConfig;
use Berlioz\Core\Core;
use Berlioz\Core\Exception\BerliozException;
use GetOpt\Command;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class CliAppTest extends TestCase
{
    public function invokeMethod($object, $methodName, array $parameters = array())
    {
        $reflection = new ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    public function testCreateCommandsFromConfigWithBadConfig()
    {
        $cliApp = new CliApp(new Core(new FakeDefaultDirectories(), false));
        $config = new JsonConfig('{"commands": "BadCommand"}');

        $this->expectException(BerliozException::class);

        $this->invokeMethod($cliApp, 'createCommandsFromConfig', [$config]);
    }

    public function testCreateCommandsFromConfigWithBadConfig2()
    {
        $cliApp = new CliApp(new Core(new FakeDefaultDirectories(), false));
        $config = new JsonConfig('{"commands": [["BadCommand"]]}');

        $this->expectException(BerliozException::class);

        $this->invokeMethod($cliApp, 'createCommandsFromConfig', [$config]);
    }

    public function testCreateCommandsFromConfigWithClassDoesNotExists()
    {
        $cliApp = new CliApp(new Core(new FakeDefaultDirectories(), false));
        $config = new JsonConfig('{"commands": ["BadCommand"]}');

        $this->expectException(CommandException::class);

        $this->invokeMethod($cliApp, 'createCommandsFromConfig', [$config]);
    }

    public function testCreateCommandsFromConfigWithBadCommandClass()
    {
        $cliApp = new CliApp(new Core(new FakeDefaultDirectories(), false));
        $config = new JsonConfig('{"commands": {"fake:command": "Berlioz\\\\CliCore\\\\TestProject\\\\NotACommand"}}');

        $this->expectException(CommandException::class);

        $this->invokeMethod($cliApp, 'createCommandsFromConfig', [$config]);
    }

    public function testCreateCommandsFromConfigWithNoName()
    {
        $cliApp = new CliApp(new Core(new FakeDefaultDirectories(), false));
        $config = new JsonConfig('{"commands": ["Berlioz\\\\CliCore\\\\TestProject\\\\FakeCommand"]}');

        $this->expectException(InvalidArgumentException::class);

        $this->invokeMethod($cliApp, 'createCommandsFromConfig', [$config]);
    }

    public function testCreateCommandsFromConfig()
    {
        $cliApp = new CliApp(new Core(new FakeDefaultDirectories(), false));
        $config = new JsonConfig('{"commands": {"fake:command": "Berlioz\\\\CliCore\\\\TestProject\\\\FakeCommand"}}');

        $commands = $this->invokeMethod($cliApp, 'createCommandsFromConfig', [$config]);

        $this->assertCount(1, $commands);

        /** @var Command $command */
        $command = reset($commands);
        $this->assertEquals('fake:command', $command->getName());
        $this->assertEquals('Long description', $command->getDescription());
        $this->assertEquals('Short description', $command->getShortDescription());
        $this->assertCount(2, $command->getOptions());
        $this->assertCount(2, $command->getOperands());

        $this->assertEquals(1234, $cliApp->handle('fake:command -b 0'));

        $this->expectOutputRegex('#Shows this help#');
        $this->expectOutputRegex('#fake:command#');
        $cliApp->handle('');

        $this->expectOutputRegex('#Shows this help#');
        $this->expectOutputRegex('#fake:command#');
        $cliApp->handle('fake:command');
    }
}
