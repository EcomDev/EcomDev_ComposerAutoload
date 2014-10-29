<?php

class EcomDev_ComposerAutoload_Model_Composer_Resolver_Parent
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
        while (!in_array(dirname($basePath), array('.', '..', '')) 
                && $basePath !== dirname($basePath) // Fix recursion on root directory 
                && is_dir(dirname($basePath))) {
            $basePath = dirname($basePath);
            $expectedFilePath = $basePath . DIRECTORY_SEPARATOR . self::COMPOSER_FILENAME; 
            
            if (file_exists($expectedFilePath)) {
                return $expectedFilePath;
            }
        }
        
        return false;
    }
}