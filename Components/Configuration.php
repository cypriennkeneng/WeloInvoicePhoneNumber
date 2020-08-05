<?php
/**
 * Copyright (c) Web Loupe. All rights reserved.
 * This file is part of software that is released under a proprietary license.
 * You must not copy, modify, distribute, make publicly available, or execute
 * its contents or parts thereof without express permission by the copyright
 * holder, unless otherwise permitted by law.
 */

namespace WeloInvoicePhoneNumber\Components;

use Shopware\Components\DependencyInjection\Container as DIContainer;
use Shopware\Components\Model\ModelManager;
use Shopware\Components\Plugin\ConfigReader;
use Shopware_Components_Config;

/**
 * Class Configuration
 *
 * @author    WEB LOUPE <shopware@webloupe.de>
 * @copyright Copyright (c) 2017-2020 WEB LOUPE
 * @package   WeloInvoicePhoneNumber\Components
 * @version   1
 */
class Configuration
{
    /**
     * Plugin namespace
     *
     * @var Shopware_Components_Config
     */
    protected $namespace;
    
    /**
     * @var ConfigReader
     */
    private $configReader;
    
    /**
     * @var ModelManager
     */
    private $modelManager;
    /**
     * @var DIContainer
     */
    private $container;

    /**
     * Configuration constructor.
     * @param                                      $namespace
     * @param ConfigReader                         $configReader
     * @param ModelManager                         $modelManager
     * @param DIContainer                          $container
     */
    public function __construct(
        $namespace,
        ConfigReader $configReader,
        ModelManager $modelManager,
        DIContainer $container
    ) {
        $this->configReader = $configReader;
        $this->modelManager = $modelManager;
        $this->namespace = $namespace;
        $this->container = $container;
    }

    /**
     * @param $key
     * @return bool|mixed
     * @throws \Exception
     */
    public function getPluginConfig($key)
    {
        if ('' === $this->namespace) {
            throw new \Exception('Plugin namespace does not exist.');
        }
        $config = $this->getShop()
            ? $this->configReader->getByPluginName($this->namespace, $this->getShop())
            : $this->configReader->getByPluginName($this->namespace);
        return isset($config[$key]) ? $config[$key] : false;
    }

    /**
     * @return mixed|object|DIContainer|\Shopware\Models\Shop\Shop|null
     */
    public function getShop()
    {
        if ($this->container->has('shop')) {
            return $this->container->get('shop');
        }

        return null;
    }
}
