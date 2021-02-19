<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//Get todos los facturas x moviminto;
$app->get('/api/facturas/movimientos', function(Request $request, Response $response){
    /*echo "Api clientes";*/

    $sql = "SELECT 
                    *

                    from v_factura";
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
        echo '{["error": { "text":'.$e->getMessage().']}';

    };
});

//Get todos los facturas x moviminto;
$app->post('/api/movimiento/facturas/tomar', function(Request $request, Response $response){
    $value = json_decode($request->getBody());

    $sql = "SELECT *
                    FROM facturas 
                    WHERE solicitudId =  :solicitudId";
    try{

        /*asignaUsuarioFacturas( $value->_id,  $value->_id);*/

        $db = new db();
        $db = $db->connectDB();
        $stmt = $db->prepare($sql);
      
        
        $stmt->bindParam(":solicitudId", $value->_id );
      
        $stmt->execute();
       
        if($stmt->rowCount()>0 ){
            $facturas = $stmt->fetchAll(PDO::FETCH_OBJ);
        }else{
            $facturas = [];           
        }

        $sql = "SELECT *
        FROM v_solicitud 
        WHERE _id =  :solicitudId";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":solicitudId", $value->_id );
        $stmt->execute();

        if($stmt->rowCount()>0 ){
            $solicitud = $stmt->fetchAll(PDO::FETCH_OBJ);


        }else{
            $solicitud = [];
           
        }
        $data = array(
            "solicitud" => $solicitud[0],
            "facturas" => $facturas,
             
        );

        
        echo json_encode($data);
        $resultado = null;
        $db = null;

    }catch (PDOException $e)
    {
        echo '{"error": { "text":'.$e->getMessage().'}';

    };
});


