<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/sendfcmmessage.php';
	require_once GLBRutaFUNC.'/sendiosmessage.php';
	require_once GLBRutaFUNC.'/class.phpmailer.php';
	require_once GLBRutaFUNC.'/class.smtp.php';
	require_once GLBRutaAPI  . '/mailchimp.php';
	require_once GLBRutaFUNC . '/constants.php';
	
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------

	$percodigo 	= (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre 	= (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$perapelli 	= (isset($_SESSION[GLBAPPPORT.'PERAPELLI']))? trim($_SESSION[GLBAPPPORT.'PERAPELLI']) : '';
	$perusuacc 	= (isset($_SESSION[GLBAPPPORT.'PERUSUACC']))? trim($_SESSION[GLBAPPPORT.'PERUSUACC']) : '';
	$percompan 	= (isset($_SESSION[GLBAPPPORT.'PERCOMPAN']))? trim($_SESSION[GLBAPPPORT.'PERCOMPAN']) : '';
	$peradmin 	= (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';
	$percorreo 	= (isset($_SESSION[GLBAPPPORT.'PERCORREO']))? trim($_SESSION[GLBAPPPORT.'PERCORREO']) : '';
	$perusareu 	= (isset($_SESSION[GLBAPPPORT.'PERUSAREU']))? trim($_SESSION[GLBAPPPORT.'PERUSAREU']) : '';
	$pertipolog = (isset($_SESSION[GLBAPPPORT.'PERTIPO']))? trim($_SESSION[GLBAPPPORT.'PERTIPO']) 	  : '';
	$perclaselog= (isset($_SESSION[GLBAPPPORT.'PERCLASE']))? trim($_SESSION[GLBAPPPORT.'PERCLASE'])   : '';
	//--------------------------------------------------------------------------------------------------------------
	
	$errcod = 0;
	$errmsg = '';
	$msgtipo5 = 'Datos compartidos / Dados compartilhados'; //Descripcion de confirmacion para solicitud enviada a un Tipo de Perfil 5
	$istipo5 = false;
	$err 	= 'SQLACCEPT';
	
	
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$percoddst 	= (isset($_POST['percoddst']))? trim($_POST['percoddst']) : 0; //Codigo de perfil al que se solicita	
	//--------------------------------------------------------------------------------------------------------------
	
	$query = "SELECT PERCORREO FROM PER_MAEST WHERE PERCODIGO=$percodigo ";
		$Table = sql_query($query,$conn);
		$row = $Table->Rows[0];
		$percorreo 	= trim($row['PERCORREO']);
	
	

				
		
			
				
				//Busco los contactos escaneados del perfil
		$query = "	SELECT P.PERCODIGO,P.PERNOMBRE,P.PERAPELLI,P.PERCOMPAN,P.PERTELEFO,P.PERCORREO,P.PERCARGO
		FROM PER_QR Q
		LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=Q.PERQRPER
		WHERE Q.PERCODIGO=$percodigo AND Q.PERQRAGE=0 AND Q.PERQRPER<100000
		ORDER BY Q.PERQRFCH DESC";

			$Table = sql_query($query,$conn);
			for($i=0; $i<$Table->Rows_Count; $i++){
			$row = $Table->Rows[$i];
			$percodigo 	= trim($row['PERCODIGO']);
			$pernombre 	= trim($row['PERNOMBRE']);
			$perapelli 	= trim($row['PERAPELLI']);
			$percompan 	= trim($row['PERCOMPAN']);
			$pertelefo 	= trim($row['PERTELEFO']);
			$percorreo 	= trim($row['PERCORREO']);
			$percargo 	= trim($row['PERCARGO']);


			$body ='<!DOCTYPE html>
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
					<font color="#FFC210">Datos Compartidos!</font>
					</b>
					<br>
				<br>	
				</div>
				<div style="text-align:center;">
								<b>'.$perapelli.' '.$pernombre.'</b>
								<br>Empresa/Company: '.$percompan.'
								<br>Telefono/Phone number: '.$pertelefo.'
								<br>Correo/email: '.$percorreo.'
								<br>Cargo/Position: '.$percargo.'
				</div><br>
										
			</div>
	</body>
	</html>
	';
	
	$mail = [
	"from_email" => SEND_MAIL_USUARIO,
	"from_name" => MAIL_NAME_APP,
	"subject" => SHARED_CONTACT,
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


		
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = 'Datos compartidos! / Contact Shared!';
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = 'Error';
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
	
?>	
