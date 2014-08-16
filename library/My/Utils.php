<?php

/**
 * Created by PhpStorm.
 * User: NicolaeSergiu
 * Date: 12/7/13
 * Time: 3:05 PM
 */
class My_Utils
{


    static protected $_instance;
    static protected $_config;

    public function __construct()
    {


       // self::getInstance();


    }

    static public function getInstance()
    {
        if (is_null(self::$_instance)) {
              //    print "Instanta Noua !\n";
            self::$_instance = new My_Utils();
            self::getRegConfig();
        }

        return self::$_instance;

    }

    static public function getRegConfig(){
        self::$_config = Zend_Registry::get("__CONFIG__");
        My_Log_Me::Log(self::$_config);
        return self::$_config;

    }

    static public function randomstr($n)
    {

        $s = "";
        for ($i = 1; $i <= $n; $i++) {
            $s = $s . chr(rand(65, 90));
        }
        return $s;
    }

    static public function randomString($n)
    {
        $model = "0123456789abcdefghijklmnopqrstuvxyz";
        $length = strlen($model);
        $s = '';
        for ($i = 1; $i <= $n; $i++) {
            $s .= $model[rand(0, $length - 1)];
        }
        return $s;
    }


    static public function buildImageFile($id)
    {
        //   return sprintf("p%05d%s", $id, self::randomString(10));
        return sprintf("avatar%05d%s", $id, "");
    }

    static public function buildTimedFile($pic)
    {
        $datetime = new DateTime();

        $ext = strtolower(pathinfo($pic, PATHINFO_EXTENSION));
        $filename = strtolower(pathinfo($pic, PATHINFO_FILENAME));
        $newpic = $filename . $datetime->format('HidmY') . "." . $ext;

        return $newpic;

    }


