<?php include('../val/valuser.php'); 
      include('../phpqrcode/qrlib.php');?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma
	require_once GLBRutaFUNC.'/constants.php';	

	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$errcod 	= 0;
	$errmsg 	= '';
	$err 		= 'SQLACCEPT';

		
	$pathimagenes = '../expimg/';
	$imgAvatarNull = '../app-assets/img/avatar.png';


	$pathqrimagenes = '../qrimage/';
	if (!file_exists($pathqrimagenes)) {
		mkdir($pathqrimagenes);
	}
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	$query = "SELECT PERCODIGO
	FROM PER_MAEST
	WHERE ESTCODIGO<>3";

$Table = sql_query($query,$conn);
for($i=0; $i<$Table->Rows_Count; $i++){
	$row = $Table->Rows[$i];
	$percodigo 	= trim($row['PERCODIGO']);
    
	$tempDir = $pathqrimagenes;

	
	//$codeContents = 'P||'.$percodigo;
	$codeContents = URL_WEB . 'login.php?QRCode=P||'.$percodigo;

	// we need to generate filename somehow, 
	// with md5 or with database ID used to obtains $codeContents...
	//$fileName = '005_file_'.md5($codeContents).'.png';
	$fileName = $percodigo.'_PER_'.md5($codeContents).'.png';

	$pngAbsoluteFilePath = $tempDir.$fileName;
	$urlRelativeFilePath = '../qrimage/'.$fileName;

	// generating
	if (!file_exists($pngAbsoluteFilePath)) {
		QRcode::png($codeContents, $pngAbsoluteFilePath,QR_ECLEVEL_H, 4);
		echo 'File generated!';
		echo '<hr />';
	} else {
		echo 'File already generated! We can use this cached file to speed up site on common codes!';
		echo '<hr />';
	}
	
	if($percodigo!=0){
		//Elimino el registro
		$query = "UPDATE PER_MAEST SET QRCODE='$urlRelativeFilePath' WHERE PERCODIGO=$percodigo ";
		$err = sql_execute($query,$conn,$trans);
	}

	}
	
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = 'Qr Generado!';      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? 'Error al generar QR!' : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';

?>
