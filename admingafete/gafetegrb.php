<?php include('../val/valuser.php'); ?>
<?
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';

$errcod = 0;
$errmsg = '';
$err 	= 'SQLACCEPT';

$pathimagenes = '../admimg/';
if (!file_exists($pathimagenes)) {
	mkdir($pathimagenes);
}
$conn = sql_conectar(); //Apertura de Conexion
$trans	= sql_begin_trans($conn);

$cuadrante3tit = (isset($_POST['cuadrante3tit'])) ? trim($_POST['cuadrante3tit']) : '';
$cuadrante3des = (isset($_POST['cuadrante3des'])) ? trim($_POST['cuadrante3des']) : '';


$cuadranteinfo = (isset($_POST['cuadranteinfo'])) ? trim($_POST['cuadranteinfo']) : 0;

if(isset($_FILES['filecuadrante3img'])){
	
	$query = "	SELECT GAF_IMG3 FROM ADM_GAF";
	$Table = sql_query($query,$conn);
	if($Table->Rows_Count>0){
	
		$row= $Table->Rows[$popupreg];
		$gaf3img 	= trim($row['GAF_IMG3']);

		if(file_exists($pathimagenes.'/'.$gaf3img)){
			unlink($pathimagenes.'/'.$gaf3img);
		}
	
	}
	
	
	$ext 	= pathinfo($_FILES['filecuadrante3img']['name'], PATHINFO_EXTENSION);
	$name 	="cuadrante3img".date("His.").$ext;
	
	if (!file_exists($pathimagenes)) {
		mkdir($pathimagenes);	   				
	}
	if(file_exists($pathimagenes.'/'.$name)){
		unlink($pathimagenes.'/'.$name);
	}

	move_uploaded_file( $_FILES['filecuadrante3img']['tmp_name'], $pathimagenes.'/'.$name);



  /// ACTUALIZO EL NOMBRE
	$query = "	UPDATE ADM_GAF SET GAF_IMG3='$name' WHERE GAF_REG=0";
	$err = sql_execute($query,$conn,$trans);
	
}

if(isset($_FILES['filecuadrante4img'])){
	
	$query = "	SELECT GAF_IMG4 FROM ADM_GAF";
	$Table = sql_query($query,$conn);
	if($Table->Rows_Count>0){
	
		$row= $Table->Rows[$popupreg];
		$gaf4img 	= trim($row['GAF_IMG4']);

		if(file_exists($pathimagenes.'/'.$gaf4img)){
			unlink($pathimagenes.'/'.$gaf4img);
		}
	
	}
	
	
	$ext 	= pathinfo($_FILES['filecuadrante4img']['name'], PATHINFO_EXTENSION);
	$name 	="cuadrante4img".date("His.").$ext;
	
	if (!file_exists($pathimagenes)) {
		mkdir($pathimagenes);	   				
	}
	if(file_exists($pathimagenes.'/'.$name)){
		unlink($pathimagenes.'/'.$name);
	}

	move_uploaded_file( $_FILES['filecuadrante4img']['tmp_name'], $pathimagenes.'/'.$name);



  /// ACTUALIZO EL NOMBRE
	$query = "	UPDATE ADM_GAF SET GAF_IMG4='$name' WHERE GAF_REG=0";
	$err = sql_execute($query,$conn,$trans);
	
}



$query = "	UPDATE ADM_GAF SET CUA_TIT='$cuadrante3tit', CUA_DES='$cuadrante3des' ,CUA_INFO='$cuadranteinfo' WHERE GAF_REG=0";
$err = sql_execute($query,$conn,$trans);

	

//--------------------------------------------------------------------------------------------------------------
if ($err == 'SQLACCEPT' && $errcod == 0) {
	sql_commit_trans($trans);
	$errcod = 0;
	$errmsg = 'Gafete Modificado!';
} else {
	sql_rollback_trans($trans);
	$errcod = 2;
	$errmsg = ($errmsg == '') ? 'Gafete Modificado!' : $errmsg;
}
//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
echo '{"errcod":"' . $errcod . '","errmsg":"' . $errmsg . '"}';


?>