<?php

class EcomDev_ComposerAutoload_Model_Composer_Resolver_Lib
    implements EcomDev_ComposerAutoload_Model_Composer_ResolverInterface
{
    /**
     * Composer file location search
     *
     * @param $basePath
     * @return string|bool
     */
    public function resolve($basePath)
    {
        $expectedFilePath = $basePath . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . self::COMPOSER_FILENAME;
        
        if (file_exists($expectedFilePath)) {
            return $expectedFilePath;
        }
        
        return false;
    }
}