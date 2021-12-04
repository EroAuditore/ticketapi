<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


//Obtiene todos los agentes y sus respectivos clientes;
$app->get('/api/listado/agente', function(Request $request, Response $response){
    /*echo "Api clientes";*/

    $sql = "select a._id, a.Nombre as Agente from agente a";
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


//Obtiene todos los clientes de un agente;
$app->post('/api/filtro/agente', function(Request $request, Response $response){
    
    $value = json_decode($request->getBody());
    $sql = "select _id,  c.Nombre as Cliente from cliente c where c.idAgente=:idagente";
    try{

        $db = new db();
        $db = $db->connectDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":idagente", $value->_id );

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


//buscar los datos del cliente con el id de cliente 
$app->post('/api/cliente/filtrar', function(Request $request, Response $response){
    
    $value = json_decode($request->getBody());
    $sql = "select *
    from cliente where  _id =:_id";
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



//buscar los datos del todos los clientes, agrupados por agente
$app->get('/api/clientes/todos', function(Request $request, Response $response){
    
    $value = json_decode($request->getBody());
    $sql = "select *
    from v_tclientes ";
    try{

        $db = new db();
        $db = $db->connectDB();
        $stmt = $db->prepare($sql);
        

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


//buscar los datos del todos los clientes, agrupados por agente
$app->post('/api/cliente/nuevo', function(Request $request, Response $response){
    
    $value = json_decode($request->getBody());
    $sql = "INSERT INTO cliente (
        Nombre,
        RFC,
        direccion,
        email,
        idAgente)
            values (
        :Nombre,
        :RFC,
        :direccion,
        :email,
        :idAgente
        )";
    try{

        $db = new db();
        $db = $db->connectDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":RFC", $value->RFC);
        $stmt->bindParam(":Nombre", $value->Nombre);
        $stmt->bindParam(":direccion", $value->direccion);
        $stmt->bindParam(":email", $value->email);
        $stmt->bindParam(":idAgente", $value->idAgente);
    
        $stmt->execute();
        
        $sql = "select *
        from v_tclientes ";
        $stmt = $db->prepare($sql);
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


//Crea un nuevo agente
$app->post('/api/agente/nuevo', function(Request $request, Response $response){
    
    $value = json_decode($request->getBody());
    $sql = "INSERT INTO agente (
        Nombre
        )
            values (
        :Nombre
        )";
    try{

        $db = new db();
        $db = $db->connectDB();
        $stmt = $db->prepare($sql);
       
        $stmt->bindParam(":Nombre", $value->Agente);
        
    
        $stmt->execute();
        
        $sql = "select a._id, a.Nombre as Agente from agente a";
        $stmt = $db->prepare($sql);
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