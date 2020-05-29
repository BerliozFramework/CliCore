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
use Berlioz\CliCore\App\CliAppAwareTrait;
use Berlioz\CliCore\Tests\FakeDefaultDirectories;
use Berlioz\Core\Core;
use PHPUnit\Framework\TestCase;

class CliAppAwareTraitTest extends TestCase
{
    public function test()
    {
        $obj = new class {
            use CliAppAwareTrait;
        };

        $cliApp = new CliApp(new Core(new FakeDefaultDirectories(), false));

        $this->assertFalse($obj->hasApp());
        $this->assertNull($obj->getApp());

        $obj->setApp($cliApp);

        $this->assertTrue($obj->hasApp());
        $this->assertSame($cliApp, $obj->getApp());
    }
}
