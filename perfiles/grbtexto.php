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
$txtnombre 		= (isset($_POST['txtnombre'])) ? trim($_POST['txtnombre']) : '';
$txtdescri 		= (isset($_POST['txtdescri'])) ? trim($_POST['txtdescri']) : '';
$cattxt 		= (isset($_POST['cattxt'])) ? trim($_POST['cattxt']) : 0;



$expreg		= VarNullBD($expreg, 	'N');
$txtnombre	= VarNullBD($txtnombre, 'S');
$txtdescri	= VarNullBD($txtdescri, 'S');

//Chequeo catidad de productos
$count = "SELECT COUNT (*)  FROM EXP_TXT WHERE EXPREG = $expreg";
$TblCount = sql_query($count, $conn);
$RowCount		= $TblCount->Rows[0];
$count 		= trim($RowCount['COUNT']);

$PARAMETRO_TEXTO = $cattxt;

if($count <= $PARAMETRO_TEXTO){




	//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
	//Genero un ID 
	$query 		= 'SELECT GEN_ID(G_EXPTXT,1) AS ID FROM RDB$DATABASE';
	$TblId		= sql_query($query, $conn, $trans);
	$RowId		= $TblId->Rows[0];
	$txtreg 	= trim($RowId['ID']);
	//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

	$query = " 	INSERT INTO EXP_TXT(TXTREG,EXPREG,TXTNOMBRE,TXTDESCRI)
					VALUES($txtreg,$expreg,$txtnombre,$txtdescri) ";

$err = sql_execute($query, $conn, $trans);

}else{
	$errcod = 2;
	$errmsg = "Cantidad Maxima de textos";
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