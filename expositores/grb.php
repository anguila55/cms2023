<?php include('../val/valuser.php');
include('../phpqrcode/qrlib.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC . '/constants.php';

//--------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------
$errcod = 0;
$errmsg = '';
$err 	= 'SQLACCEPT';

$pathimagenes = '../expimg/';
if (!file_exists($pathimagenes)) {
	mkdir($pathimagenes);
}

$pathqrimagenes = '../qrimage/';
	if (!file_exists($pathqrimagenes)) {
		mkdir($pathqrimagenes);
	}
//--------------------------------------------------------------------------------------------------------------
$conn = sql_conectar(); //Apertura de Conexion
$trans	= sql_begin_trans($conn);

//Control de Datos
$expreg 		= (isset($_POST['expreg'])) ? trim($_POST['expreg']) : 0;
$expnombre 		= (isset($_POST['expnombre'])) ? trim($_POST['expnombre']) : '';
$expnombre1=$expnombre;
$expweb 		= (isset($_POST['expweb'])) ? trim($_POST['expweb']) : '';
$expmail 		= (isset($_POST['expmail'])) ? trim($_POST['expmail']) : '';
$expstand 		= (isset($_POST['expstand'])) ? trim($_POST['expstand']) : '';
$exprubros 		= (isset($_POST['exprubros'])) ? trim($_POST['exprubros']) : '';
$expposx 		= (isset($_POST['expposx'])) ? trim($_POST['expposx']) : '';
$expposy 		= (isset($_POST['expposy'])) ? trim($_POST['expposy']) : '';
$expcatego 		= (isset($_POST['expcatego'])) ? trim($_POST['expcatego']) : '';
$percodigo 		= (isset($_POST['percodigo'])) ? trim($_POST['percodigo']) : '';
$percodigocontacto 		= (isset($_POST['percodigocontacto'])) ? trim($_POST['percodigocontacto']) : 0;
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
$expreutit		= (isset($_POST['expreutit'])) ? trim($_POST['expreutit']) : '';
$expreulnk		= (isset($_POST['expreulnk'])) ? trim($_POST['expreulnk']) : '';
$expbrolnk		= (isset($_POST['expbrolnk'])) ? trim($_POST['expbrolnk']) : '';
//DESAFIOS
$areadesafio	= (isset($_POST['areadesafio'])) ? trim($_POST['areadesafio']) : '';
$desafiodesafio	= (isset($_POST['desafiodesafio'])) ? trim($_POST['desafiodesafio']) : '';
$buscamosdesafio= (isset($_POST['buscamosdesafio'])) ? trim($_POST['buscamosdesafio']) : '';


//orden por categoria 
$categorias ="SELECT  CATVALOR,CATVALORMAX,CATPER
FROM EXP_CAT
WHERE CATREG = $expcatego";

$Table_categorias = sql_query($categorias, $conn); 

for ($index_categorias = 0; $index_categorias < $Table_categorias->Rows_Count; $index_categorias++) {

$row_cateogoria = $Table_categorias->Rows[$index_categorias];

$catval 		= trim($row_cateogoria['CATVALOR']);
$catvalmax 		= trim($row_cateogoria['CATVALORMAX']);
$catper 		= trim($row_cateogoria['CATPER']);
}

if ($exppos < $catval || $exppos > $catvalmax ) {
	$errcod =2;
	$errmsg = "Posicion debe estar entre los siguientes valores: ".$catval.'-'.$catvalmax;
	echo '{"errcod":"' . $errcod . '","errmsg":"' . $errmsg . '"}';
	die;
}

if ($expreg == '') {
	$expreg = 0;
}

if ($errcod == 2) {
	echo '{"errcod":"' . $errcod . '","errmsg":"' . $errmsg . '"}';
	exit;
}

//Perfiles relacionados
$perfiles  =  explode(',',$percodigo);
if ($catper < count($perfiles)) {
	$errcod =2;
	$errmsg = "El maximo de perfiles relacionados es de: ".$catper;
	echo '{"errcod":"' . $errcod . '","errmsg":"' . $errmsg . '"}';
	die;
}

//--------------------------------------------------------------------------------------------------------------
$expreg			= VarNullBD($expreg, 'N');
$expnombre		= VarNullBD($expnombre, 'S');
$expweb			= VarNullBD($expweb, 'S');
$expmail		= VarNullBD($expmail, 'S');
$expstand		= VarNullBD($expstand, 'S');
$exprubros		= VarNullBD($exprubros, 'S');
$expcatego		= VarNullBD($expcatego, 'N');
$expposx		= VarNullBD($expposx, 'N');
$expposy		= VarNullBD($expposy, 'N');
$percodigocontacto		= VarNullBD($percodigocontacto, 'N');
$estcodigo		= VarNullBD($estcodigo, 'N');

$exppos			= VarNullBD($exppos, 'N');
$explinked		= VarNullBD($explinked, 'S');
$expfac			= VarNullBD($expfac, 'S');
$exptwi			= VarNullBD($exptwi, 'S');
$expdireccion	= VarNullBD($expdireccion, 'S');
$exptelefo		= VarNullBD($exptelefo, 'S');
$expyoutub		= VarNullBD($expyoutub, 'S');
$expinsta		= VarNullBD($expinsta, 'S');
$expreutit		= VarNullBD($expreutit, 'S');
$expreulnk		= VarNullBD($expreulnk, 'S');
$expbrolnk		= VarNullBD($expbrolnk, 'S');
//Desafio
$areadesafio	= VarNullBD($areadesafio, 'S');
$desafiodesafio	= VarNullBD($desafiodesafio, 'S');
$buscamosdesafio= VarNullBD($buscamosdesafio, 'S');


if ($expreg == 0) {
	//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
	//Genero un ID 
	$query 		= 'SELECT GEN_ID(G_EXPOSITORES,1) AS ID FROM RDB$DATABASE';
	$TblId		= sql_query($query, $conn, $trans);
	$RowId		= $TblId->Rows[0];
	$expreg 	= trim($RowId['ID']);
	$tempDir = $pathqrimagenes;

	
			$codeContents = URL_WEB . 'login.php?QRCode=E||'.$expreg;

			// we need to generate filename somehow, 
			// with md5 or with database ID used to obtains $codeContents...
			$userName 	= convBBBdatos($expnombre1);
			$fileName = $expreg.'_EXP_'.$userName.'_'.md5($codeContents).'.png';

			$pngAbsoluteFilePath = $tempDir.$fileName;
			$urlRelativeFilePath = '../qrimage/'.$fileName;

			// generating
			if (!file_exists($pngAbsoluteFilePath)) {
				define('IMAGE_WIDTH',1024);
				define('IMAGE_HEIGHT',1024);
				QRcode::png($codeContents, $pngAbsoluteFilePath,QR_ECLEVEL_H, 4);
				
			} else {
				
			}
	//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

	$query = " 	INSERT INTO EXP_MAEST(EXPREG,EXPNOMBRE,EXPWEB,EXPMAIL,EXPSTAND,EXPRUBROS,EXPPOSX,EXPPOSY, ESTCODIGO,EXPCATEGO,EXPPOS,EXPLINKED,
	 						EXPFAC,EXPTWI,EXPDIRECCION,EXPTELEFO,EXPYOUTUB,EXPINSTA,EXPDESAFIO1,EXPDESAFIO2,EXPDESAFIO3,EXPREUTIT,EXPREULNK,PERCODIGO,QRCODE,EXPPRODDES4)
					VALUES($expreg,$expnombre,$expweb,$expmail,$expstand,$exprubros,$expposx,$expposy,$estcodigo,$expcatego,
						   $exppos,$explinked,$expfac,$exptwi,$expdireccion,$exptelefo,$expyoutub,$expinsta,$areadesafio,$desafiodesafio,$buscamosdesafio,$expreutit,$expreulnk,$percodigocontacto,'$urlRelativeFilePath',$expbrolnk) ";
} else {
	$query = " 	UPDATE EXP_MAEST SET 
					EXPNOMBRE=$expnombre, EXPWEB=$expweb, EXPMAIL=$expmail, EXPSTAND=$expstand, EXPRUBROS=$exprubros,
					EXPPOSX=$expposx,EXPPOSY=$expposy, ESTCODIGO=$estcodigo, EXPCATEGO=$expcatego, EXPPOS=$exppos,
					EXPLINKED = $explinked, EXPFAC = $expfac, EXPTWI  = $exptwi , EXPDIRECCION = $expdireccion , EXPTELEFO = $exptelefo,
					EXPYOUTUB = $expyoutub, EXPINSTA = $expinsta, EXPDESAFIO1=$areadesafio, EXPDESAFIO2=$desafiodesafio, EXPDESAFIO3=$buscamosdesafio, EXPREUTIT=$expreutit, EXPREULNK=$expreulnk,PERCODIGO=$percodigocontacto,EXPPRODDES4=$expbrolnk
					WHERE EXPREG=$expreg ";
}
$err = sql_execute($query, $conn, $trans);

