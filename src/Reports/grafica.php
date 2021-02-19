<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//Get todos los clientes cada uno con su suma total de comisiones;
$app->get('/api/movimiento/comision/cliente', function(Request $request, Response $response){

    $sql = "select Cliente, SUM(totalComisiones) as totalComision from movimiento group by cliente";
    try{

        $db = new db();
        $db = $db->connectDB();
        $resultado = $db->query($sql);
        if($resultado->rowCount()>0 ){
            $data = $resultado->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($data);
        }else{
            echo json_encode("No existen retornos en la BD.");
        }
        $resultado = null;
        $db = null;

    }catch (PDOException $e)
    {
        echo '{"error": { "text":'.$e->getMessage().'}';

    };

});
//Get todos los clientes con su total de movimientos;
$app->get('/api/movimiento/cliente', function(Request $request, Response $response){

    $sql = "select Cliente, count(totalComisiones) as totalMovimientos from movimiento group by cliente";
    try{

        $db = new db();
        $db = $db->connectDB();
        $resultado = $db->query($sql);
        if($resultado->rowCount()>0 ){
            $data = $resultado->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($data);
        }else{
            echo json_encode("No existen retornos en la BD.");
        }
        $resultado = null;
        $db = null;

    }catch (PDOException $e)
    {
        echo '{"error": { "text":'.$e->getMessage().'}';

    };

});

//Get todos los movimientos por cliente agrupado quincenalmente;
$app->post('/api/movimineto/quincenal/cliente', function(Request $request, Response $response){
    
    $value = json_decode($request->getBody());
    $sql = "select Cliente, count(totalComisiones), ceil(week(fecha)/2) as quincena from movimiento where idCliente = :idcliente group by quincena order by quincena ";
    try{

        $db = new db();
        $db = $db->connectDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":idcliente", $value->idCliente );

        $stmt->execute();
       
        if($stmt->rowCount()>0 ){
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($data);
        }else{
            echo json_encode("No existen solicitudes en la BD.");
        }
        $resultado = null;
        $db = null;

    }catch (PDOException $e)
    {
        echo '{"error": { "text":'.$e->getMessage().'}';

    };

});

//Get todos los movimientos por cliente agrupado mensualmente;
$app->post('/api/movimineto/mensual/cliente', function(Request $request, Response $response){
    
    $value = json_decode($request->getBody());
    $sql = "select Cliente, count(totalComisiones), month(fecha) as mes from movimiento where idCliente = :idcliente group by mes order by mes ";
    try{

        $db = new db();
        $db = $db->connectDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":idcliente", $value->idCliente );

        $stmt->execute();
       
        if($stmt->rowCount()>0 ){
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($data);
        }else{
            echo json_encode("No existen solicitudes en la BD.");
        }
        $resultado = null;
        $db = null;

    }catch (PDOException $e)
    {
        echo '{"error": { "text":'.$e->getMessage().'}';

    };

});

//Get todos los movimientos por cliente agrupado año;
$app->post('/api/movimineto/anual/cliente', function(Request $request, Response $response){
    
    $value = json_decode($request->getBody());
    $sql = "select Cliente, count(totalComisiones), year(fecha) as anual from movimiento where idCliente = :idcliente group by anual order by anual ";
    try{

        $db = new db();
        $db = $db->connectDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":idcliente", $value->idCliente );

        $stmt->execute();
       
        if($stmt->rowCount()>0 ){
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($data);
        }else{
            echo json_encode("No existen solicitudes en la BD.");
        }
        $resultado = null;
        $db = null;

    }catch (PDOException $e)
    {
        echo '{"error": { "text":'.$e->getMessage().'}';

    };

});