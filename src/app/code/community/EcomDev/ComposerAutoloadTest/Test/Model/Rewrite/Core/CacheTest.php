<?php

class EcomDev_ComposerAutoloadTest_Test_Model_Rewrite_Core_CacheTest
    extends EcomDev_PHPUnit_Test_Case
{
    public function testItRunsSetupModelOnInstantiation()
    {
        $setupMock = $this->mockModel('ecomdev_composerautoload/setup');
        $setupMock->expects($this->once())
            ->method('setup')
            ->with($this->isInstanceOf('Varien_Cache_Core'))
            ->willReturnSelf();
        
        $this->replaceByMock('singleton', 'ecomdev_composerautoload/setup', $setupMock);
        
        new EcomDev_ComposerAutoload_Model_Rewrite_Core_Cache();
    }
}