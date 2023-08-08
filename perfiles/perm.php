<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC . '/class.phpmailer.php';
require_once GLBRutaFUNC . '/class.smtp.php';
require_once GLBRutaFUNC . '/idioma.php'; //Idioma	
require_once GLBRutaAPI  . '/mailchimp.php';
require_once GLBRutaFUNC . '/constants.php';

//--------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------
$errcod 	= 0;
$errmsg 	= '';
$err 		= 'SQLACCEPT';





//--------------------------------------------------------------------------------------------------------------
$conn = sql_conectar(); //Apertura de Conexion
$trans	= sql_begin_trans($conn);


//Control de Datos
// $percodigo 	= (isset($_POST['percodigo'])) ? trim($_POST['percodigo']) : 0;
$permiso 	= (isset($_POST['permiso'])) ? trim($_POST['permiso']) : 0;
$setPermisoUsuarios = (isset($_POST['setusuarios'])) ? trim($_POST['setusuarios']) : 0;
$seleccion = (isset($_POST['seleccion'])) ? trim($_POST['seleccion']) : 0;
$opcion   = (isset($_POST['opcion'])) ? trim($_POST['opcion']) : 0;
$email = 0;
$envioEmail = 0;

$fltnombre 		= (isset($_POST['fltnombre']))? trim($_POST['fltnombre']):'';
	$fltusuacc 		= (isset($_POST['fltusuacc']))? trim($_POST['fltusuacc']):'';
	$fltapelli 		= (isset($_POST['fltapelli']))? trim($_POST['fltapelli']):'';
	$fltcompan 		= (isset($_POST['fltcompan']))? trim($_POST['fltcompan']):'';
	$fltcorreo 		= (isset($_POST['fltcorreo']))? trim($_POST['fltcorreo']):'';
	$fltorden 		= (isset($_POST['fltorden']))? trim($_POST['fltorden']):'';
	$fltordentipo 	= (isset($_POST['fltordentipo']))? trim($_POST['fltordentipo']):'';
	$fltestado 		= (isset($_POST['fltestado']))? trim($_POST['fltestado']):'';
	$fltpertipo 	= (isset($_POST['fltpertipo']))? trim($_POST['fltpertipo']):'';
	$fltperclase 	= (isset($_POST['fltperclase']))? trim($_POST['fltperclase']):'';
 
	

	$where = ' 1=1 ';
	//Nombre
	if($fltusuacc!=''){
		$where .= " AND PERUSUACC CONTAINING '$fltusuacc' ";
	}
	//Nombre
	if($fltnombre!=''){
		$where .= " AND PERNOMBRE CONTAINING '$fltnombre' ";
	}
	//Correo
	if($fltcorreo!=''){
		$where .= " AND PERCORREO CONTAINING '$fltcorreo' ";
	}
	//Apellido
	if($fltapelli!=''){
		$where .= " AND PERAPELLI CONTAINING '$fltapelli' ";
	}
	//Compañia
	if($fltcompan!=''){
		$where .= " AND PERCOMPAN CONTAINING '$fltcompan' ";
	}
	//Estado
	if($fltestado!=''){
		if ($fltestado == 0){

			$where .= " AND ESTCODIGO!=3 ";
			
		}else{

			$where .= " AND ESTCODIGO=$fltestado ";

		}
		
	}
	//Tipo de Perfiles
	if($fltpertipo!=''){
		$where .= " AND PERTIPO=$fltpertipo ";
	}
	//Clase de Perfiles
	if($fltperclase!=''){
		$where .= " AND PERCLASE=$fltperclase ";
	}

	




//--------------------------------------------------------------------------------------------------------------
// $percodigo = VarNullBD($percodigo, 'N');



