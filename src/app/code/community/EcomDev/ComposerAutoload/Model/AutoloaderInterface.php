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
 * Autoloader interface
 * 
 */
interface EcomDev_ComposerAutoload_Model_AutoloaderInterface
{
    const CACHE_KEY = 'composer_file_content';
    const CACHE_TAG = 'composer';
    
    /**
     * Registers composer file
     * 
     * @return $this
     */
    public function register();

    /**
     * Adds lookup model for composer file location
     * 
     * @param EcomDev_ComposerAutoload_Model_Composer_ResolverInterface $lookupModel
     * @return $this
     */
    public function add(EcomDev_ComposerAutoload_Model_Composer_ResolverInterface $lookupModel);

    /**
     * Sets base path for Magento directory location
     * 
     * @param string $path
     * @return $this
     */
    public function setBasePath($path);

    /**
     * Sets cache adapter for autoloader
     * 
     * @param Varien_Cache_Core $cacheAdapter
     * @return $this
     */
    public function setCacheAdapter(Varien_Cache_Core $cacheAdapter);
}
