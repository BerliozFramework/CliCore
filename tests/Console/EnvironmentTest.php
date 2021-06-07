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

namespace Berlioz\Cli\Core\Tests\Console;

use Berlioz\Cli\Core\Command\CommandDeclaration;
use Berlioz\Cli\Core\Console\Console;
use Berlioz\Cli\Core\Console\Environment;
use Berlioz\Cli\Core\Exception\InvalidArgumentException;
use Berlioz\Cli\Core\Tests\Command\FakeCommand;
use PHPUnit\Framework\TestCase;

class EnvironmentTest extends TestCase
{
    public function testConsole()
    {
        $environment = new Environment($console = new Console(), new CommandDeclaration('foo', FakeCommand::class));

        $this->assertSame($console, $environment->console());
    }

    public function testGetArgument()
    {
        $environment = new Environment($console = new Console(), new CommandDeclaration('foo', FakeCommand::class));
        $console->arguments->add(['foo' => ['prefix' => 'f'], 'bar' => ['longPrefix' => 'bar']]);
        $console->arguments->parse(['exec', 'command', '-f', 'value1']);

        $this->assertEquals('value1', $environment->getArgument('foo'));
    }

    public function testGetArgument_undefined()
    {
        $this->expectException(InvalidArgumentException::class);

        $environment = new Environment($console = new Console(), new CommandDeclaration('foo', FakeCommand::class));
        $console->arguments->add(['foo' => ['prefix' => 'f'], 'bar' => ['longPrefix' => 'bar']]);
        $environment->getArgument('qux');
    }

    public function testGetArguments()
    {
        $environment = new Environment($console = new Console(), new CommandDeclaration('foo', FakeCommand::class));
        $console->arguments->add(['foo' => ['prefix' => 'f'], 'bar' => ['longPrefix' => 'bar']]);
        $console->arguments->parse(['exec', 'command', '-f', 'value1']);

        $this->assertSame(['foo' => 'value1', 'bar' => ''], $environment->getArguments());
    }

    public function testIsArgumentDefined()
    {
        $environment = new Environment($console = new Console(), new CommandDeclaration('foo', FakeCommand::class));
        $console->arguments->add(['foo' => ['prefix' => 'f'], 'bar' => ['longPrefix' => 'bar']]);

        $this->assertTrue($environment->isArgumentDefined('foo', ['exec', 'command', '-f', 'value1']));
        $this->assertFalse($environment->isArgumentDefined('bar', ['exec', 'command', '-f', 'value1']));
    }

    public function testIsArgumentDefined_undefined()
    {
        $this->expectException(InvalidArgumentException::class);

        $environment = new Environment($console = new Console(), new CommandDeclaration('foo', FakeCommand::class));
        $console->arguments->add(['foo' => ['prefix' => 'f'], 'bar' => ['longPrefix' => 'bar']]);
        $environment->isArgumentDefined('qux', []);
    }
}
