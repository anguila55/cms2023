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

	$pathimagenes = '../pitchimg/';
	if (!file_exists($pathimagenes)) {
		mkdir($pathimagenes);
	}
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$pitchreg 		= (isset($_POST['pitchreg'])) ? trim($_POST['pitchreg']) : 0;
	$pitchemp 		= (isset($_POST['pitchemp'])) ? trim($_POST['pitchemp']) : '';
	$pitchdes 		= (isset($_POST['pitchdes'])) ? trim($_POST['pitchdes']) : '';
	$pitchurl 		= (isset($_POST['pitchurl'])) ? trim($_POST['pitchurl']) : '';
	$pitchurlpdf 		= (isset($_POST['pitchurlpdf'])) ? trim($_POST['pitchurlpdf']) : '';
	$pitchid 		= (isset($_POST['pitchid'])) ? trim($_POST['pitchid']) : 0;
	$pitchvid 		= (isset($_POST['pitchvid'])) ? trim($_POST['pitchvid']) : '';
	$percodigo 		= (isset($_POST['percodigo'])) ? trim($_POST['percodigo']) : 0;
	$estcodigo 		= 1;
	
	
	if($pitchreg==''){
		$pitchreg=0;
	}
	if($pitchemp==''){
		$errcod=2;
		$errmsg='Falta Nombre de Empresa';
	}
	
	

	if(is_numeric($pitchid) && $pitchid!='0'){

		
	}else{
		$errcod=2;
		$errmsg='Hashtag seleecione un hashtag';

	}		
	if($errcod==2){
		echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
		exit;
	}

	//Perfiles relacionados
	
	
	$deletePer =  "DELETE FROM PITCH_PER WHERE PITCH_REG = $pitchreg";
	$err =  sql_execute($deletePer,$conn,$trans);

	
		$inserPer =  "INSERT INTO PITCH_PER(PERCODIGO,PITCH_REG) VALUES($percodigo,$pitchreg)";
		$err = sql_execute($inserPer, $conn, $trans);
		
	

	//--------------------------------------------------------------------------------------------------------------
	$pitchreg		= VarNullBD($pitchreg, 'N');
	$pitchemp	= VarNullBD($pitchemp, 'S');
	$pitchdes	= VarNullBD($pitchdes, 'S');
	$pitchurl		= VarNullBD($pitchurl, 'S');
	$estcodigo	= VarNullBD($estcodigo, 'N');
	$pitchurlpdf	= VarNullBD($pitchurlpdf, 'S');
	$pitchid		= VarNullBD($pitchid, 'N');
	$pitchvid	= VarNullBD($pitchvid, 'S');
	
	
	if($pitchreg==0){
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT GEN_ID(G_VIDEOS,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query,$conn,$trans);
		$RowId		= $TblId->Rows[0];			
		$pitchreg 	= trim($RowId['ID']);
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		$query = " 	INSERT INTO PITCH_MAEST(PITCH_REG,PITCH_EMP,PITCH_DES,PITCH_URL,ESTCODIGO,PITCH_URLPDF,PITCH_ID,PITCH_VID)
					VALUES($pitchreg,$pitchemp,$pitchdes,$pitchurl,$estcodigo,$pitchurlpdf,$pitchid,$pitchvid) ";
	}else{
		$query = " 	UPDATE PITCH_MAEST SET 
					PITCH_REG=$pitchreg,PITCH_EMP=$pitchemp, PITCH_DES=$pitchdes, PITCH_URL=$pitchurl, ESTCODIGO=$estcodigo, PITCH_URLPDF=$pitchurlpdf, PITCH_ID=$pitchid, PITCH_VID=$pitchvid
					WHERE PITCH_REG=$pitchreg ";
	}
	$err = sql_execute($query,$conn,$trans);	
	
//BANNER PERFIL
if (isset($_FILES['pitchimg'])) {
	$ext 	= pathinfo($_FILES['pitchimg']['name'], PATHINFO_EXTENSION);
	$name 	= "PITCH_IMG" . date("H-i-s.") . $ext;

	if (!file_exists($pathimagenes . $pitchreg)) {
		mkdir($pathimagenes . $pitchreg);
	}
	if (file_exists($pathimagenes . $pitchreg . '/' . $name)) {
		unlink($pathimagenes . $pitchreg . '/' . $name);
	}

	//Limpio el directorio - - - - - - - - - - - - - - - - - 
	$dirint = dir($pathimagenes . $pitchreg . '/');
	while (($archivo = $dirint->read()) !== false) {
		if (strpos($archivo, 'PITCH_IMG') !== false) {
			unlink($pathimagenes . $pitchreg . '/' . $archivo);
		}
	}
	$dirint->close();
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - 

	move_uploaded_file($_FILES['pitchimg']['tmp_name'], $pathimagenes . $pitchreg . '/' . $name);


	$query = "	UPDATE PITCH_MAEST SET PITCH_IMG='$name' WHERE PITCH_REG=$pitchreg ";
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
