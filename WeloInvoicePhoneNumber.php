<?php
/**
 * Copyright (c) Web Loupe. All rights reserved.
 * This file is part of software that is released under a proprietary license.
 * You must not copy, modify, distribute, make publicly available, or execute
 * its contents or parts thereof without express permission by the copyright
 * holder, unless otherwise permitted by law.
 */

namespace WeloInvoicePhoneNumber;

use Shopware\Components\Plugin;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class WeloInvoicePhoneNumber
 *
 * @author    Cyprien Nkeneng <cyprien.nkeneng@webloupe.de>
 * @copyright Copyright (c) 2017-2020 WEB LOUPE
 * @package   WeloInvoicePhoneNumber
 * @version   1
 */
class WeloInvoicePhoneNumber extends Plugin
{
    /**
     * @param ContainerBuilder $container
     *
     * @return void
     */
    public function build(ContainerBuilder $container)
    {
        $container->setParameter('welo_invoice_phone_number.plugin_dir', $this->getPath());
        $container->setParameter('welo_invoice_phone_number.namespace', $this->getName());
        parent::build($container);
    }
}
