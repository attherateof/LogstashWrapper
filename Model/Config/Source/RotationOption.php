<?php

/**
 * Copyright © 2025 MageStack. All rights reserved.
 * See COPYING.txt for license details.
 *
 * DISCLAIMER
 *
 * Do not make any kind of changes to this file if you
 * wish to upgrade this extension to newer version in the future.
 *
 * @category  MageStack
 * @package   MageStack_LogstashWrapper
 * @author    Amit Biswas <amit.biswas.webdeveloper@gmail.com>
 * @copyright 2025 MageStack
 * @license   https://opensource.org/licenses/MIT  MIT License
 * @link      https://github.com/attherateof/LogstashWrapper
 */

declare(strict_types=1);

namespace MageStack\LogstashWrapper\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Log Levels for config options
 *
 * Class RotationOption
 *
 * namespace MageStack\LogstashWrapper\Model\Config\Source
 */
class RotationOption implements OptionSourceInterface
{
    /**
     * Get the options as an array of arrays.
     *
     * @return array<int, array<string, int|string>>
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => 'YYYY.MM.dd', 'label' => __('Daily')->render()],
            ['value' => 'YYYY.MM', 'label' => __('Monthly')->render()],
        ];
    }
}
