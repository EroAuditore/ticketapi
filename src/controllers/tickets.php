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
   /* $retornos = json_decode( $value->retornos);*/
    
    $sql = "insert into tickets ( rfc,
                    nombre,
                    montoFacturar,
                    metodoPago,
                    condicionesPago,
                    empresaFacturadora,
                    Comprobante,
                    agente,
                    cliente,
                    formaPago,
                    CFDI,
                    Correo,
                    ComisionAgente,
                    ComisionOficina
                    
                    ) values(
                    :rfc,
                    :nombre,
                    :montoFacturar,
                    :metodoPago,
                    :condicionesPago,
                    :empresaFacturadora,
                    :Comprobante,
                    :agente,
                    :cliente,
                    :formaPago,
                    :CFDI,
                    :Correo,
                    :ComisionAgente,
                    :ComisionOficina
                    )";
    try{

        $db = new db();
        $db = $db->connectDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":rfc", $value->rfc);
        $stmt->bindParam(":nombre", $value->nombreSolicitante);
        $stmt->bindParam(":montoFacturar", $value->montoFacturar);
        $stmt->bindParam(":metodoPago", $value->metodoPago);
        $stmt->bindParam(":condicionesPago", $value->condicionPago);
        $stmt->bindParam(":empresaFacturadora", $value->empresaFacturadora);
        $stmt->bindParam(":Comprobante", $value->tipoComprobante);
        $stmt->bindParam(":agente", $value->agente);
        $stmt->bindParam(":cliente", $value->cliente);
        $stmt->bindParam(":formaPago", $value->formaPago);
        $stmt->bindParam(":CFDI", $value->usoCFDI);
        $stmt->bindParam(":Correo", $value->correo);
        $stmt->bindParam(":ComisionAgente", $value->comisionAgente);
        $stmt->bindParam(":ComisionOficina", $value->comisionOficina);
        


        $stmt->execute();

        //obtenemos el id del ticket
        $id_ticket = $db->lastInsertId();
        
       
        //insertamos los retornos ligados al ticket
        
        $sqlqry = "insert into retornos ( Nombre,
        Cuenta_clabe,
        Banco,
        Monto,
        id_ticket) values(
        :Nombre,
        :Cuenta_clabe,
        :Banco,
        :Monto,
        :id_ticket
        )";
        
        $stmt = $db->prepare($sqlqry);
        foreach ($value->retornos as $retorno) {
        $stmt->bindParam(":Nombre", $retorno->nombre);
        $stmt->bindParam(":Cuenta_clabe", $retorno->cuenta);
        $stmt->bindParam(":Banco", $retorno->banco);
        $stmt->bindParam(":Monto", $retorno->monto);
        $stmt->bindParam(":id_ticket", $id_ticket);
        $stmt->execute();

        }
        

        //insertamos los depositos ligados al ticket
        
        $sqlqry = "insert into depositos ( monto,
        banco,
        fecha,
        id_ticket
        ) values(
        :monto,
        :banco,
        :fecha,
        :id_ticket
        )";
        
        $stmt = $db->prepare($sqlqry);
        foreach ($value->depositos as $deposito) {
        $stmt->bindParam(":monto", $deposito->montoDeposito);
        $stmt->bindParam(":banco", $deposito->bancoDeposito);
        $stmt->bindParam(":fecha", $deposito->fechaDeposito);
        $stmt->bindParam(":id_ticket", $id_ticket);
        $stmt->execute();
        }

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


//Get todos los tickets;
$app->get('/api/karen', function(Request $request, Response $response){
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