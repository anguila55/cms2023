<?php include('../val/valuser.php'); ?>
<?
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';

$errcod = 0;
$errmsg = '';
$err 	= 'SQLACCEPT';

$pathimagenes = '../popimg/';
if (!file_exists($pathimagenes)) {
	mkdir($pathimagenes);
}
$conn = sql_conectar(); //Apertura de Conexion
$trans	= sql_begin_trans($conn);

$popupurl = (isset($_POST['popupurl'])) ? trim($_POST['popupurl']) : '';
$popupdescri = (isset($_POST['popupdescri'])) ? trim($_POST['popupdescri']) : '';
$popuptipo = (isset($_POST['popuptipo'])) ? trim($_POST['popuptipo']) : 1;

$popupreg = (isset($_POST['popupreg'])) ? trim($_POST['popupreg']) : 0;
$estcodigo = (isset($_POST['estcodigo'])) ? trim($_POST['estcodigo']) : 1;

$popupinicioinfo = (isset($_POST['popupinicioinfo'])) ? trim($_POST['popupinicioinfo']) : 0;

if(isset($_FILES['filepopupimg'.$popupreg])){
	
	$query = "	SELECT POP_IMG FROM ADM_POP";
	$Table = sql_query($query,$conn);
	if($Table->Rows_Count>0){
	
		$row= $Table->Rows[$popupreg];
	$popupimg 	= trim($row['POP_IMG']);

		if(file_exists($pathimagenes.'/'.$popupimg)){
			unlink($pathimagenes.'/'.$popupimg);
		}
	
	}
	
	
	$ext 	= pathinfo($_FILES['filepopupimg'.$popupreg]['name'], PATHINFO_EXTENSION);
	$name 	="popuphome".date("His.").$ext;
	
	if (!file_exists($pathimagenes)) {
		mkdir($pathimagenes);	   				
	}
	if(file_exists($pathimagenes.'/'.$name)){
		unlink($pathimagenes.'/'.$name);
	}

	move_uploaded_file( $_FILES['filepopupimg'.$popupreg]['tmp_name'], $pathimagenes.'/'.$name);



  /// ACTUALIZO EL NOMBRE
	$query = "	UPDATE ADM_POP SET POP_IMG='$name' WHERE POP_REG=$popupreg";
	$err = sql_execute($query,$conn,$trans);
	
}

$query = "	UPDATE ADM_POP SET POP_URL='$popupurl', POP_DESCRI='$popupdescri' ,ESTCODIGO=$estcodigo, POP_TIPO=$popuptipo WHERE POP_REG=$popupreg";
	$err = sql_execute($query,$conn,$trans);

	$query = "	UPDATE ADM_POP SET POP_URL='$popupinicioinfo' 
	WHERE POP_REG=0";
	$err = sql_execute($query,$conn,$trans);	

//--------------------------------------------------------------------------------------------------------------
if ($err == 'SQLACCEPT' && $errcod == 0) {
	sql_commit_trans($trans);
	$errcod = 0;
	$errmsg = 'PopUp Home Modificado!';
} else {
	sql_rollback_trans($trans);
	$errcod = 2;
	$errmsg = ($errmsg == '') ? 'PopUp Home Modificado!' : $errmsg;
}
//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
echo '{"errcod":"' . $errcod . '","errmsg":"' . $errmsg . '"}';


?>