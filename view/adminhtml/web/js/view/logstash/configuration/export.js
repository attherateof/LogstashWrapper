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
 * @package   MageStack_Core
 * @author    Amit Biswas <amit.biswas.webdeveloper@gmail.com>
 * @copyright 2025 MageStack
 * @license   https://opensource.org/licenses/MIT  MIT License
 * @link      https://github.com/attherateof/LogstashWrapper
*/
define(
    [
    'uiComponent',
    'jquery',
    'ko'
    ], function (Component, $, ko) {
        'use strict';

        return Component.extend(
            {
                defaults: {
                    template: 'MageStack_LogstashWrapper/logstash/configuration/form',
                    url: null,
                    form_key: null,
                },
                exportConfig: function () {
                    if (!this.url) {
                        console.error('Download URL is not set.');
                        return;
                    }

                    var form = $(
                        '<form>', {
                            action: this.url,
                            method: 'POST'
                        }
                    ).append(
                        $(
                            '<input>', {
                                type: 'hidden',
                                name: 'form_key',
                                value: this.form_key
                            }
                        )
                    ).appendTo('body');

                    form.submit();
            
                    form.remove();
                }
            }
        );
    }
);