    static public function uploadAvatar($photo) //apelata la incarcare initiala sau preluare din tabela
    {


        $uploadFolder = realpath(Zend_Registry::get('__CONFIG__')['upload']['folder']);
        try {
            if ($uploadFolder === false) {
                throw new RuntimeException("Invalid Upload Folder !");

            }


            // Undefined | Multiple Files | $_FILES Corruption Attack
            // If this request falls under any of them, treat it invalid.
            if (
                !isset($photo['error']) || is_array($photo['error']) ) {
                throw new RuntimeException('Invalid parameters.');
            }

            // Check $_FILES['upfile']['error'] value.
            switch ($photo['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new RuntimeException('No file sent.');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new RuntimeException('Exceeded filesize limit.');
                default:
                    throw new RuntimeException('Unknown errors.');
            }

            // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
            // Check MIME Type by yourself.
            $finfo = new finfo(FILEINFO_MIME_TYPE);

            if (false === $ext = array_search(
                    $finfo->file($photo['tmp_name']),
                    array(
                        'jpg' => 'image/jpeg',
                        'png' => 'image/png',
                        'gif' => 'image/gif',
                    ),
                    true
                )
            ) {
                throw new RuntimeException('Invalid file format.');
            }
            // You should name it uniquely.
            // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
            // On this example, obtain safe unique name from its binary data.


            $newFileName = $photo['name'];
       //     My_Log_Me::Log($photo);

            if ($photo['tmp_name'] != $uploadFolder . "/" . $newFileName)
                if (!move_uploaded_file($photo['tmp_name'], $uploadFolder . "/" . $newFileName)) {
                    throw new RuntimeException('Failed to move uploaded file.');
                }

            $photoarray['name'] = $newFileName;
            $photoarray['size'] = filesize($uploadFolder . "/" . $newFileName);
            $photoarray["error"] = null;


            return $photoarray;

        } catch (RuntimeException $e) {

            $photoarray['name'] = null;
            $photoarray['size'] = null;
            $photoarray["error"] = $e->getMessage();
              My_Log_Me::Log( $e->getMessage());
            return $photoarray;
        }
    }


    static public function uploadPhoto($userId, $photo)
    {
        $uploadFolder = realpath(Zend_Registry::get('__CONFIG__')['upload']['folder']);

        try {
        //   My_Log_Me::Log($photo);
            if ($uploadFolder === false) {
                throw new RuntimeException("Invalid Upload Folder !");
            }

            // Undefined | Multiple Files | $_FILES Corruption Attack
            // If this request falls under any of them, treat it invalid.
            if (!isset($photo['error']) ) {
                throw new RuntimeException('Invalid parameters.');
            }

            // Check $_FILES['upfile']['error'] value.
            switch ($photo['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new RuntimeException('No file sent.');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new RuntimeException('Exceeded filesize limit.');
                default:
                    throw new RuntimeException('Unknown errors.');
            }


            // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
            // Check MIME Type by yourself.
            $fInfo = new finfo(FILEINFO_MIME_TYPE);

            if (false === $ext = array_search(
                    $fInfo->file($photo['tmp_name']),
                    array(
                        'jpg' => 'image/jpeg',
                        'png' => 'image/png',
                        'gif' => 'image/gif',
                    ),
                    true
                )
            ) {
                throw new RuntimeException('Invalid file format.');
            }
            // You should name it uniquely.
            // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
            // On this example, obtain safe unique name from its binary data.

            $photoTable = new Table_Photo();
            /** @var Model_Photo $photoObj */
            $photoObj = $photoTable->createRow();
            $photoObj->setUserId($userId);
            $photoObj->setOrigName($photo['name']);
            $photoObj->setName("not-available");

            $photoObj->save();

            $photoId = $photoObj->getId();

            $newFileName = sprintf('p%06d%06d%s.%s',$userId,$photoId,self::randomString(8),$ext);

            $photoObj->setName( $newFileName );

            if ( ! move_uploaded_file($photo['tmp_name'], $uploadFolder . "/gallery/original/" . $newFileName)) {
                $photoObj->delete();
                throw new RuntimeException('Failed to move uploaded file.');
            }

            $photoObj->save();

            $photoarray['name'] = $newFileName;
            $photoarray['size'] = filesize($uploadFolder . "/gallery/original/" . $newFileName);
            $photoarray["error"] = null;

            //set scale dimensions for thumbs
            $scaleW=0;
            $scaleH=260;
            $scfile=array();
            $scfile["name"]=pathinfo($uploadFolder . "/gallery/original/".$newFileName,PATHINFO_FILENAME);
            $scfile["ext"]=pathinfo($uploadFolder . "/gallery/original/".$newFileName,PATHINFO_EXTENSION);
            My_Utils::pictureScale($uploadFolder . "/gallery/original/".$newFileName,$uploadFolder ."/gallery/thumb/",$scfile["name"].$scaleW."x".$scaleH.".".$scfile["ext"],($scaleW==0)?"auto":$scaleW,($scaleH==0)?"auto":$scaleH);



            return $photoarray;

        } catch (RuntimeException $e) {

            $photoarray['name'] = null;
            $photoarray['size'] = null;
            $photoarray["error"] = $e->getMessage();
       //       My_Log_Me::Log($photoarray);
            return $photoarray;
        }
    }


    static public function cleanAvatars()
    {

        self::getRegConfig();
        if( isset(self::$_config["cleaning"]) ){
            if( array_key_exists("time",self::$_config["cleaning"])){
                $timetoclean  = self::$_config["cleaning"]["time"];
            }
        }
        else
        {
            $timetoclean = 2; //hours   - cleaning files created ($timetoclean) hours ago
        }
    //    My_Log_Me::Log(self::$_config);
        echo $timetoclean;
        $photopath = $_SERVER["DOCUMENT_ROOT"].'/photos';

        $datetime = new DateTime();
        $datetime2 = new DateTime();

        $picturesFiles = glob($photopath . '/*.*');
        $pictures = array();
        for ($i = 0; $i < count($picturesFiles); $i++) {
            $countpic = 0;
            $pictures[] = pathinfo($picturesFiles[$i], PATHINFO_BASENAME);
            $filetime = date('H:i d-m-Y', filectime(realpath($photopath . "/" . $pictures[$i])));

            $datetime2->setTimestamp(strtotime($filetime));
            //  $interval = $datetime2->diff($datetime);
            //  echo " --- ".$datetime->format('H:i d-m-Y');
            //  echo "&nbsp;&nbsp;&nbsp;".$interval->format('%R%d Days %h Hours %i Minute %s Seconds');
            My_Log_Me::Log($picturesFiles);
            if (substr($pictures[$i], 0, 6) != "avatar") {
                if ($datetime2 < $datetime->modify("-$timetoclean hour")) {


                    if (unlink(realpath($photopath . "/" . $pictures[$i]))) {
                        $countpic++;
                    }


                }
                $datetime->modify("+$timetoclean hour");
            }

        }


    }

   static public function cleanPhotos(){

        $photopath = $_SERVER["DOCUMENT_ROOT"].('/photos/gallery/original');

        $id=Zend_Auth::getInstance()->getIdentity()["id"];
        $picturesFiles = glob ( $photopath."/".sprintf("p%06d%s",$id,"*.*"));

        $picturesOnServer = array();
        for( $i = 0; $i < count($picturesFiles); $i++ ){
            $picturesOnServer[] = pathinfo( $picturesFiles[$i],PATHINFO_BASENAME );
        }


       $photoTable=new Table_Photo();
       $photosNamesArray=array();
       $photosGlobalArray=$photoTable->getAll($id)->toArray();
       for ($i=0;$i<count($photosGlobalArray);$i++)
       {
           $photosNamesArray[]=$photosGlobalArray[$i]["name"];
       }

       $filestodelete=array_diff($photosNamesArray,$picturesOnServer);

       foreach ($filestodelete as $filetodelete)
       {
           $photoTable->getByName($filetodelete)->delete();
       }


   }

    static public function getPictureDetails($file){
        if (file_exists($file))
        {
            $fileDetails=array();
            $fileDetails["name"]=pathinfo($file,PATHINFO_BASENAME);
            $fileDetails["fullpath"]=$file;
            $fileDetails["type"]= image_type_to_mime_type(exif_imagetype($file));
            $fileDetails["dimensions"]=getimagesize($file);
            $fileDetails["width"]=$fileDetails["dimensions"]["0"];
            $fileDetails["height"]=$fileDetails["dimensions"]["1"];
            return $fileDetails;
        }
        else
            return false;

    }

    static public function pictureScale($file,$filescPath,$filename,$newW,$newH){


        if (! $fileDetails=My_Utils::getPictureDetails($file)){
            return false;

        }
        if ($newW=="auto"){
            $newW=$fileDetails["width"] * $newH/$fileDetails["height"];
        }
        if ($newH=="auto"){
            $newH=$fileDetails["height"] * $newW/$fileDetails["width"];
        }
        switch ($fileDetails["type"]){
            case "image/jpeg":
                $src=imagecreatefromjpeg($file);

                $dst = imagecreatetruecolor($newW, $newH);
                imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $fileDetails["width"], $fileDetails["height"]);

                imagejpeg($dst, $filescPath.$filename,100);
                $scale_info = getimagesize($filescPath."newimage.jpg");
                break;

            case "image/png":
                $src=imagecreatefrompng($file);
                $dst = imagecreatetruecolor($newW, $newH);
                imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $fileDetails["width"], $fileDetails["height"]);
                imagepng($dst, $filescPath.$filename,0);
                $scale_info = getimagesize($filescPath."newimage.png");
                break;

            case "image/gif":
                $src=imagecreatefromgif($file);
                $dst = imagecreatetruecolor($newW, $newH);
                imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $fileDetails["width"], $fileDetails["height"]);
                imagegif($dst, $filescPath.$filename);
                $scale_info = getimagesize($filescPath."newimage.gif");
                break;
        }
        imagedestroy($dst);
        if (array_keys($scale_info,3))
            return $scale_info[3];
        else
            return false;


    }

}