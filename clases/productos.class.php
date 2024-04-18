<?php
require_once "clases/respuestas.class.php";
require_once "conexion/conexion.php";

class tareas extends conexion
{
    private $id = "codprod";
    private $table = "productos";
    private $nombre = "nombre";
    private $categoria = "categoria";
    private $pvp = "pvp";
    private $stock = "stock";
    private $imagen = "imagen";
    private $Observaciones = "Observaciones";
    private $token = "";

    public function listaProductos($pagina = 1)
    {
        $inicio  = 0;
        $cantidad = 2;
        if ($pagina > 1) {
            $inicio = ($cantidad * ($pagina - 1)) + 1;
            $cantidad = $cantidad * $pagina;
        }
        //esta funcion es llamada desde el metodo Get
        // Fijamos que se ordene por categoría en la consulta y le pasamos las variables anteriores para la paginación
        $query = "SELECT *  FROM " . $this->table . " ORDER BY categoria limit $inicio,$cantidad";
        $datos = parent::obtenerDatos($query);
        return $datos;
    }
    public function obtenerProducto($id)
    {
        //llamada del metodo get
        // Hace una consulta del producto con el id que le pasasmos por postman
        $query = "SELECT *  FROM " . $this->table . " WHERE codprod = '$id'";
        return parent::obtenerDatos($query);
    }
    public function post($json)
    {
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);

        if (!isset($datos['token'])) {
            return $_respuestas->error_401();
        } else {
            $this->token = $datos['token'];
            $arrayToken = $this->buscarToken();
            if ($arrayToken) {
                if (!isset($datos['nombre']) || !isset($datos['categoria']) || !isset($datos['pvp'])) {
                    return $_respuestas->error_400();
                } else {
                    $this->nombre = $datos['nombre'];
                    $this->categoria = $datos['categoria'];
                    $this->pvp = $datos['pvp'];
                    if (isset($datos['stock'])) [$this->stock = $datos['stock']];
                    if (isset($datos['Observaciones'])) [$this->Observaciones = $datos['Observaciones']];
                    if (isset($datos['imagen'])){
                        $resp = $this->procesarImagen($datos['imagen']);
                        $this->imagen = $resp;
                    }
                    $resp = $this->insertarProducto();
                    if ($resp) {
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "id" => $resp
                        );
                        return $respuesta;
                    } else {
                        return $_respuestas->error_500();
                    }
                }
            } else {
                return $_respuestas->error_401("El token es invalido o esta caducado");
            }
        }
    }
    public function put($json)
    {
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);
        if (!isset($datos['token'])) {
            return $_respuestas->error_401();
        } else {
            $this->token = $datos['token'];
            $arrayToken = $this->buscarToken();
            if ($arrayToken) {
                if (!isset($datos['codprod'])) {
                    return $_respuestas->error_400();
                } else {
                    $this->id = $datos['codprod'];
                    if (isset($datos['nombre'])) [$this-> nombre= $datos['nombre']];
                    if (isset($datos['categoria'])) [$this->categoria = $datos['categoria']];
                    if (isset($datos['pvp'])) [$this->pvp = $datos['pvp']];
                    if (isset($datos['stock'])) [$this->stock = $datos['stock']];
                    if (isset($datos['Observaciones'])) [$this->Observaciones = $datos['Observaciones']];
                    if (isset($datos['imagen'])){
                        $resp = $this->procesarImagen($datos['imagen']);
                        $this->imagen = $resp;
                    }
                    $resp = $this->modificarProducto();
                    if ($resp) {
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "id" => $this->id
                        );
                        return $respuesta;
                    } else {
                        return $_respuestas->error_500();
                    }
                }
            } else {
                return $_respuestas->error_401("El token es invalido o esta caducado");
            }
        }
        
    }
    public function delete($json)
    {
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);
        if (!isset($datos['token'])) {
            return $_respuestas->error_401();
        } else {
            $this->token = $datos['token'];
            $arrayToken = $this->buscarToken();
            if ($arrayToken) {
                if (!isset($datos['codprod'])) {
                    return $_respuestas->error_400();
                } else {
                    $this->id = $datos['codprod'];
        
                    $resp = $this->eliminarProducto();
                    if ($resp) {
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "id" => $this->id
                        );
                        return $respuesta;
                    } else {
                        return $_respuestas->error_500();
                    }
                }
            } else {
                return $_respuestas->error_401("El token es invalido o esta caducado");
            }
           
        }
    
        
    }

    
    private function procesarImagen($img) {
        $direccion = dirname(__DIR__) . "/public/imagenes/";
        $partes = explode(";base64,", $img);
        
        // Obtener la extensión del tipo MIME
        $tipoMIME = explode('/', mime_content_type($img));
        $extension = $tipoMIME[1];
    
        $imagen_Base64 = base64_decode($partes[1]);
    
        // Generar un nombre de archivo único con extensión
        $nombreArchivo = uniqid() . "." . $extension;
        $file = $direccion . $nombreArchivo;
    
        // Guardar la imagen decodificada en el archivo
        file_put_contents($file, $imagen_Base64);
    
        // Devolver la nueva dirección
        $nuevaDireccion = str_replace('\\', '/', $file);
        return $nuevaDireccion;
    }
    private function insertarProducto()
    
    {
        $query = "INSERT INTO " . $this->table . " (nombre, imagen, categoria, pvp, stock, Observaciones) 
        VALUES ('" . $this->nombre . "','" . $this->imagen . "','" . $this->categoria . "','" . $this->pvp . 
        "','" . $this->stock . "','" . $this->Observaciones . "')";

        $resp = parent::nonQueryId($query);
        if ($resp) {
            return $resp;
        } else {
            return 0;
        }
    }
    private function modificarProducto()
    {
        $query = "UPDATE " . $this->table . " SET nombre='" . $this->nombre . "', imagen = '" . $this->imagen . "', categoria = '" . $this->categoria . "', pvp = '" .
            $this->pvp . "', stock = '" . $this->stock . "', Observaciones = '" . $this->Observaciones . "' WHERE codprod = '" . $this->id . "'";


        $resp = parent::nonQuery($query);
        if ($resp >= 1) {
            return $resp;
        } else {
            return 0;
        }
    }
    private function eliminarProducto()
    {
        $query = "DELETE FROM " . $this->table . " WHERE codprod= '" . $this->id . "'";
        $resp = parent::nonQuery($query);
        if ($resp >= 1) {
            return $resp;
        } else {
            return 0;
        }
    }
    private function buscarToken()
    {
        $query = "SELECT  TokenId,UsuarioId,Estado from usuarios_token WHERE Token = '" . $this->token . "' AND Estado = 'Activo'";
        $resp = parent::obtenerDatos($query);
        if ($resp) {
            return $resp;
        } else {
            return 0;
        }
    }
    
}
