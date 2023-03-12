<?php

class BBDD
{

    private $conexion;

    public function __construct(){
        $datos = parse_ini_file("./conexion.ini");
        $host = $datos['host'];
        $user = $datos['user'];
        $database = $datos['database'];
        //$port = $datos['port'];
        $pass = $datos['pass'];

        try{
            $this->conexion = new mysqli($host, $user, $pass, $database);

        }catch(mysqli_sql_exception $ex){
            die("Error conectando ".$ex->getMessage());
        }
    }

    public function get_producto(mixed $cod) {
        $sentencia="SELECT * FROM producto where cod = ? ";
        $parametros=[$cod];
        $rtdo = $this->ejecuta_sentencia($sentencia, $parametros, "producto");
        return $rtdo[0];

    }

    public function get_listado_familias() {
        $sentencia="SELECT * FROM familia";
        $parametros=[];
        $rtdo = $this->ejecuta_sentencia($sentencia, $parametros, "familia");
        return $rtdo;
    }

    public function get_productos_familia(mixed $familia) {
        $sentencia="SELECT * FROM producto where familia = ? ";
        $parametros=[$familia];
        $rtdo = $this->ejecuta_sentencia($sentencia, $parametros, "producto");
        return $rtdo;
    }


    public function obtener_campos($tabla){
        $sentencia = "DESCRIBE $tabla";
        $rtdo = $this->conexion->query($sentencia);
        while( $filas = $rtdo->fetch_row() ){
            $campos[] = $filas[0];
        }
        return $campos;
        
    }

    public function update_producto($codigo, $nombre_corto, $nombre, $descripcion, $pvp){
        if(!isset($nombre) || $nombre=""){
            $nombre = null;
        }
        $sentencia="UPDATE producto set nombre_corto = ?, nombre = ?, descripcion = ?, pvp = ? WHERE cod = ?";
        $parametros=[];
        $parametros=[$nombre_corto, $nombre, $descripcion, $pvp, $codigo];
        $rtdo = $this->ejecuta_sentencia($sentencia, $parametros, "producto");
        return $rtdo;
    }

    public function ejecuta_sentencia($sentencia, $parametros, $tabla):array|bool{
        $stmt = $this->conexion->stmt_init();
        $stmt->prepare($sentencia);
        if(sizeof($parametros) > 0){
            foreach( $parametros as $param ) {
                $tipo = gettype($param);
                switch($tipo){
                    case "integer":
                        $tipo = "i";
                        break;
                    case "double":
                        $tipo = "d";
                        break;
                    default:
                        $tipo = "s";
                        break;
                }
                $stmt->bind_param($tipo, $param);
            }
        }

        $resultado = $stmt->execute();

        if(str_contains(strtoupper($sentencia), "SELECT")){
            $stmt->store_result();
            $campos = $this->obtener_campos($tabla);
            $stmt->bind_result(...$campos);
            $resultado = [];
            while($stmt->fetch())
                $resultado[]=$this->valores($campos, $tabla);
        }

        return $resultado;
    }

    private function valores($campos, $tabla){
        $sentencia = "DESCRIBE $tabla";
        $consulta = $this->conexion->query($sentencia);
        foreach( $campos as $i => $valor )
            $rtdo[$consulta->fetch_row()[0]]=$valor;
        return $rtdo;
    }
}