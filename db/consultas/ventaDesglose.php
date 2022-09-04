<?php
session_start();
include("../conexiones/conexion.php");
date_default_timezone_set('America/Monterrey');

$ventaid = $_POST["ventaid"];
$idProd = $_POST["idProd"];
$nombre = $_POST["nombre"];
$prov = $_POST["prov"];
$pUni = $_POST["pUni"];
$cant = $_POST["cant"];
$porcen = $_POST["porcen"];
$total = $_POST["total"];
$vendedor = $_POST["vendedor"];
$date = date("Y-m-d");
$hora = date("H:i:s");

$query1 = "SELECT ident FROM proveedores WHERE nombre = '".$prov."'";
$exec = mysqli_query($conn,$query1);
$row = mysqli_fetch_array($exec);
$idprov = $row['ident'];


$query = "INSERT INTO ventadesg (idventa,fecha,idProd,nombre,proveedor,pUni,cant,total,totdesc,hora) VALUES (".$ventaid.",'".$date."',".$idProd.",'".$nombre."',".$idprov.",".$pUni.",".$cant.",".$total.",".$porcen.",'".$hora."')";
$exec = mysqli_query($conn,$query);



$queryInv = "UPDATE inventario SET existencia = existencia -".$cant." WHERE ident = ".$idProd;
$execInv = mysqli_query($conn,$queryInv);


$registro = "INSERT INTO registro (accion,user,fecha) VALUES ('Desglose de venta con id ".$ventaid."','".$vendedor."','".date("Y-m-d")."')";
$exec2 = mysqli_query($conn,$registro);
if($exec && $exec2 && $execInv){
	echo "Desglose registrado";
}else{
    echo $query1.$query.$queryInv.$registro;
	echo "Error al registrar desglose de venta";
	$deletelast = "DELETE FROM ventas WHERE idventa = ".$ventaid;
	mysqli_query($conn,$deletelast);
	$queryInv = "UPDATE inventario SET existencia = existencia +".$cant." WHERE ident = ".$idProd;
	$execInv = mysqli_query($conn,$queryInv);
	
}


mysqli_close($conn);



?>