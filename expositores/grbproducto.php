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
$prodnombre 	= (isset($_POST['prodnombre'])) ? trim($_POST['prodnombre']) : '';
$proddes 	= (isset($_POST['proddes'])) ? trim($_POST['proddes']) : '';
$catprod 		= (isset($_POST['catprod'])) ? trim($_POST['catprod']) : 0;
$prodreg 		= (isset($_POST['prodreg'])) ? trim($_POST['prodreg']) : 0;




$expreg		= VarNullBD($expreg, 	'N');
$prodnombre	= VarNullBD($prodnombre, 'S');
$proddes	= VarNullBD($proddes, 'S');
$prodreg		= VarNullBD($prodreg, 	'N');

//Chequeo catidad de productos
$count = "SELECT COUNT (*)  FROM EXP_PROD WHERE EXPREG = $expreg";
$TblCount = sql_query($count, $conn);
$RowCount		= $TblCount->Rows[0];
$count 		= trim($RowCount['COUNT']);

$PARAMETRO_PRODUCTO = $catprod;

if ($prodreg>0){

	$PARAMETRO_PRODUCTO = 1000;
	
}
if($count <= $PARAMETRO_PRODUCTO){
	


	if ($prodreg == 0) {
//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
	//Genero un ID 
	$query 		= 'SELECT GEN_ID(G_EXPPROD,1) AS ID FROM RDB$DATABASE';
	$TblId		= sql_query($query, $conn, $trans);
	$RowId		= $TblId->Rows[0];
	$prodreg 	= trim($RowId['ID']);
	//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

	$query = " 	INSERT INTO EXP_PROD(PRODREG,EXPREG,PRODNOMBRE,PRODDES)
					VALUES($prodreg,$expreg,$prodnombre,$proddes) ";

	}else{

		$query = " 	UPDATE EXP_PROD SET
		PRODREG= $prodreg, EXPREG=$expreg, PRODNOMBRE=$prodnombre, PRODDES=$proddes
		WHERE PRODREG=$prodreg";

	}



	
$err = sql_execute($query, $conn, $trans);



//imagenes productos
//Imagen 1
if (isset($_FILES['prodimg'])) {
	$ext 	= pathinfo($_FILES['prodimg']['name'], PATHINFO_EXTENSION);
	$name 	= "EXP_PROD_IMG" . date("H-i-s.") . $ext;

	if (!file_exists($pathimagenes . $expreg)) {
		mkdir($pathimagenes . $expreg);
	}
	if (file_exists($pathimagenes . $expreg . '/' . $name)) {
		unlink($pathimagenes . $expreg . '/' . $name);
	}


	// - - - - - - - - - - - - - - - - - - - - - - - - - - - 

	move_uploaded_file($_FILES['prodimg']['tmp_name'], $pathimagenes . $expreg . '/' . $name);


	$query = "	UPDATE EXP_PROD SET PRODIMG='$name' WHERE PRODREG=$prodreg ";
	$err = sql_execute($query, $conn, $trans);
}

$nombreImagen = isset($name) ? $name : '';


if (isset($_FILES['prodfolleto'])) {
	$ext 	= pathinfo($_FILES['prodfolleto']['name'], PATHINFO_EXTENSION);
	$name 	= "EXP_PROD_FOLLETO" . date("H-i-s.") . $ext;

	if (!file_exists($pathimagenes . $expreg)) {
		mkdir($pathimagenes . $expreg);
	}
	if (file_exists($pathimagenes . $expreg . '/' . $name)) {
		unlink($pathimagenes . $expreg . '/' . $name);
	}


	move_uploaded_file($_FILES['prodfolleto']['tmp_name'], $pathimagenes . $expreg . '/' . $name);


	$query = "	UPDATE EXP_PROD SET PRODFOLLETO='$name' WHERE PRODREG=$prodreg ";
	$err = sql_execute($query, $conn, $trans);
}



}else{
	$errcod = 2;
	$errmsg = "Cantidad Maxima de productos";
	$nombreImagen = '';
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
echo '{"errcod":"' . $errcod . '","errmsg":"' . $errmsg . '","imgname":"' . $nombreImagen . '"}';






?>