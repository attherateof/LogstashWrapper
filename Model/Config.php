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

namespace MageStack\LogstashWrapper\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use MageStack\LogstashWrapper\Api\ConfigInterface;

/**
 * Configuration model for MageStack OpenSearch Logger
 *
 * Class Config
 *
 * namespace MageStack\LogstashWrapper\Model
 */
class Config implements ConfigInterface
{
    /**
     * Configuration paths
     */
    public const LOG_LEVELS = 'logstash/general/log_levels';
    public const ROTATION = 'logstash/general/rotation';

    /**
     * constructor
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getLogLevels(): array
    {
        $logLevels = $this->scopeConfig->getValue(
            self::LOG_LEVELS,
            ScopeInterface::SCOPE_STORE
        );

        if ($logLevels && is_string($logLevels)) {
            return explode(',', $logLevels);
        }

        return [];
    }

    /**
     * @inheritDoc
     */
    public function getRotationFormat(): string
    {
        $rotation = $this->scopeConfig->getValue(
            self::ROTATION,
            ScopeInterface::SCOPE_STORE
        );

        return is_string($rotation) ? $rotation : '';
    }
}
