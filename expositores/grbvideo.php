<?php include('../val/valuser.php'); ?>
<?
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';

$errcod = 0;
$errmsg = '';
$err 	= 'SQLACCEPT';


$conn = sql_conectar(); //Apertura de Conexion
$trans	= sql_begin_trans($conn);

//Control de Datos
$expreg 		= (isset($_POST['expreg'])) ? trim($_POST['expreg']) : 0;
$vidnombre 		= (isset($_POST['vidnombre'])) ? trim($_POST['vidnombre']) : '';
$vidurl 		= (isset($_POST['vidurl'])) ? trim($_POST['vidurl']) : '';
$vidurlid 		= (isset($_POST['vidurlid'])) ? trim($_POST['vidurlid']) : '';
$catvid 		= (isset($_POST['catvid'])) ? trim($_POST['catvid']) : 0;
$vidreg 		= (isset($_POST['vidreg'])) ? trim($_POST['vidreg']) : 0;


$expreg		= VarNullBD($expreg, 	'N');
$vidnombre	= VarNullBD($vidnombre, 'S');
$vidurl		= VarNullBD($vidurl, 	'S');
$vidurlid	= VarNullBD($vidurlid, 	'S');
$vidreg		= VarNullBD($vidreg, 	'N');		


//Chequeo catidad de productos
$count = "SELECT COUNT (*)  FROM EXP_VID WHERE EXPREG = $expreg";
$TblCount = sql_query($count, $conn);
$RowCount		= $TblCount->Rows[0];
$count 		= trim($RowCount['COUNT']);

$PARAMETRO_VIDEO = $catvid;

if ($vidreg>0){

	$PARAMETRO_VIDEO = 1000;
	
}

if($count <= $PARAMETRO_VIDEO){


	
	if ($vidreg == 0) {
	//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
	//Genero un ID 
	$query 		= 'SELECT GEN_ID(G_EXPVID,1) AS ID FROM RDB$DATABASE';
	$TblId		= sql_query($query, $conn, $trans);
	$RowId		= $TblId->Rows[0];
	$vidreg 	= trim($RowId['ID']);
	//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

	$query = " 	INSERT INTO EXP_VID(VIDREG,EXPREG,VIDNOMBRE,VIDURL,VIDURLID)
					VALUES($vidreg,$expreg,$vidnombre,$vidurl,$vidurlid) ";
	}else{


		$query = " 	UPDATE EXP_VID SET
		VIDREG= $vidreg, EXPREG=$expreg, VIDNOMBRE=$vidnombre, VIDURL=$vidurl, VIDURLID=$vidurlid
		WHERE VIDREG=$vidreg";

	}

$err = sql_execute($query, $conn, $trans);

}else{
	$errcod = 2;
	$errmsg = "Cantidad Maxima de videos";
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