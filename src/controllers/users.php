<?php


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


//Get todos los tickets;
$app->get('/api/usuarios', function(Request $request, Response $response){
    /*echo "Api clientes";*/

    $sql = "select * from usuarios";
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

$app->post('/api/usuario/nuevo', function(Request $request, Response $response){

    $value = json_decode($request->getBody());
   
   try{

    $sqlqry = "insert into usuarios (
                            Nombre,
                            Username,
                            Password,
                            Area,
                            facturacion,
                            movimientos,
                            depositos,
                            retornos,
                            configuracion,
                            activo
                            ) VALUES (
                            :Nombre,
                            :Username,
                            :Password,
                            :Area,
                            :facturacion,
                            :movimientos,
                            :depositos,
                            :retornos,
                            :configuracion,
                            :activo)";
        
        $db = new db();
        $db = $db->connectDB();
        $stmt = $db->prepare($sqlqry);
       
        $stmt->bindParam(":Nombre", $value->nombre);
        $stmt->bindParam(":Username", $value->userName);
        $stmt->bindParam(":Password", $value->password);
        $stmt->bindParam(":Area", $value->area);
        $stmt->bindParam(":facturacion", intval($value->facturas));
        $stmt->bindParam(":movimientos", intval($value->movimientos));
        $stmt->bindParam(":depositos", intval($value->depositos));
        $stmt->bindParam(":retornos", intval($value->retornos));
        $stmt->bindParam(":configuracion", intval($value->configuracion));
        $stmt->bindParam(":activo", intval($value->activo));
        $stmt->execute();

        }catch (PDOException $e)
        {
            echo '{"error": { "text":'.$e->getMessage().'}';

        };
});


$app->post('/api/usuario/editar', function(Request $request, Response $response){

    $value = json_decode($request->getBody());
   
   try{

    $sqlqry = "update usuarios set 
                            Nombre = :Nombre,
                            Username = :Username,
                            Password =:Password,
                            Area = :Area,
                            facturacion = :facturacion,
                            movimientos = :movimientos,
                            depositos = :depositos,
                            retornos = :retornos,
                            configuracion = :configuracion,
                            activo = :activo
                            where ID_usuario = :ID_usuario";
        
        $db = new db();
        $db = $db->connectDB();
        $stmt = $db->prepare($sqlqry);
        $stmt->bindParam(":ID_usuario", $value->ID_usuario);
        $stmt->bindParam(":Nombre", $value->nombre);
        $stmt->bindParam(":Username", $value->userName);
        $stmt->bindParam(":Password", $value->password);
        $stmt->bindParam(":Area", $value->area);
        $stmt->bindParam(":facturacion", intval($value->facturas));
        $stmt->bindParam(":movimientos", intval($value->movimientos));
        $stmt->bindParam(":depositos", intval($value->depositos));
        $stmt->bindParam(":retornos", intval($value->retornos));
        $stmt->bindParam(":configuracion", intval($value->configuracion));
        $stmt->bindParam(":activo", intval($value->activo));
        $stmt->execute();

        }catch (PDOException $e)
        {
            echo '{"error": { "text":'.$e->getMessage().'}';

        };
});


$app->post('/api/usuario/buscar', function(Request $request, Response $response){
    
    $value = json_decode($request->getBody());
    $sql = "select * from usuarios where nombre like :nombre";
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
            echo json_encode (json_decode ("[]"));
        }
        $resultado = null;
        $db = null;

    }catch (PDOException $e)
    {
        echo '{"error": { "text":'.$e->getMessage().'}';

    };

});

