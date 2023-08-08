<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('mst.html');
	DDIdioma($tmpl);
	
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$msgreg = (isset($_POST['msgreg']))? trim($_POST['msgreg']) : 0;
	$msgestado = 1; //Activo por defecto
	$secdescri = '';
	
	
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	if ($IdiomView=='ESP') {
		$variabletextotipo1="La plataforma notifica al usuario que su cuenta se ha creado exitosamente y solicita confirmación de su dirección de email para finalizar el proceso de activación del perfil.";
		$variabletextotipo2="La plataforma notifica al usuario su nueva contraseña cuando activa la función de recupero en la login page.";
		$variabletextotipo3="La plataforma notifica al usuario que ha sido habilitado por el Administrador para solicitarle reuniones a otros asistentes.";
		$variabletextotipo4="Cuando se indica a la plataforma que los usuarios requieren de aprobación por parte del Administrador para ingresar, este correo les notifica que ya han sido habilitados para iniciar sesión.";
		$variabletextotipo5="La plataforma notifica al usuario que otro usuario le ha enviado un mensaje a través del sistema de chat interno.";
		$variabletextotipo6="La plataforma notifica al usuario que otro usuario le ha solicitado una reunión.";
		$variabletextotipo7="La plataforma notifica al usuario que otro usuario le ha confirmado una reunión pendiente.";
		$variabletextotipo8="La plataforma notifica al usuario que otro usuario le ha cancelado una reunión pendiente.";
		$variabletextotipo9="La plataforma notifica al usuario que otro usuario le ha solicitado una reunión a través de su stand/micrositio.";
		$variabletextotipo10="La plataforma le recuerda a los usuarios 10 minutos antes de la reunión.";
		$variabletextotipo11="La plataforma notifica al usuario que se generó una reunión a través del administrador de reuniones.";
		$variabletextotipo12="La plataforma notifica al usuario para que acceda a su gafete";
		$variabletextotitulo1="01- MAIL DE REGISTRO - CONFIRMAR CUENTA";
		$variabletextotitulo2="03- MAIL DE RECUPERO DE CONTRASEÑA";
		$variabletextotitulo3="04- MAIL DE PERMISO PARA SOLICITAR REUNIONES";
		$variabletextotitulo4="02- MAIL DE LIBERACIÓN DEL PERFIL";
		$variabletextotitulo5="09- MAIL DE CHAT RECIBIDO";
		$variabletextotitulo6="05- MAIL DE SOLICITUD DE REUNION";
		$variabletextotitulo7="07- MAIL DE CONFIRMACIÓN DE REUNION";
		$variabletextotitulo8="08- MAIL DE CANCELACIÓN DE REUNIONES";
		$variabletextotitulo9="06- MAIL DE SOLICITUD DE REUNION POR MICROSITIO";
		$variabletextotitulo10="10- MAIL REUNION 10 MINUTOS ANTES";
		$variabletextotitulo11="11- MAIL REUNION DESDE ADMIN REUNIONES";
		$variabletextotitulo12="12- MAIL GAFETE";
		
	}else if ($IdiomView=='ING'){
		$variabletextotipo1="The platform notifies the user that their account has been successfully created and requests confirmation of their email address to complete the profile activation process.";
		$variabletextotipo2="The platform notifies the user of the new password when the recovery function on the login page is activated.";
		$variabletextotipo3="The platform notifies the user that they have been enabled by the Administrator to request meetings from other attendees.";
		$variabletextotipo4="When the platform is set to require user approval by the Administrator, this email notifies them that they have already been enabled to log in.";
		$variabletextotipo5="The platform notifies the user that another user has sent him a message through the internal chat system.";
		$variabletextotipo6="The platform notifies the user that another user has requested a meeting.";
		$variabletextotipo7="The platform notifies the user that another user has confirmed a pending meeting.";
		$variabletextotipo8="The platform notifies the user that another user has canceled a pending meeting.";
		$variabletextotipo9="The platform notifies the user that another user has requested a meeting through their booth/microsite.";
		$variabletextotipo10="The platform notifies the users that meeting starts in 10 minutes.";
		$variabletextotipo11="The platform notifies the user that meeting had been requested through the meeting configuration (administrator).";
		$variabletextotipo12="The platform notifies the user to access his or her entrance";
		$variabletextotitulo1="01- SIGN IN NOTIFICATION - CONFIRM YOUR ACCOUNT";
		$variabletextotitulo2="03- PASSWORD RECOVERY NOTIFICATION";
		$variabletextotitulo3="04- PERMISSION TO REQUEST MEETINGS NOTIFICATION";
		$variabletextotitulo4="02- PROFILE APPROVED NOTIFICATION";
		$variabletextotitulo5="09- CHAT RECEIVED NOTIFICATION";
		$variabletextotitulo6="05- MEETING REQUESTED NOTIFICATION";
		$variabletextotitulo7="07- MEETING CONFIRMED NOTFICATION";
		$variabletextotitulo8="08- MEETING CANCELLED NOTIFICATION";
		$variabletextotitulo9="06- MEETING REQUESTED VIA STAND NOTIFICATION";
		$variabletextotitulo10="10- MAIL REUNION 10 MINUTOS ANTES";
		$variabletextotitulo11="11- MAIL REUNION DESDE ADMIN REUNIONES";
		$variabletextotitulo12="12- EMAIL ENTRANCE";
		
	}else{
		$variabletextotipo1="La plataforma notifica al usuario que su cuenta se ha creado exitosamente y solicita confirmación de su dirección de email para finalizar el proceso de activación del perfil.";
		$variabletextotipo2="La plataforma notifica al usuario su nueva contraseña cuando activa la función de recupero en la login page.";
		$variabletextotipo3="La plataforma notifica al usuario que ha sido habilitado por el Administrador para solicitarle reuniones a otros asistentes.";
		$variabletextotipo4="Cuando se indica a la plataforma que los usuarios requieren de aprobación por parte del Administrador para ingresar, este correo les notifica que ya han sido habilitados para iniciar sesión.";
		$variabletextotipo5="La plataforma notifica al usuario que otro usuario le ha enviado un mensaje a través del sistema de chat interno.";
		$variabletextotipo6="La plataforma notifica al usuario que otro usuario le ha solicitado una reunión.";
		$variabletextotipo7="La plataforma notifica al usuario que otro usuario le ha confirmado una reunión pendiente.";
		$variabletextotipo8="La plataforma notifica al usuario que otro usuario le ha cancelado una reunión pendiente.";
		$variabletextotipo9="La plataforma notifica al usuario que otro usuario le ha solicitado una reunión a través de su stand/micrositio.";
		$variabletextotipo10="La plataforma le recuerda a los usuarios 10 minutos antes de la reunión.";
		$variabletextotipo11="La plataforma notifica al usuario que se generó una reunión a través del administrador de reuniones.";
		$variabletextotipo12="La plataforma notifica al usuario para que acceda a su gafete";
		$variabletextotitulo1="01- MAIL DE REGISTRO - CONFIRMAR CUENTA";
		$variabletextotitulo2="03- MAIL DE RECUPERO DE CONTRASEÑA";
		$variabletextotitulo3="04- MAIL DE PERMISO PARA SOLICITAR REUNIONES";
		$variabletextotitulo4="02- MAIL DE LIBERACIÓN DEL PERFIL";
		$variabletextotitulo5="09- MAIL DE CHAT RECIBIDO";
		$variabletextotitulo6="05- MAIL DE SOLICITUD DE REUNION";
		$variabletextotitulo7="07- MAIL DE CONFIRMACIÓN DE REUNION";
		$variabletextotitulo8="08- MAIL DE CANCELACIÓN DE REUNIONES";
		$variabletextotitulo9="06- MAIL DE SOLICITUD DE REUNION POR MICROSITIO";
		$variabletextotitulo10="10- MAIL REUNION 10 MINUTOS ANTES";
		$variabletextotitulo11="11- MAIL REUNION DESDE ADMIN REUNIONES";
		$variabletextotitulo12="12- MAIL GAFETE";
		
	}
	if($msgreg!=0 && $msgreg!=10){
		$query = "SELECT MSGREG, MSGFCHREG, MSGTITULO, MSGDESCRI, MSGSUB, MSGBOT, MSGLNK, MSGIMG
					FROM MSG_CABE
					WHERE MSGREG=$msgreg";

		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			$msgreg = trim($row['MSGREG']);
			$msgfchreg = trim($row['MSGFCHREG']);
			$msgtitulo = trim($row['MSGTITULO']);
			$msgdescri = trim($row['MSGDESCRI']);
			$msgsub = trim($row['MSGSUB']);
			$msgbot = trim($row['MSGBOT']);
			$msglnk = trim($row['MSGLNK']);
			$msgimg = trim($row['MSGIMG']);
			$folderProd =  '../mailsimg/'.$msgreg.'/';

			$tmpl->setVariable('displaydescripcion'	, 'd-none'	);
			$tmpl->setVariable('disabledtitulo'	, ''	);
			if ($msgreg<14){
				if ($msgreg==1){
					$descripcionmail=$variabletextotipo1;
					$msgtitulo=$variabletextotitulo1;
				}else if ($msgreg==2){
					$descripcionmail=$variabletextotipo2;
					$msgtitulo=$variabletextotitulo2;
				}else if ($msgreg==3){
					$descripcionmail=$variabletextotipo3;
					$msgtitulo=$variabletextotitulo3;
				}else if ($msgreg==4){
					$descripcionmail=$variabletextotipo4;
					$msgtitulo=$variabletextotitulo4;
				}else if ($msgreg==5){
					$descripcionmail=$variabletextotipo5;
					$msgtitulo=$variabletextotitulo5;
				}else if ($msgreg==6){
					$descripcionmail=$variabletextotipo6;
					$msgtitulo=$variabletextotitulo6;
				}else if ($msgreg==7){
					$descripcionmail=$variabletextotipo7;
					$msgtitulo=$variabletextotitulo7;
				}else if ($msgreg==8){
					$descripcionmail=$variabletextotipo8;
					$msgtitulo=$variabletextotitulo8;
				}else if ($msgreg==9){
					$descripcionmail=$variabletextotipo9;
					$msgtitulo=$variabletextotitulo9;
				}else if ($msgreg==11){
					$descripcionmail=$variabletextotipo10;
					$msgtitulo=$variabletextotitulo10;
				}else if ($msgreg==12){
					$descripcionmail=$variabletextotipo11;
					$msgtitulo=$variabletextotitulo11;
				}else if ($msgreg==13){
					$descripcionmail=$variabletextotipo12;
					$msgtitulo=$variabletextotitulo12;
				}
				
				
				$tmpl->setVariable('displaydescripcion'	, ''	);
				$tmpl->setVariable('descripcionmail'	, $descripcionmail	);
				$tmpl->setVariable('displaylnk'	, 'd-none'	);
				$tmpl->setVariable('disabledtitulo'	, 'disabled'	);

			}

			$tmpl->setVariable('msgreg'	, $msgreg	);
			$tmpl->setVariable('msgfchreg'	, $msgfchreg	);
			$tmpl->setVariable('msgtitulo'	, $msgtitulo	);
			htmlspecialchars_decode($msgdescri);
			$tmpl->setVariable('msgdescri'	, htmlspecialchars_decode($msgdescri));
			$tmpl->setVariable('msgsub'	, $msgsub	);
			$tmpl->setVariable('msgbot'	, $msgbot	);
			$tmpl->setVariable('msglnk'	, $msglnk	);
			$tmpl->setVariable('msgimg'	,  $folderProd.'/'.$msgimg 	);
			
		}
	}
	if($msgreg==10){
		$query = "SELECT MSGREG, MSGTITULO, MSGREP, MSGCC, MSGCCO
					FROM MSG_CABE
					WHERE MSGREG=$msgreg";

		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			$msgreg = trim($row['MSGREG']);
			$msgtitulo = trim($row['MSGTITULO']);
			$msgrep = trim($row['MSGREP']);
			$msgcc = trim($row['MSGCC']);
			$msgcco = trim($row['MSGCCO']);



			$tmpl->setVariable('msgreg'	, $msgreg	);
			$tmpl->setVariable('msgtitulo'	, $msgtitulo	);
			$tmpl->setVariable('msgrep'	, $msgrep	);
			$tmpl->setVariable('msgbot'	, $msgbot	);
			$tmpl->setVariable('msgcc'	, $msgcc	);
			$tmpl->setVariable('msgcco'	,  $msgcco 	);
			
		}
	}
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
