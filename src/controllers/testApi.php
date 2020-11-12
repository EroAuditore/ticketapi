<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/api/test', function(Request $request, Response $response){
    echo "Api corriendo";
});
