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


	$percodigo 			= (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre 			= (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$perapelli 			=(isset($_SESSION[GLBAPPPORT.'PERAPELLI']))? trim($_SESSION[GLBAPPPORT.'PERAPELLI']) : '';
	$percompan 			= (isset($_SESSION[GLBAPPPORT.'PERCOMPAN']))? trim($_SESSION[GLBAPPPORT.'PERCOMPAN']) : '';
	$perusuacc 			= (isset($_SESSION[GLBAPPPORT.'PERUSUACC']))? trim($_SESSION[GLBAPPPORT.'PERUSUACC']) : '';
	$percorreo 			= (isset($_SESSION[GLBAPPPORT.'PERCORREO']))? trim($_SESSION[GLBAPPPORT.'PERCORREO']) : '';
	$peradmin 			= (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';
	$peravatar 			= (isset($_SESSION[GLBAPPPORT.'PERAVATAR']))? trim($_SESSION[GLBAPPPORT.'PERAVATAR']) : '';
	$btnsectores 		= (isset($_SESSION[GLBAPPPORT.'SECTORES']))? trim($_SESSION[GLBAPPPORT.'SECTORES']) : '';
	$btnsubsectores 	= (isset($_SESSION[GLBAPPPORT.'SUBSECTORES']))? trim($_SESSION[GLBAPPPORT.'SUBSECTORES']) : '';
	$btncategorias 		= (isset($_SESSION[GLBAPPPORT.'CATEGORIAS']))? trim($_SESSION[GLBAPPPORT.'CATEGORIAS']) : '';
	$btnsubcategorias 	= (isset($_SESSION[GLBAPPPORT.'SUBCATEGORIAS']))? trim($_SESSION[GLBAPPPORT.'SUBCATEGORIAS']) : '';
	$pertipo 			= (isset($_SESSION[GLBAPPPORT.'PERTIPO']))? trim($_SESSION[GLBAPPPORT.'PERTIPO']) : '';
	$perclase 			= (isset($_SESSION[GLBAPPPORT.'PERCLASE']))? trim($_SESSION[GLBAPPPORT.'PERCLASE']) : '';
	$percargo 			= (isset($_SESSION[GLBAPPPORT.'PERCARGO']))? trim($_SESSION[GLBAPPPORT.'PERCARGO']) : '';
	//--------------------------------------------------------------------------------------------------------------		
	$pernomsol = $pernombre;
	$perapesol = $perapelli;
	$percomsol = $percompan;
	$percarsol = $percargo; 
		
		
	$errcod 	= 0;
	$errmsg 	= '';
	$err 		= 'SQLACCEPT';
	
	
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$percoddst 	= (isset($_POST['percoddst']))? trim($_POST['percoddst']) : 0;
	$mensaje 	= (isset($_POST['mensaje']))? trim($_POST['mensaje']) : '';



	$mensaje = str_replace("'", "", $mensaje);
	//--------------------------------------------------------------------------------------------------------------
	
	if($percodigo!=0 && $percoddst!=0 && $mensaje!=''){
		$percoddst 	= VarNullBD($percoddst , 'N');
		$query="SELECT MSGTITULO,MSGDESCRI, MSGSUB, MSGBOT, MSGLNK, MSGIMG FROM MSG_CABE WHERE MSGREG=5";
		$Table = sql_query($query,$conn);
		$row = $Table->Rows[0];
		$msgtitulo 	= trim($row['MSGTITULO']);
		$msgdescri	= trim($row['MSGDESCRI']);
		$msgsub = trim($row['MSGSUB']);
		$msgbot = trim($row['MSGBOT']);
		$msglnk = trim($row['MSGLNK']);
		$msgimg = trim($row['MSGIMG']);
		$msgdescri = htmlspecialchars_decode($msgdescri);
		$msgdescri = str_replace('class="ql-align-center"','style="text-align:center"',$msgdescri);
		$msgdescri = str_replace('class="ql-align-justify"','style="text-align:justify"',$msgdescri);
		$msgdescri = str_replace('class="ql-align-right"','style="text-align:right"',$msgdescri);

		$query="SELECT MSGREP, MSGCC, MSGCCO FROM MSG_CABE WHERE MSGREG=10";
		$Table = sql_query($query,$conn);
		$row = $Table->Rows[0];
		$msgrep 	= trim($row['MSGREP']);
		$msgcc	= trim($row['MSGCC']);
		$msgcco	= trim($row['MSGCCO']);
		
		//Busco si es el primer mensaje que envia al perfil
		$query  = "	SELECT FIRST 1 PD.PERCORREO,PD.PERNOMBRE,PD.PERAPELLI,PD.PERCOMPAN,C.CHAREG
					FROM PER_MAEST PD
					LEFT OUTER JOIN TBL_CHAT C ON C.PERCODDST=PD.PERCODIGO AND C.PERCODIGO=$percodigo
					WHERE  PD.PERCODIGO=$percoddst ";
		$Table 	= sql_query($query,$conn);		
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];			
			$pernomdst 	= trim($row['PERNOMBRE']);
			$perapedst 	= trim($row['PERAPELLI']);
			$percomdst 	= trim($row['PERCOMPAN']);
			$percordst 	= trim($row['PERCORREO']);
			$chareg 	= trim($row['CHAREG']);
			$msgdescriaux = str_replace("*|Nombre Usuario|*",$pernomdst.' '.$perapedst,$msgdescri);
			$msgdescriaux = str_replace("*|Empresa Usuario|*",$percomdst,$msgdescriaux);
			$msgdescriaux = str_replace("*|Nombre Contraparte|*",$pernomsol.' '.$perapesol,$msgdescriaux);
			$msgdescriaux = str_replace("*|Empresa Contraparte|*",$percomsol,$msgdescriaux);
			$msgdescriaux = str_replace("*|Correo Usuario|*",$percordst,$msgdescriaux);

			if($chareg==''){//Primer chat con el perfil de destino
				//Envio notificacion web

				switch($IdiomView){
					case 'ESP':
						$msgnoti = "Solicitud de chat";
						break;
						case 'POR':
							$msgnoti = "Solicitação de bate-papo";
							break;
					case 'ING':
						$msgnoti = "Chat Invitation";
						break;			
				}		

				
				$query = " INSERT INTO NOT_CABE (NOTREG, NOTFCHREG, NOTTITULO, NOTFCHLEI, PERCODDST, NOTESTADO, PERCODORI, REUREG,NOTCODIGO)
							VALUES (GEN_ID(G_NOTIFICACION,1), CURRENT_TIMESTAMP, '$msgnoti', NULL, $percoddst, 1, $percodigo, 0,5); ";
				$err = sql_execute($query,$conn);
				
				$body ='<!DOCTYPE html>
				<html lang="en" class="loading">
					<head>
						<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
					</head>
			
				<body>
				<div style="text-align:center">
					<img src=' . getUrl("/mailsimg/5/$msgimg") . ' alt="image.png" style="max-width: 800px; height:auto; margin-right:0px" data-image-whitelisted="" class="CToWUd">
					<!--app-assets/img/logo-light.png
					-->
					<br>
				</div>
				<div dir="ltr">
					<br>
					<br>
			
	
					<font color="#212121" style="font-family:arial,sans-serif;font-size:18px;">
						<div>'.$msgdescriaux.'</div>
					</font>
					
					<div style="text-align:center; margin-top: 30px;">
						<b><a style="background-color: grey;color:white;padding: 15px;font-size: 15px;border-radius:20px;text-decoration: none;" href="' . URL_WEB . 'chat/bsq">'.$msgbot.'</a></b>
					</div>
				</div>
			</body>
			</html>';
			$tagnombreevento = preg_replace('/\s+/', '-', NAME_TITLE);
							if ($msgcc!=''){
								$mail = [
									"from_email" => SEND_MAIL_USUARIO,
									"from_name" => $msgcco,
									"subject" => $msgsub,
									"html" => $body,
									"tags"=> [
										$tagnombreevento."_5"
									],
									"headers" =>[
	
										"Reply-To" => $msgrep
									],
									"to" => [
										[
											"email" => $percordst,
											"type" => "to"
										],
										[
											"email" => $msgcc,
											"type" => "cc"
										]
									]
								];

							}else{
								$mail = [
									"from_email" => SEND_MAIL_USUARIO,
									"from_name" => $msgcco,
									"subject" => $msgsub,
									"html" => $body,
									"tags"=> [
										$tagnombreevento."_5"
									],
									"headers" =>[
	
										"Reply-To" => $msgrep
									],
									"to" => [
										[
											"email" => $percordst,
											"type" => "to"
										]
									]
								];

							}
							
							
							if (filter_var($percordst, FILTER_VALIDATE_EMAIL)) {
								
								 sendMail($mail);
							   // var_dump(sendMail($mail)); die;
							}
			
			}		
		}

		$query = "	INSERT INTO TBL_CHAT (CHAREG, CHAFCHREG, PERCODIGO, PERCODDST, CHATEXTO, ESTCODIGO, CHALEIDO) 
					VALUES (GEN_ID(G_CHATS,1), CURRENT_TIMESTAMP, $percodigo, $percoddst, '$mensaje', 1, 0);";
		$err = sql_execute($query,$conn);

		$query = " 	UPDATE PER_MAEST SET 
					PERQRPUN=PERQRPUN+4
					WHERE PERCODIGO=$percodigo ";

			$err = sql_execute($query,$conn,$trans);

	}
	
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = 'Mensaje enviado!';
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = 'Error al enviar mensaje.';
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
