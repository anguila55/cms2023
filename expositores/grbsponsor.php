<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';

//--------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------
$errcod = 0;
$errmsg = '';
$err 	= 'SQLACCEPT';

$pathimagenes = '../expimg/';
if (!file_exists($pathimagenes)) {
	mkdir($pathimagenes);
}
//--------------------------------------------------------------------------------------------------------------
$conn = sql_conectar(); //Apertura de Conexion
$trans	= sql_begin_trans($conn);

//Control de Datos
$expreg 		= (isset($_POST['expreg'])) ? trim($_POST['expreg']) : 0;
$expnombre 		= (isset($_POST['expnombre'])) ? trim($_POST['expnombre']) : '';
$expweb 		= (isset($_POST['expweb'])) ? trim($_POST['expweb']) : '';
$expmail 		= (isset($_POST['expmail'])) ? trim($_POST['expmail']) : '';
$expstand 		= (isset($_POST['expstand'])) ? trim($_POST['expstand']) : '';
$exprubros 		= (isset($_POST['exprubros'])) ? trim($_POST['exprubros']) : '';
$expposx 		= (isset($_POST['expposx'])) ? trim($_POST['expposx']) : '';
$expposy 		= (isset($_POST['expposy'])) ? trim($_POST['expposy']) : '';
$expcatego 		= (isset($_POST['expcatego'])) ? trim($_POST['expcatego']) : '';
$percodigo 		= (isset($_POST['percodigo'])) ? trim($_POST['percodigo']) : '';
$estcodigo 		= 1;
$exppos 		= (isset($_POST['orden'])) ? trim($_POST['orden']) : '';


//NUEVOS DATOS

$explinked		= (isset($_POST['explinked'])) ? trim($_POST['explinked']) : '';
$expfac			= (isset($_POST['expfac'])) ? trim($_POST['expfac']) : '';
$exptwi			= (isset($_POST['exptwi'])) ? trim($_POST['exptwi']) : '';
$expinsta		= (isset($_POST['expinsta'])) ? trim($_POST['expinsta']) : '';
$expdireccion	= (isset($_POST['expdireccion'])) ? trim($_POST['expdireccion']) : '';
$exptelefo		= (isset($_POST['exptelefo'])) ? trim($_POST['exptelefo']) : '';
$expyoutub		= (isset($_POST['expyoutub'])) ? trim($_POST['expyoutub']) : '';



if ($expreg == '') {
	$expreg = 0;
}

if ($errcod == 2) {
	echo '{"errcod":"' . $errcod . '","errmsg":"' . $errmsg . '"}';
	exit;
}
//--------------------------------------------------------------------------------------------------------------
$expreg			= VarNullBD($expreg, 'N');
$expnombre		= VarNullBD($expnombre, 'S');
$expweb			= VarNullBD($expweb, 'S');
$expmail		= VarNullBD($expmail, 'S');
$expstand		= VarNullBD($expstand, 'S');
$exprubros		= VarNullBD($exprubros, 'S');
$expcatego		= VarNullBD($expcatego, 'S');
$expposx		= VarNullBD($expposx, 'N');
$expposy		= VarNullBD($expposy, 'N');
$estcodigo		= VarNullBD($estcodigo, 'N');
$percodigo		= VarNullBD($percodigo, 'N');
$exppos			= VarNullBD($exppos, 'N');
$explinked		= VarNullBD($explinked, 'S');
$expfac			= VarNullBD($expfac, 'S');
$exptwi			= VarNullBD($exptwi, 'S');
$expdireccion	= VarNullBD($expdireccion, 'S');
$exptelefo		= VarNullBD($exptelefo, 'S');
$expyoutub		= VarNullBD($expyoutub, 'S');
$expinsta		= VarNullBD($expinsta, 'S');


if ($expreg == 0) {
	//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
	//Genero un ID 
	$query 		= 'SELECT GEN_ID(G_EXPOSITORES,1) AS ID FROM RDB$DATABASE';
	$TblId		= sql_query($query, $conn, $trans);
	$RowId		= $TblId->Rows[0];
	$expreg 	= trim($RowId['ID']);
	//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

	$query = " 	INSERT INTO EXP_MAEST(EXPREG,EXPNOMBRE,EXPWEB,EXPMAIL,EXPSTAND,EXPRUBROS,EXPPOSX,EXPPOSY, ESTCODIGO,EXPCATEGO,PERCODIGO,EXPPOS,EXPLINKED,
	 						EXPFAC,EXPTWI,EXPDIRECCION,EXPTELEFO,EXPYOUTUB,EXPINSTA)
					VALUES($expreg,$expnombre,$expweb,$expmail,$expstand,$exprubros,$expposx,$expposy,$estcodigo,$expcatego,$percodigo,
						   $exppos,$explinked,$expfac,$exptwi,$expdireccion,$exptelefo,$expyoutub,$expinsta) ";
} else {
	$query = " 	UPDATE EXP_MAEST SET 
					EXPNOMBRE=$expnombre, EXPWEB=$expweb, EXPMAIL=$expmail, EXPSTAND=$expstand, EXPRUBROS=$exprubros,
					EXPPOSX=$expposx,EXPPOSY=$expposy, ESTCODIGO=$estcodigo, EXPCATEGO=$expcatego,PERCODIGO=$percodigo, EXPPOS=$exppos,
					EXPLINKED = $explinked, EXPFAC = $expfac, EXPTWI  = $exptwi , EXPDIRECCION = $expdireccion , EXPTELEFO = $exptelefo,
					EXPYOUTUB = $expyoutub, EXPINSTA = $expinsta
					WHERE EXPREG=$expreg ";
}
$err = sql_execute($query, $conn, $trans);






