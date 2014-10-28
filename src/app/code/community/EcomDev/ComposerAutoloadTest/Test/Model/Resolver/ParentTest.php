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
 * Test for parent path resolver
 */
class EcomDev_ComposerAutoloadTest_Test_Model_Resolver_ParentTest
    extends EcomDev_PHPUnit_Test_Case
{
    /**
     * @var EcomDev_ComposerAutoload_Model_Composer_Resolver_Parent
     */
    protected $resolver;
    
    protected function setUp()
    {
        $this->resolver = new EcomDev_ComposerAutoload_Model_Composer_Resolver_Parent();
    }

    /**
     * @param $basePath
     * @loadFixture composer-locations
     * @dataProvider dataProvider
     */
    public function testItLooksForAFileInParentDirectory($basePath)
    {
        $basePath = $this->getFixture()->getVfs()->url($basePath);
        $this->assertSame(
            $this->expected('auto')->getFile(), 
            $this->resolver->resolve($basePath)
        );
    }
}