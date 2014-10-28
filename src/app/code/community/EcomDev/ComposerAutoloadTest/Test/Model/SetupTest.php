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
 * Test of setup model
 */
class EcomDev_ComposerAutoloadTest_Test_Model_SetupTest
    extends EcomDev_PHPUnit_Test_Case
{
    /**
     * @var EcomDev_ComposerAutoload_Model_Setup
     */
    private $model;
    
    protected function setUp()
    {
        $this->model = new EcomDev_ComposerAutoload_Model_Setup();
    }


    public function testItCorrectlyInstantiatesAutoloaderInstance()
    {
        $autoloaderMock = $this->getMockForAbstractClass('EcomDev_ComposerAutoload_Model_AutoloaderInterface');
        $this->replaceByMock('model', 'ecomdev_composerautoload/autoloader', $autoloaderMock);
        
        $autoloaderMock->expects($this->exactly(3))
            ->method('add')
            ->withConsecutive(
                array(
                    $this->isInstanceOf('EcomDev_ComposerAutoload_Model_Composer_Resolver_Parent')
                ),
                array(
                    $this->isInstanceOf('EcomDev_ComposerAutoload_Model_Composer_Resolver_Default')
                ),
                array(
                    $this->isInstanceOf('EcomDev_ComposerAutoload_Model_Composer_Resolver_Lib')
                )
            )
            ->willReturnSelf();

        $autoloaderMock->expects($this->once())
            ->method('setBasePath')
            ->with(BP)
            ->willReturnSelf();
        
        $this->assertSame($autoloaderMock, $this->model->getAutoloader());
        $this->assertSame($autoloaderMock, $this->model->getAutoloader());
        $this->assertAttributeSame($autoloaderMock, '_autoloader', $this->model);
    }
    
    public function testItCallsSetupMethod()
    {
        $autoloaderMock = $this->getMockForAbstractClass('EcomDev_ComposerAutoload_Model_AutoloaderInterface');
        EcomDev_Utils_Reflection::setRestrictedPropertyValue($this->model, '_autoloader', $autoloaderMock);

        $autoloaderMock->expects($this->once())
            ->method('register')
            ->willReturnSelf();

        $autoloaderMock->expects($this->once())
            ->method('setCacheAdapter')
            ->with(Mage::app()->getCache())
            ->willReturnSelf();


        $this->model->setup(Mage::app()->getCache());
        $this->model->setup();
    }
}
