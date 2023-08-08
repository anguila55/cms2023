<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('call.html');
	DDIdioma($tmpl);
	//ID de Agora de aplicacion
	//$appid = "bac30415866e4090b01e2314dd7ec16b";
	//$tmpl->setVariable('appid'	, $appid);
	
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodlog = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernomlog = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$perapelog = (isset($_SESSION[GLBAPPPORT.'PERAPELLI']))? trim($_SESSION[GLBAPPPORT.'PERAPELLI']) : '';

	$meetingid 	= (isset($_POST['meetingid']))? $_POST['meetingid']:'';
	//--------------------------------------------------------------------------------------------------------------

							
	$urlmeet	= 'https://b2b.btoolbox.com/bigbluebutton/api/';
	$secreto	= 'sSV96OTbkVHIoMCNEPR6MfcxXs7xw9ENx0TTuhI2eA'; 

				$data ='getMeetings'.$secreto;
				$keycreate	= sha1($data);

				$urlcreacion = $urlmeet.'getMeetings?checksum='.$keycreate;
			
				//Ejecuto la llamada a la url de creacion
				$ch = curl_init($urlcreacion);
				curl_setopt($ch, CURLOPT_HEADER, "Content-Type:application/xml");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				$content= curl_exec($ch);
				curl_close($ch);
				//logerror($content);
				$xmlmeet = simplexml_load_string($content);
				
				//Link de ingreso a reunion
				if($xmlmeet->returncode=='SUCCESS'){
					
					$arraycharlas=$xmlmeet->meetings->meeting;
					foreach ($arraycharlas as $valorcharlas) {
						if ($valorcharlas->meetingID==$meetingid)
					{
						
						$array = $valorcharlas->attendees->attendee;
						
						foreach ($array as $valor) {
							$tmpl->setCurrentBlock('browser');
							$conectado = $valor->fullName;
							$rolconectado = $valor->role;
							$tmpl->setVariable('conectado'	, $conectado); //$urlmeetjoin.$token);
							$tmpl->setVariable('rolconectado'	, $rolconectado); //$urlmeetjoin.$token);
							$tmpl->parse('browser');
						}
						

					}else{

						if ($IdiomView == 'ING'){

							$tmpl->setVariable('conectado'	, 'Users'); //$urlmeetjoin.$token);
						$tmpl->setVariable('rolconectado'	, 'No users connected right now'); //$urlmeetjoin.$token);
						}else if ($IdiomView == 'ESP'){
							$tmpl->setVariable('conectado'	, 'Usuarios'); //$urlmeetjoin.$token);
						$tmpl->setVariable('rolconectado'	, 'No hay usuarios dentro de la reunión en este momento'); //$urlmeetjoin.$token);
						}else{
							$tmpl->setVariable('conectado'	, 'Usuarios'); //$urlmeetjoin.$token);
						$tmpl->setVariable('rolconectado'	, 'No hay usuarios dentro de la reunión en este momento'); //$urlmeetjoin.$token);
						}

						
					}

					}
					
						
						
				
					
				}else{
					if ($IdiomView == 'ING'){

						$tmpl->setVariable('conectado'	, 'Something went wrong'); //$urlmeetjoin.$token);
					$tmpl->setVariable('rolconectado'	, 'We could not connect to the meeting'); //$urlmeetjoin.$token);
					}else if ($IdiomView == 'ESP'){
						$tmpl->setVariable('conectado'	, 'Hubo un error'); //$urlmeetjoin.$token);
					$tmpl->setVariable('rolconectado'	, 'No se pudo conectar a la reunión'); //$urlmeetjoin.$token);
					}else{
						$tmpl->setVariable('conectado'	, 'Hubo un error'); //$urlmeetjoin.$token);
						$tmpl->setVariable('rolconectado'	, 'No se pudo conectar a la reunión'); //$urlmeetjoin.$token);
					}
					

				}

	$tmpl->show();
	//--------------------------------------------------------------------------------------------------------------
?>	
