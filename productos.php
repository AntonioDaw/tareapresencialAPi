<?php
require_once 'clases/respuestas.class.php';
require_once 'clases/productos.class.php';
$_respuestas = new respuestas;
$_tareas = new tareas;
// token "43cdff9a2a899dfd8fab16d866eb0acc"

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET["page"])) {
        $pagina = $_GET["page"];
        $listaTareas = $_tareas->listaProductos($pagina);
        header('Content-Type: application/json');
        echo json_encode($listaTareas);
        http_response_code(200);
    }else if(isset($_GET['id'])){
        $tareaId = $_GET['id'];
        $datosTareas = $_tareas->obtenerProducto($tareaId);
        header('Content-Type: application/json');
        echo json_encode($datosTareas);
        http_response_code(200);
    }
} elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
    //recibimos los datos enviados
    $postBody = file_get_contents("php://input");
    //los enviamos al controlador
    $datosArray= $_tareas->post($postBody);
        //devolvemos la respuesta
        header('Content-Type: application/json');
        if(isset($datosArray["result"]["error_id"])){
        $responseCode = $datosArray["result"]["error_id"];
        http_response_code($responseCode);
    }else{
        http_response_code(200);
    }
    echo json_encode($datosArray);
} elseif ($_SERVER['REQUEST_METHOD'] == "PUT") {
     //recibimos los datos enviados
     $postBody = file_get_contents("php://input");
     //enviamos datos al controlador
     $datosArray= $_tareas->put($postBody);
     header('Content-Type: application/json');
        if(isset($datosArray["result"]["error_id"])){
        $responseCode = $datosArray["result"]["error_id"];
        http_response_code($responseCode);
    }else{
        http_response_code(200);
    }
    echo json_encode($datosArray);
} elseif ($_SERVER['REQUEST_METHOD'] == "DELETE") {
    //recibimos los datos enviados
    $postBody = file_get_contents("php://input");
    //enviamos datos al controlador
    $datosArray= $_tareas->delete($postBody);
    header('Content-Type: application/json');
       if(isset($datosArray["result"]["error_id"])){
       $responseCode = $datosArray["result"]["error_id"];
       http_response_code($responseCode);
   }else{
       http_response_code(200);
   }
   echo json_encode($datosArray);
} else {
    header('Content-Type: application/json');
    $datosArray = $_respuestas->error_405();
    echo json_encode($datosArray);
}
