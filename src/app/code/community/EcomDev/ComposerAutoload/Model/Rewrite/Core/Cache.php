<?php
/**
 * Composer autoloader for Magento projects
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   EcomDev
 * @package    EcomDev_ComposerAutoload
 * @copyright  Copyright (c) 2014 EcomDev BV (http://www.ecomdev.org)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Ivan Chepurnyi <ivan.chepurnyi@ecomdev.org>
 */

/**
 * Rewritten core/cache model
 * 
 * There is no other option on substituting autoloader without redefining the whole autoloader file, 
 * so this options has been taken as most suitable
 * 
 * Anyway, no one can rewrite this model in standard way, so possiblity of module conflict is very low.
 */
class EcomDev_ComposerAutoload_Model_Rewrite_Core_Cache 
    extends Mage_Core_Model_Cache
{
    public function __construct(array $options = array())
    {
        parent::__construct($options);
        
        Mage::getSingleton('ecomdev_composerautoload/setup')
            ->setup($this->getFrontend());
    }
}