//Archivo del Avatar
if (isset($_FILES['expavatar'])) {
	$ext 	= pathinfo($_FILES['expavatar']['name'], PATHINFO_EXTENSION);
	$name 	= "EXP_" . date("H-i-s.") . $ext;

	if (!file_exists($pathimagenes . $expreg)) {
		mkdir($pathimagenes . $expreg);
	}
	if (file_exists($pathimagenes . $expreg . '/' . $name)) {
		unlink($pathimagenes . $expreg . '/' . $name);
	}

	//Limpio el directorio - - - - - - - - - - - - - - - - - 
	$dirint = dir($pathimagenes . $expreg . '/');
	while (($archivo = $dirint->read()) !== false) {
		if (strpos($archivo, 'UAVATAR') !== false) {
			unlink($pathimagenes . $expreg . '/' . $archivo);
		}
	}
	$dirint->close();
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - 

	move_uploaded_file($_FILES['expavatar']['tmp_name'], $pathimagenes . $expreg . '/' . $name);

	//-------------Redimension de imagen----------------------------------//
	$imagen_optimizada = redimensionar_imagen($name, $pathimagenes . $expreg . '/' . $name, 200, 200);

	//Guardado de imagen
	imagepng($imagen_optimizada, $pathimagenes . $expreg . '/' . $name);

	$query = "	UPDATE EXP_MAEST SET EXPAVATAR='$name' WHERE EXPREG=$expreg ";
	$err = sql_execute($query, $conn, $trans);
}


//BANNER PERFIL
if (isset($_FILES['expbanimg1'])) {
	$ext 	= pathinfo($_FILES['expbanimg1']['name'], PATHINFO_EXTENSION);
	$name 	= "EXP_BAN_PERFIL" . date("H-i-s.") . $ext;

	if (!file_exists($pathimagenes . $expreg)) {
		mkdir($pathimagenes . $expreg);
	}
	if (file_exists($pathimagenes . $expreg . '/' . $name)) {
		unlink($pathimagenes . $expreg . '/' . $name);
	}

	//Limpio el directorio - - - - - - - - - - - - - - - - - 
	$dirint = dir($pathimagenes . $expreg . '/');
	while (($archivo = $dirint->read()) !== false) {
		if (strpos($archivo, 'EXP_BAN_PERFIL') !== false) {
			unlink($pathimagenes . $expreg . '/' . $archivo);
		}
	}
	$dirint->close();
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - 

	move_uploaded_file($_FILES['expbanimg1']['tmp_name'], $pathimagenes . $expreg . '/' . $name);


	$query = "	UPDATE EXP_MAEST SET EXPBANIMG1='$name' WHERE EXPREG=$expreg ";
	$err = sql_execute($query, $conn, $trans);
}
//BANNER Listado
if (isset($_FILES['expbanimg2'])) {
	$ext 	= pathinfo($_FILES['expbanimg2']['name'], PATHINFO_EXTENSION);
	$name 	= "EXP_BAN_LISTADO" . date("H-i-s.") . $ext;

	if (!file_exists($pathimagenes . $expreg)) {
		mkdir($pathimagenes . $expreg);
	}
	if (file_exists($pathimagenes . $expreg . '/' . $name)) {
		unlink($pathimagenes . $expreg . '/' . $name);
	}

	//Limpio el directorio - - - - - - - - - - - - - - - - - 
	$dirint = dir($pathimagenes . $expreg . '/');
	while (($archivo = $dirint->read()) !== false) {
		if (strpos($archivo, 'EXP_BAN_LISTADO') !== false) {
			unlink($pathimagenes . $expreg . '/' . $archivo);
		}
	}
	$dirint->close();
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - 

	move_uploaded_file($_FILES['expbanimg2']['tmp_name'], $pathimagenes . $expreg . '/' . $name);


	$query = "	UPDATE EXP_MAEST SET EXPBANIMG2='$name' WHERE EXPREG=$expreg ";
	$err = sql_execute($query, $conn, $trans);
}
//BANNER Responsive
if (isset($_FILES['expbanimg3'])) {
	$ext 	= pathinfo($_FILES['expbanimg3']['name'], PATHINFO_EXTENSION);
	$name 	= "EXP_BAN_RESPONSIVE" . date("H-i-s.") . $ext;

	if (!file_exists($pathimagenes . $expreg)) {
		mkdir($pathimagenes . $expreg);
	}
	if (file_exists($pathimagenes . $expreg . '/' . $name)) {
		unlink($pathimagenes . $expreg . '/' . $name);
	}

	//Limpio el directorio - - - - - - - - - - - - - - - - - 
	$dirint = dir($pathimagenes . $expreg . '/');
	while (($archivo = $dirint->read()) !== false) {
		if (strpos($archivo, 'EXP_BAN_RESPONSIVE') !== false) {
			unlink($pathimagenes . $expreg . '/' . $archivo);
		}
	}
	$dirint->close();
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - 

	move_uploaded_file($_FILES['expbanimg3']['tmp_name'], $pathimagenes . $expreg . '/' . $name);


	$query = "	UPDATE EXP_MAEST SET EXPBANIMG2='$name' WHERE EXPREG=$expreg ";
	$err = sql_execute($query, $conn, $trans);
}

