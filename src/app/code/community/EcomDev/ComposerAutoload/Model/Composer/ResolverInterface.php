<?php

interface EcomDev_ComposerAutoload_Model_Composer_ResolverInterface
{
    const COMPOSER_FILENAME = 'composer.json';
    
    /**
     * Composer file location search
     * 
     * @param $basePath
     * @return string|bool
     */
    public function resolve($basePath);
}

    