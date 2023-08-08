<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
	require_once GLBRutaAPI  . '/mailchimp.php';
	require_once GLBRutaFUNC . '/constants.php';

			
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$errcod 	= 0;
	$errmsg 	= '';
	$err 		= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$percodigo 	= (isset($_POST['percodigo']))? trim($_POST['percodigo']) : 0;
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = VarNullBD($percodigo , 'N');
	
	if($percodigo!=0){
		//Elimino el registro
		$query = "UPDATE PER_MAEST SET ESTCODANT=ESTCODIGO, ESTCODIGO=3 WHERE PERCODIGO=$percodigo ";
		$err = sql_execute($query,$conn,$trans);

		//Cancelo las reuniones del usuario y aviso a la contraparte
		$diaactual = date('m/d/Y');
			//Busco la reunion, para poder tomar a quien comunicar la notificacion
			$query = " 	SELECT PERCODSOL,PERCODDST, REUREG
						FROM REU_CABE R 
						WHERE (R.PERCODDST=$percodigo OR R.PERCODSOL=$percodigo) AND R.REUESTADO!=5 AND R.REUESTADO=2 AND R.REUFECHA>='$diaactual'";
			
			$Table = sql_query($query,$conn);
			for($i=0; $i<$Table->Rows_Count; $i++){
				$row = $Table->Rows[$i];
				$percodsol = trim($row['PERCODSOL']);
				$percoddst = trim($row['PERCODDST']);
				$reureg = trim($row['REUREG']);
			
				$query="SELECT MSGTITULO,MSGDESCRI, MSGSUB, MSGBOT, MSGLNK, MSGIMG FROM MSG_CABE WHERE MSGREG=8";
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



			
			if($percoddst==$percodigo){
				$percoddst = $percodsol;
			}
			
			$query = " 	UPDATE REU_CABE SET REUESTADO=3,REUFCHCAN=CURRENT_TIMESTAMP,MESCODIGO=0 WHERE REUREG=$reureg ";
			$err = sql_execute($query,$conn,$trans);
			
			$query = " 	UPDATE REU_SOLI SET REUESTADO=3 WHERE REUREG=$reureg ";
			$err = sql_execute($query,$conn,$trans);
	
	
			// VER SI TENIA MESA FLOTANTE
			// BUSCO LA MESA FLOTANTE 
			$querymesa = " 	SELECT MESCODIGO 
			FROM MES_DISP 
			WHERE REUREG=$reureg";
	
			$TableMesa = sql_query($querymesa,$conn);
			if($TableMesa->Rows_Count>0){
				
			$query=" DELETE FROM MES_DISP WHERE REUREG=$reureg ";
			$err = sql_execute($query,$conn,$trans);
			}
	
			switch($IdiomView){
				case 'ESP':
					$msgnoti = "Reunion cancelada";
					break;
				case 'POR':
					$msgnoti = "Reunião cancelada";
					break;
				case 'ING':
					$msgnoti = "Meeting cancelled";
					break;			
			}	
	
			//Inserto Notificacion de Cancelacion
			$query = " INSERT INTO NOT_CABE (NOTREG, NOTFCHREG, NOTTITULO, NOTFCHLEI, PERCODDST, NOTESTADO, PERCODORI, REUREG, NOTCODIGO)
						VALUES (GEN_ID(G_NOTIFICACION,1), CURRENT_TIMESTAMP, '$msgnoti', NULL, $percoddst, 1, $percodigo, $reureg, 3); ";
			$err = sql_execute($query,$conn,$trans);
				
			// 	//Busco los datos de la empresa que solicita la reunion
				 $query = "	SELECT PERNOMBRE,PERAPELLI,PERCOMPAN
							 FROM PER_MAEST
							WHERE PERCODIGO=$percodigo";
				 $TableOrigen = sql_query($query,$conn);
				 $pernombre = trim($TableOrigen->Rows[0]['PERNOMBRE']);
				 $perapelli = trim($TableOrigen->Rows[0]['PERAPELLI']);
				 $percompan = trim($TableOrigen->Rows[0]['PERCOMPAN']);
				
				 $titulo 	= 'Reunion cancelada';
				 $message 	= "$perapelli $pernombre de $percompan ha cancelado la reunion.";
				
		
			//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
			$querydst ="SELECT PERCORREO, PERNOMBRE, PERAPELLI, PERCOMPAN
						FROM PER_MAEST  
						WHERE PERCODIGO=$percoddst" ;
			$Table = sql_query($querydst,$conn);
			$row= $Table->Rows[0];
			$correodst 	= trim($row['PERCORREO']);
			$nombrerecibe 	= trim($row['PERNOMBRE']);
			$apellidorecibe 	= trim($row['PERAPELLI']);
			$empresarecibe 	= trim($row['PERCOMPAN']);
				//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
			//Envio Mail de Reunion Cancelada al Destino
		
			if($correodst!=''){
				
				
					$msgdescriaux = str_replace("*|Nombre Usuario|*",$nombrerecibe.' '.$apellidorecibe,$msgdescri);
					$msgdescriaux = str_replace("*|Nombre Contraparte|*",$pernombre.' '.$perapelli,$msgdescriaux);
					$msgdescriaux = str_replace("*|Correo Usuario|*",$correodst,$msgdescriaux);
					$msgdescriaux = str_replace("*|Empresa Usuario|*",$empresarecibe,$msgdescriaux);
				$msgdescriaux = str_replace("*|Empresa Contraparte|*",$percompan,$msgdescriaux);
				
				$body ='<!DOCTYPE html>
				<html lang="en" class="loading">
					<head>
						<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
					</head>
			
				<body>
				<div style="text-align:center">
					<img src=' . getUrl("/mailsimg/8/$msgimg") . ' alt="image.png" style="max-width: 800px; height:auto; margin-right:0px" data-image-whitelisted="" class="CToWUd">
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
						<b><a style="background-color: grey;color:white;padding: 15px;font-size: 15px;border-radius:20px;text-decoration: none;" href="' . URL_WEB . 'reuniones/bsq?T=3">'.$msgbot.'</a></b>
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
						$tagnombreevento."_8"
					],
					"headers" =>[
	
						"Reply-To" => $msgrep
					],
					"to" => [
						[
							"email" => $correodst,
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
						$tagnombreevento."_8"
					],
					"headers" =>[
	
						"Reply-To" => $msgrep
					],
					"to" => [
						[
							"email" => $correodst,
							"type" => "to"
						]
					]
				];
	
	
			}
				
				
				if (filter_var($correodst, FILTER_VALIDATE_EMAIL)) {
					
					sendMail($mail);
				   
				}
				
			}
		}

		}
		//Cancelo las reuniones del usuario y aviso a la contraparte

		//Elimino la mesa asignada a el
		$query = "UPDATE MES_MAEST SET ESTCODIGO=3 WHERE PERCODIGO=$percodigo ";
		$err = sql_execute($query,$conn,$trans);
	
	
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = TrMessage('Perfil eliminado!');      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? TrMessage('Error al eliminar el perfil!') : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
