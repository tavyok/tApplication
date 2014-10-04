<?php
/**
 * Created by PhpStorm.
 * User: Tavy
 * Date: 05.06.2014
 * Time: 04:00
 */

//redenumire poza la editare user
if( isset ($params["photoup"] ) )
{

    $photo = $params["photoup"];
    My_Log_Me::Log("extract ".$photo);
    $uploadFolder = realpath( $this->config['upload']['folder'] );
    if ($photo!="")
    {
        if( file_exists( $uploadFolder."/".$photo)){
            $ext  = strtolower( pathinfo($photo,PATHINFO_EXTENSION) );
            $newFileName = My_Utils::buildImageFile( $user->getId() ) . "." . $ext;

            $scaleW = Zend_Registry::get('__CONFIG__')['thumb']['Width'];
            $scaleH= Zend_Registry::get('__CONFIG__')['thumb']['Height'];

            // $scaleW = 240;
            // $scaleH = 180;

            My_Utils::pictureScale(
                $uploadFolder . "/" . $photo,
                $uploadFolder ."/",
                $newFileName,
                $scaleW,$scaleH
            );

/*           if(  rename($uploadFolder. "/" .$photo, $uploadFolder  . '/' .  $newFileName )){
               My_Log_Me::Log( "moved". $photo ."->".$newFileName);

           }
*/

            $user->setPhoto($newFileName);
            try {
                $user->save();
            } catch (Exception $e) {
                Zend_Debug::dump($e->getMessage());
                return;
            }
        }
    }

    else
    {

        $photo=$user->getPhoto();
        if( file_exists( $uploadFolder."/".$photo)){
            $ext  = strtolower( pathinfo($photo,PATHINFO_EXTENSION) );
            $filetoremove= My_Utils::buildImageFile( $user->getId() ) . "." . $ext;
            unlink($uploadFolder."/".$filetoremove);
            $user->setPhoto("");
            try {
                $user->save();
            }
            catch (Exception $e) {
                Zend_Debug::dump($e->getMessage());
                return;

            }
        }
    }

}

?>