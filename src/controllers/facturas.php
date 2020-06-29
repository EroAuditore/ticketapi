<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//Get todos los facturas x moviminto;
$app->get('/api/facturas/movimientos', function(Request $request, Response $response){
    /*echo "Api clientes";*/

    $sql = "SELECT 
                    t._id, 
                    t.Nombre, 
                    t.Agente, 
                    t.cliente, 
                    t.Solicitante, 
                    u.Nombre as  nAtencion, 
                    t.Estatus_Facturacion,
                    u.id_usuario

                    from tickets t left join 
                    usuarios u on t.Facturas_user_id =  u.id_usuario";
    try{

        $db = new db();
        $db = $db->connectDB();
        $resultado = $db->query($sql);
        if($resultado->rowCount()>0 ){
            $clientes = $resultado->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($clientes);
        }else{
            echo json_encode("No existen clientes en la BD.");
        }
        $resultado = null;
        $db = null;

    }catch (PDOException $e)
    {
        echo '{"error": { "text":'.$e->getMessage().'}';

    };
});

//Get todos los facturas x moviminto;
$app->post('/api/movimiento/facturas/tomar', function(Request $request, Response $response){
    /*echo "Api clientes";*/

    $value = json_decode($request->getBody());

    $sql = "SELECT *
                    FROM facturas 
                    WHERE id_ticket =  :id_ticket";
    try{

        asignaUsuarioFacturas( $value->ID_usuario,  $value->_id);

        $db = new db();
        $db = $db->connectDB();
        $stmt = $db->prepare($sql);
      
        
        $stmt->bindParam(":id_ticket", $value->_id );
      
        $stmt->execute();
       
        if($stmt->rowCount()>0 ){
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
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


function asignaUsuarioFacturas ($usuarioID, $movimientoID){

    $sql = "UPDATE tickets 
                        SET
                    Facturas_user_id = :Facturas_user_id,
                    Estatus_Facturacion = :Estatus_Facturacion
                    WHERE _id = :_id";

try{

    $db = new db();
    $db = $db->connectDB();
    $stmt = $db->prepare($sql);
/***** Estatus facturas ****
1	Pendiente
2	Atendiendo
3	Finalizado
4	Cerrado
*/
    $Estatus_Facturacion = 2;
    $stmt->bindParam(":_id", $movimientoID);
    $stmt->bindParam(":Facturas_user_id", $usuarioID);
    $stmt->bindParam(":Estatus_Facturacion", $Estatus_Facturacion);
    $stmt->execute();

    }catch (PDOException $e)
    {
        echo '{"error": { "text":'.$e->getMessage().'}';

    };
    
}



$app->post('/api/facturas/editar', function(Request $request, Response $response){

    $value = json_decode($request->getBody());
   
   try{

    $sqlqry = "UPDATE facturas
    SET
    _id = :_id,
    RFC= :RFC,
    empresaFacturadora= :empresaFacturadora,
    Cliente= :Cliente,
    montoTotal= :montoTotal,
    tipoComprobante= :tipoComprobante,
    condicionPago= :condicionPago,
    usoCFDI= :usoCFDI,
    metodoPago= :metodoPago,
    formaPago= :formaPago,
    direccionCliente= :direccionCliente,
    generada= :generada ,
    email= :email
    WHERE _id= :_id";
        
        $db = new db();
        $db = $db->connectDB();
        $stmt = $db->prepare($sqlqry);
        $stmt->bindParam(":_id", $value->_id);
        $stmt->bindParam(":RFC", $value->RFC);
        $stmt->bindParam(":empresaFacturadora", $value->empresaFacturadora);
        $stmt->bindParam(":Cliente", $value->Cliente);
        $stmt->bindParam(":montoTotal", $value->montoTotal);
        $stmt->bindParam(":tipoComprobante", $value->tipoComprobante);
        $stmt->bindParam(":condicionPago", $value->condicionPago);
        $stmt->bindParam(":usoCFDI", $value->usoCFDI);
        $stmt->bindParam(":metodoPago", $value->metodoPago);
        $stmt->bindParam(":formaPago", $value->formaPago);
        $stmt->bindParam(":direccionCliente", $value->direccionCliente);
        $stmt->bindParam(":email", $value->email);
        $stmt->bindParam(":generada", intval($value->generada));
        $stmt->execute();

        }catch (PDOException $e)
        {
            echo '{"error": { "text":'.$e->getMessage().'}';

        };
});