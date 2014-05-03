<?php

class UploadController extends My_Controller_Action
{

    public function indexAction()
    {
        $this->disableLayout()->disableView();

        $identity = Zend_Auth::getInstance()->getIdentity();


        $uploadFolder = realpath( $this->config['upload']['folder'] );

        if( $uploadFolder === false ) {
            throw new Exception("Invalid Upload Folder !");

        }
        My_Log_Me::Log("calea ".$uploadFolder);

        if( array_key_exists('photo', $_FILES ) ){
            $fileArr = $_FILES["photo"];

            if( $fileArr["error"] == 0 ) {
                if( file_exists( $fileArr['tmp_name']) && in_array($fileArr['type'] ,array('image/jpeg','image/png') )){
                    $ext  = strtolower( pathinfo($fileArr['name'],PATHINFO_EXTENSION) );

                    $newFileName=$fileArr['name'];
                     if(  move_uploaded_file($fileArr['tmp_name'], $uploadFolder  . '/' .  $newFileName )){
                         My_Log_Me::Log( "uploaded ". $fileArr["name"] );

                        exit;
                    }
                }
            }
        }

        My_Log_Me::Log( "Fail to upload" );
    }

    function deltempfileAction(){
        $filetoremove=$this->getRequest()->getParams()["file"];

        $filetoremove=realpath( $this->config['upload']['folder'] )."/".$filetoremove;

        $result=unlink($filetoremove);

        }
    }

