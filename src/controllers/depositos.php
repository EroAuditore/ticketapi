<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

if (!function_exists('array_key_first')) {
    function array_key_first(array $arr) {
        foreach($arr as $key => $unused) {
            return $key;
        }
        return NULL;
    }
}

$container = $app->getContainer();
$container['upload_directory'] = __DIR__ . '/uploads';

//Get todos los tickets;
$app->get('/api/depositos', function(Request $request, Response $response){
    /*echo "Api clientes";*/

    $sql = "select * from depositos";
    try{

        $db = new db();
        $db = $db->connectDB();
        $resultado = $db->query($sql);
        if($resultado->rowCount()>0 ){
            $data = $resultado->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($data);
        }else{
            echo json_encode("No existen depositos en la BD.");
        }
        $resultado = null;
        $db = null;

    }catch (PDOException $e)
    {
        echo '{"error": { "text":'.$e->getMessage().'}';

    };

});



//Get todos los tickets;
$app->post('/api/depositos/valida', function(Request $request, Response $response){
    $directory = $this->get('upload_directory');
    $uploadedFiles = $request->getUploadedFiles();
    /*$id = $request->getAttribute('id');*/
    $id = 3;
    $statusValid = 1;    

    $sql = "update depositos set validado=:validado and evidencia=:evidencia where _id=:_id";

    try{


        $uploadedFiles = $request->getUploadedFiles();
        // handle single input with single file upload
        $uploadedFile = file_get_contents($uploadedFiles["IMG_0879_jpg"]);
        
        $db = new db();
        $db = $db->connectDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":evidencia", $uploadedFile, PDO::PARAM_LOB);
        $stmt->bindParam(":validado", $statusValid);
        $stmt->bindParam(":_id", $id);
        
        $stmt->execute();
        /*echo json_encode("validado.");*/

        $db = null;

    }catch (PDOException $e)
    {
        echo '{"error": { "text":'.$e->getMessage().'}';

    };

});



/**
 * Moves the uploaded file to the upload directory and assigns it a unique name
 * to avoid overwriting an existing uploaded file.
 *
 * @param string $directory directory to which the file is moved
 * @param UploadedFile $uploadedFile file uploaded file to move
 * @return string filename of moved file
 */
function moveUploadedFile($directory, UploadedFile $uploadedFile)
{
    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
    $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
    $filename = sprintf('%s.%0.8s', $basename, $extension);

    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    return $filename;
}