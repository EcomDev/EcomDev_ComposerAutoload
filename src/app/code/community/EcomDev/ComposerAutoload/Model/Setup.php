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
 * Autoloader setup class
 * 
 * Should be used as singleton,
 * because it controls the whole process of autoloading instantiation
 * 
 */
class EcomDev_ComposerAutoload_Model_Setup
{
    /**
     * @var EcomDev_ComposerAutoload_Model_AutoloaderInterface
     */
    protected $_autoloader;

    /**
     * Flag if it was already setup
     * 
     * @var bool
     */
    protected $_isSetup = false;
    
    /**
     * Returns an instance of autoloader
     * 
     * @return EcomDev_ComposerAutoload_Model_AutoloaderInterface
     */
    public function getAutoloader()
    {
        if ($this->_autoloader === null) {
            $this->_autoloader = Mage::getModel('ecomdev_composerautoload/autoloader');
            $this->_autoloader
                ->add(Mage::getSingleton('ecomdev_composerautoload/composer_resolver_parent'))
                ->add(Mage::getSingleton('ecomdev_composerautoload/composer_resolver_default'))
                ->add(Mage::getSingleton('ecomdev_composerautoload/composer_resolver_lib'))
                ->setBasePath(Mage::getBaseDir());
        }
        
        return $this->_autoloader;
    }

    /**
     * Set ups the 
     * 
     * @return $this
     */
    public function setup($cacheModel = null)
    {
        if (!$this->_isSetup) {
            $this->_isSetup = true;
            
            if ($cacheModel) {
                $this->getAutoloader()->setCacheAdapter($cacheModel);
            }
            
            $this->getAutoloader()->register();
        }
        return $this;
    }
}