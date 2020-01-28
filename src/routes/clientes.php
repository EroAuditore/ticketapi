<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;




//Get Obetenr cliente por ID;
$app->get('/api/clientes/{id}', function(Request $request, Response $response){
    /*echo "Api clientes";*/
    $id_cliente = $request->getAttribute('id');
    $sql = "select * from usuarios where id=$id_cliente";
    try{

        $db = new db();
        $db = $db->connectDB();
        $resultado = $db->query($sql);
        if($resultado->rowCount()>0 ){
            $clientes = $resultado->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($clientes);
        }else{
            echo json_encode("No existen el cliente con ese id.");
        }
        $resultado = null;
        $db = null;

    }catch (PDOException $e)
    {
        echo '{"error": { "text":'.$e->getMessage().'}';

    };   

});


//POST Crear nuevo cliente;
$app->get('/api/clientes/nuevo', function(Request $request, Response $response){
    /*echo "Api clientes";*/
    $nombre = $request->getQueryParam('nombre');
    $apellido = $request->getQueryParam('apellido');
    $telefono = $request->getQueryParam('telefono');
    $email = $request->getQueryParam('email');

    $sql = "INSERT INTO CLIENTES (NOMBRE, APELLIDO, TELEFONO, EMAIL) VALUES (:NOMBRE, :APELLIDO, :TELEFONO, :EMAIL)";

    try{

        $db = new db();
        $db = $db->connectDB();

        $resultado = $db->prepare($sql);
        $resultado->bindParam(":NOMBRE", $nombre);
        $resultado->bindParam(":APELLIDO", $nombre);
        $resultado->bindParam(":TELEFONO", $nombre);
        $resultado->bindParam(":EMAIL", $nombre);
        
        $resultado->execute();
        echo json_encode("Cliente generado");
       
        $resultado = null;
        $db = null;

    }catch (PDOException $e)
    {
        echo '{"error": { "text":'.$e->getMessage().'}';

    };   

});
