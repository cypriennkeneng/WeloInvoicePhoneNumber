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
use Enlight_Template_Manager;
use Enlight_Event_EventArgs as EventArgs;
use Shopware\Components\CacheManager;
use Shopware_Controllers_Backend_Config;

/**
 * Class Template
 *
 * @author    WEB LOUPE <shopware@webloupe.de>
 * @copyright Copyright (c) 2017-2020 WEB LOUPE
 * @package   WeloInvoicePhoneNumber\Subscriber
 * @version   1
 */
class Template implements SubscriberInterface
{
    /**
     * @var Enlight_Template_Manager
     */
    private $templateManager;

    /**
     * @var string
     */
    private $pluginDir;

    /**
     * @var string
     */
    private $pluginName;
    /**
     * @var CacheManager
     */
    private $cacheManager;

    /**
     * @param string                   $pluginName
     * @param                          $pluginDir
     * @param Enlight_Template_Manager $templateManager
     * @param CacheManager             $cacheManager
     */
    public function __construct(
        $pluginName,
        $pluginDir,
        Enlight_Template_Manager $templateManager,
        CacheManager $cacheManager
    ) {
        $this->templateManager = $templateManager;
        $this->pluginDir = $pluginDir;
        $this->pluginName = $pluginName;
        $this->cacheManager = $cacheManager;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PreDispatch' => 'onPreDispatch',
            'Theme_Inheritance_Template_Directories_Collected' => [
                'onTemplateDirectoriesCollect',
                1000
            ],
            'Enlight_Controller_Action_PostDispatchSecure_Backend_Config' => 'onPostDispatchConfig'
        ];
    }

    public function onPreDispatch()
    {
        $this->templateManager->addTemplateDir($this->pluginDir . '/Resources/views');
    }

    /**
     * @param EventArgs $args
     */
    public function onTemplateDirectoriesCollect(EventArgs $args)
    {
        $dirs = $args->getReturn();
        $dirs[] = $this->pluginDir . '/Resources/views';
        $args->setReturn($dirs);
    }

    public function onPostDispatchConfig(\Enlight_Event_EventArgs $args)
    {
        /** @var Shopware_Controllers_Backend_Config $subject */
        $subject = $args->get('subject');
        $request = $subject->Request();

        // If this is a POST-Request, and affects our plugin, we may clear the config cache
        if($request->isPost() && $request->getParam('name') === $this->pluginDir) {
            $this->cacheManager->clearByTag(CacheManager::CACHE_TAG_CONFIG);
        }
    }
}
