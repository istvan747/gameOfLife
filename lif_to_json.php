<?php

if(isset($_GET['file']) && !empty($_GET['file'])){
    try{
        $fileName = htmlspecialchars( stripslashes( trim( $_GET['file']) ) );
        $lines = file('uploads/' . $fileName . '.lif', FILE_SKIP_EMPTY_LINES);
        $coordsArray = array();
        
        
        
        if(strpos($lines[0], '#Life') === 0){
            $version =trim(substr($lines[0], strlen('#Life')));
            if($version === '1.05'){
                $groups = array();
                $groupsStart = array();
                
                for($i = 1; $i < count($lines); $i++){
                    if( strpos($lines[$i], "#P") === 0 ){
                        array_push($groupsStart, $i);
                    }
                }
                array_push($groupsStart, count($lines));
                
                for($i = 0; $i < count($groupsStart) - 1; $i++){
                    $start = $groupsStart[$i];
                    $end = $groupsStart[$i + 1];
                    $length = $end - $start;
                    array_push($groups, array_slice($lines, $start, $length));
                }
                
                for($i = 0; $i < count($groups); $i++ ){
                    $cords = array(); // $cords[0] -> x, $cords[1] -> y
                    for($j = 0; $j < count($groups[$i]); $j++){
                        if($j == 0){
                            $cords = explode(" ", trim(substr($groups[$i][$j], strlen('#P'))));
                            $cords[0];
                            $cords[1];
                        }else{
                            $rowArray = str_split($groups[$i][$j]);
                            for($k = 0; $k < count($rowArray); $k++ ){
                                if($rowArray[$k] == '*'){
                                    array_push($coordsArray, array(($cords[0] + $k), ($cords[1] + $j - 1)));
                                }
                            }
                        }
                    }
                }                
            }else{
                header('Location: http://' . $_SERVER['HTTP_HOST'] . "/game_of_life/?error=6");
            }  
        }else{
            header('Location: http://' . $_SERVER['HTTP_HOST'] . "/game_of_life/?error=5");
        }
        
        $json = json_encode($coordsArray);
        
        unlink('uploads/' . $fileName . '.lif');
        
        echo $json;
        
        
    }catch(Exception $e){
        //header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}else{
   header('Location: ' . $_SERVER['HTTP_REFERER']);
}


?>