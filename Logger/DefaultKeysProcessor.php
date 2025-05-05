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

namespace MageStack\LogstashWrapper\Logger;

use Monolog\LogRecord;

/**
 * Adds default context keys to Monolog records if not already present.
 *
 * Class DefaultKeysProcessor
 *
 * namespace MageStack\LogstashWrapper\Logger
 */
class DefaultKeysProcessor
{
    /**
     * Constructor for DefaultKeysProcessor.
     *
     * @param array<int|string, mixed> $context Context values to inject into log records.
     */
    public function __construct(private readonly array $context = [])
    {
    }

    /**
     * Invoke the processor to add default context keys.
     *
     * @param LogRecord $record
     *
     * @return LogRecord
     */
    public function __invoke(LogRecord $record): LogRecord
    {
        $newContext = $record->context ?? [];
        foreach ($this->context as $key => $value) {
            if (!array_key_exists($key, $newContext)) {
                $newContext[$key] = $value;
            }
        }
        $record = $record->with(context: $newContext);

        return $record;
    }
}
