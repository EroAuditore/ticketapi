<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\StreamInterface as StreamInterface;


$app->post('/api/file', function(Request $request, Response $response){
    /*echo "Api clientes";*/
    $value = json_decode($request->getBody());
    /*$fileId = $value->fileId;*/
    $fileId = 12;
    $sql = "select *
    from archivos where  _id =:_id";
    try{

        $db = new db();
        $db = $db->connectDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":_id", $fileId );

        $stmt->execute();
        $stmt->bindColumn(5, $mime); //fname
        $stmt->bindColumn(3, $data, PDO::PARAM_LOB);
        $stmt->bindColumn(4, $fname);

        
        $stmt->fetch(PDO::FETCH_BOUND);
        /*$stream = new \Slim\Http\Stream($data);*/
        $stream = new \Slim\Http\Stream($data);
        /*$response->headers->set('Content-disposition:', 'attachment; filename=' . $fname);*/
        $response->getBody()->write($data);

        return $response->withStatus(200)
                        ->withHeader('Content-Type', 'application/force-download')
                        ->withHeader('Content-Type', 'application/octet-stream')
                        ->withHeader('Content-Type', 'application/download')
                        ->withHeader('Content-Description', 'File Transfer')
                        ->withHeader('Content-Transfer-Encoding', 'binary')
                        ->withHeader('Content-Type', $mime)
                        ->withHeader('Pragma', "public")
                        ->withHeader('Content-disposition:', 'attachment; filename=' . $fname)
                        ->withHeader('Content-Transfer-Encoding', 'binary');
                        /*->withHeader('Content-Length', filesize($data))*/
                        /*->withBody($data);*/
        
        /*
        $response->headers->set('Content-Type', $mime);
        $response->headers->set('Pragma', "public");
        $response->headers->set('Content-disposition:', 'attachment; filename=' . $fname);
        $response->headers->set('Content-Transfer-Encoding', 'binary');
     

        /*$stmt->fetch(PDO::FETCH_BOUND);
       
        if($stmt->rowCount()>0 ){
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);


        }*/

    }catch (PDOException $e)
    {
        echo '{"error": { "text":'.$e->getMessage().'}';

    };


});

$app->post('/api/files/movimiento', function(Request $request, Response $response){
    /*echo "Api clientes";*/
    $value = json_decode($request->getBody());
   
    $sql = "select _id, fileName from archivos where idMovimiento = :_id";
    try{

        $db = new db();
        $db = $db->connectDB();
        $stmt = $db->prepare($sql);
      
        
        $stmt->bindParam(":_id", $value->_id );
      
        $stmt->execute();
        
        if($stmt->rowCount()>0 ){
            $files = $stmt->fetchAll(PDO::FETCH_OBJ);
           
        }else{
            $files = [];
           
        }

        echo json_encode($files);
        $resultado = null;
        $db = null;

    }catch (PDOException $e)
    {
        echo '{"error": { "text":'.$e->getMessage().'}';

    };

});

$app->post('/api/files/solicitud', function(Request $request, Response $response){
    /*echo "Api clientes";*/
    $value = json_decode($request->getBody());
   
    $sql = "select _id, fileName from archivos where solicitudId = :_id";
    try{

        $db = new db();
        $db = $db->connectDB();
        $stmt = $db->prepare($sql);
      
        
        $stmt->bindParam(":_id", $value->_id );
      
        $stmt->execute();
        
        if($stmt->rowCount()>0 ){
            $files = $stmt->fetchAll(PDO::FETCH_OBJ);
           
        }else{
            $files = [];
           
        }

        echo json_encode($files);
        $resultado = null;
        $db = null;

    }catch (PDOException $e)
    {
        echo '{"error": { "text":'.$e->getMessage().'}';

    };

});