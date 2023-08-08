<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/class.phpmailer.php';
	require_once GLBRutaFUNC.'/class.smtp.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
	require_once GLBRutaAPI  . '/mailchimp.php';
	require_once GLBRutaFUNC . '/constants.php';
			
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$errcod 	= 0;
	$errmsg 	= '';
	$err 		= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	
	
	//--------------------------------------------------------------------------------------------------------------
	
	//$urlweb = 'http://localhost/webcoordinador/'; //DEV
	//$urlweb = 'http://EXPOAGROeventos.com/2019/argentina/17-congreso-credito/'; //PRD
	/*	
	<div style="text-align:center">
		<b style="font-family:arial,sans-serif;font-size:16px;">
			<font color="#274e13">Click <a href="'.$urlweb.'login"> aqui </a> para ingresar a tu perfil / Click <a href="'.$urlweb.'login"> here </a> to start completing your profile</font>
		</b>
	</div>
	*/
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$percodigo 	= (isset($_POST['percodigo']))? trim($_POST['percodigo']) : 0;
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = VarNullBD($percodigo , 'N');
	
	if($percodigo!=0){
		$query = " 	SELECT PERNOMBRE,PERAPELLI,PERCORREO FROM PER_MAEST WHERE PERCODIGO=$percodigo ";
		
		$Table = sql_query($query,$conn,$trans);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			$pernombre = trim($row['PERNOMBRE']);
			$perapelli = trim($row['PERAPELLI']);
			$percorreo = trim($row['PERCORREO']);
		}
		
		//Elimino el registro
		$query = "UPDATE PER_MAEST SET ESTCODIGO=1 WHERE PERCODIGO=$percodigo ";
		$err = sql_execute($query,$conn,$trans);
		
		
		

		$body = '<!DOCTYPE html>
	<html lang="en" class="loading">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		</head>
	
	<body>
	<div style="text-align:center">
	<img src=' . getUrl("/assets-nuevodisenio/img/mail.jpg") . ' alt="image.png" style="width:25%; height:auto; margin-right:0px" data-image-whitelisted="" class="CToWUd">
		<!--app-assets/img/logo-light.png
		-->
		<br>
	</div>
	<div dir="ltr">
		<div style="text-align:left">
			<b style="font-family:arial,sans-serif;font-size:20px;">
				<font color="#FFC210">Su perfil ha sido aprobado para ingresar a BTBOX RONDAS - versión DEMO</font>
			</b>
<br>
		<br>
			
		</div>
		<font color="#212121" style="font-family:arial,sans-serif;font-size:18px;">
				<div style="text-align:left">Esta notificación anuncia al usuario que su perfil ha sido aprobado por el organizador para ingresar a la plataforma. A partir de este momento, puede acceder a todas las funcionalidades y contenidos que haya habilitado el organizador.</div>
			</font>
		</div>
		<br>
		<br>
		
		<div style="text-align:left">
			<b style="font-family:arial,sans-serif;font-size:18px;">
				<font color="#212121">Ingrese <a href="' . URL_WEB . 'login">aquí</a> y comience completando su peril.</font>
			</b>
	</div>
	</body>
	</html>';

			
						$mail = [
							"from_email" => SEND_MAIL_USUARIO,
							"from_name" => MAIL_NAME_APP,
							"subject" => SUBJECT_LIBPERFIL,
							"html" => $body,
							
							"to" => [
								[
									"email" => $percorreo,
									"type" => "to"
								]
							]
						];
						
						if (filter_var($percorreo, FILTER_VALIDATE_EMAIL)) {
							
							sendMail($mail);
						   // var_dump(sendMail($mail)); die;
						}
		
	
	}
	
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = TrMessage('Perfil liberado!');      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? TrMessage('Error al liberar el perfil!') : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