//ANCHOR APLICO PERMISOS A TRAVES DE LAS  LAS SELECCIONES------------------------------------------------------
if ($seleccion == 1) {

	switch ($opcion) {
		case 'D': //Permiso de Disponibilidad
			$updpermiso = ' PERUSADIS=1 ';
			break;
		case 'R': //Permiso de Solicitud de Reuniones
			$updpermiso = ' PERUSAREU=1';
			$email = 1;
			break;
		case 'M': //Permiso de Mensajeria
			// $updpermiso = ' PERUSAMSG=CASE WHEN COALESCE(PERUSAMSG,0)=1 THEN 0 ELSE 1 END ';
			$updpermiso = ' PERUSAMSG=1';
			break;
		case 'L': //Permiso de Mensajeria
			// $updpermiso = ' PERUSAMSG=CASE WHEN COALESCE(PERUSAMSG,0)=1 THEN 0 ELSE 1 END ';
			$updpermiso = ' ESTCODIGO=1';
			$email = 2;
			break;
		case 'MA': //Permiso de Mensajeria
			// $updpermiso = ' PERUSAMSG=CASE WHEN COALESCE(PERUSAMSG,0)=1 THEN 0 ELSE 1 END ';
			$updpermiso = ' ESTCODIGO=8';
			$email = 3;
			break;

		case 'GA': //Permiso de Mensajeria
				// $updpermiso = ' PERUSAMSG=CASE WHEN COALESCE(PERUSAMSG,0)=1 THEN 0 ELSE 1 END ';
				$updpermiso = '';
				$email = 4;
				break;
			//QUITAR PERMISOS 
		case 'QD': //Permiso de Disponibilidad

			$updpermiso = ' PERUSADIS = 0 ';
			break;
		case 'QR': //Permiso de Reuniones

			$updpermiso = ' PERUSAREU = 0';
			break;
		case 'QM': //Permiso de Mensajeria

			$updpermiso = ' PERUSAMSG = 0';
			break;
	}

	//------------------------------------------------------------------------------------
} else if ($seleccion == 0) {

	switch ($opcion) {
		case 'D': //Permiso de Disponibilidad
			$updpermiso = ' PERUSADIS=CASE WHEN COALESCE(PERUSADIS,0)=1 THEN 0 ELSE 1 END ';
			break;
		case 'R': //Permiso de Solicitud de Reuniones
			$email = 1;
			$updpermiso = ' PERUSAREU=CASE WHEN COALESCE(PERUSAREU,0)=1 THEN 0 ELSE 1 END ';
			break;
		case 'M': //Permiso de Mensajeria
			$updpermiso = ' PERUSAMSG=CASE WHEN COALESCE(PERUSAMSG,0)=1 THEN 0 ELSE 1 END ';
			break;
		case 'L': //Permiso de Liberar
			$updpermiso = ' ESTCODIGO=CASE WHEN COALESCE(ESTCODIGO,8)=1 THEN 8 ELSE 1 END ';
			$email = 2;
			break;
		case 'MA': //Permiso de Liberar
			$updpermiso = ' ESTCODIGO=8';
			$email = 3;
			break;	
		case 'GA': //Permiso de Liberar
			$updpermiso = '';
			$email = 4;
			break;
	}
}

