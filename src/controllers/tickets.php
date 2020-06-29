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
    
    $sql = "insert into tickets ( 
                    agente,
                    nombre,
                    cliente,
                    CantidadTotal,
                    ComisionAgente,
                    ComisionOficina
                    ) values(
                    :agente,
                    :nombre,
                    :cliente,
                    :CantidadTotal,
                    :ComisionAgente,
                    :ComisionOficina
                    )";
    try{

        $db = new db();
        $db = $db->connectDB();
        $stmt = $db->prepare($sql);
        /*foreach ($value->movimiento as $ticket) {*/
            $cantidadTotal = (double)$value->movimiento->cantidadTotal;
            $comisionAgente = (double)$value->movimiento->comisionAgente;
            $comisionOficina = (double)$value->movimiento->comisionOficina;
           
        $stmt->bindParam(":agente", $value->movimiento->agente);
        $stmt->bindParam(":nombre", $value->movimiento->nombre);
        $stmt->bindParam(":cliente", $value->movimiento->cliente);
        $stmt->bindParam(":CantidadTotal", $cantidadTotal);
        $stmt->bindParam(":ComisionAgente", $comisionAgente);
        $stmt->bindParam(":ComisionOficina",$comisionOficina);

        $stmt->execute();

        //obtenemos el id del ticket
        $id_ticket = $db->lastInsertId();
        
   /* }*/
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
        $stmt->bindParam(":Nombre", $retorno->nombreRetorno);
        $stmt->bindParam(":Cuenta_clabe", $retorno->cuentaRetorno);
        $stmt->bindParam(":Banco", $retorno->entidadRetorno);
        $stmt->bindParam(":Monto", $retorno->retornoMonto);
        $stmt->bindParam(":id_ticket", $id_ticket);
        $stmt->execute();

        }
        

            //insertamos los depositos ligados al ticket
            
            $sqlqry = "insert into facturas ( 
            RFC,
            Empresa,
            Cliente,
            Concepto,
            Monto,
            id_ticket
            ) values(
            :RFC,
            :Empresa,
            :Cliente,
            :Concepto,
            :Monto,
            :id_ticket
            )";
            
            $stmt = $db->prepare($sqlqry);
            foreach ($value->facturas as $factura) {
            $stmt->bindParam(":RFC", $factura->rfcFactura);
            $stmt->bindParam(":Empresa", $factura->empresaFactura);
            $stmt->bindParam(":Cliente", $factura->clienteFactura);
            $stmt->bindParam(":Concepto", $factura->conceptoFactura);
            $stmt->bindParam(":Monto", $factura->montoFactura);
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
        $stmt->bindParam(":monto", $deposito->depositoMonto);
        $stmt->bindParam(":banco", $deposito->bancoDeposito);
        $stmt->bindParam(":fecha", $deposito->fechaDepositoStr);
        $stmt->bindParam(":id_ticket", $id_ticket);
        $stmt->execute();
        }

    }catch (PDOException $e)
    {
        echo '{"error": { "text":'.$e->getMessage().'}';

    };
});



//Buscar ticket;
$app->post('/api/tickets/filtrar', function(Request $request, Response $response){
    
    $value = json_decode($request->getBody());
    $sql = "select * from tickets where nombre like :nombre";
    try{

        $db = new db();
        $db = $db->connectDB();
        $stmt = $db->prepare($sql);
        $param = "%" . $value->filterText . "%";
        
        $stmt->bindParam(":nombre", $param );
      
        $stmt->execute();
       
        if($stmt->rowCount()>0 ){
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
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


$app->get('/api/tickets/{id}}', function(Request $request, Response $response){
    
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
            echo json_encode (json_decode ("[]"));
        }
        $resultado = null;
        $db = null;

    }catch (PDOException $e)
    {
        echo '{"error": { "text":'.$e->getMessage().'}';

    };

});


<<<<<<< HEAD



=======
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
>>>>>>> ec06864a01e6ce1a98d751ca329f40c6da521778
