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

/**
 * Class TemplateRenderer
 *
 * This class is responsible for rendering the Logstash pipeline configuration template.
 *
 * package MageStack\LogstashWrapper\Model\Pipeline
 */
class TemplateRenderer
{
    /**
     * Render the Logstash pipeline configuration template.
     *
     * @param string $logPath
     * @param string $logLevels
     * @param string $outputConfig
     */
    public function render(string $logPath, string $logLevels, string $outputConfig): string
    {
        return sprintf($this->getDefaultTemplate(), $logPath, $logLevels, $outputConfig);
    }

    /**
     * Get the default template for the Logstash pipeline configuration.
     *
     * @return string
     */
    private function getDefaultTemplate(): string
    {
        // phpcs:disable Generic.Files.LineLength.TooLong, Magento2.SQL.RawSQL
        return <<<EOT
        input {
            file {
                path => "%s"
                start_position => "beginning"
                codec => multiline {
                    pattern => "^\[.*\]"
                    negate => true
                    what => "previous"
                }
            }
        }

        filter {
            grok {
                match => {
                    "message" => "\[%%{TIMESTAMP_ISO8601:timestamp}\] %%{WORD:channel}\.%%{LOGLEVEL:level}: %%{GREEDYDATA:raw_log}"
                }
                add_tag => ["grok_success"]
                remove_tag => ["_grokparsefailure"]
            }

            if !([level] in [%s]) {
                drop { }
            }

            date {
                match => ["timestamp", "ISO8601"]
                target => "@timestamp"
            }

            grok {
                match => {
                    "raw_log" => [
                        "(?m)^(?<clean_message>.*?)\s(?<context_json>\{.*?\})\s(?<extra_json>\{.*?\})$",
                        "(?m)^(?<clean_message>.*?)\s(?<context_json>\{.*?\})\s(\[\])$",
                        "(?m)^(?<clean_message>.*?)\s(\[\])\s(?<extra_json>\{.*?\})$",
                        "(?m)^(?<clean_message>.*?)\s(\[\])\s(\[\])$",
                        "(?m)^(?<clean_message>.*?)$"
                    ]
                }
            }

            if [context_json] {
                mutate {
                    gsub => [
                        "context_json", "\\n", " ",
                        "context_json", "\\r", " ",
                        "context_json", "\\s+", " "
                    ]
                }
                ruby {
                    code => "
                        require 'json'
                        ctx = event.get('context_json')
                        if ctx && !ctx.empty?
                            begin
                                parsed = JSON.parse(ctx)
                                event.set('context', parsed)
                                event.remove('context_json')
                            rescue JSON::ParserError
                                event.set('context_string', ctx)
                                event.remove('context')
                                event.remove('context_json')
                            end
                        else
                            event.set('context_string', 'No valid JSON')
                            event.remove('context')
                            event.remove('context_json')
                        end
                    "
                }
            }

            if [extra_json] {
                json {
                    source => "extra_json"
                    target => "extra"
                    skip_on_invalid_json => true
                }
            }

            mutate {
                rename => { "clean_message" => "message" }
                remove_field => ["timestamp", "raw_log", "extra_json", "event"]
            }
        }

        output {
            opensearch {
        %s
            }
        }
        EOT;
        // phpcs:enable Generic.Files.LineLength.TooLong, Magento2.SQL.RawSQL
    }
}
