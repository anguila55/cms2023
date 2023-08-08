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
	$percodlog = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernomlog = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$perapelog = (isset($_SESSION[GLBAPPPORT.'PERAPELLI']))? trim($_SESSION[GLBAPPPORT.'PERAPELLI']) : '';
	
	
	
	$errcod 	= 0;
	$errmsg 	= '';
	$err 		= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	//Control de Datos
	$percodinv 	= (isset($_POST['percodinv']))? trim($_POST['percodinv']) : 0;
	$percorinv 	= (isset($_POST['percorinv']))? trim($_POST['percorinv']) : '';
	$reureginv 	= (isset($_POST['reureginv']))? trim($_POST['reureginv']) : 0;
	//--------------------------------------------------------------------------------------------------------------
	
	if($percodinv!=0 && $percorinv!=''){
		$query = " 	SELECT PERCODIGO,PERNOMBRE,PERAPELLI,PERCOMPAN,PERCORREO 
					FROM PER_MAEST 
					WHERE ESTCODIGO=1 AND PERCORREO='$percorinv'
						AND PERCODIGO=$percodinv ";
		
		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			$percodigo = trim($row['PERCODIGO']);
			$pernombre = trim($row['PERNOMBRE']);
			$perapelli = trim($row['PERAPELLI']);
			$percompan = trim($row['PERCOMPAN']);
			$percorreo = trim($row['PERCORREO']);
			
			//Verifico si el perfil logueado es destino o solicitante en la reuion
			$queryReu = " 	SELECT R.REUFECHA,R.REUHORA,
									PD.PERCODIGO AS PERCODDST, PD.PERNOMBRE AS PERNOMDST, PD.PERAPELLI AS PERAPEDST, PD.PERCOMPAN AS PERCOMDST, PD.PERCORREO AS PERCORDST, PD.PERAVATAR AS PERAVADST,
									PS.PERCODIGO AS PERCODSOL, PS.PERNOMBRE AS PERNOMSOL, PS.PERAPELLI AS PERAPESOL, PS.PERCOMPAN AS PERCOMSOL, PS.PERCORREO AS PERCORSOL, PS.PERAVATAR AS PERAVASOL
							FROM REU_CABE R
							LEFT OUTER JOIN PER_MAEST PD ON PD.PERCODIGO=R.PERCODDST
							LEFT OUTER JOIN PER_MAEST PS ON PS.PERCODIGO=R.PERCODSOL
							WHERE REUREG=$reureginv ";
			
			$TableReu = sql_query($queryReu,$conn);
			$rowReu= $TableReu->Rows[0];
			$percodsol 	= trim($rowReu['PERCODSOL']);
			$pernomsol 	= trim($rowReu['PERNOMSOL']);
			$perapesol 	= trim($rowReu['PERAPESOL']);
			$percomsol 	= trim($rowReu['PERCOMSOL']);
			
			$percoddst 	= trim($rowReu['PERCODDST']);
			$pernomdst 	= trim($rowReu['PERNOMDST']);
			$perapedst 	= trim($rowReu['PERAPEDST']);
			$percomdst 	= trim($rowReu['PERCOMDST']);
			
			$reufecha 	= BDConvFch($rowReu['REUFECHA']);
			$reuhora 	= substr(trim($rowReu['REUHORA']),0,5);
			
			if($percodsol==$percodlog){
				$reupertip=3;//Solicitante
			}else{
				$reupertip=4;//Destinatario
			}
			
			//Invito al perfil, relaciono
			$query = "	DELETE FROM REU_PER WHERE REUREG=$reureginv AND REUPERTIP=$reupertip ";
			$err = sql_execute($query,$conn);
			
			$query = "	INSERT INTO REU_PER(REUREG,PERCODIGO,REUPERTIP)
						VALUES($reureginv,$percodigo,$reupertip) ";
			$err = sql_execute($query,$conn);
			
			$errmsg='Perfil Invitado.';
			
			//Link de Reunion
			$urlmeet	= 'https://b2b.btoolbox.com/bigbluebutton/api/';
			$secreto	= 'sSV96OTbkVHIoMCNEPR6MfcxXs7xw9ENx0TTuhI2eA'; 
			$meetingID 	= md5('ReuN'.$reureginv);
			$userName 	= convBBBdatos($pernombre).'+'.convBBBdatos($perapelli);
			$apifun 	='join';
			$attendeePW = md5($percoddst.$pernomdst.$perapedst);
			$userID		= $percodigo;
			$campos 	= "meetingID=$meetingID&password=mp&fullName=$userName&userID=$userID&redirect=true"; 
			$data 		= $apifun.$campos.$secreto;
			$keyjoin	= sha1($data);
			$urljoin 	= $urlmeet.$apifun.'?'.$campos.'&checksum='.$keyjoin;
			
			
			
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
            <font color="#FFC210">Te han invitado a participar de una reunión en BTBOX RONDAS - versión DEMO</font></b>
            <br><br>
            <font color="#212121" style="font-family:arial,sans-serif;font-size:18px;">Te han invitado a participar de una reunión virtual el día '.$reufecha.' a las '.$reuhora.' horas</font>
            <font color="#212121" style="font-family:arial,sans-serif;font-size:18px;">
            <div style="text-align:left">Por favor, haz click en el link detallado más abajo y espera a que comience el encuentro.</div>
        </font>
    </div>
</div>

<br><br>

<div style="text-align:left">
    <b style="font-family:arial,sans-serif;font-size:18px;">
        <font color="#274e13"><a href="'.$urljoin.'">Ingresar a la reunión.</a></font>
    </b>
</div>

</body>
</html>';
			
							$mail = [
								"from_email" => SEND_MAIL_USUARIO,
								"from_name" => MAIL_NAME_APP,
								"subject" => SUBJETC_INVITARREUNION,
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
			
		}else{
			$errcod=2;
			$errmsg='Error al invitar perfil.';
		}
	}else{
		$errcod=2;
		$errmsg='Error al invitar perfil.';
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{	"errcod":"'.$errcod.'",
			"errmsg":"'.$errmsg.'" }';
	
	
	//--------------------------------------------------------------------------------------------------------------
	function convBBBdatos($texto){
		$textofin = $texto;
		$textofin = str_replace(' ','+',$textofin);
		
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
	//--------------------------------------------------------------------------------------------------------------
?>	
