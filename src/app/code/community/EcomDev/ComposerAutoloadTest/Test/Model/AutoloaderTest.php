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
 * Test of autoloader model
 */
class EcomDev_ComposerAutoloadTest_Test_Model_AutoloaderTest
    extends EcomDev_PHPUnit_Test_Case
{
    /**
     * @var EcomDev_ComposerAutoload_Model_Autoloader
     */
    private $model;
    
    protected function setUp()
    {
        $this->model = new EcomDev_ComposerAutoload_Model_Autoloader();
    }
    
    public function testItHasLookupsPropertyDefined()
    {
        $this->assertObjectHasAttribute('_resolvers', $this->model);
    }
    
    public function testItHasCacheAdapterPropertyDefined()
    {
        $this->assertObjectHasAttribute('_cacheAdapter', $this->model);
    }

    public function testItHasBasePathPropertyDefined()
    {
        $this->assertObjectHasAttribute('_basePath', $this->model);
    }

    public function testItHasComposerPropertyDefined()
    {
        $this->assertObjectHasAttribute('_composer', $this->model);
    }


    public function testItIsPossibleToAddComposerResolverAndItAddItOnlyOnce()
    {
        /* @var EcomDev_ComposerAutoload_Model_Composer_ResolverInterface $resolver */
        $resolver = $this->getMock('EcomDev_ComposerAutoload_Model_Composer_ResolverInterface');
        $this->assertSame($this->model, $this->model->add($resolver));
        $this->assertSame($this->model, $this->model->add($resolver));
        $this->assertAttributeSame(array($resolver), '_resolvers', $this->model);
    }
    
    public function testItAddsMultipleResolvers()
    {
        /* @var EcomDev_ComposerAutoload_Model_Composer_ResolverInterface $resolverOne */
        $resolverOne = $this->getMock('EcomDev_ComposerAutoload_Model_Composer_ResolverInterface');
        /* @var EcomDev_ComposerAutoload_Model_Composer_ResolverInterface $resolverTwo */
        $resolverTwo = $this->getMock('EcomDev_ComposerAutoload_Model_Composer_ResolverInterface');
        
        $this->assertSame($this->model, $this->model->add($resolverOne));
        $this->assertSame($this->model, $this->model->add($resolverTwo));

        $this->assertAttributeSame(array($resolverOne, $resolverTwo), '_resolvers', $this->model);
    }
    
    public function testItIsPossibleToSetCacheAdapter()
    {
        /* @var $cacheAdapter Varien_Cache_Core */
        $cacheAdapter = $this->getMockBuilder('Varien_Cache_Core')->disableOriginalConstructor()->getMock();
        $this->assertSame($this->model, $this->model->setCacheAdapter($cacheAdapter));
        
        $this->assertAttributeSame($cacheAdapter, '_cacheAdapter', $this->model);
    }
    
    public function testItIsPossibleToSetBasePath()
    {
        $this->assertSame($this->model, $this->model->setBasePath('test/path'));
        
        $this->assertAttributeSame('test/path', '_basePath', $this->model);
    }
    
    public function testItUsesResolversToFindComposerFile()
    {
        /* @var EcomDev_ComposerAutoload_Model_Composer_ResolverInterface $resolverOneNotFound */
        $resolverOneNotFound = $this->getMock('EcomDev_ComposerAutoload_Model_Composer_ResolverInterface');
        /* @var EcomDev_ComposerAutoload_Model_Composer_ResolverInterface $resolverTwoFound */
        $resolverTwoFound = $this->getMock('EcomDev_ComposerAutoload_Model_Composer_ResolverInterface');
        /* @var EcomDev_ComposerAutoload_Model_Composer_ResolverInterface $resolverThreeNotFound */
        $resolverThreeNotFound = $this->getMock('EcomDev_ComposerAutoload_Model_Composer_ResolverInterface');

        $basePath = 'test/path';
        
        $resolverOneNotFound->expects($this->once())
            ->method('resolve')
            ->with($basePath)
            ->willReturn(false);
        
        $resolverTwoFound->expects($this->once())
            ->method('resolve')
            ->with($basePath)
            ->willReturn('test/path/composer.json');
        
        $resolverThreeNotFound->expects($this->never())
            ->method('resolve');
        
        $this->model->add($resolverOneNotFound);
        $this->model->add($resolverTwoFound);
        $this->model->add($resolverThreeNotFound);
        
        $this->model->setBasePath($basePath);
        
        $this->assertEquals(
            'test/path/composer.json', 
            $this->model->resolveComposer()
        );
    }

    /**
     * @loadFixture composer-files
     * @dataProvider dataProvider
     * @dataProviderFile fileParse
     * @loadExpectation fileParse
     */
    public function testItLoadsComposerJsonConfigurationCorrectlyAndOnlyOnce($path)
    {
        $basePath = $this->getFixture()->getVfs()->url($path);
        $composerFile = $this->getFixture()->getVfs()->url($path . '/composer.json');

        $resolver = $this->getMock('EcomDev_ComposerAutoload_Model_Composer_ResolverInterface');
        $resolver->expects($this->once())
            ->method('resolve')
            ->with($basePath)
            ->willReturn($composerFile);
        
        $this->model->add($resolver);
        $this->model->setBasePath($basePath);
        $this->model->loadComposer();
        $this->model->loadComposer();
        $this->assertAttributeEquals(
            $this->expected('auto')->getData(),
            '_composer',
            $this->model
        );
    }

    /**
     * @loadFixture composer-files
     * @dataProvider dataProvider
     * @dataProviderFile fileParse
     * @loadExpectation fileParse
     */
    public function testItUsesCacheAdapterForStoringJsonData($path)
    {
        $basePath = $this->getFixture()->getVfs()->url($path);
        $composerFile = $this->getFixture()->getVfs()->url($path . '/composer.json');

        $resolver = $this->getMock('EcomDev_ComposerAutoload_Model_Composer_ResolverInterface');
        $resolver->expects($this->once())
            ->method('resolve')
            ->with($basePath)
            ->willReturn($composerFile);

        $expectedComposerData = $this->expected('auto')->getData();

        $cacheAdapter = $this->getMockBuilder('Varien_Cache_Core')->disableOriginalConstructor()->getMock();
        $cacheAdapter->expects($this->once())
            ->method('save')
            ->with(
                serialize($expectedComposerData), 
                EcomDev_ComposerAutoload_Model_Autoloader::CACHE_KEY, 
                array(EcomDev_ComposerAutoload_Model_Autoloader::CACHE_TAG)
            )
            ->willReturnSelf();
        
        $cacheAdapter->expects($this->once())
            ->method('load')
            ->with(EcomDev_ComposerAutoload_Model_Autoloader::CACHE_KEY)
            ->willReturn(false);
        
        $this->model->setCacheAdapter($cacheAdapter);
        $this->model->add($resolver);
        $this->model->setBasePath($basePath);
        $this->model->loadComposer();
    }

    /**
     * @loadFixture composer-files
     * @dataProvider dataProvider
     * @dataProviderFile fileParse
     * @loadExpectation fileParse
     */
    public function testItUsesCacheAdapterForLoadingJsonData($path)
    {
        $basePath = $this->getFixture()->getVfs()->url($path);

        $resolver = $this->getMock('EcomDev_ComposerAutoload_Model_Composer_ResolverInterface');
        $resolver->expects($this->never())
            ->method('resolve')
            ->with($basePath);

        $expectedComposerData = $this->expected('auto')->getData();

        $cacheAdapter = $this->getMockBuilder('Varien_Cache_Core')->disableOriginalConstructor()->getMock();
        $cacheAdapter->expects($this->never())
            ->method('save');

        $cacheAdapter->expects($this->once())
            ->method('load')
            ->with(EcomDev_ComposerAutoload_Model_Autoloader::CACHE_KEY)
            ->willReturn(serialize($expectedComposerData));

        $this->model->setCacheAdapter($cacheAdapter);
        $this->model->add($resolver);
        $this->model->setBasePath($basePath);
        $this->model->loadComposer();

        $this->assertAttributeEquals(
            $expectedComposerData,
            '_composer',
            $this->model
        );
    }

    /**
     * 
     * @param $configuration
     * @dataProvider dataProvider
     * @loadFixture composer-files
     */
    public function testItTakesVendorAutoloadFileFromConfiguration($configuration)
    {
        if (is_array($configuration)) {
            $configuration['file_path'] = $this->getFixture()->getVfs()->url($configuration['file_path']);
        }
        
        EcomDev_Utils_Reflection::setRestrictedPropertyValue(
            $this->model,
            '_composer',
            $configuration
        );
        
        $this->assertEquals(
            $this->expected('auto')->getFile(),
            $this->model->getAutoloadFilePath()
        );
    }
    
    public function testItRegistersAutoloadWrapperIfVarienAutoloadFunctionIsNotRegistered()
    {
        spl_autoload_register(array(Varien_Autoload::instance(), 'autoload'));
        $this->model->register();
        $this->assertNotContains(array(Varien_Autoload::instance(), 'autoload'), spl_autoload_functions());
        $this->assertContains(array($this->model, 'autoload'), spl_autoload_functions());
    }

    public function testItDoesNotRiseExceptionOnAutoloadOfUnknownClass()
    {
        $this->assertNull(
            $this->model->autoload('Unkown_Class_Name')
        );
    }
    
    /**
     * Clear ups autoload functions
     * 
     */
    protected function tearDown()
    {
        if (in_array(array($this->model, 'autoload'), spl_autoload_functions())) {
            spl_autoload_unregister(array($this->model, 'autoload'));
        }
    }
}
