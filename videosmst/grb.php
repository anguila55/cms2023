<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';

	$pathimagenes = '../vidimg/';
	if (!file_exists($pathimagenes)) {
		mkdir($pathimagenes);
	}
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$vidreg 		= (isset($_POST['vidreg'])) ? trim($_POST['vidreg']) : 0;
	$vidtitulo 		= (isset($_POST['vidtitulo'])) ? trim($_POST['vidtitulo']) : '';
	$vidtitulocarta 		= (isset($_POST['vidtitulocarta'])) ? trim($_POST['vidtitulocarta']) : '';
	$vidurl 		= (isset($_POST['vidurl'])) ? trim($_POST['vidurl']) : '';
	$vidurlpdf 		= (isset($_POST['vidurlpdf'])) ? trim($_POST['vidurlpdf']) : '';
	$vidid 		= (isset($_POST['vidid'])) ? trim($_POST['vidid']) : 0;
	$usnombre 		= (isset($_POST['usnombre'])) ? trim($_POST['usnombre']) : '';
	$usmail 		= (isset($_POST['usmail'])) ? trim($_POST['usmail']) : '';
	$ustelefono 		= (isset($_POST['ustelefono'])) ? trim($_POST['ustelefono']) : '';
	$usempresa 		= (isset($_POST['usempresa'])) ? trim($_POST['usempresa']) : '';
	$uspais 		= (isset($_POST['uspais'])) ? trim($_POST['uspais']) : '';
	$estcodigo 		= 1;
	$uslinkedin 		= (isset($_POST['uslinkedin'])) ? trim($_POST['uslinkedin']) : '';
	$usfacebook		= (isset($_POST['usfacebook'])) ? trim($_POST['usfacebook']) : '';
	$ustwitter			= (isset($_POST['ustwitter'])) ? trim($_POST['ustwitter']) : '';
	$usweb			= (isset($_POST['usweb'])) ? trim($_POST['usweb']) : '';
	$vidcatego		= (isset($_POST['vidcatego'])) ? trim($_POST['vidcatego']) : '';
	
	if($vidreg==''){
		$vidreg=0;
	}
	if($vidtitulo==''){
		$errcod=2;
		$errmsg='Falta el titulo';
	}
	
	

	if(is_numeric($vidid) || $vidid==''){
		
	}else{
		$errcod=2;
		$errmsg='Hashtag se ingresa un numero';

	}		
	if($errcod==2){
		echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
		exit;
	}
	//--------------------------------------------------------------------------------------------------------------
	$vidreg		= VarNullBD($vidreg, 'N');
	$vidtitulo	= VarNullBD($vidtitulo, 'S');
	$vidtitulocarta	= VarNullBD($vidtitulocarta, 'S');
	$vidurl		= VarNullBD($vidurl, 'S');
	$estcodigo	= VarNullBD($estcodigo, 'N');
	$vidurlpdf	= VarNullBD($vidurlpdf, 'S');
	$vidid		= VarNullBD($vidid, 'N');
	$usnombre	= VarNullBD($usnombre, 'S');
	$usmail		= VarNullBD($usmail, 'S');
	$ustelefono	= VarNullBD($ustelefono, 'S');
	$usempresa	= VarNullBD($usempresa, 'S');
	$uspais		= VarNullBD($uspais, 'S');
	$uslinkedin	= VarNullBD($uslinkedin, 'S');
	$usfacebook	= VarNullBD($usfacebook, 'S');
	$ustwitter	= VarNullBD($ustwitter, 'S');
	$usweb		= VarNullBD($usweb, 'S');
	$vidcatego		= VarNullBD($vidcatego, 'S');
	
	if($vidreg==0){
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT GEN_ID(G_VIDEOS,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query,$conn,$trans);
		$RowId		= $TblId->Rows[0];			
		$vidreg 	= trim($RowId['ID']);
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		$query = " 	INSERT INTO VID_MAEST(VIDREG,VIDTITULO,VIDTITULOCARTA,VIDURL,ESTCODIGO,VIDURLPDF,VIDID,US_NOMBRE,US_MAIL,US_TEL,US_EMP,US_PAI,US_LIN,US_FAC,US_TWI,US_WEB,VIDCATEGO)
					VALUES($vidreg,$vidtitulo,$vidtitulocarta,$vidurl,$estcodigo,$vidurlpdf,$vidid,$usnombre,$usmail,$ustelefono,$usempresa,$uspais,$uslinkedin,$usfacebook,$ustwitter,$usweb,$vidcatego) ";
	}else{
		$query = " 	UPDATE VID_MAEST SET 
					VIDREG=$vidreg,VIDTITULO=$vidtitulo, VIDTITULOCARTA=$vidtitulocarta, VIDURL=$vidurl, ESTCODIGO=$estcodigo, VIDURLPDF=$vidurlpdf, VIDID=$vidid, US_NOMBRE=$usnombre, US_MAIL=$usmail, US_TEL=$ustelefono, US_EMP=$usempresa, US_PAI=$uspais, US_LIN=$uslinkedin, US_FAC=$usfacebook, US_TWI=$ustwitter, US_WEB=$usweb, VIDCATEGO = $vidcatego
					WHERE VIDREG=$vidreg ";
	}
	$err = sql_execute($query,$conn,$trans);	
	
//BANNER PERFIL
if (isset($_FILES['vidimg'])) {
	$ext 	= pathinfo($_FILES['vidimg']['name'], PATHINFO_EXTENSION);
	$name 	= "VID_IMAGEN" . date("H-i-s.") . $ext;

	if (!file_exists($pathimagenes . $vidreg)) {
		mkdir($pathimagenes . $vidreg);
	}
	if (file_exists($pathimagenes . $vidreg . '/' . $name)) {
		unlink($pathimagenes . $vidreg . '/' . $name);
	}

	//Limpio el directorio - - - - - - - - - - - - - - - - - 
	$dirint = dir($pathimagenes . $vidreg . '/');
	while (($archivo = $dirint->read()) !== false) {
		if (strpos($archivo, 'VID_IMAGEN') !== false) {
			unlink($pathimagenes . $vidreg . '/' . $archivo);
		}
	}
	$dirint->close();
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - 

	move_uploaded_file($_FILES['vidimg']['tmp_name'], $pathimagenes . $vidreg . '/' . $name);


	$query = "	UPDATE VID_MAEST SET VIDIMG='$name' WHERE VIDREG=$vidreg ";
	$err = sql_execute($query, $conn, $trans);
}

//--------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = TrMessage('Guardado correctamente!');      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? TrMessage('Guardado correctamente!') : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
