<?php

$carga = fn($clase)=>require("$clase.php");
spl_autoload_register($carga);

if($_GET['r']==1){
    $texto = "Se ha actualizado correctamente el producto";
}else{
    $texto = "Se ha cancelado o ha sucedido un error";
}

header("refresh:5;url=listado.php?familia=".$_GET['familia']);


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
        <input type="text" name="nombre_corto" value="<?php echo $producto['nombre_corto']?>">
        <br>
        <input type="text" name="nombre" placeholder="Ingresar nombre del producto" value="<?php echo $producto['nombre']?>">
        <br>
        <label for="descripcion">Descripcion: </label><br>
        <textarea style="min-height: 200px;" name="descripcion"><?php echo $producto['descripcion']?></textarea>
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
