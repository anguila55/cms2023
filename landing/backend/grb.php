<?php include('../../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';

//--------------------------------------------------------------------------------------------------------------
$pathimagenes = '../../landing/landingimg/';
if (!file_exists($pathimagenes)) {
	mkdir($pathimagenes);
}

//--------------------------------------------------------------------------------------------------------------
$errcod = 0;
$errmsg = '';
$err 	= 'SQLACCEPT';

//--------------------------------------------------------------------------------------------------------------
$conn = sql_conectar(); //Apertura de Conexion
$trans	= sql_begin_trans($conn);

//Control de Datos
$id_seccion 		= (isset($_POST['id_seccion']))	  	? trim($_POST['id_seccion']) 	: 0;
$nombreseccion 		= (isset($_POST['nombreseccion']))		? trim($_POST['nombreseccion']) 	: '';
$titulosuperior 		= (isset($_POST['titulosuperior']))	? trim($_POST['titulosuperior']) : '';
$titulo 		= (isset($_POST['titulo'])) 	? trim($_POST['titulo']) : '';
$descri 		= (isset($_POST['descri']))		? trim($_POST['descri']) 	: '';
$opcional1 		= (isset($_POST['opcional1']))		? trim($_POST['opcional1']) 	: '';
$opcional2 		= (isset($_POST['opcional2']))		? trim($_POST['opcional2']) 	: '';
$opcional3 		= (isset($_POST['opcional3']))		? trim($_POST['opcional3']) 	: '';
$opcional4 		= (isset($_POST['opcional4']))		? trim($_POST['opcional4']) 	: '';
$opcional5 		= (isset($_POST['opcional5']))		? trim($_POST['opcional5']) 	: '';
$opcional6 		= (isset($_POST['opcional6']))		? trim($_POST['opcional6']) 	: '';
$estcodigo 		= (isset($_POST['visibilidad']))	? trim($_POST['visibilidad']) : 1;


//--------------------------------------------------------------------------------------------------------------
$id_seccion			= VarNullBD($id_seccion, 'N');
$titulosuperior		= VarNullBD($titulosuperior, 'S');
$titulo      = VarNullBD($titulo, 'S');
$estcodigo		= VarNullBD($estcodigo, 'N');
$descri      = VarNullBD($descri, 'S');
$opcional1      = VarNullBD($opcional1, 'S');
$opcional2      = VarNullBD($opcional2, 'S');
$opcional3      = VarNullBD($opcional3, 'S');
$opcional4      = VarNullBD($opcional4, 'S');
$opcional5      = VarNullBD($opcional5, 'S');
$opcional6      = VarNullBD($opcional6, 'S');
$nombreseccion      = VarNullBD($nombreseccion, 'S');

$query = " 	UPDATE TBL_LANDING SET 
					TIT_SUP=$titulosuperior,NOM_SEC=$nombreseccion,TITULO=$titulo, DESCRI=$descri, OPCIONAL1=$opcional1,
					OPCIONAL2=$opcional2,OPCIONAL3=$opcional3,OPCIONAL4=$opcional4,OPCIONAL5=$opcional5,OPCIONAL6=$opcional6, ESTCODIGO=$estcodigo
					WHERE ID_SECCION=$id_seccion ";


$err = sql_execute($query, $conn, $trans);

date_default_timezone_set('UTC');
//--------------------------------------------------------------------------------------------------------------
if (isset($_FILES['imagen'])) {

	$ext 	= pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
	$name 	= 'IMAGEN' . date('His') . rand(100, 200) . '.' . $ext;  //$file['name'];

	if (!file_exists($pathimagenes . $id_seccion)) {
		mkdir($pathimagenes . $id_seccion);
	}
	if (file_exists($pathimagenes . $id_seccion . '/' . $name)) {
		unlink($pathimagenes . $id_seccion . '/' . $name);
	}
	move_uploaded_file($_FILES['imagen']['tmp_name'], $pathimagenes . $id_seccion . '/' . $name);

	$_SESSION[GLBAPPPORT . 'IMAGEN'] =  $pathimagenes . $id_seccion . '/' . $name; //Actualizo la variable de Session del AVATAR

	$query = "	UPDATE TBL_LANDING SET IMAGEN='$name' WHERE ID_SECCION=$id_seccion ";

	//-------------Redimension de imagen----------------------------------//
	//$imagen_optimizada = redimensionar_imagen($name, $pathimagenes . $id_seccion . '/' . $name, 200, 200);

	//Guardado de imagen
	//imagepng($imagen_optimizada, $pathimagenes . $id_seccion . '/' . $name);

	$err = sql_execute($query, $conn, $trans);
}
//--------------------------------------------------------------------------------------------------------------
if ($err == 'SQLACCEPT' && $errcod == 0) {
	sql_commit_trans($trans);
	$errcod = 0;
	$errmsg = 'Guardado correctamente!';
} else {
	sql_rollback_trans($trans);
	$errcod = 2;
	$errmsg = ($errmsg == '') ? 'Guardado correctamente!' : $errmsg;
}
//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
echo '{"errcod":"' . $errcod . '","errmsg":"' . $errmsg . '"}';

?>	
