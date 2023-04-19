<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Service\Form\Element;

use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Component\ComponentRegistrarInterface;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Data\Form\Element\CollectionFactory;
use Magento\Framework\Data\Form\Element\Factory;
use Magento\Framework\Escaper;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\ValidatorException;
use Magento\Framework\Filesystem\Directory\ReadFactory;
use Magento\Framework\Module\ModuleList;
use Magento\Framework\Serialize\SerializerInterface;
use Psr\Log\LoggerInterface;

class Version extends AbstractElement
{
    /**
     * @var ModuleList
     */
    private ModuleList $magentoModuleList;

    /**
     * @var ComponentRegistrarInterface
     */
    private ComponentRegistrarInterface $componentRegistrar;

    /**
     * @var ReadFactory
     */
    private ReadFactory $readFactory;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var array
     */
    private array $moduleConfigList = [];

    /**
     * @param ComponentRegistrarInterface $componentRegistrar
     * @param ModuleList                  $magentoModuleList
     * @param ReadFactory                 $readFactory
     * @param SerializerInterface         $serializer
     * @param LoggerInterface             $logger
     * @param Factory                     $factoryElement
     * @param CollectionFactory           $factoryCollection
     * @param Escaper                     $escaper
     * @param array                       $data
     */
    public function __construct(
        ComponentRegistrarInterface $componentRegistrar,
        ModuleList $magentoModuleList,
        ReadFactory $readFactory,
        SerializerInterface $serializer,
        LoggerInterface $logger,
        Factory $factoryElement,
        CollectionFactory $factoryCollection,
        Escaper $escaper,
        array $data = []
    ) {
        parent::__construct($factoryElement, $factoryCollection, $escaper, $data);
        $this->setType('version');
        $this->componentRegistrar = $componentRegistrar;
        $this->magentoModuleList = $magentoModuleList;
        $this->readFactory = $readFactory;
        $this->serializer = $serializer;
        $this->logger = $logger;
    }

    /**
     * Get Version HTML.
     *
     * @return string
     */
    public function getElementHtml(): string
    {
        $html = $this->getBeforeElementHtml()
            . '<div id="'
            . $this->getHtmlId()
            . '" class="control-value admin__field-value">'
            . $this->getText()
            . '</div>'
            . $this->getAfterElementHtml();
        return $html;
    }

    /**
     * Get Text.
     *
     * @return string
     */
    private function getText(): string
    {
        $resultText = '';
        foreach ($this->getModuleList() as $extension => $version) {
            $resultText .= $extension . ': ' . $version . '<br />';
        }

        return $resultText;
    }

    /**
     * Get All Hokodo Extensions.
     *
     * @return array
     */
    private function getModuleList(): array
    {
        if (!count($this->moduleConfigList)) {
            $moduleList = [];
            $moduleConfigList = $this->magentoModuleList->getAll();
            foreach ($moduleConfigList as $moduleCode => $moduleConfig) {
                if (!str_contains($moduleCode, 'Hokodo_')) {
                    continue;
                }
                $moduleList[$moduleCode] = $this->getVersionFromComposer($moduleConfig['name']);
            }
            $this->moduleConfigList = $moduleList;
        }

        return $this->moduleConfigList;
    }

    /**
     * Get Extension Version ftm Composer File.
     *
     * @param string $name
     *
     * @return string
     */
    public function getVersionFromComposer(string $name): string
    {
        $version = '';

        try {
            $path = $this->componentRegistrar->getPath(
                ComponentRegistrar::MODULE,
                $name
            );
            $directoryRead = $this->readFactory->create($path);
            $composerJson = $directoryRead->readFile('composer.json');
            $data = $this->serializer->unserialize($composerJson);
            if (isset($data['version'])) {
                return (string) $data['version'];
            }
        } catch (FileSystemException|ValidatorException $e) {
            $this->logger->warning(__('Can not read composer.json for module %1', $name));
        }
        $this->logger->warning(__('Version for module %1 can not be detected', $name));
        return $version;
    }
}