function asignaUsuarioFacturas ($usuarioID, $facturaId){

    $sql = "UPDATE facturas 
                        SET
                    userId = :userId,
                    estatus = :Estatus_Facturacion
                    WHERE _id = :_id";

try{

    $db = new db();
    $db = $db->connectDB();
    $stmt = $db->prepare($sql);
/***** Estatus facturas ****
0	Pendiente
1	Atendiendo
2	generada
3	cerrado
4   cancelada
*/
    $Estatus_Facturacion = 1;
    $stmt->bindParam(":_id", $facturaId);
    $stmt->bindParam(":userId", $usuarioID);
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



//Get todas las solicitudes de facturas;
$app->get('/api/solicitud/facturas', function(Request $request, Response $response){

    $sql = "select s._id, s.Agente, s.Cliente, u.Nombre as Creador,u2.Nombre as Atendedor, s.Estatus_solicitud
    s.Total_Solicitud
    from solicitud_factura s, usuarios u, usuarios u2
    where s.userId_created=u._id
    AND  s.userId_attend=u2._id";
    try{

        $db = new db();
        $db = $db->connectDB();
        $resultado = $db->query($sql);
        if($resultado->rowCount()>0){    
            $data = $resultado->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($data);
        }else{
            echo json_encode([]);
        }
        $resultado = null;
        $db = null;

    }catch (PDOException $e)
    {
        echo '{"error": { "text":'.$e->getMessage().'}';

    };

});


//Guardar las solicitudes de facturas y las N facturas;
$app->post('/api/solicitud/guardar', function(Request $request, Response $response){
    $value = json_decode($request->getBody());
    //Obtenemos el total de solicitudes
    $TotalSolicitudes=0;
    $Estatus_solicitud=1;
    foreach ($value->facturas as $facturas) {
        $TotalSolicitudes=$TotalSolicitudes+1;
        }

    $solicitud = $value->solicitud;
    //hacemos el insert a la base de datos
    $sql = "insert into solicitud_factura ( idAgente,
                    idCliente,
                    userId_created,
                    Total_Solicitud,
                    Estatus_solicitud
                    ) values(
                    :Agente,
                    :Cliente,
                    :userId_created,
                    :Total_Solicitud,
                    :Estatus_solicitud
                    )";
    try{

        $db = new db();
        $db = $db->connectDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":Agente",$solicitud->Agente);
        $stmt->bindParam(":Cliente",$solicitud->Cliente);
        $stmt->bindParam(":userId_created",$solicitud->userId);
        $stmt->bindParam(":Total_Solicitud",$solicitud->totalSolicitud);
        $stmt->bindParam(":Estatus_solicitud",$Estatus_solicitud);
        $stmt->execute();

        //obtenemos el id de la solicitud de factura que creamos
        $id_solicitud = $db->lastInsertId();

        //insert las facturas ligadas a la solicitud de facturas
        $sqlqry = "insert into facturas ( RFC,
        empresaFacturadora,
        Cliente,
        montoTotal,
        Folio,
        tipoComprobante,
        condicionPago,
        usoCFDI,
        metodoPago,
        formaPago,
        direccionCliente,
        estatus,
        generada,
        email,
        solicitudId,
        conceptoFactura
        ) values(
        :RFC,
        :empresaFacturadora,
        :Cliente,
        :montoTotal,
        :Folio,
        :tipoComprobante,
        :condicionPago,
        :usoCFDI,
        :metodoPago,
        :formaPago,
        :direccionCliente,
        :estatus,
        :generada,
        :email,
        :solicitudId,
        :conceptoFactura 
        )";
        
        $stmt = $db->prepare($sqlqry);
        foreach ($value->facturas as $facturas) {
        $stmt->bindParam(":RFC", $facturas->RFC);
        $stmt->bindParam(":empresaFacturadora", $facturas->empresaFacturadora);
        $stmt->bindParam(":Cliente", $facturas->Cliente);
        $stmt->bindParam(":montoTotal", $facturas->montoTotal);
        $stmt->bindParam(":Folio", $facturas->Folio);
        $stmt->bindParam(":tipoComprobante", $facturas->tipoComprobante);
        $stmt->bindParam(":condicionPago", $facturas->condicionPago);
        $stmt->bindParam(":usoCFDI", $facturas->usoCFDI);
        $stmt->bindParam(":metodoPago", $facturas->metodoPago);
        $stmt->bindParam(":formaPago", $facturas->formaPago);
        $stmt->bindParam(":direccionCliente", $facturas->direccionCliente);
        $stmt->bindParam(":estatus", $facturas->estatus);
        $stmt->bindParam(":generada", intval($facturas->generada));
        $stmt->bindParam(":email", $facturas->email);
        $stmt->bindParam(":solicitudId", $id_solicitud);
        $stmt->bindParam(":conceptoFactura", $facturas->conceptoFactura);
        $stmt->execute();
        }


        if(!empty($_FILES)){
            /** si hay archivo lo insertamos en la base de datos */
            $inputFile = $files['file']['tmp_name'];
            $inputFileName = $files['file']['name'];
            $inputFileType = $files['file']['type'];
            $dataFile = file_get_contents($inputFile);

            $sqlqry = "insert into archivos ( 
                file,
                fileName,
                fileType,
                idMovimiento
        
        ) values(
            :file,
            :fileName,
            :fileType,
            :idSolicitud
        )";
        
        $stmt = $db->prepare($sqlqry);
        
        $stmt->bindParam(":file", $dataFile);
        $stmt->bindParam(":fileName", $inputFileName);
        $stmt->bindParam(":fileType", $inputFileType);
        $stmt->bindParam(":idSolicitud", $id_solicitud);
        $stmt->execute();
     

        }

    }catch (PDOException $e)
    {
        echo '{"error": { "text":'.$e->getMessage().'}';

    };
});

$app->get('/api/facturas/solicitudes', function(Request $request, Response $response){

    $sql = "SELECT * from v_solicitud ";
    try{

        $db = new db();
        $db = $db->connectDB();
        $resultado = $db->query($sql);
        if($resultado->rowCount()>0){    
            $data = $resultado->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($data);
        }else{
            echo json_encode([]);
        }
        $resultado = null;
        $db = null;

    }catch (PDOException $e)
    {
        echo '{"error": { "text":'.$e->getMessage().'}';

    };

});

$app->post('/api/facturas/filtrar', function(Request $request, Response $response){
    
    $value = json_decode($request->getBody());
    $sql = "select * from v_solicitud where idCliente = :_id AND id_movimiento IS NULL";
    try{

        $db = new db();
        $db = $db->connectDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":_id", $value->_id );

        $stmt->execute();
       
        if($stmt->rowCount()>0 ){
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($data);
        }else{
            echo json_encode([]);
        }
        $resultado = null;
        $db = null;

    }catch (PDOException $e)
    {
        echo '{"error": { "text":'.$e->getMessage().'}';

    };
});


