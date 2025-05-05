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

use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use MageStack\LogstashWrapper\Api\ConfigInterface;

/**
 * Class LogIndexResolver
 *
 * This class is responsible for resolving the log index name
 * based on the current date and the configured rotation format.
 *
 * package MageStack\LogstashWrapper\Model
 */
class LogIndexResolver
{
    /**
     * @var string|null
     */
    private ?string $resolvedLogIndex = null;

    /**
     * @param TimezoneInterface $timezone
     * @param ConfigInterface   $config
     */
    public function __construct(
        private readonly TimezoneInterface $timezone,
        private readonly ConfigInterface $config
    ) {
    }

    /**
     * Get the resolved log index name.
     *
     * @return string
     */
    public function get(): string
    {
        if ($this->resolvedLogIndex !== null) {
            return $this->resolvedLogIndex;
        }

        $rotationFormat = $this->config->getRotationFormat(); // e.g., 'YYYY.MM' or 'YYYY.MM.dd'
        $indexPrefix = $this->config->getIndexPrefix();       // e.g., 'magento2-log'

        // Convert Logstash-style format to PHP date format
        $phpDateFormat = strtr(
            $rotationFormat,
            [
                'YYYY' => 'Y',
                'MM'   => 'm',
                'dd'   => 'd',
            ]
        );

        $datePart = $this->timezone->date()->format($phpDateFormat);

        $this->resolvedLogIndex = sprintf('%s-%s', $indexPrefix, $datePart);

        return $this->resolvedLogIndex;
    }

    /**
     * Get the format for the log index.
     *
     * @return string
     */
    public function getFormat(): string
    {
        return '"' . $this->config->getIndexPrefix() . '-%{+' . $this->config->getRotationFormat() . '}"';
    }
}
