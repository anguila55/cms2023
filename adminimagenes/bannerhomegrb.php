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

$urlbanner = (isset($_POST['urlbanner'])) ? trim($_POST['urlbanner']) : '';

$bannerid = (isset($_POST['bannerid'])) ? trim($_POST['bannerid']) : 0;
$estcodigo = (isset($_POST['estcodigo'])) ? trim($_POST['estcodigo']) : 1;





if(isset($_FILES['filebannerhome'.$bannerid])){

	$ext 	= pathinfo($_FILES['filebannerhome'.$bannerid]['name'], PATHINFO_EXTENSION);

	if($bannerid==9){
		if ($ext != 'jpg'){
			$errcod =2;
		$errmsg = "El favicon debe ser jpg ";
		echo '{"errcod":"' . $errcod . '","errmsg":"' . $errmsg . '"}';
		die;
		}
	}
	
	$query = "	SELECT BANNERS FROM ADM_IMG";
	$Table = sql_query($query,$conn);
	if($Table->Rows_Count>0){
	
		$row= $Table->Rows[$bannerid];
	$bannerhomeimg 	= trim($row['BANNERS']);

		if(file_exists($pathimagenes.'/'.$bannerhomeimg)){
			unlink($pathimagenes.'/'.$bannerhomeimg);
		}
	
	}
	
	
	if($bannerid==9){

		$pathimagenesfavicon = '../assets-nuevodisenio/img/';
	
	$name 	="favicon".".".$ext;
	
	}else if ($bannerid==10){
		$name 	="navbar".date("His.").$ext;
	}else{
	
		$name 	="bannerhome".date("His.").$ext;
	
	}
	
	if (!file_exists($pathimagenes)) {
		mkdir($pathimagenes);	   				
	}

	
	if($bannerid==9){
		if(file_exists($pathimagenesfavicon.'/'.$name)){
			unlink($pathimagenesfavicon.'/'.$name);
		}
	
		move_uploaded_file( $_FILES['filebannerhome'.$bannerid]['tmp_name'], $pathimagenesfavicon.'/'.$name);

	}else{
		if(file_exists($pathimagenes.'/'.$name)){
			unlink($pathimagenes.'/'.$name);
		}
	
		move_uploaded_file( $_FILES['filebannerhome'.$bannerid]['tmp_name'], $pathimagenes.'/'.$name);
		
	}

	



  /// ACTUALIZO EL NOMBRE
	$query = "	UPDATE ADM_IMG SET BANNERS='$name' WHERE BANID=$bannerid";
	$err = sql_execute($query,$conn,$trans);
	
}

$query = "	UPDATE ADM_IMG SET URLBAN='$urlbanner', ESTCODIGO=$estcodigo WHERE BANID=$bannerid";
	$err = sql_execute($query,$conn,$trans);

//--------------------------------------------------------------------------------------------------------------
if ($err == 'SQLACCEPT' && $errcod == 0) {
	sql_commit_trans($trans);
	$errcod = 0;
	$errmsg = 'Banner Modificado!';
} else {
	sql_rollback_trans($trans);
	$errcod = 2;
	$errmsg = ($errmsg == '') ? 'Banner Modificado!' : $errmsg;
}
//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
echo '{"errcod":"' . $errcod . '","errmsg":"' . $errmsg . '"}';


?>