<?php include('../val/valuser.php'); ?>
<?
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';

$errcod = 0;
$errmsg = '';
$err 	= 'SQLACCEPT';

$pathimagenes = '../mapimg/';
if (!file_exists($pathimagenes)) {
	mkdir($pathimagenes);
}
$conn = sql_conectar(); //Apertura de Conexion
$trans	= sql_begin_trans($conn);

$mapreg = (isset($_POST['mapreg'])) ? trim($_POST['mapreg']) : 0;
$visibilidad = (isset($_POST['visibilidad'])) ? trim($_POST['visibilidad']) : 1;
$estcodigo 		= 4;


if(isset($_FILES['filemaphome'])){
	
	$query = "	SELECT MAPREG, IMAGEN FROM MAP_TABLE WHERE MAPREG=$mapreg AND ESTCODIGO=4";
	
	$Table = sql_query($query,$conn);
	if($Table->Rows_Count>0){
	
		$row= $Table->Rows[$mapreg];
	$mapimage 	= trim($row['IMAGEN']);

		if(file_exists($pathimagenes.'/'.$mapimage)){
			unlink($pathimagenes.'/'.$mapimage);
		}
	
	}
	
	
	$ext 	= pathinfo($_FILES['filemaphome']['name'], PATHINFO_EXTENSION);
	$name 	="maphome".date("His.").$ext;
	
	if (!file_exists($pathimagenes)) {
		mkdir($pathimagenes);	   				
	}
	if(file_exists($pathimagenes.'/'.$name)){
		unlink($pathimagenes.'/'.$name);
	}

	move_uploaded_file( $_FILES['filemaphome']['tmp_name'], $pathimagenes.'/'.$name);



  /// ACTUALIZO EL NOMBRE
	$query = "	UPDATE MAP_TABLE SET IMAGEN='$name' WHERE MAPREG=$mapreg";
	
	$err = sql_execute($query,$conn,$trans);
	
}
$query = "	UPDATE MAP_TABLE SET ESTCODIGO=$estcodigo, VISIBILIDAD=$visibilidad WHERE MAPREG=$mapreg";
	$err = sql_execute($query,$conn,$trans);

//--------------------------------------------------------------------------------------------------------------
if ($err == 'SQLACCEPT' && $errcod == 0) {
	sql_commit_trans($trans);
	$errcod = 0;
	$errmsg = 'Mapa Modificado!';
} else {
	sql_rollback_trans($trans);
	$errcod = 2;
	$errmsg = ($errmsg == '') ? 'Mapa Modificado!' : $errmsg;
}
//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
echo '{"errcod":"' . $errcod . '","errmsg":"' . $errmsg . '"}';


?>