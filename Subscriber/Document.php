<?php
/**
 * Copyright (c) Web Loupe. All rights reserved.
 * This file is part of software that is released under a proprietary license.
 * You must not copy, modify, distribute, make publicly available, or execute
 * its contents or parts thereof without express permission by the copyright
 * holder, unless otherwise permitted by law.
 */

namespace WeloInvoicePhoneNumber\Subscriber;

use Enlight\Event\SubscriberInterface;
use Enlight_Hook_HookArgs;
use ReflectionException;
use Shopware\Components\Model\ModelManager;
use Shopware\Models\Plugin\Plugin;
use Shopware_Components_Document;
use Smarty_Data;
use WeloInvoicePhoneNumber\Components\Configuration;

/**
 * Class Document
 *
 * @author    Cyprien Nkeneng <cyprien.nkeneng@webloupe.de>
 * @copyright Copyright (c) 2017-2020 WEB LOUPE
 * @package   WeloExtendInvoice\Subscriber
 * @version   1
 */
class Document implements SubscriberInterface
{
    /**
     * @var ModelManager
     */
    private $modelManager;
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * Document constructor.
     * @param ModelManager  $modelManager
     * @param Configuration $configuration
     */
    public function __construct(ModelManager $modelManager, Configuration $configuration)
    {
        $this->modelManager = $modelManager;
        $this->configuration = $configuration;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'Shopware_Components_Document::assignValues::after' => 'onBeforeRenderDocument',
        ];
    }

    /**
     * @param Enlight_Hook_HookArgs $args
     * @throws ReflectionException
     */
    public function onBeforeRenderDocument(Enlight_Hook_HookArgs $args)
    {
        /* @var Shopware_Components_Document $document */
        $document = $args->getSubject();
        $documentTypeId = (int)$this->getDocumentTypeId($document);

        /* @var Smarty_Data $view */
        $view = $document->_view;

        if (1 != $documentTypeId || !(bool)$this->configuration->getPluginConfig('DisplayPhoneNumber')) {
            return;
        }

        $weloPhoneNumber = [
            'wDocumentType' => $documentTypeId,
            'isWeloEmailEnabled' => $this->isWeloEmailEnabled(),
            'DisplayPhoneNumber' => $this->configuration->getPluginConfig('DisplayPhoneNumber'),
        ];

        $view->assign('weloPhoneNumber', $weloPhoneNumber);
    }

    /**
     * @param \Shopware_Components_Document $documentComponent
     * @return int
     * @throws ReflectionException
     */
    private static function getDocumentTypeId(Shopware_Components_Document $documentComponent)
    {
        $reflectionObject = new \ReflectionObject($documentComponent);
        $reflectionProperty = $reflectionObject->getProperty('_typID');
        $reflectionProperty->setAccessible(true);

        return intval($reflectionProperty->getValue($documentComponent));
    }

    /**
     * Check if the other plugin is activated
     * @return bool
     */
    public function isWeloEmailEnabled()
    {
        $builder = $this->modelManager->createQueryBuilder();
        $builder->select('plugin')
            ->from(Plugin::class, 'plugin')
            ->where('plugin.name like :name')
            ->andWhere('plugin.active = :plugin_active')
            ->setParameter('name', '%WeloInvoiceEmail%')
            ->setParameter('plugin_active', true)
        ;

        return $builder->getQuery()->getArrayResult() !== [] ? true : false;
    }
}