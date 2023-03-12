<?php

$carga = fn($clase)=>require("$clase.php");
spl_autoload_register($carga);


$bd = new BBDD();
$producto = $bd->get_producto($_POST['codigo']);
$familia = $_POST['familia'];
$cod = $_POST['codigo'];

if(isset($_POST['submit'])){
    $opcion= $_POST['submit'];

    switch($opcion){
    case "Actualizar":
        $nc = htmlentities($_POST['nombre_corto']);
        $n = htmlentities($_POST['nombre']);
        $d = htmlentities($_POST['descripcion']);
        $p = $_POST['PVP'];

        if($bd->update_producto($cod, $nc, $n, $d, $p)){
            header("Location: ".$_SERVER["PHP_SELF"]."/actualizar.php?r=1&familia=".$familia);
        }else{
            header("Location: ".$_SERVER["PHP_SELF"]."/actualizar.php?r=0&familia=".$familia);
        }
        break;
    case "Cancelar":
        header("Location: ".$_SERVER["PHP_SELF"]."/actualizar.php?r=0&familia=".$familia);
        break;
    default:
        break;  
}
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Documents</title>
</head>
<body >

    <form action="editar.php" method="post">
        <label for="nombre_corto">Nombre corto: </label><br>
        <input type="text" name="nombre_corto" value="<?php echo htmlentities($producto['nombre_corto'])?>">
        <br>
        <input type="text" name="nombre" placeholder="Ingresar nombre del producto" value="<?php echo htmlentities($producto['nombre'])?>">
        <br>
        <label for="descripcion">Descripcion: </label><br>
        <textarea style="min-height: 200px;" name="descripcion"><?php echo htmlentities($producto['descripcion'])?></textarea>
        <br>
        <label for="pvp">Precio de venta al publico: </label><br>
        <input type="number" step="0.01" name="PVP" value="<?php echo $producto['PVP']?>">
        <br><br>
        <input type="hidden" name="codigo" value="<?php echo $cod ?>">
        <input type="hidden" name="familia" value="<?php echo $familia ?>">
        <input type="submit" value="Actualizar" name="submit">
        <input type="submit" value="Cancelar" name="submit">
    </form>

</body>
