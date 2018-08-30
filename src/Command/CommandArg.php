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

namespace Berlioz\CliCore\Command;

class CommandArg
{
    protected $short_name;
    protected $long_name;
    protected $description;
    protected $has_value;
    protected $required;

    /**
     * CommandArg constructor.
     *
     * @param null|string $short_name  One character
     * @param null|string $long_name   More than one character
     * @param string|null $description Description
     * @param bool        $has_value   Has value?
     * @param bool        $required    Requirement
     */
    public function __construct(?string $short_name, ?string $long_name = null, string $description = null, bool $has_value = false, bool $required = false)
    {
        $this->short_name = $short_name;
        $this->long_name = $long_name;
        $this->description = $description;
        $this->has_value = $has_value;
        $this->required = $required;
    }

    /**
     * Get short name.
     *
     * Must be on one character.
     *
     * @return null|string
     */
    public function getShortName(): ?string
    {
        return $this->short_name;
    }

    /**
     * Get long name.
     *
     * Must be more than one character.
     *
     * @return null|string
     */
    public function getLongName(): ?string
    {
        return $this->long_name;
    }

    /**
     * Has value?
     *
     * @return bool
     */
    public function hasValue(): bool
    {
        return $this->has_value ?? false;
    }

    /**
     * Is required?
     *
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required ?? false;
    }
}