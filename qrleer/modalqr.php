<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
	require_once GLBRutaFUNC . '/constants.php';	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('modalqr.html');

			
	
	//Diccionario de idiomas
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pertipo = (isset($_SESSION[GLBAPPPORT.'PERTIPO']))? trim($_SESSION[GLBAPPPORT.'PERTIPO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$peradmin  = (isset($_SESSION[GLBAPPPORT . 'PERADMIN'])) ? trim($_SESSION[GLBAPPPORT . 'PERADMIN']) : '';

	$qrreg = (isset($_POST['qrreg'])) ? trim($_POST['qrreg']) : '';
	
	$varseccion=0;//// Es Perfil

	if($qrreg!=''){
		$vaux = explode('||',$qrreg);
		
		if(count($vaux)>1){
			$tmpl->setVariable('modalagenda'	,  'd-none'	);
			$tmpl->setVariable('modalperfil'	,  'd-none'	);
			$tmpl->setVariable('modalsponsor'	,  'd-none'	);
			$tmpl->setVariable('modalperfilcontrol'	,  'd-none'	);
			if ($vaux[0] == URL_WEB . 'login.php?QRCode=P'){
				if ($pertipo == 71){
					$tmpl->setVariable('modalperfilcontrol'	,  ''	);
					$varseccion=3;///// Es  QR control de ingresos

				}else{
					$tmpl->setVariable('modalperfil'	,  ''	);
					$varseccion=0;///// Es Perfil
				}
				

			}else if ($vaux[0] == URL_WEB . 'login.php?QRCode=E'){
				$tmpl->setVariable('modalsponsor'	,  ''	);
				$varseccion=1;////// Es Expositor

			}else if ($vaux[0] == URL_WEB . 'login.php?QRCode=A'){
				
				$tmpl->setVariable('modalagenda'	,  ''	);
				$varseccion=2;////// Es Agenda
			}else{

			}
			$qrcodereg = $vaux[1];
		}
	}
	$tmpl->setVariable('varseccion'	,  $varseccion	);
	$tmpl->setVariable('qrcodereg'	,  $qrcodereg	);
	
	/////////// Funciones Botones ///////////////
	$conn= sql_conectar();//Apertura de Conexion
		if ($varseccion==1){ 
			$query="SELECT 	EM.EXPREG,EM.EXPNOMBRE,EM.EXPDIRECCION,EM.EXPTELEFO,EM.EXPMAIL,EM.EXPWEB,EM.EXPLINKED,
				EM.EXPAVATAR,EM.EXPCATEGO,EM.EXPRUBROS,EM.EXPSTAND,EM.EXPPOSX,EM.EXPPOSY,EM.ESTCODIGO,
				EM.PERCODIGO,EM.EXPPOS,EM.EXPBANIMG1,EM.EXPFAC,EM.EXPTWI,EM.EXPINSTA, EM.QRCODE,
				PM.PERCODIGO AS PERFIL_RELACIONADO,PM.PERNOMBRE,PM.PERAPELLI, PM.PERTIPO
		FROM EXP_MAEST EM 
		LEFT OUTER JOIN PER_MAEST PM ON EM.PERCODIGO = PM.PERCODIGO
		WHERE EM.EXPREG=$qrcodereg ";

			$Table = sql_query($query, $conn); 

			for ($i = 0; $i < $Table->Rows_Count; $i++) {

				
				$row = $Table->Rows[$i];
				//Datos base del sponsor
				$expreg 		= trim($row['EXPREG']);
				$expnombre 		= trim($row['EXPNOMBRE']);

				//Avatar
				$expavatar 	= trim($row['EXPAVATAR']);
				//sobre la empresa
				$exprubros 	= trim($row['EXPRUBROS']);

				//datos del evento
				$estcodigo 	= trim($row['ESTCODIGO']);
				$percodcontacto 	= trim($row['PERCODIGO']);



				$tmpl->setVariable('styleypf'	, ''	);
				//Datos de la empresa
			
				
				$tmpl->setVariable('expreg'	, $expreg);
				$tmpl->setVariable('expnombre'	, $expnombre);
				$tmpl->setVariable('exprubros'	, nl2br($exprubros));
				$tmpl->setVariable('percodcontacto'	, $percodcontacto);
				$pathimagenes = '../expimg/';
				$tmpl->setVariable('expavatar'	, $pathimagenes.$expreg.'/'.$expavatar);

			}

		}else if ($varseccion==2){
			$query = "	SELECT AGEYOULNK, AGETITULO, AGEFCH,AGEDESCRI,SPKREG, AGEHORINI, AGEHORFIN, AGEYOULNKING, AGEYOULNKPOR
				FROM AGE_MAEST A 
				WHERE A.AGEREG=$qrcodereg ";
			$Table = sql_query($query,$conn);
			if($Table->Rows_Count>0){
				$row = $Table->Rows[0];
				$agedescri 	= trim($row['AGEDESCRI']);
				$agetitulo 	= trim($row['AGETITULO']);
				$agefch     = BDConvFch($row['AGEFCH']);
				$agehorini  = substr(trim($row['AGEHORINI']), 0, 5);
				$agehorfin  = substr(trim($row['AGEHORFIN']), 0, 5);

				//$agefch	= substr($agefch, 0, 2). '-' . substr($agefch, 3, 2) . '-' . substr($agefch, 6, 4) ; //Formato calendario (yyyy-mm-dd)
				$agehorini = ($agehorini != '') ?  $agehorini : '';
				$agehorfin = ($agehorfin != '') ?  $agehorfin : '';
				
				
				$tmpl->setVariable('agetitulo'	, $agetitulo);
				$tmpl->setVariable('agehorini', $agehorini);
				$tmpl->setVariable('agehorfin', $agehorfin);
				$tmpl->setVariable('agefch', $agefch);
				$tmpl->setVariable('agedescri', $agedescri);
				}

		}else if ($varseccion==3){

			$query = "	SELECT P.PERCODIGO,P.PERNOMBRE,P.PERAPELLI,P.ESTCODIGO,P.PERCOMPAN,P.PERCORREO,P.PERCIUDAD,P.PERESTADO,
							P.PERCODPOS,P.PERTELEFO,P.PERURLWEB,P.PERUSUACC,P.PERPASACC,P.PERDIRECC,P.PERCARGO,
							P.PAICODIGO,I.PAIDESCRI,P.PEREMPDES,P.PERAVATAR
					FROM PER_MAEST P
					LEFT OUTER JOIN TBL_PAIS I ON I.PAICODIGO=P.PAICODIGO
					WHERE P.PERCODIGO=$qrcodereg ";
		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			$percodigo 	= trim($row['PERCODIGO']);
			$pernombre 	= trim($row['PERNOMBRE']);
			$perapelli 	= trim($row['PERAPELLI']);
			$percompan 	= trim($row['PERCOMPAN']);
			$percorreo 	= trim($row['PERCORREO']);
			$peravatar 	= trim($row['PERAVATAR']);
			$estcodigo 	= trim($row['ESTCODIGO']);
			if ($estcodigo==3){

				if ($IdiomView == 'ING'){
					$tmpl->setVariable('perhabilitar'	, 'Status: Not Enabled');
					
					}else if ($IdiomView == 'ESP'){
						$tmpl->setVariable('perhabilitar'	, 'Estado: No Habilitado');
					}else{
						$tmpl->setVariable('perhabilitar'	, 'Estado: Não ativado');
					}
					$tmpl->setVariable('colorperhabilitar'	, 'red');
			
			}else if ($estcodigo==8){

				if ($IdiomView == 'ING'){
					$tmpl->setVariable('perhabilitar'	, 'Status: Email not confirmed');
					}else if ($IdiomView == 'ESP'){
						$tmpl->setVariable('perhabilitar'	, 'Estado: Correo no confirmado');
					}else{
						$tmpl->setVariable('perhabilitar'	, 'Estado: E-mail não confirmado');
					}
					$tmpl->setVariable('colorperhabilitar'	, 'yellow');
			}else if ($estcodigo==9){
				if ($IdiomView == 'ING'){
					$tmpl->setVariable('perhabilitar'	, 'Status: Not Enabled');
					}else if ($IdiomView == 'ESP'){
						$tmpl->setVariable('perhabilitar'	, 'Estado: No Habilitado');
					}else{
						$tmpl->setVariable('perhabilitar'	, 'Estado: Não ativado');
					}
					$tmpl->setVariable('colorperhabilitar'	, 'red');
			}else{
				if ($IdiomView == 'ING'){
					$tmpl->setVariable('perhabilitar'	, 'Status: Enabled');
					}else if ($IdiomView == 'ESP'){
						$tmpl->setVariable('perhabilitar'	, 'Estado: Habilitado');
					}else{
						$tmpl->setVariable('perhabilitar'	, 'Estado: Ativado');
					}
					$tmpl->setVariable('colorperhabilitar'	, 'green');

			}
			
			$tmpl->setVariable('percodigo'	, $percodigo	);
			$tmpl->setVariable('pernombre'	, $pernombre	);
			$tmpl->setVariable('perapelli'	, $perapelli	);
			$tmpl->setVariable('percompan'	, $percompan	);
			$pathimagenes = '../perimg/'.$percodigo.'/';
			$imgAvatarNull = '../app-assets/img/avatar.png';
			if($peravatar!=''){
				$tmpl->setVariable('peravatar'	, $pathimagenes.$peravatar);
			}else{
				$tmpl->setVariable('peravatar'	, $imgAvatarNull);
			}
			
			
		}
	

		}else{

			$query = "	SELECT P.PERCODIGO,P.PERNOMBRE,P.PERAPELLI,P.ESTCODIGO,P.PERCOMPAN,P.PERCORREO,P.PERCIUDAD,P.PERESTADO,
							P.PERCODPOS,P.PERTELEFO,P.PERURLWEB,P.PERUSUACC,P.PERPASACC,P.PERDIRECC,P.PERCARGO,
							P.PAICODIGO,I.PAIDESCRI,P.PEREMPDES,P.PERAVATAR
					FROM PER_MAEST P
					LEFT OUTER JOIN TBL_PAIS I ON I.PAICODIGO=P.PAICODIGO
					WHERE P.PERCODIGO=$qrcodereg ";
		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			$percodigo 	= trim($row['PERCODIGO']);
			$pernombre 	= trim($row['PERNOMBRE']);
			$perapelli 	= trim($row['PERAPELLI']);
			$percompan 	= trim($row['PERCOMPAN']);
			$percorreo 	= trim($row['PERCORREO']);
			$peravatar 	= trim($row['PERAVATAR']);
			
			$tmpl->setVariable('percodigo'	, $percodigo	);
			$tmpl->setVariable('pernombre'	, $pernombre	);
			$tmpl->setVariable('perapelli'	, $perapelli	);
			$tmpl->setVariable('percompan'	, $percompan	);
			$pathimagenes = '../perimg/'.$percodigo.'/';
			$imgAvatarNull = '../app-assets/img/avatar.png';
			if($peravatar!=''){
				$tmpl->setVariable('peravatar'	, $pathimagenes.$peravatar);
			}else{
				$tmpl->setVariable('peravatar'	, $imgAvatarNull);
			}
			
			
		}
	

		}
	
	

	$tmpl->setVariable('qrcodereg'	,  $qrcodereg	);

	sql_close($conn);	

	$tmpl->show();
	
?>	
