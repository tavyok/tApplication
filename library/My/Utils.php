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

    public function __construct()
    {

        //self::getInstance();


    }

    static public function getInstance()
    {
        if (is_null(self::$_instance)) {
            //      print "Instanta Noua !\n";
            self::$_instance = new My_Utils();
        }
        return self::$_instance;

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
            My_Log_Me::Log($photo);

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

         //         My_Log_Me::Log($photoarray);
            return $photoarray;

        } catch (RuntimeException $e) {

            $photoarray['name'] = null;
            $photoarray['size'] = null;
            $photoarray["error"] = $e->getMessage();
       //       My_Log_Me::Log($photoarray);
            return $photoarray;
        }
    }

}