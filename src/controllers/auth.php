<?php
use \Firebase\JWT\JWT;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//Get todos los tickets;
$app->post('/api/auth', function(Request $request, Response $response){
      
    $value = json_decode($request->getBody());
   
    $sql = "select username, perfil from usuarios where username= :usr and password = :pwd";
    try{
        $key = "key_ultra_secreta";
        $db = new db();

        $db = $db->connectDB();
      
        $resultado = $db->prepare($sql);

        $resultado->bindParam(":usr", $value->username);
        $resultado->bindParam(":pwd", $value->password);

        $resultado->execute();
        if($resultado->rowCount()>0 ){
            $data = $resultado->fetchAll(PDO::FETCH_OBJ);
           
        $payload = array(
            "iss" => "http://localhost",
            "aud" => "http://localhost",
            "iat" =>  time(),
            "nbf" =>  time(),
            "data"=> $data[0]
        );

        $jwt = JWT::encode($payload, $key);
            echo $jwt;
        }else{
            $err['error'] = 'Usuario o Contraseña incorrecto';
            return $response->withJson($err, 400);
        }
        $resultado = null;
        $db = null;

    }catch (PDOException $e)
    {
        echo '{"error": { "text":'.$e->getMessage().'}';

    };

});