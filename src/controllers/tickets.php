<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//Get todos los tickets;
$app->post('/api/tickets', function(Request $request, Response $response){
    /*echo "Api clientes";*/
    $value = json_decode($request->getBody());
    $userId = $value->userId;

    /*$sql = "select * from v_movimiento";*/
    $sql = "SELECT * FROM v_movimiento
	        WHERE  not (estatusRetorno = 'generado' and estatusDeposito = 'generado' and estatusComision='generado' )";
    try{

        $db = new db();
        $db = $db->connectDB();
        $resultado = $db->query($sql);
        if($resultado->rowCount()>0 ){
            $data = $resultado->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($data);
        }else{
            echo '{"error": { "text":"No hay movimientos"}';
        }
        $resultado = null;
        $db = null;

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



//Get todos los movimientos;
$app->post('/api/movimiento/nuevo', function(Request $request, Response $response){
  
    
    $directory = $this->get('upload_directory');
    $uploadedFiles = $request->getUploadedFiles();
    $files = $_FILES;
    
    
    $value = json_decode($request->getParam('movimientoObj'));
  
    
    $sql = "insert into movimiento ( 
                    idAgente,
                    idCliente,
                    estatusFactura,
                    estatusRetorno,
                    estatusDeposito,
                    cantidadTotal,
                    totalDepositos,
                    totalRetornos,
                    totalComisiones,
                    solicitudId,
                    comisionAgente,
                    comisionOficina
                    ) values(
                    :idAgente,
                    :idCliente,
                    :estatusFactura,
                    :estatusRetorno,
                    :estatusDeposito,
                    :cantidadTotal,
                    :totalDepositos,
                    :totalRetornos,
                    :totalComisiones,
                    :solicitudId,
                    :comisionAgente,
                    :comisionOficina
                    )";
    try{

        $db = new db();
        $db = $db->connectDB();
        $stmt = $db->prepare($sql);
            $cantidadTotal = (double)$value->movimiento->cantidadTotal;
            $comisionAgente = (double)$value->movimiento->comisionAgente;
            $comisionOficina = (double)$value->movimiento->comisionOficina;
         
       
        $movimiento = $value->movimiento;
        $stmt->bindValue(":idAgente", $movimiento->agente);
        $stmt->bindValue(":idCliente", $movimiento->cliente);
        $stmt->bindValue(":estatusFactura", $movimiento->estatusFactura);
        $stmt->bindValue(":estatusRetorno", $movimiento->estatusRetorno);
        $stmt->bindValue(":estatusDeposito", $movimiento->estatusDeposito);
        $stmt->bindValue(":cantidadTotal", $cantidadTotal);
        $stmt->bindValue(":totalDepositos", $movimiento->totalDepositos);
        $stmt->bindValue(":totalRetornos", $movimiento->totalRetornos);
        $stmt->bindValue(":totalComisiones", $movimiento->totalComisiones);
        $stmt->bindValue(":solicitudId", $movimiento->solicitudId);
        $stmt->bindValue(":comisionAgente", $comisionAgente);
        $stmt->bindValue(":comisionOficina", $comisionOficina);                
        $stmt->execute();
        //obtenemos el id del movimiento
        $idMovimiento = $db->lastInsertId();

             
        //insertamos los RETORNOS ligados al movimiento
        $sqlqry = "insert into retornos ( 
        Nombre,
        Cuenta_clabe,
        Banco,
        Monto,
        idMovimiento,
        Comentario,
        codigoSwift,
        direccionBanco
        ) values(
        :Nombre,
        :Cuenta_clabe,
        :Banco,
        :Monto,
        :idMovimiento,
        :Comentario,
        :codigoSwift,
        :direccionBanco
        
        )";
        
        $stmt = $db->prepare($sqlqry);
        foreach ($value->retornos as $retorno) {
        $stmt->bindParam(":Comentario", $retorno->comentarioRetorno);
        $stmt->bindParam(":Nombre", $retorno->nombreRetorno);
        $stmt->bindParam(":Cuenta_clabe", $retorno->cuentaRetorno);
        $stmt->bindParam(":Banco", $retorno->Banco);
        $stmt->bindParam(":Monto", $retorno->retornoMonto);
        $stmt->bindParam(":codigoSwift", $retorno->codigoSwift);
        $stmt->bindParam(":direccionBanco", $retorno->direccionBanco);
        $stmt->bindParam(":idMovimiento", $idMovimiento);
        $stmt->execute();

        }
        

        //insertamos las COMISIONES ligadas al movimiento
        
        $sqlqry = "insert into comision ( Tipo,
        Monto,
        Comentarios,
        idMovimiento,
        Porcentaje
        ) values(
        :Tipo,
        :Monto,
        :Comentarios,
        :idMovimiento,
        :Porcentaje
        )";
        
        $stmt = $db->prepare($sqlqry);
        foreach ($value->comisiones as $comision) {
        $stmt->bindParam(":Tipo", $comision->Tipo);
        $stmt->bindParam(":Monto", $comision->Monto);
        $stmt->bindParam(":Comentarios", $comision->Comentarios);
        $stmt->bindParam(":idMovimiento", $idMovimiento);
        $stmt->bindParam(":Porcentaje", $comision->Porcentaje);
        $stmt->execute();
        }

        //insertamos los DEPOSITOS ligados al movimento
        
        $sqlqry = "insert into depositos ( 
        monto,
        banco,
        fecha,
        idMovimiento,
        comentarios,
        nombreDeposito
        ) values(
        :monto,
        :banco,
        :fecha,
        :idMovimiento,
        :comentarios,
        :nombreDeposito
        )";
        
        $stmt = $db->prepare($sqlqry);
        foreach ($value->depositos as $deposito) {
        $stmt->bindParam(":monto", $deposito->depositoMonto);
        $stmt->bindParam(":banco", $deposito->bancoDeposito);
        $stmt->bindParam(":fecha", $deposito->fechaDepositoStr);
        $stmt->bindParam(":comentarios", $deposito->comentarioDeposito);
        $stmt->bindParam(":nombreDeposito", $deposito->nombreDeposito);
        $stmt->bindParam(":idMovimiento", $idMovimiento);
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
            :idMovimiento
        )";
        
        $stmt = $db->prepare($sqlqry);
        
        $stmt->bindParam(":file", $dataFile);
        $stmt->bindParam(":fileName", $inputFileName);
        $stmt->bindParam(":fileType", $inputFileType);
        $stmt->bindParam(":idMovimiento", $idMovimiento);
        $stmt->execute();

       //Actualizamos la solicitud con el movimiento asignado
       if($movimiento->solicitudId != null){
        asignaMovimientoIdSolicitud($movimiento->solicitudId,$idMovimiento);
       }
       
     

        }
        
        


    }catch (PDOException $e)
    {
        echo '{"error": { "text":'.$e->getMessage().'}';

    };
});


