<?php 


$srcName = $argv[1];

$dstName = "output.mytar";

function explorer($chemin, &$fileArray){

    $lstat    = lstat($chemin);

    $mtime    = date('d/m/Y H:i:s', $lstat['mtime']); 
    
    $filetype = filetype($chemin); 
     
    // Si $chemin est un dossier => on appelle la fonction explorer() pour chaque élément (fichier ou dossier) du dossier$chemin
    if( is_dir($chemin) ){

        $me = opendir($chemin);

        while( $child = readdir($me) ){

            if( $child != '.' && $child != '..' ){

                explorer( $chemin.DIRECTORY_SEPARATOR.$child, $fileArray );

            }
        }
    } else {
        array_push($fileArray, dirname(__FILE__)."/" .$chemin);
    }
}


function compress(&$fileArray, $dstName){
    
    //echo "fichier temporaire $dstName <br>";
    $zip = gzopen($dstName, "w9");
    for ($i=0; $i < count($fileArray); $i++) { 
        //echo "open file $fileArray[$i] \n";     
        $data = file_get_contents($fileArray[$i]);
        gzwrite($zip, $data);
    }    
    gzclose($zip);  

}

    $fileArray = [];
    // Récupère l'ensemble des fichiers passés en paramètre
    // Si le fichier est un répertoire, on appelle la fonction récursive explorer
    // for ($i=1; $i < count($argv); $i++) { 
    //     if (filetype($argv[$i]) === "dir"){
    //         explorer($argv[$i], $fileArray);
    //     } else {
    //         array_push($fileArray, dirname(__FILE__)."/" .$argv[$i]);
    //     }
    // }
    
    // echo "final $fileArray \n";
    // var_dump($fileArray);
    // compress($fileArray, $dstName);

    //var_dump($_POST);
    $archiveName = $_POST["archive"];
    //echo "archiveName $archiveName <br>";
    //var_dump($_FILES);
    $myFile = $_FILES["myFile"]["tmp_name"];
    $fileName = $_FILES["myFile"]["name"];
    //echo "file $myFile , $fileName <br>";
   
    $temp = tmpfile();
    $tempPath = stream_get_meta_data($temp)['uri'];
    array_push($fileArray, $myFile);
    compress($fileArray, $tempPath);

    header('Content-Type: application/x-binary');         # its a text file
    header("Content-disposition: attachment;filename=$archiveName");
    readfile($tempPath);

?>



    
    
    
    
    
    