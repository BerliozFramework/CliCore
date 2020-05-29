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

declare(strict_types=1);

namespace Berlioz\CliCore\App;

/**
 * Trait CliAppAwareTrait.
 *
 * @package Berlioz\CliCore\App
 */
trait CliAppAwareTrait
{
    /** @var CliApp Application */
    private $app;

    /**
     * Get application.
     *
     * @return CliApp|null
     */
    public function getApp(): ?CliApp
    {
        return $this->app;
    }

    /**
     * Set application.
     *
     * @param CliApp $app
     *
     * @return static
     */
    public function setApp(CliApp $app)
    {
        $this->app = $app;

        return $this;
    }

    /**
     * Has application?
     *
     * @return bool
     */
    public function hasApp(): bool
    {
        return !is_null($this->app);
    }
}