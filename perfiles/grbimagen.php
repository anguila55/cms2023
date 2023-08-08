<?php include('../val/valuser.php'); ?>
<?
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';

$errcod = 0;
$errmsg = '';
$err 	= 'SQLACCEPT';

$pathimagenes = '../expimg/';
if (!file_exists($pathimagenes)) {
	mkdir($pathimagenes);
}
$conn = sql_conectar(); //Apertura de Conexion
$trans	= sql_begin_trans($conn);

//Control de Datos
$expreg 		= (isset($_POST['expreg'])) ? trim($_POST['expreg']) : 0;
$catimg 		= (isset($_POST['catimg'])) ? trim($_POST['catimg']) : 0;



$expreg		= VarNullBD($expreg, 	'N');


//Chequeo catidad de imagenes
$count = "SELECT COUNT (*)  FROM EXP_IMG WHERE EXPREG = $expreg";
$TblCount = sql_query($count, $conn);
$RowCount		= $TblCount->Rows[0];
$count 		= trim($RowCount['COUNT']);




if($count <= $catimg){

		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT GEN_ID(G_IMG,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query, $conn, $trans);
		$RowId		= $TblId->Rows[0];
		$imgreg 	= trim($RowId['ID']);
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

	if (isset($_FILES['expimg'])) {
		$ext 	= pathinfo($_FILES['expimg']['name'], PATHINFO_EXTENSION);
		$name 	= "EXP_IMAGEN" . date("H-i-s.") . $ext;

		if (!file_exists($pathimagenes . $expreg)) {
			mkdir($pathimagenes . $expreg);
		}
		if (file_exists($pathimagenes . $expreg . '/' . $name)) {
			unlink($pathimagenes . $expreg . '/' . $name);
		}

	


		move_uploaded_file($_FILES['expimg']['tmp_name'], $pathimagenes . $expreg . '/' . $name);


		$query = "	INSERT INTO EXP_IMG(IMGREG,EXPREG,EXPIMG)
		VALUES($imgreg,$expreg,'$name')  ";
		$err = sql_execute($query, $conn, $trans);
	}
}else{
	$errcod = 2;
	$errmsg = "Cantidad Maxima de imagenes"; 
	$name = '';
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
echo '{"errcod":"' . $errcod . '","errmsg":"' . $errmsg . '","imgname":"' . $name . '"}';






?>