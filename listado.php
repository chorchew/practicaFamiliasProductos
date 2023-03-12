<?php

$carga = fn($clase)=>require("$clase.php");
spl_autoload_register($carga);


$bd = new BBDD();
$familias = $bd->get_listado_familias();


if(isset($_POST['submit'])){
    $opcion= $_POST['submit'];

    switch($opcion){
        case "Mostrar productos":
            $familia = $_POST['familia'];
            $listado = $bd->get_productos_familia($familia);
            break;
        default:
            break;  
    }
}else{
    if(isset($_GET['familia'])){
        $familia = $_GET['familia'];
        $listado = $bd->get_productos_familia($familia);
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
<body>

    <form action="listado.php" method="post">
        Familia 
        <select name="familia">
            <?php echo $familias?>
            <?php foreach($familias as $familia){ ?>
                <option <?php if($familia['cod'] == $_POST['familia']){?> selected <?php } ?> value="<?php echo $familia['cod']?>"><?php echo $familia['nombre']?></option>
            <?php } ?>
        </select>
        <input type="submit" value="Mostrar productos" name="submit">
    </form>

    <?php if(isset($listado)){ ?>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Editar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listado as $producto){ ?>
                    <tr>
                        <td><?php echo $producto['nombre_corto'] ?></td>
                        <td><?php echo $producto['PVP'] ?></td>
                        <td>
                            <form action="editar.php" method="post">
                                <input type="submit" value="Modificar" name="submit">
                                <input type="hidden" value="<?php echo $producto['cod']?>" name="codigo">
                                <input type="hidden" value="<?php echo $producto['familia']?>" name="familia">
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>

</body>
