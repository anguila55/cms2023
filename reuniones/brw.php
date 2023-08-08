<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/agora/RtcTokenBuilder.php';
	require_once GLBRutaFUNC . '/idioma.php'; //Idioma	
	require_once GLBRutaFUNC.'/constants.php';

			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('brw.html');
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodlog = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernomlog = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$percompanlog = (isset($_SESSION[GLBAPPPORT.'PERCOMPAN']))? trim($_SESSION[GLBAPPPORT.'PERCOMPAN']) : '';
	$percargolog = (isset($_SESSION[GLBAPPPORT.'PERCARGO']))? trim($_SESSION[GLBAPPPORT.'PERCARGO']) : '';
	$perapelog = (isset($_SESSION[GLBAPPPORT.'PERAPELLI']))? trim($_SESSION[GLBAPPPORT.'PERAPELLI']) : '';
	$perusuacc = (isset($_SESSION[GLBAPPPORT.'PERUSUACC']))? trim($_SESSION[GLBAPPPORT.'PERUSUACC']) : '';
	$percorreo = (isset($_SESSION[GLBAPPPORT.'PERCORREO']))? trim($_SESSION[GLBAPPPORT.'PERCORREO']) : '';
	$peradmin = (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';
	$peravatarlog = (isset($_SESSION[GLBAPPPORT.'PERAVATAR']))? trim($_SESSION[GLBAPPPORT.'PERAVATAR']) : '';
	
	$videoconferencia = "BBB"; //BBB (BigBlueButton), AGORA
	$tmpl->setVariable('SisNombreEvento', NAME_TITLE );
	$pathimagenes = '../perimg/';
	$imgAvatarNull = '../app-assets/img/avatar.png';
	
	$fltbuscar 	= (isset($_POST['fltbuscar']))? $_POST['fltbuscar']:1;
	
	$conn= sql_conectar();//Apertura de Conexion
	
	//Busco los parametros de configuracion
	$query = "	SELECT ZPARAM,ZVALUE FROM ZZZ_CONF WHERE ZPARAM CONTAINING 'SisEvento' ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row= $Table->Rows[$i];
		$params[trim($row['ZPARAM'])] = trim($row['ZVALUE']);
	}


	// VEO SI ES EVENTO HIBRIDO
	$query2 = " SELECT ZVALUE FROM ZZZ_CONF WHERE ZPARAM = 'TipoEvento'";
