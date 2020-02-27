<?php



//Get todos los cleintes;
$app->get('/api/clientes', function(Request $request, Response $response){
    /*echo "Api clientes";*/

    $sql = "select * from usuarios";
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