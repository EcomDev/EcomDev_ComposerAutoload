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
 * Composer autoloader integration
 *
 * 
 */
class EcomDev_ComposerAutoload_Model_Autoloader
    implements EcomDev_ComposerAutoload_Model_AutoloaderInterface
{

    /**
     * Adds resolver interfaces
     * 
     * @var EcomDev_ComposerAutoload_Model_Composer_ResolverInterface[]
     */
    protected $_resolvers = array();

    /**
     * @var Varien_Cache_Core
     */
    protected $_cacheAdapter;

    /**
     * @var string
     */
    protected $_basePath;

    /**
     * Composer configuration information
     * 
     * @var bool|array
     */
    protected $_composer;
    
    /**
     * Registers composer file
     *
     * @return $this
     */
    public function register()
    {
        if ($autoloaderFile = $this->getAutoloadFilePath()) {
            include $autoloaderFile;
        }

        if (in_array(array(Varien_Autoload::instance(), 'autoload'), spl_autoload_functions(), true)) {
            spl_autoload_unregister(array(Varien_Autoload::instance(), 'autoload'));
            spl_autoload_register(array($this, 'autoload'), true, true);
        }

        return $this;
    }

    /**
     * Autoload a class
     * 
     * @param string $className
     * @return void
     */
    public function autoload($className)
    {
        @Varien_Autoload::instance()->autoload($className);
    }

    /**
     * Adds resolver model for composer file location
     *
     * @param EcomDev_ComposerAutoload_Model_Composer_ResolverInterface $lookupModel
     * @return $this
     */
    public function add(EcomDev_ComposerAutoload_Model_Composer_ResolverInterface $lookupModel)
    {        
        if (in_array($lookupModel, $this->_resolvers, true)) {
            return $this;
        }
        
        $this->_resolvers[] = $lookupModel;
        return $this;
    }

    /**
     * Sets base path for Magento directory location
     *
     * @param string $path
     * @return $this
     */
    public function setBasePath($path)
    {
        $this->_basePath = $path;
        return $this;
    }

    /**
     * Sets cache adapter for autoloader
     *
     * @param Varien_Cache_Core $cacheAdapter
     * @return $this
     */
    public function setCacheAdapter(Varien_Cache_Core $cacheAdapter)
    {
        $this->_cacheAdapter = $cacheAdapter;
        return $this;
    }

    /**
     * Resolves composer file location by using assigned resolvers
     * 
     * @return bool|string
     */
    public function resolveComposer()
    {
        foreach ($this->_resolvers as $resolver) { 
            $composerPath = $resolver->resolve($this->_basePath);
            if ($composerPath !== false) {
                return $composerPath;
            }
        }
        
        return false;
    }

    /**
     * Loads composer configuration path
     * 
     * @return $this
     */
    public function loadComposer()
    {
        if ($this->_composer !== null) {
            return $this;
        }
        
        if ($this->_cacheAdapter && ($data = $this->_cacheAdapter->load(self::CACHE_KEY))) {
            $this->_composer = unserialize($data);
            return $this;
        }
        
        $filePath = $this->resolveComposer();
        $data = false;
        if ($filePath) {
            $data = json_decode(file_get_contents($filePath), true);
            if (!$data) {
                $data = array();
            }
            $data['file_path'] = $filePath;
        }
        
        $this->_composer = $data;
        if ($this->_cacheAdapter) {
            $this->_cacheAdapter->save(serialize($data), self::CACHE_KEY, array(self::CACHE_TAG));
        }
        return $this;
    }

    /**
     * Returns path to an autoload file based on composer configuration
     * 
     * @return bool|string
     */
    public function getAutoloadFilePath()
    {
        $this->loadComposer();
        
        if (is_array($this->_composer)) {
            $basePath = dirname($this->_composer['file_path']);
            $vendorDir = 'vendor';
            if (isset($this->_composer['config']['vendor-dir'])) {
                $vendorDir = $this->_composer['config']['vendor-dir'];
            }

            $filePath = $basePath . '/' . $vendorDir . '/autoload.php';
            if (file_exists($filePath)) {
                return $filePath;
            }
        }   
        
        return false;
    }
}
