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

namespace MageStack\LogstashWrapper\Model\Pipeline;

use MageStack\LogstashWrapper\Model\Config\Source\LogLevels;

/**
 * Class LogLevelFormatter
 *
 * This class is responsible for formatting log levels for Logstash config.
 *
 * package MageStack\LogstashWrapper\Model\Pipeline
 */
class LogLevelFormatter
{
    /**
     * @param LogLevels $logLevels
     */
    public function __construct(private readonly LogLevels $logLevels)
    {
    }

    /**
     * Format the log levels for Logstash config.
     *
     * @param array<int|string, mixed> $values
     *
     * @return string
     */
    public function format(array $values): string
    {
        $labels = $this->getLabels($values);

        return '"' . implode('", "', $labels) . '"';
    }

    /**
     * Get the labels for the log levels.
     *
     * @param array<int|string, mixed> $values
     *
     * @return array<int|string, mixed>
     */
    private function getLabels(array $values): array
    {
        $options = $this->logLevels->toOptionArray();
        $valueToLabelMap = array_column($options, 'label', 'value');

        return array_map(
            static fn($value) => $valueToLabelMap[$value] ?? $value,
            $values
        );
    }
}
