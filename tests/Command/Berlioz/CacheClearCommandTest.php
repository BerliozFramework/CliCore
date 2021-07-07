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

namespace Berlioz\Cli\Core\Tests\Command\Berlioz;

use Berlioz\Cli\Core\App\CliApp;
use Berlioz\Cli\Core\Command\Berlioz\CacheClearCommand;
use Berlioz\Cli\Core\Command\CommandDeclaration;
use Berlioz\Cli\Core\Console\Console;
use Berlioz\Cli\Core\Console\Environment;
use Berlioz\Cli\Core\Tests\FakeDefaultDirectories;
use Berlioz\Core\Core;
use PHPUnit\Framework\TestCase;

class CacheClearCommandTest extends TestCase
{
    public function testGetDescription()
    {
        $this->assertNotNull(CacheClearCommand::getDescription());
    }

    public function testGetHelp()
    {
        $this->assertNull(CacheClearCommand::getHelp());
    }

    public function testClearCache()
    {
        $app = new CliApp(new Core(new FakeDefaultDirectories(), cache: false));
        $app->getCore()->getCache()->set('foo', 'bar');
        $command = new CacheClearCommand();
        $command->setApp($app);

        $this->assertEquals('bar', $app->getCore()->getCache()->get('foo'));
        $this->assertTrue($command->clearCache());
        $this->assertNull($app->getCore()->getCache()->get('foo'));
    }

    public function testRun()
    {
        $app = new CliApp(new Core(new FakeDefaultDirectories(), cache: false));
        $app->getCore()->getCache()->set('foo', 'bar');
        $command = new CacheClearCommand();
        $command->setApp($app);
        $console = new Console();
        $console->output->defaultTo('buffer');

        $this->assertEquals('bar', $app->getCore()->getCache()->get('foo'));
        $this->assertSame(
            0,
            $command->run(
                new Environment(
                    $console,
                    new CommandDeclaration('berlioz:cache-clear', CacheClearCommand::class)
                )
            )
        );
        $this->assertNull($app->getCore()->getCache()->get('foo'));
    }
}
