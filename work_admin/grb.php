<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';

	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$workreg 		= (isset($_POST['workreg']))? trim($_POST['workreg']) : 0;
	$worktitulo 		= (isset($_POST['worktitulo']))? trim($_POST['worktitulo']) : '';
	$workdescri 		= (isset($_POST['workdescri']))? trim($_POST['workdescri']) : '';
	$workfch 		= (isset($_POST['workfch']))? trim($_POST['workfch']) : '';
	$workhorini 		= (isset($_POST['workhorini']))? trim($_POST['workhorini']) : 0;
	$workhorfin		= (isset($_POST['workhorfin']))? trim($_POST['workhorfin']) : 0;
	$spkreg			= (isset($_POST['speakers']))? trim($_POST['speakers']) : 0;
	$percodigo 		= (isset($_POST['invitados'])) ? trim($_POST['invitados']) : 0;
	$workbbb		= (isset($_POST['workbbb']))? trim($_POST['workbbb']) : '';
	$worklink		= (isset($_POST['worklink']))? trim($_POST['worklink']) : '';
	$workpdf		= (isset($_POST['workpdf']))? trim($_POST['workpdf']) : '';
	
	$estcodigo 		= 1;
	//$fecha= BDConvFch($row['FECHA']);
	if($workreg==''){
		$workreg=0;
	}
	
	if($errcod==2){
		echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
		exit;
	}

	//Perfiles relacionados
	$perfiles  =  explode(',',$percodigo);
	
	$deletePer =  "DELETE FROM WORK_PER WHERE WORK_REG = $workreg";
	$err =  sql_execute($deletePer,$conn,$trans);

	foreach ($perfiles as $key => $value) {
		$value			= VarNullBD($value, 'N');
		$inserPer =  "INSERT INTO WORK_PER(PERCODIGO,WORK_REG) VALUES($value,$workreg)";
		$err = sql_execute($inserPer, $conn, $trans);
		
	}

	//--------------------------------------------------------------------------------------------------------------
	$workreg			= VarNullBD($workreg			, 'N');
	$worktitulo		= VarNullBD($worktitulo		, 'S');
	$workdescri      = VarNullBD($workdescri		, 'S');
	$workhorini		= VarNullBD($workhorini		, 'S');
	$workhorfin		= VarNullBD($workhorfin		, 'S');
	$estcodigo		= VarNullBD($estcodigo		, 'N');
	$workbbb		= VarNullBD($workbbb		, 'S');
	$spkreg			= VarNullBD($spkreg			, 'S');
	$worklink		= VarNullBD($worklink		, 'S');
	$workpdf		= VarNullBD($workpdf		, 'S');

	 // AGREGADOS PARA GUARDAR HORARIOS SEGUN TIMEREG PASARLO A BUENOS AIRES
	 $horainicial		= substr($workhorini,2,5);
	 $horafinal		= substr($workhorfin,2,5);
	 
	 $haux = date('H:i', strtotime(-$_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($horainicial)));   // pongo en horario 0
	 $workhorini = date('H:i', strtotime('-10800 seconds', strtotime($haux))); //Pongo la hora en huso horario argentino
	 
 
	  $haux2 = date('H:i', strtotime(-$_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($horafinal)));   // pongo en horario 0
	  $workhorfin = date('H:i', strtotime('-10800 seconds', strtotime($haux2))); //Pongo la hora en huso horario argentino

	//fecha 2019-03-24 -> mm/dd/yyyy BD 
	$workfch = substr($workfch,5,2).'/'.substr($workfch,8,2).'/'.substr($workfch,0,4);
	
	if($workreg==0){
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT GEN_ID(G_AGENDA,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query,$conn,$trans);
		$RowId		= $TblId->Rows[0];			
		$workreg 	= trim($RowId['ID']);
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		$query = " 	INSERT INTO WORK_MAEST (WORK_REG, WORK_TITULO, WORK_DESCRI, WORK_FCH, WORK_HORINI, WORK_HORFIN, ESTCODIGO, SPKREG, WORK_BBB, WORK_LINK, WORK_PDF)
					VALUES  ($workreg, $worktitulo, $workdescri, '$workfch', '$workhorini', '$workhorfin',$estcodigo, $spkreg, $workbbb, $worklink, $workpdf ) ";
					//logerror($query);
					
	}else{
		$query = " UPDATE WORK_MAEST SET 
					 WORK_TITULO=$worktitulo, WORK_DESCRI=$workdescri, WORK_FCH='$workfch',WORK_HORINI='$workhorini', WORK_HORFIN='$workhorfin', 
					 ESTCODIGO=$estcodigo, SPKREG=$spkreg, WORK_BBB=$workbbb, WORK_LINK=$worklink, WORK_PDF=$workpdf
					WHERE WORK_REG=$workreg ";
	}
	
	$err = sql_execute($query,$conn,$trans);	
	
		
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = TrMessage('Guardado correctamente!');      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? 'Guardado correctamente!' : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
