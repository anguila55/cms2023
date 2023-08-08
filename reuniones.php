<?php include('func/valuser.php'); ?>
<?

	define("SEND_MAIL_USUARIO",'contacto@btoolbox.com');
	define("URL_WEB",'https://demo.btoolbox.com/');	
	define("MINUTOS_PREVIOS_REUNION",'10');	//Minutos previos a la reunion para enviar el mail de aviso
	//Funciones	
	require_once 'func/zglobals.php';	
	require_once 'func/zdatabase.php';
	require_once 'func/zfvarias.php';
	require_once 'api/mailchimp.php';
	//require_once 'func/constants.php';
	//--------------------------------------------------------------------------------------------------------------
	$conn	= sql_conectar();//Apertura de Conexion
		
	$query="SELECT MSGREP, MSGCC, MSGCCO FROM MSG_CABE WHERE MSGREG=10";
	$Table = sql_query($query,$conn);
	$row = $Table->Rows[0];
	$msgrep 	= trim($row['MSGREP']);
	$msgcc	= trim($row['MSGCC']);
	$msgcco	= trim($row['MSGCCO']);
	$query="SELECT MSGTITULO,MSGDESCRI, MSGSUB, MSGBOT, MSGLNK, MSGIMG FROM MSG_CABE WHERE MSGREG=11";
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
		
	//Ejecuto el generador de reservas
	$query = "	SELECT R.REUREG,R.REUFECHA,R.REUHORA,
						PS.PERCODIGO AS SOLCODIGO,PS.PERNOMBRE AS SOLNOMBRE,PS.PERAPELLI AS SOLAPELLI,PS.PERCOMPAN AS SOLCOMPAN,
						PS.PERCORREO AS SOLCORREO,PS.PERIDIOMA AS SOLIDIOMA,PS.TIMOFFSET AS SOLTIME,
						PD.PERCODIGO AS DESCODIGO,PD.PERNOMBRE AS DESNOMBRE,PD.PERAPELLI AS DESAPELLI,PD.PERCOMPAN AS DESCOMPAN,
						PD.PERCORREO AS DESCORREO,PD.PERIDIOMA AS DESIDIOMA,PD.TIMOFFSET AS DESTIME
				FROM REU_CABE R
				LEFT OUTER JOIN PER_MAEST PS ON PS.PERCODIGO=R.PERCODSOL
				LEFT OUTER JOIN PER_MAEST PD ON PD.PERCODIGO=R.PERCODDST
				LEFT OUTER JOIN NOT_CABE N ON N.REUREG=R.REUREG AND N.NOTCODIGO=900
				WHERE R.REUFECHA=CURRENT_DATE AND CURRENT_TIME BETWEEN R.REUHORA-(60*".MINUTOS_PREVIOS_REUNION.") AND R.REUHORA
					AND R.REUESTADO=2 AND N.NOTREG IS NULL ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++) {
		$row = $Table->Rows[0];
		$reureg 	= trim($row['REUREG']);
		$reufecha 	= BDConvFch($row['REUFECHA']);
		$reuhora 	= substr(trim($row['REUHORA']),0,5);
		
		$solcodigo 	= trim($row['SOLCODIGO']);
		$solnombre 	= trim($row['SOLNOMBRE']);
		$solapelli 	= trim($row['SOLAPELLI']);
		$solcompan 	= trim($row['SOLCOMPAN']);
		$solcorreo 	= trim($row['SOLCORREO']);
		$solidioma 	= trim($row['SOLIDIOMA']);
		$soltime 	= trim($row['SOLTIME']);
		
		$descodigo 	= trim($row['DESCODIGO']);
		$desnombre 	= trim($row['DESNOMBRE']);
		$desapelli 	= trim($row['DESAPELLI']);
		$descompan 	= trim($row['DESCOMPAN']);
		$descorreo 	= trim($row['DESCORREO']);
		$desidioma 	= trim($row['DESIDIOMA']);
		$destime 	= trim($row['DESTIME']);
		
		//Horario solicitante
		$haux = date('H:i', strtotime('+10800 seconds', strtotime($reuhora)));
		$haux = date('H:i', strtotime($soltime.' seconds', strtotime($haux)));
		$reuhoraSol = $haux;
	
		//Horario destinatario
		$haux = date('H:i', strtotime('+10800 seconds', strtotime($reuhora)));
		$haux = date('H:i', strtotime($destime.' seconds', strtotime($haux)));
		$reuhoraDes = $haux;
		
		//------------------------------------------------------
		//Mail al SOLICITANTE
		switch($solidioma){
			case 'ESP':
				$asunto = "Proxima Reunion: $reufecha $reuhoraSol";
				$mensaje= "En ".MINUTOS_PREVIOS_REUNION."min. aprox. tendra una reunión con $desnombre $desapelli de $descompan.
							<br>Horario: $reufecha $reuhoraSol";
				break;
			case 'ING':
				$asunto = "Next meeting: $reufecha $reuhoraSol";
				$mensaje= "In ".MINUTOS_PREVIOS_REUNION."minutes, you will attend a meeting with $desnombre $desapelli from $descompan.
							<br>Schedule: $reufecha $reuhoraSol";
				break;	
			case 'POR':
				$asunto = "Proxima reunião: $reufecha $reuhoraSol";
				$mensaje= "Em ".MINUTOS_PREVIOS_REUNION."minutos, você participará de uma reunião com $desnombre $desapelli de $descompan.
							<br>Cronograma: $reufecha $reuhoraSol";
				break;			
		}	
		$msgdescriaux = str_replace("*|Nombre Usuario|*",$solnombre.' '.$solapelli,$msgdescri);
		$msgdescriaux = str_replace("*|Nombre Contraparte|*",$desnombre.' '.$desapelli,$msgdescriaux);
		$msgdescriaux = str_replace("*|Empresa Usuario|*",$solcompan,$msgdescriaux);
		$msgdescriaux = str_replace("*|Correo Usuario|*",$solcorreo,$msgdescriaux);
		$msgdescriaux = str_replace("*|Empresa Contraparte|*",$descompan,$msgdescriaux);					
		
		$body ='<!DOCTYPE html>
			<html lang="en" class="loading">
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
				</head>
		
			<body>
			<div style="text-align:center">
				<img src=' . getUrl("/mailsimg/11/$msgimg") . ' alt="image.png" style="max-width: 800px; height:auto; margin-right:0px" data-image-whitelisted="" class="CToWUd">
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
					<b><a style="background-color: grey;color:white;padding: 15px;font-size: 15px;border-radius:20px;text-decoration: none;" href="' . URL_WEB . 'reuniones/bsq?T=1">'.$msgbot.'</a></b>
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
					$tagnombreevento."_11"
				],
				"headers" =>[

					"Reply-To" => $msgrep
				],
				"to" => [
					[
						"email" => $solcorreo,
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
					$tagnombreevento."_11"
				],
				"headers" =>[

					"Reply-To" => $msgrep
				],
				"to" => [
					[
						"email" => $solcorreo,
						"type" => "to"
					]
				]
			];


		}	
		sendMail($mail);
		
		//Marco la reunion como avisada con una notificacion (NotCodigo = 900)
		$query = "	INSERT INTO NOT_CABE (NOTREG, NOTFCHREG, NOTTITULO, NOTFCHLEI, PERCODDST, NOTESTADO, PERCODORI, REUREG, NOTCODIGO) 
					VALUES (GEN_ID(G_NOTIFICACION,1), CURRENT_TIMESTAMP, '$asunto', NULL, $solcodigo, 1, $descodigo, $reureg, 900) ";
		$err = sql_execute($query,$conn);
		//------------------------------------------------------
		
		
		//------------------------------------------------------
		//Mail al DESTINATARIO
		switch($desidioma){
			case 'ESP':
				$asunto = "Proxima Reunion: $reufecha $reuhoraDes";
				$mensaje= "En ".MINUTOS_PREVIOS_REUNION."min. aprox. tendra una reunión con $solnombre $solapelli de $solcompan.
							<br>Horario: $reufecha $reuhoraDes";	
				break;
			case 'ING':
				$asunto = "Next meeting: $reufecha $reuhoraDes";
				$mensaje= "In ".MINUTOS_PREVIOS_REUNION."minutes, you will attend a meeting with $solnombre $solapelli from $solcompan.
							<br>Horario: $reufecha $reuhoraDes";	
				break;
			case 'POR':
				$asunto = "Proxima reunião: $reufecha $reuhoraDes";
				$mensaje= "Em ".MINUTOS_PREVIOS_REUNION."minutos, você participará de uma reunião com $solnombre $solapelli de $solcompan.
							<br>Cronograma: $reufecha $reuhoraDes";
				break;				
		}	
		
		$msgdescriaux1 = str_replace("*|Nombre Usuario|*",$desnombre.' '.$desapelli,$msgdescri);
		$msgdescriaux1 = str_replace("*|Nombre Contraparte|*",$solnombre.' '.$solapelli,$msgdescriaux1);
		$msgdescriaux1 = str_replace("*|Empresa Usuario|*",$descompan,$msgdescriaux1);
		$msgdescriaux1 = str_replace("*|Correo Usuario|*",$descorreo,$msgdescriaux1);
		$msgdescriaux1 = str_replace("*|Empresa Contraparte|*",$solcompan,$msgdescriaux1);					
		
		$body1 ='<!DOCTYPE html>
			<html lang="en" class="loading">
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
				</head>
		
			<body>
			<div style="text-align:center">
				<img src=' . getUrl("/mailsimg/11/$msgimg") . ' alt="image.png" style="max-width: 800px; height:auto; margin-right:0px" data-image-whitelisted="" class="CToWUd">
				<!--app-assets/img/logo-light.png
				-->
				<br>
			</div>
			<div dir="ltr">
				
				<br>
				<br>
		
				<font color="#212121" style="font-family:arial,sans-serif;font-size:18px;">
					<div>'.$msgdescriaux1.'</div>
				</font>
				
				<div style="text-align:center">
					<b><a href="' . URL_WEB . 'reuniones/bsq?T=1">'.$msgbot.'</a></b>
				</div>
			</div>
		</body>
		</html>';
		
		if ($msgcc!=''){
			$mail = [
				"from_email" => SEND_MAIL_USUARIO,
				"from_name" => $msgcco,
				"subject" => $msgsub,
				"html" => $body1,
				"tags"=> [
					$tagnombreevento."_11"
				],
				"headers" =>[

					"Reply-To" => $msgrep
				],
				"to" => [
					[
						"email" => $descorreo,
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
				"html" => $body1,
				"tags"=> [
					$tagnombreevento."_11"
				],
				"headers" =>[

					"Reply-To" => $msgrep
				],
				"to" => [
					[
						"email" => $descorreo,
						"type" => "to"
					]
				]
			];


		}	
		sendMail($mail);
		
		//Marco la reunion como avisada con una notificacion (NotCodigo = 900)
		$query = "	INSERT INTO NOT_CABE (NOTREG, NOTFCHREG, NOTTITULO, NOTFCHLEI, PERCODDST, NOTESTADO, PERCODORI, REUREG, NOTCODIGO) 
					VALUES (GEN_ID(G_NOTIFICACION,1), CURRENT_TIMESTAMP, '$asunto', NULL, $descodigo, 1, $solcodigo, $reureg, 900) ";
		$err = sql_execute($query,$conn);
		//------------------------------------------------------
		
		
		
		
	}
		
	sql_close($conn);
	//--------------------------------------------------------------------------------------------------------------		
?>