$app->post('/api/movimiento/atender', function(Request $request, Response $response){

    $value = json_decode($request->getBody());
    $sql = "select * from v_movimiento where _id = :_id";

    $db = new db();
    $db = $db->connectDB();
    $stmt = $db->prepare($sql);
   
    
    $stmt->bindParam(":_id", $value->_id );
  
    $stmt->execute();
   
    if($stmt->rowCount()>0 ){
        $movimiento = $stmt->fetchAll(PDO::FETCH_OBJ);
        
    }else{
       $movimiento = [];
    }

    $sqlqry = "select * from depositos where idMovimiento = :_id";
    $stmt = $db->prepare($sqlqry);
    $stmt->bindParam(":_id", $value->_id );
    $stmt->execute();
    if($stmt->rowCount()>0 ){
        $depositos = $stmt->fetchAll(PDO::FETCH_OBJ);
    }else{
        $depositos = [];
    }

    $sqlqry = "select * from retornos where idMovimiento = :_id";
    $stmt = $db->prepare($sqlqry);
    $stmt->bindParam(":_id", $value->_id );
    $stmt->execute();
    if($stmt->rowCount()>0 ){
        $retornos = $stmt->fetchAll(PDO::FETCH_OBJ);
    }else{
        $retornos = [];
    }

    $sqlqry = "select * from comision where idMovimiento = :_id";
    $stmt = $db->prepare($sqlqry);
    $stmt->bindParam(":_id", $value->_id );
    $stmt->execute();
    if($stmt->rowCount()>0 ){
        $comisiones = $stmt->fetchAll(PDO::FETCH_OBJ);
    }else{
        $comisiones = [];
    }

    $sqlqry = "select _id, fileName, fileType from archivos where idMovimiento = :_id";
    $stmt = $db->prepare($sqlqry);
    $stmt->bindParam(":_id", $value->_id );
    $stmt->execute();
    if($stmt->rowCount()>0 ){
        $files = $stmt->fetchAll(PDO::FETCH_OBJ);
    }else{
        $files = [];
    }

    $resultado = null;
    $db = null;

    $data = array(
        "movimiento" => $movimiento[0],
        "depositos" => $depositos,
        "retornos" =>  $retornos,
        "comisiones" =>  $comisiones,
        "files" => $files,      
    );

    echo json_encode($data);

});