//$array=null;
$query2 = " SELECT ZDESCRI FROM ZZZ_CONF WHERE ZPARAM = 'SisCorreoLinkAppStore'";
	$Table2 = sql_query($query2, $conn);
	for ($i = 0; $i < $Table2->Rows_Count; $i++) {
		$row = $Table2->Rows[$i];
		$evefch = trim($row['ZDESCRI']);	
	}

	$query2 = " SELECT ZDESCRI FROM ZZZ_CONF WHERE ZPARAM = 'SisCorreoLinkPlayStore'";
	$Table2 = sql_query($query2, $conn);
	for ($i = 0; $i < $Table2->Rows_Count; $i++) {
		$row = $Table2->Rows[$i];
		$evefchfin = trim($row['ZDESCRI']);
		
	}
	$evefch = substr($evefch, 8, 2).'-'.substr($evefch,5,2).'-'.substr($evefch,0,4);
	$evefchfin = substr($evefchfin, 8, 2).'-'.substr($evefchfin,5,2).'-'.substr($evefchfin,0,4);
	
		$query="SELECT MSGTITULO,MSGDESCRI, MSGSUB, MSGBOT, MSGLNK, MSGIMG FROM MSG_CABE WHERE MSGREG=3";
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

		$query="SELECT MSGTITULO,MSGDESCRI, MSGSUB, MSGBOT, MSGLNK, MSGIMG FROM MSG_CABE WHERE MSGREG=4";
		$Table = sql_query($query,$conn);
		$row = $Table->Rows[0];
		$msgtitulo1 	= trim($row['MSGTITULO']);
		$msgdescri1	= trim($row['MSGDESCRI']);
		$msgsub1 = trim($row['MSGSUB']);
		$msgbot1 = trim($row['MSGBOT']);
		$msglnk1 = trim($row['MSGLNK']);
		$msgimg1 = trim($row['MSGIMG']);
		$msgdescri1 = htmlspecialchars_decode($msgdescri1);
		$msgdescri1 = str_replace('class="ql-align-center"','style="text-align:center"',$msgdescri1);
		$msgdescri1 = str_replace('class="ql-align-justify"','style="text-align:justify"',$msgdescri1);
		$msgdescri1 = str_replace('class="ql-align-right"','style="text-align:right"',$msgdescri1);

		$query="SELECT MSGTITULO,MSGDESCRI, MSGSUB, MSGBOT, MSGLNK, MSGIMG FROM MSG_CABE WHERE MSGREG=13";
		$Table = sql_query($query,$conn);
		$row = $Table->Rows[0];
		$msgtitulo2 	= trim($row['MSGTITULO']);
		$msgdescri2	= trim($row['MSGDESCRI']);
		$msgsub2 = trim($row['MSGSUB']);
		$msgbot2 = trim($row['MSGBOT']);
		$msglnk2 = trim($row['MSGLNK']);
		$msgimg2 = trim($row['MSGIMG']);
		$msgdescri2 = htmlspecialchars_decode($msgdescri2);
		$msgdescri2 = str_replace('class="ql-align-center"','style="text-align:center"',$msgdescri2);
		$msgdescri2 = str_replace('class="ql-align-justify"','style="text-align:justify"',$msgdescri2);
		$msgdescri2 = str_replace('class="ql-align-right"','style="text-align:right"',$msgdescri2);
		

		$query="SELECT MSGREP, MSGCC, MSGCCO FROM MSG_CABE WHERE MSGREG=10";
		$Table = sql_query($query,$conn);
		$row = $Table->Rows[0];
		$msgrep 	= trim($row['MSGREP']);
		$msgcc	= trim($row['MSGCC']);
		$msgcco	= trim($row['MSGCCO']);

		$tagnombreevento = preg_replace('/\s+/', '-', NAME_TITLE);
