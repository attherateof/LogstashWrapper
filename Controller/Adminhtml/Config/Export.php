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

namespace MageStack\LogstashWrapper\Controller\Adminhtml\Config;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use MageStack\LogstashWrapper\Model\PipelineBuilder;
use Magento\Framework\App\ResponseInterface;

/**
 * This class is responsible for exporting the Logstash configuration file.
 *
 * Class Export
 *
 * namespace MageStack\LogstashWrapper\Controller\Adminhtml\Config
 */
class Export implements HttpPostActionInterface
{
    /**
     * @var FileFactory
     */
    protected $fileFactory;

    /**
     * @var DirectoryList
     */
    protected $directoryList;

    /**
     * Constructor
     *
     * @param FileFactory $fileFactory
     * @param DirectoryList $directoryList
     * @param PipelineBuilder $pipelineBuilder
     */
    public function __construct(
        FileFactory $fileFactory,
        DirectoryList $directoryList,
        private readonly PipelineBuilder $pipelineBuilder
    ) {
        $this->fileFactory = $fileFactory;
        $this->directoryList = $directoryList;
    }

    /*
    * Execute the action
    *
    * @return ResponseInterface
    */
    public function execute()
    {
        return $this->fileFactory->create(
            'magento.conf',
            [
                'type'  => 'string',
                'value' => $this->pipelineBuilder->build(),
                'rm'    => true,
            ],
            $this->directoryList::VAR_DIR,
            'text/plain'
        );
    }
}
