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

namespace MageStack\LogstashWrapper\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class Export
 *
 * namespace MageStack\LogstashWrapper\Block\Adminhtml\System\Config
 */
class Export extends Field
{
    /**
     * Path to the template file
     *
     * @var string
     */
    protected $_template = 'MageStack_LogstashWrapper::system/config/export.phtml';

    /**
     * Render the element
     *
     * @param AbstractElement $element
     *
     * @return string
     */
    public function render(AbstractElement $element): string
    {
        $element->setData('disabled', 'disabled');
        return parent::render($element);
    }

    /**
     * Get the HTML for the element
     *
     * @param AbstractElement $element
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function _getElementHtml(AbstractElement $element): string
    {
        return $this->_toHtml();
    }

    /**
     * Get export Url
     *
     * @return string
     */
    public function getExportUrl(): string
    {
        return $this->getUrl('logstash/config/export');
    }
}