//leer xml, crear la factura y regresar la informacion en un json;
$app->post('/api/factura/xml', function(Request $request, Response $response){
    /*echo "Api clientes";*/
    $value = json_decode($request->getBody());
    $directory = $this->get('upload_directory');
    $uploadedFiles = $request->getUploadedFiles();
    $files = $_FILES;
    $inputFile = $files['file']['tmp_name'];
    $inputFileName = $files['file']['name'];
    $inputFileType = $files['file']['type'];
    $dataFile = file_get_contents($inputFile); //el archivo xml es leido como string
    
    $value2 = json_decode($request->getParam('solicitudObj'));
    //coneccion a la base de datos
    $db = new db();
    $db = $db->connectDB();

    $id_solicitud=1; //variable que no poseo
    $facturaId= $value2->_id;

    $xml = simplexml_load_string($dataFile); //convertimos el string a xml
    $ns = $xml->getNamespaces(true);
    $xml->registerXPathNamespace('c', $ns['cfdi']);
    $xml->registerXPathNamespace('t', $ns['tfd']);

    try{

    foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor){
  
        $empresaFacturadora = $Emisor['Nombre'];
     }
     foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor') as $Receptor){
        $rfc = $Receptor['Rfc'];
        $cliente = $Receptor['Nombre'];
        $usoCFDI = $Receptor['UsoCFDI'];
     }
     foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante){
           $montoTotal = (double)$cfdiComprobante['Total'];
           $folio = $cfdiComprobante['Folio'];
           $tipoComprobante = $cfdiComprobante['TipoDeComprobante'];
           $metodoPago = $cfdiComprobante['MetodoPago'];
           $formaPago = $cfdiComprobante['FormaPago'];
           $subtotal = (double)$cfdiComprobante['SubTotal'];
     }
            
                
            //update
            $sqlqry = "UPDATE facturas SET
            empresaFacturadora = :empresaFacturadora,
            Cliente = :Cliente,
            montoTotal = :montoTotal,
            Folio = :Folio,
            tipoComprobante = :tipoComprobante,
            usoCFDI = :usoCFDI,
            metodoPago= :metodoPago,
            formaPago = :formaPago,
            userId = :userId,
            subtotal = :subtotal,
            userId = :userId,
            estatus = :estatus

            WHERE _id = :facturaId";
            $estatus = 2;
            $userId = $value2->userId;

            $stmt = $db->prepare($sqlqry);
            $stmt->bindParam(":RFC", $rfc);
            $stmt->bindParam(":empresaFacturadora",  $empresaFacturadora);
            $stmt->bindParam(":Cliente",  $cliente);
            $stmt->bindParam(":montoTotal",  $montoTotal);
            $stmt->bindParam(":Folio", $folio);
            $stmt->bindParam(":tipoComprobante", $tipoComprobante);
            $stmt->bindParam(":usoCFDI", $usoCFDI);
            $stmt->bindParam(":metodoPago",$metodoPago);
            $stmt->bindParam(":formaPago",  $formaPago);
            $stmt->bindParam(":facturaId", $facturaId);
            $stmt->bindParam(":subtotal",  $subtotal);
            $stmt->bindParam(":userId",  $userId);
            $stmt->bindParam(":estatus",  $estatus);
                        
            $stmt->execute(); 

            //Insertamos la factura la base:
            if(!empty($_FILES)){
                /** si hay archivo lo insertamos en la base de datos */
                $inputFile = $files['file']['tmp_name'];
                $inputFileName = $files['file']['name'];
                $inputFileType = $files['file']['type'];
                $dataFile = file_get_contents($inputFile);
    
                $sqlqry = "insert into archivos ( 
                    file,
                    fileName,
                    fileType,
                    facturaId
            
            ) values(
                :file,
                :fileName,
                :fileType,
                :facturaId
            )";
            
            $stmt = $db->prepare($sqlqry);
            
            $stmt->bindParam(":file", $dataFile);
            $stmt->bindParam(":fileName", $inputFileName);
            $stmt->bindParam(":fileType", $inputFileType);
            $stmt->bindParam(":facturaId", $facturaId);
            $stmt->execute();
            }


            $sql = "SELECT *
            FROM facturas 
            WHERE _id =  :_id";


            $db = new db();
            $db = $db->connectDB();
            $stmt = $db->prepare($sql);


            $stmt->bindParam(":_id", $facturaId );

            $stmt->execute();

            if($stmt->rowCount()>0 ){
                $factura = $stmt->fetchAll(PDO::FETCH_OBJ);
                echo json_encode($factura);
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


//subir un PDF

$app->post('/api/factura/pdf', function(Request $request, Response $response){

    $directory = $this->get('upload_directory');
    $uploadedFiles = $request->getUploadedFiles();
    $files = $_FILES;
    $inputFile = $files['file']['tmp_name'];
    $inputFileName = $files['file']['name'];
    $inputFileType = $files['file']['type'];

    $value = json_decode($request->getParam('solicitudObj'));

    try{

        if(!empty($_FILES)){
            /** si hay archivo lo insertamos en la base de datos */
            $inputFile = $files['file']['tmp_name'];
            $inputFileName = $files['file']['name'];
            $inputFileType = $files['file']['type'];
            $dataFile = file_get_contents($inputFile);
    
            $sqlqry = "insert into archivos ( 
                file,
                fileName,
                fileType,
                facturaId
        
        ) values(
            :file,
            :fileName,
            :fileType,
            :facturaId
        )";
        
        $db = new db();
        $db = $db->connectDB();
        $stmt = $db->prepare($sqlqry);
        
        $stmt->bindParam(":file", $dataFile);
        $stmt->bindParam(":fileName", $inputFileName);
        $stmt->bindParam(":fileType", $inputFileType);
        $stmt->bindParam(":facturaId", $value->_id);
        $stmt->execute();

        $sql = "SELECT *
            FROM facturas 
            WHERE _id =  :_id";


            $db = new db();
            $db = $db->connectDB();
            $stmt = $db->prepare($sql);


            $stmt->bindParam(":_id", $value->_id );

            $stmt->execute();

            if($stmt->rowCount()>0 ){
                $factura = $stmt->fetchAll(PDO::FETCH_OBJ);
                echo json_encode($factura);
            }else{
                echo json_encode (json_decode ("[]"));
            
            }
            $resultado = null;
            $db = null;
        }

    } catch (PDOException $e) {
        echo '{"error": { "text":'.$e->getMessage().'}';

    };
   
    
    


});