if (array_search(999999, $_POST['percodigo'][0])!='id'){
	foreach ($_POST['percodigo'] as $index => $data) {

		$percodigo = trim($data['id']);
		
		//Si permiso de reunion 
		if ($email == 1) {
	
			$query = "SELECT PERCODIGO, PERNOMBRE,PERAPELLI,PERCORREO,PERCOMPAN FROM PER_MAEST WHERE PERUSAREU = 0 AND PERCODIGO = $percodigo";
			$Table = sql_query($query, $conn, $trans);
	
			$rows  = $Table->Rows_Count;
	
			if ($rows != -1) {
				$row = $Table->Rows[0];
				$pernombre = trim($row['PERNOMBRE']);
				$perapelli = trim($row['PERAPELLI']);
				$percorreo = trim($row['PERCORREO']);
				$percompan = trim($row['PERCOMPAN']);
				$msgdescriaux = str_replace("*|Nombre Usuario|*",$pernombre.' '.$perapelli,$msgdescri);
				$msgdescriaux = str_replace("*|Empresa Usuario|*",$percompan,$msgdescriaux);
    			$msgdescriaux = str_replace("*|Correo Usuario|*",$percorreo,$msgdescriaux);
				//Seteamos email a 3 porque al poner mail->send dentro del bucle va a enviar por cada iteracion.
				$envioEmail = 1;
				$body = '<!DOCTYPE html>
				<html lang="en" class="loading">
					<head>
						<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
					</head>
				
				<body>
				<div style="text-align:center">
				<img src=' . getUrl("/mailsimg/3/$msgimg") . ' alt="image.png" style="max-width: 800px; height:auto; margin-right:0px" data-image-whitelisted="" class="CToWUd">
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
						<b>  <a style="background-color: grey;color:white;padding: 15px;font-size: 15px;border-radius:20px;text-decoration: none;" href="' . URL_WEB . 'login">'.$msgbot.'</a></b> 
					</div>
							</div>
			</body>
			</html>';
	
				$mail = [
					"from_email" => SEND_MAIL_USUARIO,
					"from_name" => $msgcco,
					"subject" => $msgsub,
					"preserve_recipients" => FALSE,
					"html" => $body,
					"tags"=> [
						$tagnombreevento."_3"
					],
					"headers" =>[
	
						"Reply-To" => $msgrep
					],
					"to" => [
						[
							"email" => $percorreo,
							"type" => "to"
						]
					]
				];
	
	
			
		
		
			
			sendMail($mail);
				//$array[]=["email"=>$percorreo,"type"=>"to"];
			}
		}
		//Si permiso de reunion 
		if ($email == 2) {
	
			$query = "SELECT PERCODIGO, PERNOMBRE,PERAPELLI,PERCORREO,PERCOMPAN FROM PER_MAEST WHERE ESTCODIGO = 8 AND PERCODIGO = $percodigo";
			$Table = sql_query($query, $conn, $trans);
	
			$rows  = $Table->Rows_Count;
	
			if ($rows != -1) {
				$row = $Table->Rows[0];
				$pernombre = trim($row['PERNOMBRE']);
				$perapelli = trim($row['PERAPELLI']);
				$percorreo = trim($row['PERCORREO']);
				$percompan = trim($row['PERCOMPAN']);
				$msgdescriaux1 = str_replace("*|Nombre Usuario|*",$pernombre.' '.$perapelli,$msgdescri1);
				$msgdescriaux1 = str_replace("*|Empresa Usuario|*",$percompan,$msgdescriaux1);
    			$msgdescriaux1 = str_replace("*|Correo Usuario|*",$percorreo,$msgdescriaux1);
	
				//Seteamos email a 3 porque al poner mail->send dentro del bucle va a enviar por cada iteracion.
				$envioEmail = 2;
				$body = '<!DOCTYPE html>
				<html lang="en" class="loading">
					<head>
						<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
					</head>
				
				<body>
				<div style="text-align:center">
				<img src=' . getUrl("/mailsimg/4/$msgimg1") . ' alt="image.png" style="max-width: 800px; height:auto; margin-right:0px" data-image-whitelisted="" class="CToWUd">
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
					<div style="text-align:center; margin-top: 30px;">
						<b>  <a style="background-color: grey;color:white;padding: 15px;font-size: 15px;border-radius:20px;text-decoration: none;" href="' . URL_WEB . 'login">'.$msgbot1.'</a></b> 
					</div>
					<div style="text-align:center; margin-top:10px;">
						<a style="background-color: #286efa!important;border: none;color: white;padding: 15px 32px;text-align:center;text-decoration: none;display: inline-block;border-radius: 8px;font-size: 16px;" href="https://www.addevent.com/dir/?client=atcUIPxJKzRJHYfkDmWv110113&start='.$evefch.'&end='.$evefchfin.'&title='.NAME_TITLE.'&location='.URL_WEB.'">Add to Calendar</a>
					</div>
							</div>
			</body>
			</html>';
	
			
				$mail = [
					"from_email" => SEND_MAIL_USUARIO,
			"from_name" => $msgcco,
			"subject" => $msgsub1,
			"preserve_recipients" => FALSE,
			"html" => $body,
			"tags"=> [
				$tagnombreevento."_4"
			],
					"headers" =>[
	
						"Reply-To" => $msgrep
					],
					"to" => [
						[
							"email" => $percorreo,
							"type" => "to"
						]
					]
				];
	
	
			
	
		
			
			sendMail($mail);
				//$array[]=["email"=>$percorreo,"type"=>"to"];
			}
		}
		if ($email == 3) {
	
			$query = "SELECT PERCODIGO, PERNOMBRE,PERAPELLI,PERCORREO,PERCOMPAN FROM PER_MAEST WHERE ESTCODIGO = 9 AND PERCODIGO = $percodigo";
			$Table = sql_query($query, $conn, $trans);
	
			$rows  = $Table->Rows_Count;
	
			if ($rows != -1) {
				$row = $Table->Rows[0];
				$pernombre = trim($row['PERNOMBRE']);
				$perapelli = trim($row['PERAPELLI']);
				$percorreo = trim($row['PERCORREO']);
				$percompan = trim($row['PERCOMPAN']);
				$msgdescriaux1 = str_replace("*|Nombre Usuario|*",$pernombre.' '.$perapelli,$msgdescri1);
				$msgdescriaux1 = str_replace("*|Empresa Usuario|*",$percompan,$msgdescriaux1);
    			$msgdescriaux1 = str_replace("*|Correo Usuario|*",$percorreo,$msgdescriaux1);
	
				//Seteamos email a 3 porque al poner mail->send dentro del bucle va a enviar por cada iteracion.
				$envioEmail = 3;
				$body = '<!DOCTYPE html>
				<html lang="en" class="loading">
					<head>
						<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
					</head>
				
				<body>
				<div style="text-align:center">
				<img src=' . getUrl("/mailsimg/4/$msgimg1") . ' alt="image.png" style="max-width: 800px; height:auto; margin-right:0px" data-image-whitelisted="" class="CToWUd">
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
					<div style="text-align:center; margin-top: 30px;">
						<b>  <a style="background-color: grey;color:white;padding: 15px;font-size: 15px;border-radius:20px;text-decoration: none;" href="' . URL_WEB . 'login">'.$msgbot1.'</a></b> 
					</div>
					<div style="text-align:center; margin-top:10px;">
						<a style="background-color: #286efa!important;border: none;color: white;padding: 15px 32px;text-align:center;text-decoration: none;display: inline-block;border-radius: 8px;font-size: 16px;" href="https://www.addevent.com/dir/?client=atcUIPxJKzRJHYfkDmWv110113&start='.$evefch.'&end='.$evefchfin.'&title='.NAME_TITLE.'&location='.URL_WEB.'">Add to Calendar</a>
					</div>
							</div>
			</body>
			</html>';
	
			
				$mail = [
					"from_email" => SEND_MAIL_USUARIO,
			"from_name" => $msgcco,
			"subject" => $msgsub1,
			"preserve_recipients" => FALSE,
			"html" => $body,
			"tags"=> [
				$tagnombreevento."_4"
			],
					"headers" =>[
	
						"Reply-To" => $msgrep
					],
					"to" => [
						[
							"email" => $percorreo,
							"type" => "to"
						]
					]
				];
	
	
			
			
			sendMail($mail);
				//$array[]=["email"=>$percorreo,"type"=>"to"];
			}
		}

		// EMAIL 4 GAFETE //
		if ($email == 4) {
	
			$query = "SELECT PERCODIGO, PERNOMBRE,PERAPELLI,PERCORREO,PERCOMPAN FROM PER_MAEST WHERE PERCODIGO = $percodigo";
			$Table = sql_query($query, $conn, $trans);
	
			$rows  = $Table->Rows_Count;
	
			if ($rows != -1) {
				$row = $Table->Rows[0];
				$pernombre = trim($row['PERNOMBRE']);
				$perapelli = trim($row['PERAPELLI']);
				$percorreo = trim($row['PERCORREO']);
				$percompan = trim($row['PERCOMPAN']);
				$msgdescriaux2 = str_replace("*|Nombre Usuario|*",$pernombre.' '.$perapelli,$msgdescri2);
				$msgdescriaux2 = str_replace("*|Empresa Usuario|*",$percompan,$msgdescriaux2);
    			$msgdescriaux2 = str_replace("*|Correo Usuario|*",$percorreo,$msgdescriaux2);
	
				//Seteamos email a 3 porque al poner mail->send dentro del bucle va a enviar por cada iteracion.
				$envioEmail = 4;
				$body = '<!DOCTYPE html>
				<html lang="en" class="loading">
					<head>
						<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
					</head>
				
				<body>
				<div style="text-align:center">
				<img src=' . getUrl("/mailsimg/4/$msgimg2") . ' alt="image.png" style="max-width: 800px; height:auto; margin-right:0px" data-image-whitelisted="" class="CToWUd">
					<!--app-assets/img/logo-light.png
					-->
					<br>
				</div>
				<div dir="ltr">
				
			<br>
					<br>
				
					<font color="#212121" style="font-family:arial,sans-serif;font-size:18px;">
							<div>'.$msgdescriaux2.'</div>
					</font>
					<div style="text-align:center; margin-top: 30px;">
					<b>  <a style="background-color: grey;color:white;padding: 15px;font-size: 15px;border-radius:20px;text-decoration: none;" href="' . URL_WEB . 'gafete.php?ID='.$percodigo.'">'.$msgbot2.'</a></b> 
					</div>
					
				</div>
			</body>
			</html>';
	
			
				$mail = [
					"from_email" => SEND_MAIL_USUARIO,
			"from_name" => $msgcco,
			"subject" => $msgsub2,
			"preserve_recipients" => FALSE,
			"html" => $body,
			"tags"=> [
								$tagnombreevento."_4"
							],
					"headers" =>[
	
						"Reply-To" => $msgrep
					],
					"to" => [
						[
							"email" => $percorreo,
							"type" => "to"
						]
					]
				];
	
	
			
			
			sendMail($mail);
				//$array[]=["email"=>$percorreo,"type"=>"to"];
			}
		}
		// EMAIL 4 GAFETE //

	
		if($email !== 4){
		$query = "UPDATE PER_MAEST SET $updpermiso WHERE PERCODIGO = $percodigo";
		$err = sql_execute($query, $conn, $trans);
		}

	}
	
}else{
	
	$query = " 	SELECT PERCODIGO 
					FROM PER_MAEST 
					WHERE $where ";
		$Table = sql_query($query,$conn,$trans);
		
		for($i=0; $i<$Table->Rows_Count; $i++){
			$row = $Table->Rows[$i];
			$percodigo	= trim($row['PERCODIGO']);
	
		//Si permiso de reunion 
		if ($email == 1) {
	
			$query = "SELECT PERCODIGO, PERNOMBRE,PERAPELLI,PERCORREO,PERCOMPAN FROM PER_MAEST WHERE PERUSAREU = 0 AND PERCODIGO = $percodigo";
			$Table = sql_query($query, $conn, $trans);
	
			$rows  = $Table->Rows_Count;
	
			if ($rows != -1) {
				$row = $Table->Rows[0];
				$pernombre = trim($row['PERNOMBRE']);
				$perapelli = trim($row['PERAPELLI']);
				$percorreo = trim($row['PERCORREO']);
				$percompan = trim($row['PERCOMPAN']);
				$msgdescriaux = str_replace("*|Nombre Usuario|*",$pernombre.' '.$perapelli,$msgdescri);
				$msgdescriaux = str_replace("*|Empresa Usuario|*",$percompan,$msgdescriaux);
    			$msgdescriaux = str_replace("*|Correo Usuario|*",$percorreo,$msgdescriaux);
				//Seteamos email a 3 porque al poner mail->send dentro del bucle va a enviar por cada iteracion.
				$envioEmail = 1;
				$body = '<!DOCTYPE html>
				<html lang="en" class="loading">
					<head>
						<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
					</head>
				
				<body>
				<div style="text-align:center">
				<img src=' . getUrl("/mailsimg/3/$msgimg") . ' alt="image.png" style="max-width: 800px; height:auto; margin-right:0px" data-image-whitelisted="" class="CToWUd">
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
						<b>  <a style="background-color: grey;color:white;padding: 15px;font-size: 15px;border-radius:20px;text-decoration: none;" href="' . URL_WEB . 'login">'.$msgbot.'</a></b> 
					</div>
							</div>
			</body>
			</html>';
	
				$mail = [
					"from_email" => SEND_MAIL_USUARIO,
					"from_name" => $msgcco,
					"subject" => $msgsub,
					"preserve_recipients" => FALSE,
					"html" => $body,
					"tags"=> [
						$tagnombreevento."_3"
					],
					"headers" =>[
	
						"Reply-To" => $msgrep
					],
					"to" => [
						[
							"email" => $percorreo,
							"type" => "to"
						]
					]
				];
	
	
			
		
		
			
			sendMail($mail);
				//$array[]=["email"=>$percorreo,"type"=>"to"];
			}
		}
		//Si permiso de reunion 
		if ($email == 2) {
	
			$query = "SELECT PERCODIGO, PERNOMBRE,PERAPELLI,PERCORREO,PERCOMPAN FROM PER_MAEST WHERE ESTCODIGO = 8 AND PERCODIGO = $percodigo";
			$Table = sql_query($query, $conn, $trans);
	
			$rows  = $Table->Rows_Count;
	
			if ($rows != -1) {
				$row = $Table->Rows[0];
				$pernombre = trim($row['PERNOMBRE']);
				$perapelli = trim($row['PERAPELLI']);
				$percorreo = trim($row['PERCORREO']);
				$percompan = trim($row['PERCOMPAN']);
				$msgdescriaux1 = str_replace("*|Nombre Usuario|*",$pernombre.' '.$perapelli,$msgdescri1);
				$msgdescriaux1 = str_replace("*|Empresa Usuario|*",$percompan,$msgdescriaux1);
    			$msgdescriaux1 = str_replace("*|Correo Usuario|*",$percorreo,$msgdescriaux1);
	
				//Seteamos email a 3 porque al poner mail->send dentro del bucle va a enviar por cada iteracion.
				$envioEmail = 2;
				$body = '<!DOCTYPE html>
				<html lang="en" class="loading">
					<head>
						<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
					</head>
				
				<body>
				<div style="text-align:center">
				<img src=' . getUrl("/mailsimg/4/$msgimg1") . ' alt="image.png" style="max-width: 800px; height:auto; margin-right:0px" data-image-whitelisted="" class="CToWUd">
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
					<div style="text-align:center; margin-top: 30px;">
						<b>  <a style="background-color: grey;color:white;padding: 15px;font-size: 15px;border-radius:20px;text-decoration: none;" href="' . URL_WEB . 'login">'.$msgbot1.'</a></b> 
					</div>
					<div style="text-align:center; margin-top:10px;">
						<a style="background-color: #286efa!important;border: none;color: white;padding: 15px 32px;text-align:center;text-decoration: none;display: inline-block;border-radius: 8px;font-size: 16px;" href="https://www.addevent.com/dir/?client=atcUIPxJKzRJHYfkDmWv110113&start='.$evefch.'&end='.$evefchfin.'&title='.NAME_TITLE.'&location='.URL_WEB.'">Add to Calendar</a>
					</div>
							</div>
			</body>
			</html>';
	
			
				$mail = [
					"from_email" => SEND_MAIL_USUARIO,
			"from_name" => $msgcco,
			"subject" => $msgsub1,
			"preserve_recipients" => FALSE,
			"html" => $body,
			"tags"=> [
				$tagnombreevento."_4"
			],
					"headers" =>[
	
						"Reply-To" => $msgrep
					],
					"to" => [
						[
							"email" => $percorreo,
							"type" => "to"
						]
					]
				];
	
	
			
	
		
			
			sendMail($mail);
				//$array[]=["email"=>$percorreo,"type"=>"to"];
			}
		}
		if ($email == 3) {
	
			$query = "SELECT PERCODIGO, PERNOMBRE,PERAPELLI,PERCORREO,PERCOMPAN FROM PER_MAEST WHERE ESTCODIGO = 9 AND PERCODIGO = $percodigo";
			$Table = sql_query($query, $conn, $trans);
	
			$rows  = $Table->Rows_Count;
	
			if ($rows != -1) {
				$row = $Table->Rows[0];
				$pernombre = trim($row['PERNOMBRE']);
				$perapelli = trim($row['PERAPELLI']);
				$percorreo = trim($row['PERCORREO']);
				$percompan = trim($row['PERCOMPAN']);
				$msgdescriaux1 = str_replace("*|Nombre Usuario|*",$pernombre.' '.$perapelli,$msgdescri1);
				$msgdescriaux1 = str_replace("*|Empresa Usuario|*",$percompan,$msgdescriaux1);
    			$msgdescriaux1 = str_replace("*|Correo Usuario|*",$percorreo,$msgdescriaux1);
	
				//Seteamos email a 3 porque al poner mail->send dentro del bucle va a enviar por cada iteracion.
				$envioEmail = 3;
				$body = '<!DOCTYPE html>
				<html lang="en" class="loading">
					<head>
						<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
					</head>
				
				<body>
				<div style="text-align:center">
				<img src=' . getUrl("/mailsimg/4/$msgimg1") . ' alt="image.png" style="max-width: 800px; height:auto; margin-right:0px" data-image-whitelisted="" class="CToWUd">
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
					<div style="text-align:center; margin-top: 30px;">
						<b>  <a style="background-color: grey;color:white;padding: 15px;font-size: 15px;border-radius:20px;text-decoration: none;" href="' . URL_WEB . 'login">'.$msgbot1.'</a></b> 
					</div>
					<div style="text-align:center; margin-top:10px;">
						<a style="background-color: #286efa!important;border: none;color: white;padding: 15px 32px;text-align:center;text-decoration: none;display: inline-block;border-radius: 8px;font-size: 16px;" href="https://www.addevent.com/dir/?client=atcUIPxJKzRJHYfkDmWv110113&start='.$evefch.'&end='.$evefchfin.'&title='.NAME_TITLE.'&location='.URL_WEB.'">Add to Calendar</a>
					</div>
							</div>
			</body>
			</html>';
	
			
				$mail = [
					"from_email" => SEND_MAIL_USUARIO,
			"from_name" => $msgcco,
			"subject" => $msgsub1,
			"preserve_recipients" => FALSE,
			"html" => $body,
			"tags"=> [
				$tagnombreevento."_4"
			],
					"headers" =>[
	
						"Reply-To" => $msgrep
					],
					"to" => [
						[
							"email" => $percorreo,
							"type" => "to"
						]
					]
				];
	
	
			
			
			sendMail($mail);
				//$array[]=["email"=>$percorreo,"type"=>"to"];
			}
		}

		if ($email == 4) {
	
			$query = "SELECT PERCODIGO, PERNOMBRE,PERAPELLI,PERCORREO,PERCOMPAN FROM PER_MAEST WHERE PERCODIGO = $percodigo";
			$Table = sql_query($query, $conn, $trans);
	
			$rows  = $Table->Rows_Count;
	
			if ($rows != -1) {
				$row = $Table->Rows[0];
				$pernombre = trim($row['PERNOMBRE']);
				$perapelli = trim($row['PERAPELLI']);
				$percorreo = trim($row['PERCORREO']);
				$percompan = trim($row['PERCOMPAN']);
				$msgdescriaux2 = str_replace("*|Nombre Usuario|*",$pernombre.' '.$perapelli,$msgdescri2);
				$msgdescriaux2 = str_replace("*|Empresa Usuario|*",$percompan,$msgdescriaux2);
    			$msgdescriaux2 = str_replace("*|Correo Usuario|*",$percorreo,$msgdescriaux2);
	
				//Seteamos email a 3 porque al poner mail->send dentro del bucle va a enviar por cada iteracion.
				$envioEmail = 4;
				$body = '<!DOCTYPE html>
				<html lang="en" class="loading">
					<head>
						<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
					</head>
				
				<body>
				<div style="text-align:center">
				<img src=' . getUrl("/mailsimg/4/$msgimg2") . ' alt="image.png" style="max-width: 800px; height:auto; margin-right:0px" data-image-whitelisted="" class="CToWUd">
					<!--app-assets/img/logo-light.png
					-->
					<br>
				</div>
				<div dir="ltr">
				
			<br>
					<br>
				
					<font color="#212121" style="font-family:arial,sans-serif;font-size:18px;">
							<div>'.$msgdescriaux2.'</div>
					</font>
					<div style="text-align:center; margin-top: 30px;">
					<b>  <a style="background-color: grey;color:white;padding: 15px;font-size: 15px;border-radius:20px;text-decoration: none;" href="' . URL_WEB . 'gafete.php?ID='.$percodigo.'">'.$msgbot2.'</a></b> 
					</div>
					
							</div>
			</body>
			</html>';
	
			
				$mail = [
					"from_email" => SEND_MAIL_USUARIO,
			"from_name" => $msgcco,
			"subject" => $msgsub2,
			"preserve_recipients" => FALSE,
			"html" => $body,
			"tags"=> [
				$tagnombreevento."_4"
			],
					"headers" =>[
	
						"Reply-To" => $msgrep
					],
					"to" => [
						[
							"email" => $percorreo,
							"type" => "to"
						]
					]
				];
	
	
			
			
			sendMail($mail);
				//$array[]=["email"=>$percorreo,"type"=>"to"];
			}
		}
	
		if($email !== 4){
		$query = "UPDATE PER_MAEST SET $updpermiso WHERE PERCODIGO = $percodigo";
		$err = sql_execute($query, $conn, $trans);
		}
	}
	$err = sql_execute($query, $conn, $trans);

}




//------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------
if ($err == 'SQLACCEPT' && $errcod == 0) {
	sql_commit_trans($trans);
	$errcod = 0;
	$errmsg = TrMessage('Permiso actualizado!');
} else {
	sql_rollback_trans($trans);
	$errcod = 2;
	$errmsg = ($errmsg == '') ? TrMessage('Error al actualizar el permiso!') : $errmsg;
}
//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
echo '{"errcod":"' . $errcod . '","errmsg":"' . $errmsg . '"}';

?>	
