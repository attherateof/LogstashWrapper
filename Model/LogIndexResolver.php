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
use MageStack\Opensearch\Api\ConfigInterface as OpenSearchConfig;
use MageStack\Opensearch\Api\IndexResolverInterface;

/**
 * Class LogIndexResolver
 *
 * This class is responsible for resolving the log index name
 * based on the current date and the configured rotation format.
 *
 * package MageStack\LogstashWrapper\Model
 */
class LogIndexResolver implements IndexResolverInterface
{
    /**
     * @var string|null
     */
    private ?string $resolvedLogIndex = null;

    /**
     * @param TimezoneInterface $timezone
     * @param ConfigInterface $config
     * @param OpenSearchConfig $openSearchConfig
     */
    public function __construct(
        private readonly TimezoneInterface $timezone,
        private readonly ConfigInterface $config,
        private readonly OpenSearchConfig $openSearchConfig,
        private readonly string $index = ''
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getPrefix(): string
    {
        return $this->openSearchConfig->getIndexPrefix();
    }

    /**
     * @inheritDoc
     */
    public function getIndex(): string
    {
        if ($this->resolvedLogIndex !== null) {
            return $this->resolvedLogIndex;
        }

        $rotationFormat = $this->config->getRotationFormat();

        // Convert Logstash-style format to PHP date format
        $phpDateFormat = strtr(
            $rotationFormat,
            [
                'YYYY' => 'Y',
                'MM' => 'm',
                'dd' => 'd',
            ]
        );

        $datePart = $this->timezone->date()->format($phpDateFormat);

        $this->resolvedLogIndex = sprintf('%s-%s', $this->getIndexStaticPart(), $datePart);

        return $this->resolvedLogIndex;
    }

    /**
     * @inheritDoc
     */
    public function getIndexPattern(): string
    {
        return $this->getIndexStaticPart() . '-*';
    }

    /**
     * Get the format for the log index.
     *
     * @return string
     */
    public function getFormat(): string
    {
        return '"' . $this->getIndexStaticPart() . '-%{+' . $this->config->getRotationFormat() . '}"';
    }

    /**
     * Get the index static part for the log index.
     *
     * @return string
     */
    private function getIndexStaticPart(): string
    {
        return $this->getPrefix() . '-' . $this->index;
    }
}
