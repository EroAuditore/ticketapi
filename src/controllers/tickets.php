<?php


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


//Get todos los tickets;
$app->get('/api/tickets', function(Request $request, Response $response){
    /*echo "Api clientes";*/

    $sql = "select * from tickets";
    try{

        $db = new db();
        $db = $db->connectDB();
        $resultado = $db->query($sql);
        if($resultado->rowCount()>0 ){
            $data = $resultado->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($data);
        }else{
            echo json_encode("No existen tickets en la BD.");
        }
        $resultado = null;
        $db = null;

    }catch (PDOException $e)
    {
        echo '{"error": { "text":'.$e->getMessage().'}';

    };

});



//Get todos los tickets;
$app->post('/api/tickets/nuevo', function(Request $request, Response $response){
    /*echo "Api clientes";*/
    $value = json_decode($request->getBody());

    $sql = "insert into tickets ( rfc,
                    nombre,
                    monto,
                    metodoPago,
                    condicionesPago,
                    Empresa,
                    Comprobante) values(
                    :rfc,
                    :nombre,
                    :monto,
                    :metodoPago,
                    :condicionesPago,
                    :Empresa,
                    :Comprobante
                    )";
    try{

        $db = new db();
        $db = $db->connectDB();
        /*$resultado = $db->query($sql);*/
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":rfc", $value->rfc);
        $stmt->bindParam(":nombre", $value->nombre);
        $stmt->bindParam(":monto", $value->monto);
        $stmt->bindParam(":metodoPago", $value->metodoPago);
        $stmt->bindParam(":condicionesPago", $value->condicionesPago);
        $stmt->bindParam(":Empresa", $value->empresaFacturar);
        $stmt->bindParam(":Comprobante", $value->tipoComprobante);
        $stmt->execute();

        $stmt = null;
        $db = null;

    }catch (PDOException $e)
    {
        echo '{"error": { "text":'.$e->getMessage().'}';

    };
});



//Get todos los tickets;
$app->get('/api/tickets/{id}', function(Request $request, Response $response){
    /*echo "Api clientes";*/
    $id = $request->getAttribute('id');
   
    $sql = "select * from tickets where id=$id";
    try{

        $db = new db();
        $db = $db->connectDB();
        $resultado = $db->query($sql);
        if($resultado->rowCount()>0 ){
            $data = $resultado->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($data);
        }else{
            echo json_encode("No existen tickets en la BD.");
        }
        $resultado = null;
        $db = null;

    }catch (PDOException $e)
    {
        echo '{"error": { "text":'.$e->getMessage().'}';

    };

});


