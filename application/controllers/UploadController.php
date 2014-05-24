<?php

class UploadController extends My_Controller_Action
{

    public function indexAction()
    {
        $this->disableLayout()->disableView();

        if( array_key_exists('photo', $_FILES ) ){
         //   My_Log_Me::Log($_FILES['photo']);
            $obj = My_Utils::uploadAvatar($_FILES['photo']);
/*            header('Content-type: text/json');
            header('Content-type: application/json');

            echo json_encode($obj);*/
        }

    }

    function deltempfileAction(){
        $filetoremove=basename($this->getRequest()->getParams()["file"]);

        $filetoremove=realpath( $this->config['upload']['folder'] )."/".$filetoremove;

        $result=unlink($filetoremove);

        }


    function getPhotoAction()
    {

        $id = $this->getRequest()->getParams()["id"];
        $userTable = new Table_User();
        $user=$userTable->getById($id);
        $photo=$user->getPhoto();
        $uploadFolder = realpath( $this->config['upload']['folder'] );
        $obj=array("name" => $photo,
                    "tmp_name" => $uploadFolder .'/'. $photo,
                    "error" => "",
                    "size" => filesize($uploadFolder."/".$photo));

        $newobj = My_Utils::uploadAvatar($obj);

        header('Content-type: text/json');
        header('Content-type: application/json');

        echo json_encode($newobj);
     //   My_Log_Me::Log(json_encode($newobj));
        exit;

    }
    }

