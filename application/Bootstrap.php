<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{


    /*
    * Initializing Model path
    */
    protected function _initResourceLoader()
    {

        $resourceLoader = new Zend_Loader_Autoloader_Resource(array(
            'basePath' 		=> APPLICATION_PATH,
            'namespace' 	=> '',
            'resourceTypes' => array(
                'model' => array(
                    'path' => 'models/',
                    'namespace' => 'Model'
                ),
                'table' => array(
                    'path' => 'models/Table',
                    'namespace' => 'Table'
                )
            )
        ));

        return $resourceLoader;
    }

}

