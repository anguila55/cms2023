<?php include('../val/valuser.php');
include('../phpqrcode/qrlib.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
	require_once GLBRutaFUNC . '/constants.php';

			
	//--------------------------------------------------------------------------------------------------------------

	
	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';

	$pathqrimagenes = '../qrimage/';
	if (!file_exists($pathqrimagenes)) {
		mkdir($pathqrimagenes);
	}

	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$agereg 		= (isset($_POST['agereg']))? trim($_POST['agereg']) : 0;
	$agetitulo 		= (isset($_POST['agetitulo']))? trim($_POST['agetitulo']) : '';
	$agetitulo1=$agetitulo;
	$agedescri 		= (isset($_POST['agedescri']))? trim($_POST['agedescri']) : '';
	$agetituloing 		= (isset($_POST['agetituloing']))? trim($_POST['agetituloing']) : '';
	$agedescriing 		= (isset($_POST['agedescriing']))? trim($_POST['agedescriing']) : '';
	$agelugar 		= (isset($_POST['agelugar']))? trim($_POST['agelugar']) : '';
	$agefch 		= (isset($_POST['agefch']))? trim($_POST['agefch']) : '';
	$agehorini 		= (isset($_POST['agehorini']))? trim($_POST['agehorini']) : 0;
	$agehorfin		= (isset($_POST['agehorfin']))? trim($_POST['agehorfin']) : 0;
	$spkreg			= (isset($_POST['speakers']))? trim($_POST['speakers']) : 0;
	$ageprehab		= (isset($_POST['ageprehab']))? trim($_POST['ageprehab']) : 'N';
	$ageyoulnk		= (isset($_POST['ageyoulnk']))? trim($_POST['ageyoulnk']) : '';
	$ageyoulnking		= (isset($_POST['ageyoulnking']))? trim($_POST['ageyoulnking']) : '';
	$ageyoulnkpor		= (isset($_POST['ageyoulnkpor']))? trim($_POST['ageyoulnkpor']) : '';
	
	$estcodigo 		= 1;
	//$fecha= BDConvFch($row['FECHA']);
	if($agereg==''){
		$agereg=0;
	}
	
	if($errcod==2){
		echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
		exit;
	}
	//--------------------------------------------------------------------------------------------------------------
	$agereg			= VarNullBD($agereg			, 'N');
	$agetitulo		= VarNullBD($agetitulo		, 'S');
	$agedescri      = VarNullBD($agedescri		, 'S');
	$agetituloing		= VarNullBD($agetituloing		, 'S');
	$agedescriing      = VarNullBD($agedescriing		, 'S');
	$agelugar       = VarNullBD($agelugar		, 'S');
	$agehorini		= VarNullBD($agehorini		, 'S');
	$agehorfin		= VarNullBD($agehorfin		, 'S');
	$estcodigo		= VarNullBD($estcodigo		, 'N');
	$ageprehab		= VarNullBD($ageprehab		, 'S');
	$spkreg			= VarNullBD($spkreg			, 'S');
	$ageyoulnk		= VarNullBD($ageyoulnk		, 'S');
	$ageyoulnking		= VarNullBD($ageyoulnking		, 'S');
	$ageyoulnkpor		= VarNullBD($ageyoulnkpor		, 'S');


	 // AGREGADOS PARA GUARDAR HORARIOS SEGUN TIMEREG PASARLO A BUENOS AIRES
	 $horainicial		= substr($agehorini,2,5);
	 $horafinal		= substr($agehorfin,2,5);
	 
	 $haux = date('H:i', strtotime(-$_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($horainicial)));   // pongo en horario 0
	 $agehorini = date('H:i', strtotime('-10800 seconds', strtotime($haux))); //Pongo la hora en huso horario argentino
	 
 
	  $haux2 = date('H:i', strtotime(-$_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($horafinal)));   // pongo en horario 0
	  $agehorfin = date('H:i', strtotime('-10800 seconds', strtotime($haux2))); //Pongo la hora en huso horario argentino

	//fecha 2019-03-24 -> mm/dd/yyyy BD 
	$agefch = substr($agefch,5,2).'/'.substr($agefch,8,2).'/'.substr($agefch,0,4);

	
	
	if($agereg==0){
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT GEN_ID(G_AGENDA,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query,$conn,$trans);
		$RowId		= $TblId->Rows[0];			
		$agereg 	= trim($RowId['ID']);

		$tempDir = $pathqrimagenes;

	
			$codeContents = URL_WEB . 'login.php?QRCode=A||'.$agereg;

			// we need to generate filename somehow, 
			// with md5 or with database ID used to obtains $codeContents...
			$userName 	= convBBBdatos($agetitulo1);
			$fileName = $agereg.'_AGE_'.$userName.'_'.md5($codeContents).'.png';

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
		
		$query = " 	INSERT INTO AGE_MAEST (AGEREG, AGETITULO, AGETITING, AGEDESCRI, AGEDESING, AGELUGAR, AGEFCH, AGEHORINI, AGEHORFIN, ESTCODIGO, SPKREG, AGEPREHAB,AGEYOULNK,AGEYOULNKING,AGEYOULNKPOR,QRCODE)
					VALUES  ($agereg, $agetitulo, $agetituloing, $agedescri, $agedescriing, $agelugar, '$agefch', '$agehorini', '$agehorfin',$estcodigo, $spkreg, $ageprehab, $ageyoulnk,$ageyoulnking,$ageyoulnkpor,'$urlRelativeFilePath') ";
					//$agefch	= substr($agefch,6,4).'-'.substr($agefch,3,2).'-'.substr($agefch,0,2);
					//logerror($query);
					
	}else{
		$query = " UPDATE AGE_MAEST SET 
					 AGETITULO=$agetitulo, AGETITING=$agetituloing,  AGEDESCRI=$agedescri, AGEDESING=$agedescriing, AGELUGAR=$agelugar, AGEFCH='$agefch',AGEHORINI='$agehorini', AGEHORFIN='$agehorfin', 
					 ESTCODIGO=$estcodigo, SPKREG=$spkreg, AGEPREHAB=$ageprehab, AGEYOULNK=$ageyoulnk, AGEYOULNKING=$ageyoulnking,AGEYOULNKPOR=$ageyoulnkpor
					WHERE AGEREG=$agereg ";
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
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
