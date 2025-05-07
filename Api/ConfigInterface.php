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

namespace MageStack\LogstashWrapper\Api;

/**
 * Logstash config interface
 *
 * interface ConfigInterface
 *
 * namespace MageStack\LogstashWrapper\Api
 *
 * @api
 */
interface ConfigInterface
{
    /**
     * Get log levels setting.
     *
     * @return array<string>
     */
    public function getLogLevels(): array;

    /**
     * Get log rotation setting.
     *
     * @return string
     */
    public function getRotationFormat(): string;
}