//GENERAR UNA FACTURA SOLA

$app->post('/api/factura/generar', function(Request $request, Response $response){
    $value = json_decode($request->getBody());

    $sql = "SELECT *
                    FROM v_factura 
                    WHERE _id =  :_id";
    try{
        $esatusFactura = (int)$value->estatus;
        /***** Estatus facturas ****
                0,1	Pendiente
                1	Atendiendo
                2	generada
                3	cerrado
                4   cancelada
                */

        if($esatusFactura == 0 )
            asignaUsuarioFacturas( $value->currentUserId,  $value->_id);

        $db = new db();
        $db = $db->connectDB();
        $stmt = $db->prepare($sql);
      
        
        $stmt->bindParam(":_id", $value->_id );
      
        $stmt->execute();
        
        if($stmt->rowCount()>0 ){
            $factura = $stmt->fetchAll(PDO::FETCH_OBJ);
           
        }else{
            $factura = [];
           
        }

        $sqlqry = "select _id, fileName, fileType from archivos where solicitudId = :_id";
        $stmt = $db->prepare($sqlqry);
        $stmt->bindParam(":_id", $value->solicitudId );
        $stmt->execute();
        if($stmt->rowCount()>0 ){
            $files = $stmt->fetchAll(PDO::FETCH_OBJ);
        }else{
            $files = [];
        }

        $data = array(
            "factura" => $factura[0],
            "files" => $files,
        );
        echo json_encode($data);
       
        $resultado = null;
        $db = null;

    }catch (PDOException $e)
    {
        echo '{"error": { "text":'.$e->getMessage().'}';

    };
});

// facturas de la solicitud de un cliente
$app->post('/api/facturas/solicitud', function(Request $request, Response $response){

    $value = json_decode($request->getBody());

    $sql = "SELECT *
                    FROM v_factura 
                    WHERE solicitudId =  :_id";
    try{
        $solicitudId = (int)$value->_id;        

        $db = new db();
        $db = $db->connectDB();
        $stmt = $db->prepare($sql);
      
        
        $stmt->bindParam(":_id", $value->_id );
      
        $stmt->execute();
        
        if($stmt->rowCount()>0 ){
            $facturas = $stmt->fetchAll(PDO::FETCH_OBJ);
           
        }else{
            $facturas = [];
           
        }

        echo json_encode($facturas);
            
    }catch (PDOException $e)
    {
        echo '{"error": { "text":'.$e->getMessage().'}';

    };

});



//Funcion para asignar un movimiento ID a una solicitud de facturacion

function asignaMovimientoIdSolicitud($solicitudId, $movimientoId){

        $sql = "UPDATE solicitud_factura
                SET id_movimiento = :id_movimiento 
                WHERE solicitudId =  :solicitudId";

    try {
        $db = new db();
        $db = $db->connectDB();
        $stmt = $db->prepare($sql);
      
        
        $stmt->bindParam(":id_movimiento", $id_movimiento );
        $stmt->bindParam(":solicitudId", $solicitudId );
      
        $stmt->execute();

    } catch (PDOException $e) {
        //throw $th;
    }

} 

