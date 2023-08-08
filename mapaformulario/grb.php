<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC . '/idioma.php'; //Idioma	
//--------------------------------------------------------------------------------------------------------------
$errcod = 0;
$errmsg = '';
$err 	= 'SQLACCEPT';

//--------------------------------------------------------------------------------------------------------------
$conn = sql_conectar(); //Apertura de Conexion
$trans	= sql_begin_trans($conn);

//Control de Datos
$mapreg 		= (isset($_POST['mapreg'])) ? trim($_POST['mapreg']) : 0;
$link 		= (isset($_POST['link'])) ? trim($_POST['link']) : '';
$coord 		= (isset($_POST['coord'])) ? trim($_POST['coord']) : '';
$expreg			= (isset($_POST['expreg'])) ? trim($_POST['expreg']) : 0;

$estcodigo 		= 1;
//$fecha= BDConvFch($row['FECHA']);
if ($mapreg == '') {
	$mapreg = 0;
}

if ($errcod == 2) {
	echo '{"errcod":"' . $errcod . '","errmsg":"' . $errmsg . '"}';
	exit;
}



//--------------------------------------------------------------------------------------------------------------
$mapreg			= VarNullBD($mapreg, 'N');
$link		= VarNullBD($link, 'S');
$coord		= VarNullBD($coord, 'S');
$estcodigo		= VarNullBD($estcodigo, 'S');
$expreg			= VarNullBD($expreg, 'N');



if ($mapreg == 0) {
	//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
	//Genero un ID 
	$query 		= 'SELECT GEN_ID(G_MAPA,1) AS ID FROM RDB$DATABASE';
	$TblId		= sql_query($query, $conn, $trans);
	$RowId		= $TblId->Rows[0];
	$mapreg 	= trim($RowId['ID']);
	//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

	$query = " 	INSERT INTO MAP_TABLE (MAPREG, LINK, COORD, ESTCODIGO, EXPREG)
					VALUES  ($mapreg, $link, $coord, $estcodigo, $expreg) ";
} else {
	$query = " UPDATE MAP_TABLE SET 
					  LINK=$link, COORD=$coord, ESTCODIGO=$estcodigo, EXPREG=$expreg
					 WHERE MAPREG=$mapreg ";
}

$err = sql_execute($query, $conn, $trans);


//--------------------------------------------------------------------------------------------------------------
if ($err == 'SQLACCEPT' && $errcod == 0) {
	sql_commit_trans($trans);
	$errcod = 0;
	$errmsg = TrMessage('Guardado correctamente!');
} else {
	sql_rollback_trans($trans);
	$errcod = 2;
	$errmsg = ($errmsg == '') ? 'Guardado correctamente!' : $errmsg;
}
//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
echo '{"errcod":"' . $errcod . '","errmsg":"' . $errmsg . '"}';

?>	
