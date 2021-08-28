<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->get('/api/movimientos/pendientes/facturar/all', function(Request $request, Response $response){
   

    $sql = "select * from v_movimiento
                where solicitudId IS NULL";

    try{
    $db = new db();
    $db = $db->connectDB();
    $stmt = $db->prepare($sql);
    
    $stmt->execute();
   
    if($stmt->rowCount()>0 ){
        $data = $stmt->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($data);
    }else{
        echo json_encode (json_decode ("[]"));
       
    }

   
    } catch (PDOException $e)
    {
        echo '{"error": { "text":'.$e->getMessage().'}';

    };


});