//BANNER Responsive
if (isset($_FILES['expfolleto'])) {
	$ext 	= pathinfo($_FILES['expfolleto']['name'], PATHINFO_EXTENSION);
	$name 	= "EXP_FOLLETO_" . date("H-i-s.") . $ext;

	if (!file_exists($pathimagenes . $expreg)) {
		mkdir($pathimagenes . $expreg);
	}
	if (file_exists($pathimagenes . $expreg . '/' . $name)) {
		unlink($pathimagenes . $expreg . '/' . $name);
	}

	//Limpio el directorio - - - - - - - - - - - - - - - - - 
	$dirint = dir($pathimagenes . $expreg . '/');
	while (($archivo = $dirint->read()) !== false) {
		if (strpos($archivo, 'EXP_FOLLETO_') !== false) {
			unlink($pathimagenes . $expreg . '/' . $archivo);
		}
	}
	$dirint->close();
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - 

	move_uploaded_file($_FILES['expfolleto']['tmp_name'], $pathimagenes . $expreg . '/' . $name);


	$query = "	UPDATE EXP_MAEST SET EXPFOLLETO='$name' WHERE EXPREG=$expreg ";
	$err = sql_execute($query, $conn, $trans);
}


//anuncios 	expanunciobanner/expanunciocuadr
if (isset($_FILES['expanunciobanner'])) {
	$ext 	= pathinfo($_FILES['expanunciobanner']['name'], PATHINFO_EXTENSION);
	$name 	= "EXP_ANUNCIO_BANNER_" . date("H-i-s.") . $ext;

	if (!file_exists($pathimagenes . $expreg)) {
		mkdir($pathimagenes . $expreg);
	}
	if (file_exists($pathimagenes . $expreg . '/' . $name)) {
		unlink($pathimagenes . $expreg . '/' . $name);
	}

	//Limpio el directorio - - - - - - - - - - - - - - - - - 
	$dirint = dir($pathimagenes . $expreg . '/');
	while (($archivo = $dirint->read()) !== false) {
		if (strpos($archivo, 'EXP_ANUNCIO_BANNER') !== false) {
			unlink($pathimagenes . $expreg . '/' . $archivo);
		}
	}
	$dirint->close();
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - 

	move_uploaded_file($_FILES['expanunciobanner']['tmp_name'], $pathimagenes . $expreg . '/' . $name);


	$query = "	UPDATE EXP_MAEST SET EXPANUNCIOBANNER='$name' WHERE EXPREG=$expreg ";
	$err = sql_execute($query, $conn, $trans);
}

if (isset($_FILES['expanunciocuadr'])) {
	$ext 	= pathinfo($_FILES['expanunciocuadr']['name'], PATHINFO_EXTENSION);
	$name 	= "EXP_ANUNCIO_CUADRADO_" . date("H-i-s.") . $ext;

	if (!file_exists($pathimagenes . $expreg)) {
		mkdir($pathimagenes . $expreg);
	}
	if (file_exists($pathimagenes . $expreg . '/' . $name)) {
		unlink($pathimagenes . $expreg . '/' . $name);
	}

	//Limpio el directorio - - - - - - - - - - - - - - - - - 
	$dirint = dir($pathimagenes . $expreg . '/');
	while (($archivo = $dirint->read()) !== false) {
		if (strpos($archivo, 'EXP_ANUNCIO_CUADRADO') !== false) {
			unlink($pathimagenes . $expreg . '/' . $archivo);
		}
	}
	$dirint->close();
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - 

	move_uploaded_file($_FILES['expanunciocuadr']['tmp_name'], $pathimagenes . $expreg . '/' . $name);


	$query = "	UPDATE EXP_MAEST SET EXPANUNCIOCUADR='$name' WHERE EXPREG=$expreg ";
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