/// PERFILES RELACIONADOS
$deletePer =  "DELETE FROM EXP_PER WHERE EXPREG = $expreg";
$err =  sql_execute($deletePer,$conn,$trans);

foreach ($perfiles as $key => $value) {
	$value			= VarNullBD($value, 'N');
	if($expreg != 0){
		$inserPer =  "INSERT INTO EXP_PER(PERCODIGO,EXPREG) VALUES($value,$expreg)";
		$err = sql_execute($inserPer, $conn, $trans);
	}
	
	
}



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


	$query = "	UPDATE EXP_MAEST SET EXPBANIMG3='$name' WHERE EXPREG=$expreg ";
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
	$errmsg = ($errmsg == '') ? 'Debido a un error no se ha podido guardar los cambios!' : $errmsg;
}
//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
function convBBBdatos($texto){
	$textofin = $texto;
	$textofin = str_replace(' ','+',$textofin);
	$textofin = str_replace(':','+',$textofin);
	$textofin = str_replace('/','+',$textofin);
	
	$textofin = str_replace('á','a',$textofin);
	$textofin = str_replace('é','e',$textofin);
	$textofin = str_replace('í','i',$textofin);
	$textofin = str_replace('ó','o',$textofin);
	$textofin = str_replace('ú','u',$textofin);
	$textofin = str_replace('ñ','n',$textofin);
	
	$textofin = str_replace('Á','A',$textofin);
	$textofin = str_replace('É','E',$textofin);
	$textofin = str_replace('Í','I',$textofin);
	$textofin = str_replace('Ó','O',$textofin);
	$textofin = str_replace('Ú','U',$textofin);
	$textofin = str_replace('Ñ','N',$textofin);
	
	$textofin = str_replace('Ç','C',$textofin);
	$textofin = str_replace('ç','c',$textofin);
	
	return $textofin;
}
echo '{"errcod":"' . $errcod . '","errmsg":"' . $errmsg . '"}';






?>	




