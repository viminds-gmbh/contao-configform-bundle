<?php

/**
 * Contao Open Source CMS
 *
 * Config Form Extension by Qbus
 *
 * @author  Alex Wuttke <alw@qbus.de>
 * @license LGPL-3.0+
 */

namespace Qbus\ConfigFormBundle\ContaoManager;

use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;

class Plugin implements BundlePluginInterface
{

    public function getBundles(ParserInterface $parser)
    {
        return [
            (new BundleConfig('Qbus\ConfigFormBundle\QbusConfigFormBundle'))
                ->setLoadAfter(['Contao\CoreBundle\ContaoCoreBundle'])
        ];
    }

}
