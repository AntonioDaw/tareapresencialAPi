<?php
require_once "conexion/conexion.php";
require_once "respuestas.class.php";
class auth extends conexion
{
    public function login($json)
    {
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);
        if (!isset($datos['usuario']) || !isset($datos['password'])) {
            // campos no definidos
            return $_respuestas->error_400();
        } else {
            // campos estan definidos 
            $usuario = $datos['usuario'];
            $password = $datos['password'];
            //$password = parent::encriptar($password);
            $datos = $this->obtenerDatosUsuario($usuario);
            if($datos){
                // el usuario existe
                if($password == $datos[0]['Password']){
                    //contraseña correcta
                    $verificar  = $this->insertarToken($datos[0]['UsuarioId']);
                    if($verificar){
                        // si se guardo
                        $result = $_respuestas->response;
                        $result["result"] = array(
                            "token" => $verificar
                        );
                        return $result;
                }else{
                        //error al guardar
                        return $_respuestas->error_500("Error interno, No hemos podido guardar");
                }
                }else{
                    //contraseña incorrrecta
                    return $_respuestas->error_200("La contraseña $password no existe");
                    
                }

            }else{
                //no existe el usuario
                return $_respuestas->error_200("El usuario $usuario no existe");

            }
        }
    }
    private function obtenerDatosUsuario($correo){
        $query = "SELECT UsuarioId,Password FROM usuarios WHERE Usuario = '$correo'";
        $datos = parent::obtenerDatos($query);
        if (isset($datos[0]["UsuarioId"])) {
            return $datos;
        } else {
            return 0;
        }
    }
    private function insertarToken($usuarioid){
        $val = true;
        $token = bin2hex(openssl_random_pseudo_bytes(16,$val));
        $date = date("Y-m-d H:i");
        $estado = "Activo";
        $query = "INSERT INTO usuarios_token (UsuarioId,Token,Estado,Fecha)VALUES('$usuarioid','$token','$estado','$date')";
        $verifica = parent::nonQuery($query);
        if($verifica){
            return $token;
        }else{
            return 0;
        }
    }
}