$app->post('/api/movimientos/pendientes/facturar', function(Request $request, Response $response){
    $value = json_decode($request->getBody());

    $idCliente = $value->idCliente;
    $idSolFact = $value->solicitudId;
    $idMovimiento = $value->idMovimiento;

    if($idMovimiento == null){
        $sql = "select * from v_movimiento
                where solicitudId IS NULL and idCliente = :ID";
                $ID = $idCliente;

    } else {
        $sql = "select * from v_movimiento
        where _id  = :ID";
            $ID = $idMovimiento;
    }
    

    try{
    $db = new db();
    $db = $db->connectDB();
    $stmt = $db->prepare($sql);
   
    
    $stmt->bindParam(":ID", $ID );
  
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


$app->post('/api/factura/movimiento/asignar', function(Request $request, Response $response){
    $value = json_decode($request->getBody());

    
    $idFactura = $value->_id;
    $idMovimiento = $value->idMovimiento;
    $solicitudId = $value->solicitudId;

  
        $sql = "UPDATE movimiento
                SET idMovimiento = :idMovimiento
                where _id =:_id;

                UPDATE movimiento
                SET  solicitudId = :solicitudId
                where _id = :idMov

                UPDATE solicitud_factura
                SET id_movimiento = :idMov 
                WHERE solicitudId =  :solicitudId
                ";


    try{
    $db = new db();
    $db = $db->connectDB();
    $stmt = $db->prepare($sql);
   
    
    $stmt->bindParam(":_id", $idFactura );
    $stmt->bindParam(":idMovimiento", $idMovimiento );
    $stmt->bindParam(":solicitudId", $solicitudId );
    $stmt->bindParam(":idMov", $idMovimiento );
    $stmt->execute();

    $sql = "select * from v_movimiento
        where _id  = :idMovimiento";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(":idMovimiento", $idMovimiento );
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



$app->post('/api/movimiento/validar', function(Request $request, Response $response){
    $value = json_decode($request->getBody());
    
    
    $idMovimiento = $value->_id;
    $retornos = $value->retornos;
    $comisiones = $value->comisiones;
    $depositos = $value->depositos;

    $estatusRetorno = $retornos == "true"? "generado": "pendiente";
    $estatusComision = $comisiones == "true"? "generado": "pendiente";
    $estatusDeposito = $depositos == "true"? "generado": "pendiente";
        $sql = "UPDATE movimiento
                SET estatusRetorno = :estatusRetorno,
                    estatusComision = :estatusComision,
                    estatusDeposito = :estatusDeposito
                where _id = :_id";

    try{
    $db = new db();
    $db = $db->connectDB();
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":_id", $idMovimiento );
    $stmt->bindParam(":estatusRetorno", $estatusRetorno );
    $stmt->bindParam(":estatusComision", $estatusComision );
    $stmt->bindParam(":estatusDeposito", $estatusDeposito );
    $stmt->execute();
    } catch (PDOException $e)
    {
        echo '{"error": { "text":'.$e->getMessage().'}';

    };

});

$app->post('/api/factura/xml/test', function(Request $request, Response $response){
    /*echo "Api clientes";*/
    $value = json_decode($request->getBody());
    try{
    $directory = $this->get('upload_directory');
    $uploadedFiles = $request->getUploadedFiles();
    $files = $_FILES;
    $inputFile = $files['file']['tmp_name'];
    $inputFileName = $files['file']['name'];
    $inputFileType = $files['file']['type'];
    $dataFile = file_get_contents($inputFile); //el archivo xml es leido como string
    
    $value = json_decode($request->getParam('movimientoObj'));

    $xml = simplexml_load_string($dataFile); //convertimos el string a xml
    $ns = $xml->getNamespaces(true);
    $xml->registerXPathNamespace('c', $ns['cfdi']);
    $xml->registerXPathNamespace('t', $ns['tfd']);

    foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor){
  
        $empresaFacturadora = $Emisor['Nombre'];
     }
     foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor') as $Receptor){
        $rfc = $Receptor['Rfc'][0];
        $cliente = $Receptor['Nombre'];
        $usoCFDI = $Receptor['UsoCFDI'];
     }
     foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante){
           $montoTotal = $cfdiComprobante['Total'];
           $folio = $cfdiComprobante['Folio'];
           $tipoComprobante = $cfdiComprobante['TipoDeComprobante'];
           $metodoPago = $cfdiComprobante['MetodoPago'];
           $formaPago = $cfdiComprobante['FormaPago'];
           $subtotal = $cfdiComprobante['SubTotal'];
           $moneda = $cfdiComprobante['Moneda'];
           $condicionPago = $cfdiComprobante['CondicionesDePago'];
     }
     foreach ($xml->xpath('//cfdi:Impuestos//cfdi:Traslados//cfdi:Traslado') as $Impuesto){
        $iva = $Impuesto['TasaOCuota'];
     }

     $data = array(
        "RFC" =>(string)$rfc[0],
        "empresaFacturadora"  =>(string)$empresaFacturadora[0],
        "cliente" => (string)$cliente[0],
        "montoTotal" => (string)$montoTotal[0],
        "folio" =>(string)$folio[0],
        "tipoComprobante" => (string)$tipoComprobante[0],
        "usoCFDI" => (string)$usoCFDI[0],
        "metodoPago" => (string)$metodoPago[0],
        "formaPago" =>(string) $formaPago[0],
        "subTotal" =>(string) $subtotal[0],
        "iva" => (string)$iva[0],
        "moneda" => (string)$moneda[0],
        "condicionPago" => (string)$condicionPago[0]
     );
     echo json_encode($data);
    }  catch (PDOException $e)
    {
        echo '{"error": { "text":'.$e->getMessage().'}';

    };

});