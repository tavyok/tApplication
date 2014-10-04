<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{



    protected function _initConfig(){
        Zend_Registry::set("__CONFIG__",$this->getOptions());

    }



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

    protected function _initPlugins(){
        $front = Zend_Controller_Front::getInstance();
        $front->registerPlugin( new My_Controller_Plugin_Auth() );
    }

}

