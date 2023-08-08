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

	
	$reureg 	= (isset($_POST['reureg']))? trim($_POST['reureg']) : 0;

	$tmpl->setVariable('reureginv'	, $reureg);	
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	//Busco los parametros de configuracion
	$query = "	SELECT ZPARAM,ZVALUE FROM ZZZ_CONF WHERE ZPARAM CONTAINING 'SisEvento' ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row= $Table->Rows[$i];
		$params[trim($row['ZPARAM'])] = trim($row['ZVALUE']);
	}
	
	
	if($percodlog!=0 && $reureg!=0){
		$query = "	SELECT 	R.REUFECHA,R.REUHORA,
							PD.PERCODIGO AS PERCODDST, PD.PERNOMBRE AS PERNOMDST, PD.PERAPELLI AS PERAPEDST, PD.PERCOMPAN AS PERCOMDST, PD.PERCORREO AS PERCORDST, PD.PERAVATAR AS PERAVADST,
							PS.PERCODIGO AS PERCODSOL, PS.PERNOMBRE AS PERNOMSOL, PS.PERAPELLI AS PERAPESOL, PS.PERCOMPAN AS PERCOMSOL, PS.PERCORREO AS PERCORSOL, PS.PERAVATAR AS PERAVASOL							
					FROM REU_CABE R
					LEFT OUTER JOIN PER_MAEST PD ON PD.PERCODIGO=R.PERCODDST
					LEFT OUTER JOIN PER_MAEST PS ON PS.PERCODIGO=R.PERCODSOL
					WHERE R.REUREG=$reureg ";
			
		$Table = sql_query($query,$conn);	
		for($i=0; $i<$Table->Rows_Count; $i++){
			$row = $Table->Rows[$i];
			$reufecha		= BDConvFch($row['REUFECHA']);
			$reuhora		= substr(trim($row['REUHORA']),0,5);
			$percoddst 		= trim($row['PERCODDST']);
			$pernomdst		= trim($row['PERNOMDST']);
			$perapedst		= trim($row['PERAPEDST']);
			$percomdst		= trim($row['PERCOMDST']);		
			$peravadst = trim($row['PERAVADST']);
			$percodsol 		= trim($row['PERCODSOL']);
			$pernomsol		= trim($row['PERNOMSOL']);
			$perapesol		= trim($row['PERAPESOL']);
			$percomsol		= trim($row['PERCOMSOL']);
			$peravasol = trim($row['PERAVASOL']);

			if($percodlog == $percoddst ){
				$tmpl->setVariable('pernombrereunion', $pernomsol );
				$tmpl->setVariable('perapellireunion', $perapesol );
				$tmpl->setVariable('percompanreunion', $percomsol );
				$tmpl->setVariable('peravatar', $peravasol );
				
			}else{
				$tmpl->setVariable('pernombrereunion', $pernomdst );
				$tmpl->setVariable('perapellireunion', $perapedst );
				$tmpl->setVariable('percompanreunion', $percomdst );
				$tmpl->setVariable('peravatar', $peravadst );
			}
			
			//Busco si tiene un perfil invitado
			$reupertip = ($percodsol==$percodlog)? 3 : 4;
			
			$queryInv = "	SELECT P.PERCODIGO,P.PERNOMBRE,P.PERAPELLI,P.PERCOMPAN,P.PERCORREO 
							FROM REU_PER R
							LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=R.PERCODIGO
							WHERE R.REUREG=$reureg AND R.REUPERTIP=$reupertip ";
			$TableInv = sql_query($queryInv,$conn);
			if($TableInv->Rows_Count>0){
				$rowInv = $TableInv->Rows[0];
				$percodinv = trim($rowInv['PERCODIGO']);
				$pernominv = trim($rowInv['PERNOMBRE']);
				$perapeinv = trim($rowInv['PERAPELLI']);
				$percominv = trim($rowInv['PERCOMPAN']);
				$percorinv = trim($rowInv['PERCORREO']);
				
				$tmpl->setVariable('percodinv'	, $percodinv);
				$tmpl->setVariable('pernominv'	, $pernominv);
				$tmpl->setVariable('perapeinv'	, $perapeinv);
				$tmpl->setVariable('percominv'	, $percominv);
				$tmpl->setVariable('percorinv'	, $percorinv);
				
				$tmpl->setVariable('btninvitar'	, 'display:none;');
			}else{
				$tmpl->setVariable('btndelinvitar'	, 'display:none;');
			}

			$durreunion = intval($params['SisEventoHorarioIntervalo']); //Duracion de Reuniones
			$tmpl->setVariable('duracionreunion'	, $durreunion);
			$diaaux 	= explode('/',$reufecha);
			$diahor 	= strtotime($diaaux[1].'/'.$diaaux[0].'/'.$diaaux[2].' '.$reuhora); //Dia y Hora de Inicio de Reunion
			$hoy 		= strtotime(date('m/d/Y H:i:s')); //Dia y Hora actual
			$finreunion = strtotime("+$durreunion minutes", $diahor); //Dia y Hora de finalizacion de reunion
			
			logerror('Inicio:'.date( "Y-m-d H:i:s",$diahor));
			logerror('Ahora:'.date( "Y-m-d H:i:s",$hoy));
			logerror('Fin:'.date( "Y-m-d H:i:s",$finreunion));
			logerror('Durecion:'.$durreunion);
			
			$tmpl->setVariable('btnvercall'		, 'display:none;');
			$tmpl->setVariable('viewinvitado'	, 'display:none;');
			$tmpl->setVariable('btnverreulink'	, '');
			
			//Calculo el tiempo que resta a la reunion
			if($hoy > $diahor && $hoy < $finreunion){ //Si la reunion ya inicio, calculo el tiempo restante
				$timerest = intval(round(abs($finreunion - $hoy) / 60,2));
							
				$urlmeet			= 'https://b2b.btoolbox.com/bigbluebutton/api/';
				//$urlmeetjoin		= 'https://reuniones.expoagrodigital.ml/html5client/join?sessionToken=';
				$secreto 			= 'sSV96OTbkVHIoMCNEPR6MfcxXs7xw9ENx0TTuhI2eA'; //Key "secreto", generada en servidor (bbb-conf --secret)
				$nameModerator 		= convBBBdatos($pernomsol).'+'.convBBBdatos($perapesol);				//Nombre de Moderador (solicitante)
				$meetingID 			= md5('ReuN'.$reureg);						//ID de Reunion
				$attendeePW 		= md5($percoddst.$pernomdst.$perapedst);	//Clave de acceso del visitante (destino)
				$moderatorPW 		= md5($percodsol.$pernomsol.$perapesol); 	//Clave de acceso del moderador (solicitante)
				$duration			= $timerest;								//Duracion en minutos de la reunion
				$maxParticipants 	= 4;										//Cantidad de Participantes
				
				//Link de Creacion de reunion
				$apifun 	= 'create';
				$campos 	= "name=$nameModerator&meetingID=$meetingID&moderatorPW=mp&attendeePW=ap&duration=$duration&maxParticipants=$maxParticipants"; //&maxParticipants=$maxParticipants
				$data 		= $apifun.$campos.$secreto;
				$keycreate	= sha1($data);

				$urlcreacion = $urlmeet.$apifun.'?'.$campos.'&checksum='.$keycreate;
				
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
					
						//Si no tiene token lo genero
						$userName 	= convBBBdatos($pernomlog).'+'.convBBBdatos($perapelog);
						$apifun 	='join';
						$passacc 	= md5($percodlog.$pernomlog.$perapelog);
						$userID		= $percodlog;
						$campos 	= "meetingID=$meetingID&password=mp&fullName=$userName&userID=$userID&redirect=true"; //para obtener xml &redirect=false
						$data 		= $apifun.$campos.$secreto;
						$keyjoin	= sha1($data);
						$urljoin 	= $urlmeet.$apifun.'?'.$campos.'&checksum='.$keyjoin;
						$campos1 	= "meetingID=$meetingID&password=mp&fullName=Invitado&userID=99999&redirect=true"; //para obtener xml &redirect=false
						$data1 		= $apifun.$campos1.$secreto;
						$keyjoin1	= sha1($data1);
						$urljoin1 	= $urlmeet.$apifun.'?'.$campos1.'&checksum='.$keyjoin1;

						if ($IdiomView == 'ING'){
							$msg = "Connection successful";
						}else if ($IdiomView == 'ESP'){
							$msg = "Conexión correcta";
						}else{
							$msg = "Conexão correcta";
						}
												
						$tmpl->setVariable('iconmensaje'	, 'fa-check-circle-o color-verde-check');
						$tmpl->setVariable('displayentermeeting'	, '');
						$tmpl->setVariable('mensaje'	, $msg);
						$tmpl->setVariable('urljoin'	, $urljoin); //$urlmeetjoin.$token);
						$tmpl->setVariable('urljoin1'	, $urljoin1); //$urlmeetjoin.$token);
						
						//$queryTok = "UPDATE REU_PER SET REUPERTOK='$token' 
						//			 WHERE REUREG=$reureg AND PERCODIGO=$percodlog ";
						//$err = sql_execute($queryTok,$conn);					
						
						//Muestro la pantalla para invitados
						$tmpl->setVariable('viewinvitado'	, '');
					//}
					
				}else{

					if ($IdiomView == 'ING'){
						$msg = "Connection error";
					}else if ($IdiomView == 'ESP'){
						$msg = "Error en la conexión";
					}else{
						$msg = "Erro de conexão";
					}
											
					$tmpl->setVariable('iconmensaje'	, 'fa-times-circle red');
					$tmpl->setVariable('mensaje'	, $msg);
					$tmpl->setVariable('displayentermeeting'	, 'd-none');
				}
				
			}else{//Si aun no inicio la reunion // no deberia entra por eso no se le da bola
				if($hoy > $finreunion){
					if ($IdiomView == 'ING'){
						$msg = "The meeting has ended";
					}else if ($IdiomView == 'ESP'){
						$msg = "La reunión finalizó";
					}else{
						$msg = "La reunião terminou";
					}
					
				}else{
					if ($IdiomView == 'ING'){
						$msg = "Wait for the meeting to start";
					}else if ($IdiomView == 'ESP'){
						$msg = "Todavía no empezó la reunión";
					}else{
						$msg = "A reunião ainda não começou.";
					}
					
				}

									
				$tmpl->setVariable('iconmensaje'	, 'fa-times-circle yellow');
				$tmpl->setVariable('mensaje'	, $msg);
				$tmpl->setVariable('displayentermeeting'	, 'd-none');
			}
			
		}
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
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

        $textofin = str_replace('ä','a',$textofin);
        $textofin = str_replace('ë','e',$textofin);
        $textofin = str_replace('ï','i',$textofin);
        $textofin = str_replace('ö','o',$textofin);
        $textofin = str_replace('ü','u',$textofin);

        $textofin = str_replace('Ä','A',$textofin);
        $textofin = str_replace('Ë','E',$textofin);
        $textofin = str_replace('Ï','I',$textofin);
        $textofin = str_replace('Ö','O',$textofin);
        $textofin = str_replace('Ü','U',$textofin);
		
		return $textofin;
	}
	//--------------------------------------------------------------------------------------------------------------
?>	
