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
     * Get OpenSearch index name.
     *
     * @return string
     */
    public function getIndexPrefix(): string;

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

    /**
     * Get opensearch host.
     *
     * @return string
     */
    public function getOpensearchHost(): string;

    /**
     * Get opensearch port.
     *
     * @return string
     */
    public function getOpensearchPort(): string;

    /**
     * Check if Opensearch auth is enabled.
     *
     * @return bool
     */
    public function isOpensearchAuthEnabled(): bool;

    /**
     * Get Opensearch user name.
     *
     * @return string|null
     */
    public function getOpenSearhUserName(): ?string;

    /**
     * Get Opensearch password.
     *
     * @return string|null
     */
    public function getOpenSearhPassword(): ?string;
}
