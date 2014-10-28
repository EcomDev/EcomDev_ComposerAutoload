<?php

class EcomDev_ComposerAutoload_Model_Composer_Resolver_Default
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
        $expectedFilePath = $basePath . DIRECTORY_SEPARATOR . self::COMPOSER_FILENAME;

        if (file_exists($expectedFilePath)) {
            return $expectedFilePath;
        }
        
        return false;
    }
}