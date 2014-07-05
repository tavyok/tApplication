<?php

class UploadController extends My_Controller_Action
{

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
        //   $this->disableLayout()->disableView();
        //      My_Log_Me::Log($this->getRequest()->getParams());
        if (array_key_exists('photo', $_FILES)) {

            $fileAccepted = $this->getRequest()->getParam("realfiles");
            $arrFiles = Zend_Json::decode( $fileAccepted );

            My_Log_Me::Log(  "fileAccepted: " . $fileAccepted );
            My_Log_Me::Log(  $arrFiles );
            My_Log_Me::Log(  $_FILES['photo']['name'] );

            if( in_array($_FILES['photo']['name'], $arrFiles ) ){
                My_Log_Me::Log("Uploaded !");
                $photopath = realpath($this->config['upload']['folder']) . "/gallery/original/";
                $this->view->assign("photopath", $photopath);

                $obj = My_Utils::uploadPhoto($this->identity["id"], $_FILES['photo']);
                $this->view->assign("photoarray", $obj);
            }
        }
    }


    function deltempfileAction() //apelata in aplicatie prin ajax
    {
        $filetoremove = basename($this->getRequest()->getParams()["file"]);

        $filetoremove = realpath($this->config['upload']['folder']) . "/" . $filetoremove;

        $result = unlink($filetoremove);

    }

    public function cleanAvatarsAction()
    {

        $timetoclean = 0; //hours
        $datetime = new DateTime();
        $datetime2 = new DateTime();

        $picturesFiles = glob(realpath($this->config['upload']['folder']) . '/*.*');
        $pictures = array();
        for ($i = 0; $i < count($picturesFiles); $i++) {
            $countpic = 0;
            $pictures[] = pathinfo($picturesFiles[$i], PATHINFO_BASENAME);
            $filetime = date('H:i d-m-Y', filectime(realpath($this->config['upload']['folder']) . "/" . $pictures[$i]));
            // echo "<BR>". $pictures[$i]."   ".$filetime;
            $datetime2->setTimestamp(strtotime($filetime));
            //  $interval = $datetime2->diff($datetime);
            //  echo " --- ".$datetime->format('H:i d-m-Y');
            //  echo "&nbsp;&nbsp;&nbsp;".$interval->format('%R%d Days %h Hours %i Minute %s Seconds');
            if (substr($pictures[$i], 0, 6) != "avatar") {
                if ($datetime2 < $datetime->modify("-$timetoclean hour")) {


                    if (unlink(realpath($this->config['upload']['folder'] . "/" . $pictures[$i]))) {
                        $countpic++;
                    }


                }
                $datetime->modify("+$timetoclean hour");
            }

        }

        $this->view->assign("countpic", $countpic);

        // $this->redirect("/user");
    }


    function getPhotoAction() //preluare poza din baza de date
    {

        $id = $this->getRequest()->getParams()["id"];
        $userTable = new Table_User();
        $user = $userTable->getById($id);
        $photo = $user->getPhoto();
        $uploadFolder = realpath($this->config['upload']['folder']);
        $obj = array("name" => $photo,
            "tmp_name" => $uploadFolder . '/' . $photo,
            "error" => "",
            "size" => filesize($uploadFolder . "/" . $photo));

        $newobj = My_Utils::uploadAvatar($obj);

        header('Content-type: text/json');
        header('Content-type: application/json');

        echo json_encode($newobj);
        //   My_Log_Me::Log(json_encode($newobj));
        exit;

    }
}

