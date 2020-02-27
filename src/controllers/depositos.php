<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;



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