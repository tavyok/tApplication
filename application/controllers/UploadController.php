<?php

class UploadController extends My_Controller_Action
{
    public function init()
    {

        parent::init();

        //  $this->_helper->_layout->setLayout('layout-orig');

        $this->view->assign("action", $this->getRequest()->getActionName());
        $this->view->render('user/_menu.phtml'); //include _menu.phtml in pagina
        $this->view->render('user/_signin.phtml');


    }

    public function indexAction() //preluare initiala poza prin dropzone din buton upload sau drag drop
    {
        $this->disableLayout()->disableView();

        if (array_key_exists('photo', $_FILES)) {

            //  My_Log_Me::Log($_FILES['photo']);
            $obj = My_Utils::uploadAvatar($_FILES['photo']);
            //    My_Log_Me::Log($obj);

            /*            header('Content-type: text/json');
                        header('Content-type: application/json');

                        echo json_encode($obj);*/
        }

    }


    public function photoAction()
    {
        $this->disableLayout()->disableView();

        if (array_key_exists('photo', $_FILES)) {

            $fileAccepted = $this->getRequest()->getParam("realfiles");
            $arrFiles = Zend_Json::decode( $fileAccepted );


            if( in_array($_FILES['photo']['name'], $arrFiles ) ){



                $obj = My_Utils::uploadPhoto($this->identity["id"], $_FILES['photo']);

            }
        }
    }


    function deltempfileAction() //apelata in aplicatie prin ajax
    {
        $params=$this->getRequest()->getParams();
        $filetoremove = basename($params["file"]);

        $filetoremove = realpath($this->config['upload']['folder']) . "/" . $filetoremove;

        $response = unlink($filetoremove);

        echo $response;

    }

    public function cleanAvatarsAction()
    {

        $config = Zend_Registry::get("__CONFIG__");
        $timetoclean =$config['cleaning']['time']; //hours   for cleaning files created 1 hour ago


        $photopath = $_SERVER["DOCUMENT_ROOT"].'/photos';

        $datetime = new DateTime();
        $datetime2 = new DateTime();

        $picturesFiles = glob($photopath . '/*.*');

        $picturesOnServer = array();
        for( $i = 0; $i < count($picturesFiles); $i++) {
            $picturesOnServer[] = basename( $picturesFiles[$i]);
        }


        $userTable=new Table_User();

        $users=$userTable->getAll()->toArray();

        $photosArray = array();


        for ($i=0;$i<count($users);$i++)
        {
            $photosArray[$i]=$users[$i]["photo"];
        }

        $countpic = 0;
        $filestodelete=array_diff($picturesOnServer,$photosArray);
//        My_Log_Me::Log($filestodelete);
        foreach ($filestodelete as $removefile)
        {


            $filetime = date('H:i d-m-Y', filectime(realpath($photopath . "/" . $removefile)));

            $datetime2->setTimestamp(strtotime($filetime));

            if ($datetime2 < $datetime->modify("-$timetoclean hour")) {
                if (unlink($photopath . "/" . $removefile)) {
                    $countpic++;
                }
                else


                    $datetime->modify("+$timetoclean hour");
            }

        }


        $this->view->assign("countpic", $countpic);
        $this->view->assign("timetoclean", $timetoclean);

    }


    function getPhotoAction() //preluare poza din baza de date
    {


        $params = $this->getRequest()->getParams();

        $id = $params["id"];
        $userTable = new Table_User();
        $user = $userTable->getById($id);
        $photo = $user->getPhoto();
        $uploadFolder = realpath($this->config['upload']['folder']);
        $obj = array("name" => $photo,
            "tmp_name" => $uploadFolder . '/' . $photo,
            "error" => "",
            "size" => filesize($uploadFolder . "/" . $photo));

        $newobj = My_Utils::uploadAvatar($obj);
        My_Log_Me::Log($newobj);
        header('Content-type: text/json');
        header('Content-type: application/json');

        echo json_encode($newobj);


        exit;

    }

    function deleteFromAlbumAction(){
        $photostodelete=json_decode($this->getRequest()->getParam("photostodelete"));
        foreach ($photostodelete as $delphoto){
            unlink(realpath($this->config['upload']['folder']) . "/gallery/thumb/" . $delphoto);

            $otherphoto=glob(realpath($this->config['upload']['folder']) . "/gallery/original/" . substr($delphoto,0,15). "*.*");

            unlink($otherphoto[0]);

        }

        $this->redirect("/upload/photos");
    }
    function photosAction(){
        My_Utils::cleanPhotos(); //clean table photo by files not found on server for curent user(id)
    }

    function galleryAction(){


    }
}

