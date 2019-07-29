<?php
require_once 'UploadedFile.php';

if(isset($_POST["file"])){    
    $uploadedFile = new UploadedFile($_FILES["lif_file"]);
    if( $uploadedFile->getError() === 0 ){
        if( $uploadedFile->getExtension() == '.lif'){
            if( $uploadedFile->getSize() <= (2 * 1024 * 1024) ){
                if($uploadedFile->saveFile()){
                    header('Location: http://' . $_SERVER['HTTP_HOST'] . "/game_of_life/?file=" . $uploadedFile->getName());
                }else{
                    header('Location: http://' . $_SERVER['HTTP_HOST'] . "/game_of_life/?error=4");
                }
            }else{
                header('Location: http://' . $_SERVER['HTTP_HOST'] . "/game_of_life/?error=3");
            }
        }else{
            header('Location: http://' . $_SERVER['HTTP_HOST'] . "/game_of_life/?error=2");
        }
    }else{
        header('Location: http://' . $_SERVER['HTTP_HOST'] . "/game_of_life/?error=1");
    }
    
}else{   
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

?>