$Table2 = sql_query($query2, $conn);
for ($i = 0; $i < $Table2->Rows_Count; $i++) {
	$row = $Table2->Rows[$i];
	$tipoevento = trim($row['ZVALUE']);
	if ($tipoevento != 'false') {

		$tmpl->setVariable('mostrarqr', '');
	} else {
		$tmpl->setVariable('mostrarqr', 'd-none');
	}
}
	
	
	//Busco si hay una encuesta publicada, para poder presentar el boton de encuestas
	$encExists = false;
	$query = "	SELECT EC.ENCREG, EC.OBLIGA
				FROM ENC_CABE EC
				WHERE EC.ESTCODIGO=1 AND EC.ENCPUBLIC='S' ";
	$Table = sql_query($query,$conn);
	if($Table->Rows_Count>0){
		$row = $Table->Rows[0];
		$encobliga = trim($row['OBLIGA']);

		if ($encobliga == 0){
			$encObliga = false;
		}else{
			$encObliga = true;
		}

		$encExists = true;
		$encObligaSinContestar = false;
	}

	if( ($encExists) && ($encObliga) ){
		$tmpl->setVariable('encuestaobligatoria', 'true');
	}else{
		$tmpl->setVariable('encuestaobligatoria', 'false');
	}
	if($encExists){
		$tmpl->setVariable('existeencuesta', 'true');
	}else{
		$tmpl->setVariable('existeencuesta', 'false');
	}

	$diaactual = date('m/d/Y');
	$where = '';
	$relacion = '';
	if($fltbuscar == 1){ //Ver reuniones que envie
		$relacion = ' P.PERCODIGO=R.PERCODDST ';
		$where .= "  (R.PERCODDST=$percodlog OR R.PERCODSOL=$percodlog) AND R.REUESTADO!=5 ";
	}
	if($fltbuscar == 2){ //Ver reuniones que envie
		$relacion = ' P.PERCODIGO=R.PERCODDST ';
		$where .= "  (R.PERCODDST=$percodlog OR R.PERCODSOL=$percodlog) AND R.REUESTADO!=5 AND R.REUESTADO=1 ";
	}
	if($fltbuscar == 3){ //Ver reuniones que envie y me enviaron, pero ya estan confirmadas
		$relacion = ' P.PERCODIGO=R.PERCODDST ';
		$where .= "  (R.PERCODDST=$percodlog OR R.PERCODSOL=$percodlog) AND R.REUESTADO!=5 AND R.REUESTADO=2 AND R.REUFECHA>='$diaactual' ";
	}
	if($fltbuscar == 4){ //Ver reuniones que envie y me enviaron, pero ya estan canceladas
		$relacion = ' P.PERCODIGO=R.PERCODDST ';
		$where .= "  (R.PERCODDST=$percodlog OR R.PERCODSOL=$percodlog)  AND (R.REUESTADO=3 OR R.REUFECHA<'$diaactual') AND R.REUESTADO!=5";
	}
	
	$arrayreuniones=null;
	//Reuniones que solicitaron
	$query = "	SELECT 	PD.PERCODIGO AS PERCODDST, PD.PERNOMBRE AS PERNOMDST, PD.PERAPELLI AS PERAPEDST, PD.PERCOMPAN AS PERCOMDST, PD.PERCARGO AS PERCARDST, PD.PERCORREO AS PERCORDST, PD.PERAVATAR AS PERAVADST,
						PS.PERCODIGO AS PERCODSOL, PS.PERNOMBRE AS PERNOMSOL, PS.PERAPELLI AS PERAPESOL, PS.PERCOMPAN AS PERCOMSOL, PS.PERCARGO AS PERCARSOL, PS.PERCORREO AS PERCORSOL, PS.PERAVATAR AS PERAVASOL,
						R.REUESTADO,R.REUFECHA,R.REUHORA,R.REUREG,
						R.AGEREG,A.AGETITULO,A.AGELUGAR,
						PD.PERREUURL AS PERREUURLDST,PS.PERREUURL AS PERREUURLSOL,
						M.MESCODIGO,M.MESNUMERO,R.REULINK,R.REUTOKSOL,R.REUTOKDST,R.REUTIPO
				FROM REU_CABE R
				LEFT OUTER JOIN PER_MAEST PD ON PD.PERCODIGO=R.PERCODDST
				LEFT OUTER JOIN PER_MAEST PS ON PS.PERCODIGO=R.PERCODSOL
				LEFT OUTER JOIN AGE_MAEST A ON A.AGEREG=R.AGEREG
				LEFT OUTER JOIN MES_MAEST M ON M.MESCODIGO=R.MESCODIGO
				WHERE $where AND (PERCODDST!=PERCODSOL)
				ORDER BY R.REUESTADO ASC,R.REUFECHA,R.REUHORA,R.REUREG";
			
	$Table = sql_query($query,$conn);


	if ($Table->Rows_Count != -1){

		
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$percoddst 		= trim($row['PERCODDST']);
		$pernomdst		= trim($row['PERNOMDST']);
		$perapedst		= trim($row['PERAPEDST']);
		$percomdst		= trim($row['PERCOMDST']);
		$percardst		= trim($row['PERCARDST']);
		$percordst		= trim($row['PERCORDST']);
		$peravadst		= trim($row['PERAVADST']);		
		$percodsol 		= trim($row['PERCODSOL']);
		$pernomsol		= trim($row['PERNOMSOL']);
		$perapesol		= trim($row['PERAPESOL']);
		$percomsol		= trim($row['PERCOMSOL']);
		$percarsol		= trim($row['PERCARSOL']);
		$percorsol		= trim($row['PERCORSOL']);
		$peravasol		= trim($row['PERAVASOL']);
		$agereg			= trim($row['AGEREG']);
		$agetitulo		= trim($row['AGETITULO']);
		$agelugar		= trim($row['AGELUGAR']);
		$mescodigo		= trim($row['MESCODIGO']);	
		$mesnumero		= trim($row['MESNUMERO']);			
		$reufecha		= BDConvFch($row['REUFECHA']);
		
		$reuhora		= substr(trim($row['REUHORA']),0,5);
		$reuhoraorig	= substr(trim($row['REUHORA']),0,5);
		$reuestado		= trim($row['REUESTADO']);
		$reureg			= trim($row['REUREG']);
		$mescodigo  	= trim($row['MESCODIGO']);
		$perreuurldst 	= trim($row['PERREUURLDST']);
		$perreuurlsol 	= trim($row['PERREUURLSOL']);
		$reulink 		= trim($row['REULINK']);
		$reutoksol 		= trim($row['REUTOKSOL']);
		$reutokdst 		= trim($row['REUTOKDST']);
		$reutipo 		= trim($row['REUTIPO']);
		$perfil			= 0;
		$tmpl->setVariable('reutipo'	, $reutipo );

		if ($tipoevento != 'false') {

			$tmpl->setVariable('mostrarqr', '');
		} else {
			$tmpl->setVariable('mostrarqr', 'd-none');
		}


		if ($reutipo == 0){
			$tmpl->setVariable('nombretiporeunion'	, 'Virtual');
			$tmpl->setVariable('displaymesa'	, 'd-none');
		}else{

			$tmpl->setVariable('displaymesa'	, '');
			
			if($mescodigo != ""){
				$tmpl->setVariable('mesnumero'	, $mesnumero );
			}else{
				$tmpl->setVariable('mesnumero'	, 'No tiene mesa asignada' );
			}
			
			

			$tmpl->setVariable('nombretiporeunion'	, 'Presencial');
		}

	


		if ($IdiomView == 'ING'){
		$tmpl->setVariable('Ingresar_Reunion'	, 'Enter meeting');
		$tmpl->setVariable('Idioma_Contacto'	, 'Contact');
		$tmpl->setVariable('Idioma_Encuesta'	, 'Survey');
		$tmpl->setVariable('Idioma_Eliminar'	, 'Delete');
		$tmpl->setVariable('Idioma_BotonCan'	, 'Cancel');
		$tmpl->setVariable('Aceptar_Reprogramar'	, 'Accept / Edit');
		$tmpl->setVariable('Ingresar'	, 'Join');
		}else if ($IdiomView == 'ESP'){
		$tmpl->setVariable('Ingresar_Reunion'	, 'Ingresar a Reunión');
		$tmpl->setVariable('Idioma_Contacto'	, 'Contacto');
		$tmpl->setVariable('Idioma_Encuesta'	, 'Encuesta');
		$tmpl->setVariable('Idioma_Eliminar'	, 'ELIMINAR');
		$tmpl->setVariable('Idioma_BotonCan'	, 'Cancelar');
		$tmpl->setVariable('Aceptar_Reprogramar'	, 'Aceptar / EDITAR');
		$tmpl->setVariable('Ingresar'	, 'Ingresar');
		}else{
		$tmpl->setVariable('Ingresar_Reunion'	, 'Ingresar a Reuniões');
		$tmpl->setVariable('Idioma_Contacto'	, 'Contato');
		$tmpl->setVariable('Idioma_Encuesta'	, 'Poll');
		$tmpl->setVariable('Idioma_Eliminar'	, 'ELIMINAR');
		$tmpl->setVariable('Idioma_BotonCan'	, 'Fechar');
		$tmpl->setVariable('Aceptar_Reprogramar'	, 'Aceitar / Editar');
		$tmpl->setVariable('Ingresar'	, 'Ingresar');
		}
		
		
		if ($perreuurldst!=''){
			$perreuurl	= $perreuurldst;
		}else{
			$perreuurl	= $perreuurlsol;
		}
		
		//El perfil que esta viendo el browser es el Destino de las reuniones
		if($percoddst==$percodlog){
			$perfil 	= 1; //Perfil Destino
			$percod 	= $percodsol;
			$pernombre	= $pernomsol;
			$perapelli	= $perapesol;
			$percompan	= $percomsol;
			$percargo	= $percarsol;
			$percorreo	= $percorsol;
			$peravatar	= $peravasol;
			$reutoken	= $reutokdst; //Token de agora
			$reuestconf='Recibida';
			$tmpl->setVariable('btnconfirmarstyle'	, '');
			
		}else{//El perfil que esta viendo el browser es el Solicitante de las reuniones
			$perfil 	= 2; //Perfil Solicitante
			$percod 	= $percoddst;
			$pernombre	= $pernomdst;
			$perapelli	= $perapedst;
			$percompan	= $percomdst;
			$percargo	= $percardst;
			$percorreo	= $percordst;
			$peravatar	= $peravadst;
			
			$reutoken	= $reutoksol; //Token de agora
			$reuestconf='Enviada';
			$tmpl->setVariable('btnconfirmarstyle'	, 'display:none;');
		}
		
		$tmpl->setCurrentBlock('browser');
		
		if($reuestado==2){
			//Hora segun Zona Horaria
			$haux = date('H:i', strtotime('+10800 seconds', strtotime($reuhora))); //Pongo la hora en Huso horario 0
			$haux = date('H:i', strtotime($_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($haux))); //Pongo la hora, segun el Huso horario establecido por el perfil
			$reuhora = $haux;
			$reuhoraconf=$reuhora;
			$reufechaconf=$reufecha;
			$reuzonaconf='Hora Bsas';
			$reuestconf='Confirmada';
			$tmpl->setVariable('colorconexion'	, 'color: #40ae33;');
			$tmpl->setVariable('iconoconexion'	, 'icon-check');
			$reuestdes = "Confirmed meeting in <b>$reufecha</b> at <b>$reuhora</b> <br> Table N° <b>$mescodigo</b> ";
		}else{
			$reuestdes = "Unconfirmed meeting";
			$reuhoraconf=$reuhora;
			$reufechaconf=$reufecha;
			$tmpl->setVariable('colorconexion'	, 'color:#eccd1e;');
			$tmpl->setVariable('iconoconexion'	, 'icon-clock');
			$reuzonaconf='Reunion no confirmada';
			if ($reuestado==3){
				$reuhoraconf=$reuhora;
				$reufechaconf=$reufecha;
				$reuzonaconf='Reunion cancelada';
				$tmpl->setVariable('colorconexion'	, 'color: #dd3300;');
				$tmpl->setVariable('iconoconexion'	, 'icon-close');
				$reuestconf='Cancelada';
				

			}
			//Busco los horarios solicitados para reunion
			$query = "	SELECT S.REUFECHA,S.REUHORA
						FROM REU_CABE R 
						INNER JOIN REU_SOLI S ON S.REUREG=R.REUREG AND R.REUESTADO=S.REUESTADO
						WHERE R.REUREG=$reureg 
						ORDER BY S.REUFECHA,S.REUHORA ";
						
			$TableReu = sql_query($query,$conn);
			for($j=0; $j<$TableReu->Rows_Count; $j++){
				$rowReu		= $TableReu->Rows[$j]; 
				$reufecha 	= BDConvFch($rowReu['REUFECHA']);
				$reuhora 	= substr(trim($rowReu['REUHORA']),0,5);
				
				//Hora segun Zona Horaria
				$haux = date('H:i', strtotime('+10800 seconds', strtotime($reuhora))); //Pongo la hora en Huso horario 0
				$haux = date('H:i', strtotime($_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($haux))); //Pongo la hora, segun el Huso horario establecido por el perfil
				$reuhora = $haux;
				$reuhoraconf.=' '.$reuhora.'  ';
				$reufechaconf=$reufecha;
				if ($reuestado!=3){
					$reuzonaconf='Reunion no confirmada';
				}
				
				$reuestdes .= '<br> * Date: '.$reufecha.' at '.$reuhora;
			}
		}
		$tmpl->setVariable('btneliminarstyle'	, 'display:none;');
		if($fltbuscar==1 || $fltbuscar==2 || $fltbuscar==3){ //Si son enviadas o confirmadas
			
			
			if ($reuestado==3){
				$tmpl->setVariable('btneliminarstyle'	, 'display:;');
				$tmpl->setVariable('btncancelarstyle'	, 'display:none;');
				$tmpl->setVariable('btnconfirmarstyle'	, 'display:none;');
			}

			if ($reuestado==2){
				$tmpl->setVariable('btnconfirmarstyle'	, 'display:none;');
			}
			
		}else if($fltbuscar==4){ //Si son canceladas
			if ($reuestado==3){
				$tmpl->setVariable('btneliminarstyle'	, 'display:;');
			}
			$tmpl->setVariable('btnconfirmarstyle'	, 'display:none;');
			$tmpl->setVariable('btncancelarstyle'	, 'display:none;');
			
		}
		
		//Si es un evento de agenda
		if($agereg!=0 && $agereg!=''){
			$percompan = $agetitulo;
			$pernombre = '';
			$perapelli = $agelugar;
			$reuestdes = "Event: <b>$reufecha</b> at <b>$reuhora</b>";
			$tmpl->setVariable('btnverperfil'	, 'display:none;');
			$tmpl->setVariable('titulocalendar', $agetitulo);
			
		}else{

			if ($IdiomView == 'ING'){

				$tmpl->setVariable('titulocalendar', 'Meeting with '. $pernombre.' '. $perapelli );
			}else if ($IdiomView == 'ESP'){
				$tmpl->setVariable('titulocalendar', 'Reunión con '. $pernombre.' '. $perapelli );
			}else{
				$tmpl->setVariable('titulocalendar', 'Reunião  com '. $pernombre.' '. $perapelli );
			}
			
		}
		
		if ($IdiomView == 'ESP')
		{
			$tmpl->setVariable('agregarcalendario', 'Agregar');
		}else if ($IdiomView == 'ING'){
			$tmpl->setVariable('agregarcalendario', 'Add');
		}else{
			$tmpl->setVariable('agregarcalendario', 'Adicionar');
		}

		$tmpl->setVariable('reureg'		, $reureg	);
		$tmpl->setVariable('percodigo'	, $percod	);
		$tmpl->setVariable('pernombre'	, $pernombre);
		$tmpl->setVariable('perapelli'	, $perapelli);
		$tmpl->setVariable('percompan'	, $percompan);
		$tmpl->setVariable('percargo'	, $percargo);
		$tmpl->setVariable('percorreo'	, $percorreo);
		$tmpl->setVariable('reuestdes'	, $reuestdes);	
		$tmpl->setVariable('reulink'	, $reulink);
		$tmpl->setVariable('reuhoraconf'	, $reuhoraconf);
		$tmpl->setVariable('reufechaconf'	, $reufechaconf);
		$tmpl->setVariable('reuzonaconf'	, $reuzonaconf);	
		$tmpl->setVariable('percordst'	, $percordst);	
		$tmpl->setVariable('percorsol'	, $percorsol);
		$tmpl->setVariable('horacalendar',substr($reuhora, 0, 2));
		$tmpl->setVariable('minutoscalendar', $reuhora[3].$reuhora[4]);
		$tmpl->setVariable('locationcalendar', URL_WEB );
		$time = strtotime($reuhora);
		$horareunionnueva = date("H:i", strtotime('+30 minutes', $time));
		$tmpl->setVariable('minutosfincalendar', $horareunionnueva );

		$fechacalendar	= substr($reufecha, 6, 4) . '-' . substr($reufecha, 3, 2) . '-' . substr($reufecha, 0, 2); //Formato calendario (yyyy-mm-dd)
		$tmpl->setVariable('fechacalendar',str_replace ( '-', '', $fechacalendar));		
		$tmpl->setVariable('pernomlog'	, $pernomlog);
		$tmpl->setVariable('perapelog'	, $perapelog);
		$tmpl->setVariable('percompanlog'	, $percompanlog);
		$tmpl->setVariable('percargolog'	, $percargolog);
		$tmpl->setVariable('reuestconf'	, $reuestconf);
		if($peravatarlog!=''){
			$tmpl->setVariable('peravatarlog'	, $pathimagenes.$percodlog.'/'.$peravatarlog);
		}else{
			$tmpl->setVariable('peravatarlog'	, $imgAvatarNull);
		}

		//Link de Reunion Externa
		if($reulink!=''){
			$tmpl->setVariable('reuilnk'	, $reulink);
		}else{
			$tmpl->setVariable('btnverreulink'	, 'display:none;');
		}


		//Link de Reuniones de perfil
		if($perreuurl==''){
			$tmpl->setVariable('btnvercontact'	, 'display:none;');
		}else{
			$tmpl->setVariable('perreuurl', $perreuurl);
		}

		if($peravatar!=''){
			$tmpl->setVariable('peravatar'	, $pathimagenes.$percod.'/'.$peravatar);
		}else{
			$tmpl->setVariable('peravatar'	, $imgAvatarNull);
		}
		
		//Encuesta, solo para reuninones Confirmadas
		if($encExists){
			if(($fltbuscar==3 || $fltbuscar==1) && $reuestado==2 ){
				//Verifico si la reunion ya paso, para poder mostrar el boton de encuesta
				$diaaux = explode('/',$reufecha);
				$diahor = strtotime($diaaux[1].'/'.$diaaux[0].'/'.$diaaux[2].' '.$reuhoraorig);
				$hoy 	= strtotime(date('m/d/Y H:i:s'));
				$durreunion = intval($params['SisEventoHorarioIntervalo']);
				$finreunion = strtotime("+$durreunion minutes", $diahor);

				if($hoy > $finreunion){
					//Busco si la encuesta esta completa
					$queryEnc = "SELECT REUREG FROM ENC_RESP WHERE REUREG=$reureg AND PERCODIGO=$percodlog";
					$TableEnc = sql_query($queryEnc,$conn);
                    if($TableEnc->Rows_Count>0){
						$tmpl->setVariable('btnverencuesta'	, 'd-none');
					}else{

						

						if($encObliga){
							$encObligaSinContestar = true;
						}

						$tmpl->setVariable('btnverencuesta'	, '');
					}
					
				}else{ //Aun no llego el momento de la reunion
					$tmpl->setVariable('btnverencuesta'	, 'd-none');
				}
				
				
			}else{
				$tmpl->setVariable('btnverencuesta'	, 'd-none');
			}
		}else{ //No hay ninguna encuesta en el sistema
			$tmpl->setVariable('btnverencuesta'	, 'd-none');
		}
		
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
		// VIDEOCONFERENCIAS SERVICIO AGORA *******************************************
		if($videoconferencia=='AGORA'){
			//Verifico si el perfil tiene reuniones proximas para el dia de hoy
			//Si no tiene ya token generado
			//Genero token de conexion
			$reucanal = md5($reureg.'OnLifeBtBOX');
			$uid	  = $percodlog+90000;
			
			$intervalo = intval($params['SisEventoHorarioIntervalo'])+10;
			$diaaux = explode('/',$reufecha);
			$diahor = strtotime($diaaux[1].'/'.$diaaux[0].'/'.$diaaux[2].' '.$reuhoraorig);
			$hoy 	= strtotime(date('m/d/Y H:i:s'));
			$diagentoken = strtotime("-30 minutes", $diahor);
			$diafinreunion = strtotime("+$intervalo minutes", $diahor);
			
			$tmpl->setVariable('btnagendarcal'	, 'display:none;');
			$tmpl->setVariable('btnvercall'	, 'display:none;');
			if($reuestado==2 && $reutoken==''){
				$tmpl->setVariable('btnagendarcal'	, 'display:;');
				if($hoy >= $diagentoken && $hoy <= $diafinreunion){
					$reutoken = agoraGenToken($reucanal,$uid);
					
					//logerror('canal:'.$reucanal);
					//logerror('iduser:'.$uid);
					//logerror('token:'.$reutoken);
									
					if($perfil==1){ //Perfil Destino
						$query = "	UPDATE REU_CABE SET REUTOKDST='$reutoken' WHERE REUREG=$reureg ";
						$err = sql_execute($query,$conn);
					}elseif($perfil==2){ //Perfil Solicitante
						$query = "	UPDATE REU_CABE SET REUTOKSOL='$reutoken' WHERE REUREG=$reureg ";
						$err = sql_execute($query,$conn);
					}
					$tmpl->setVariable('btnvercall'	, '');				
				}			
						
			}else if($reuestado==2 && $reutoken!=''){
				if($hoy <= $diafinreunion){
					//Ya tiene token de reunion
					$tmpl->setVariable('btnvercall'	, '');
				}
			}
		}
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
		// VIDEOCONFERENCIAS SERVICIO BBB (BigBlueButton) *****************************
		if($videoconferencia=='BBB'){
			$durreunion = intval($params['SisEventoHorarioIntervalo']);
			$tmpl->setVariable('durreunion'	, $durreunion); //Duracion de Reuniones
			$diaaux 	= explode('/',$reufecha);
			$diahor 	= strtotime($diaaux[1].'/'.$diaaux[0].'/'.$diaaux[2].' '.$reuhoraorig); //Dia y Hora d
			$hoy 		= strtotime(date('m/d/Y H:i:s')); //Dia y Hora actual
			$finreunion = strtotime("+$durreunion minutes", $diahor); //Dia y Hora de finalizacion de reunion
						
			$tmpl->setVariable('btnverreulink'	, 'display:none;');
			$tmpl->setVariable('btnvercall'	, 'display:none;');
			$tmpl->setVariable('btnhorasrest'	, 'display:none;');
			$tmpl->setVariable('btnagendarcal'	, 'display:none;');
			if ($reuestado==2){
				$tmpl->setVariable('btnhorasrest'	, 'display:;');
				$tmpl->setVariable('btnagendarcal'	, 'display:;');
			}
			$arrayreuniones[]=['key'=>$diahor,'value'=>$reureg, 'cant'=>$i+1, 'estado'=>$reuestado];
			
		}

		$tmpl->parse('browser');
	}
}else{
	$tmpl->setVariable('display', 'd-none');
}

	
if($encObligaSinContestar){
	$tmpl->setvariable('encobligapend'		, 'true');
	$tmpl->setvariable('encuestassincontestar'		, '');
	
}else{
	$tmpl->setvariable('encobligapend'		, 'false');
	$tmpl->setvariable('encuestassincontestar'		, 'd-none');
}

	$myJSON = json_encode($arrayreuniones);
	$tmpl->setvariable('arrayreuniones'		, $myJSON);
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
