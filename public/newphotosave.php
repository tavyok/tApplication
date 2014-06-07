<?php
/**
 * Created by PhpStorm.
 * User: Tavy
 * Date: 05.06.2014
 * Time: 03:42
 */

//redenumire poza la salvarea userului
$user=$tableUser->getByUsername($params["username"]);

if( isset ($params["photoup"] ) ){
    $photo = $params["photoup"];

    $uploadFolder = realpath( $this->config['upload']['folder'] );
    if ($photo!="")
        if( file_exists( $uploadFolder."/".$photo)){
            $ext  = strtolower( pathinfo($photo,PATHINFO_EXTENSION) );
            $newFileName = My_Utils::buildImageFile( $user->getId() ) . "." . $ext;


            if(  rename($uploadFolder. "/" .$photo, $uploadFolder  . '/' .  $newFileName )){
                My_Log_Me::Log( "moved". $photo ."->".$newFileName);

            }


            $user->setPhoto($newFileName);
            try {
                $user->save();
            } catch (Exception $e) {
                Zend_Debug::dump($e->getMessage());
                return;
            }
        }
}   //end redenumire poza cu salvare

?>