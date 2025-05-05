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

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\State;
use Magento\Framework\Exception\FileSystemException;
use MageStack\LogstashWrapper\Api\ConfigInterface;
use MageStack\LogstashWrapper\Model\Pipeline\LogLevelFormatter;
use MageStack\LogstashWrapper\Model\Pipeline\TemplateRenderer;

/**
 * Class PipelineBuilder
 *
 * This class is responsible for building the pipeline configuration for Logstash.
 *
 * package MageStack\LogstashWrapper\Model
 */
class PipelineBuilder
{
    /**
     * Constructor
     *
     * @param ConfigInterface   $scopeConfig
     * @param DirectoryList     $directoryList
     * @param State             $state
     * @param LogIndexResolver  $logIndexResolver
     * @param TemplateRenderer  $templateRenderer
     * @param LogLevelFormatter $logLevelFormatter
     */
    public function __construct(
        private readonly ConfigInterface $scopeConfig,
        private readonly DirectoryList $directoryList,
        private readonly State $state,
        private readonly LogIndexResolver $logIndexResolver,
        private readonly TemplateRenderer $templateRenderer,
        private readonly LogLevelFormatter $logLevelFormatter
    ) {
    }

    /**
     * Build the pipeline configuration
     *
     * @return string
     * @throws FileSystemException
     */
    public function build(): string
    {
        $host = $this->normalizeHost($this->scopeConfig->getOpensearchHost());
        $port = $this->scopeConfig->getOpensearchPort();
        $baseUrl = "{$host}:{$port}";

        $useSsl = str_starts_with($host, 'https://');
        $validateCert = $useSsl && $this->isProductionMode();

        $options = [
            'hosts' => '["' . $baseUrl . '"]',
            'index' => $this->logIndexResolver->getFormat(),
            'ssl' => $useSsl ? 'true' : 'false',
            'ssl_certificate_verification' => $validateCert ? 'true' : 'false',
        ];

        if ($this->scopeConfig->isOpensearchAuthEnabled()) {
            $user = $this->scopeConfig->getOpenSearhUserName();
            if ($user) {
                $options['user'] = '"' . $user . '"';
            }
            $pass = $this->scopeConfig->getOpenSearhPassword();
            if ($pass) {
                $options['password'] = '"' . $pass . '"';
            }
        }

        $opensearchConfig = $this->formatOpensearchOptions($options);

        return $this->templateRenderer->render(
            $this->getLogPath(),
            $this->logLevelFormatter->format($this->scopeConfig->getLogLevels()),
            $opensearchConfig
        );
    }

    /**
     * Normalize host
     *
     * @param string $host
     *
     * @return string
     */
    private function normalizeHost(string $host): string
    {
        return (str_starts_with($host, 'http://') || str_starts_with($host, 'https://')) ? $host : 'http://' . $host;
    }

    /**
     * Format opensearch options
     *
     * @param array<int|string, string> $options
     *
     * @return string
     */
    private function formatOpensearchOptions(array $options): string
    {
        return implode(
            PHP_EOL,
            array_map(
                static fn($key, $value) => "\t \t{$key} => {$value}",
                array_keys($options),
                $options
            )
        );
    }

    /**
     * Get Log path
     *
     * @return string
     *
     * @throws FileSystemException
     */
    private function getLogPath(): string
    {
        return $this->directoryList->getPath('log') . '/**/*.log';
    }

    /**
     * Is Production Mode?
     *
     * @return bool
     */
    private function isProductionMode(): bool
    {
        return $this->state->getMode() === State::MODE_PRODUCTION;
    }
}
