<?php include('../val/valuser.php'); ?>
<?
	// ***********************************************************
	// REVEER FUNCIONAMIENTO GENERAL Y QUERYS
	// ***********************************************************

//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC . '/idioma.php'; //Idioma			
$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('brw.html');

	DDIdioma($tmpl);
	
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$perapelli = (isset($_SESSION[GLBAPPPORT.'PERAPELLI']))? trim($_SESSION[GLBAPPPORT.'PERAPELLI']) : '';
	$perusuacc = (isset($_SESSION[GLBAPPPORT.'PERUSUACC']))? trim($_SESSION[GLBAPPPORT.'PERUSUACC']) : '';
	$percorreo = (isset($_SESSION[GLBAPPPORT.'PERCORREO']))? trim($_SESSION[GLBAPPPORT.'PERCORREO']) : '';
	$peradmin = (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';
	
	$pathimagenes = '../perimg/';
	$imgAvatarNull = '../app-assets/img/avatar.png';
	
	$orientacionSwitch = 1;

	
	
	$conn= sql_conectar();//Apertura de Conexion
	
	//Busco los parametros de configuracion
	$query = "	SELECT ZPARAM,ZVALUE FROM ZZZ_CONF WHERE ZPARAM CONTAINING 'SisEvento' ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row= $Table->Rows[$i];
		$params[trim($row['ZPARAM'])] = trim($row['ZVALUE']);
	}
	
	
	
	
	$where = '';
	$relacion = '';
	
	//Ver reuniones que envie y me enviaron, pero ya estan confirmadas
		$relacion = ' P.PERCODIGO=R.PERCODDST ';
		$where .= "  (R.PERCODDST=$percodigo OR R.PERCODSOL=$percodigo) AND R.REUESTADO=2 ";
	
	
	//Reuniones que solicitaron
	$query = "	SELECT 	PD.PERCODIGO AS PERCODDST, PD.PERNOMBRE AS PERNOMDST, PD.PERAPELLI AS PERAPEDST, PD.PERCOMPAN AS PERCOMDST, PD.PERCORREO AS PERCORDST, PD.PERAVATAR AS PERAVADST,
						PS.PERCODIGO AS PERCODSOL, PS.PERNOMBRE AS PERNOMSOL, PS.PERAPELLI AS PERAPESOL, PS.PERCOMPAN AS PERCOMSOL, PS.PERCORREO AS PERCORSOL, PS.PERAVATAR AS PERAVASOL,
						R.REUESTADO,R.REUFECHA,R.REUHORA,R.REUREG,
						R.AGEREG,A.AGETITULO,A.AGELUGAR,
						PD.PERREUURL AS PERREUURLDST,PS.PERREUURL AS PERREUURLSOL,
						M.MESCODIGO,R.REULINK,R.REUTOKSOL,R.REUTOKDST
				FROM REU_CABE R
				LEFT OUTER JOIN PER_MAEST PD ON PD.PERCODIGO=R.PERCODDST
				LEFT OUTER JOIN PER_MAEST PS ON PS.PERCODIGO=R.PERCODSOL
				LEFT OUTER JOIN AGE_MAEST A ON A.AGEREG=R.AGEREG
				LEFT OUTER JOIN MES_DISP M ON M.REUREG=R.REUREG
				WHERE $where
				ORDER BY R.REUFECHA, R.REUHORA";
			
	$Table = sql_query($query,$conn);	
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$tmpl->setVariable('eliminartitulo'	, 'display:none;'	);
		$tmpl->setVariable('eliminartitulo1'	, 'display:none;'	);
		$percoddst 		= trim($row['PERCODDST']);
		$pernomdst		= trim($row['PERNOMDST']);
		$perapedst		= trim($row['PERAPEDST']);
		$percomdst		= trim($row['PERCOMDST']);
		$percordst		= trim($row['PERCORDST']);
		$peravadst		= trim($row['PERAVADST']);		
		$percodsol 		= trim($row['PERCODSOL']);
		$pernomsol		= trim($row['PERNOMSOL']);
		$perapesol		= trim($row['PERAPESOL']);
		$percomsol		= trim($row['PERCOMSOL']);
		$percorsol		= trim($row['PERCORSOL']);
		$peravasol		= trim($row['PERAVASOL']);
		$agereg			= trim($row['AGEREG']);
		$agetitulo		= trim($row['AGETITULO']);
		$agelugar		= trim($row['AGELUGAR']);		
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
		$perfil			= 0;
		
		//El perfil que esta viendo el browser es el Destino de las reuniones
		if($percoddst==$percodigo){
			$perfil 	= 1; //Perfil Destino
			$percod 	= $percodsol;
			$pernombre	= $pernomsol;
			$perapelli	= $perapesol;
			$percompan	= $percomsol;
			$percorreo	= $percorsol;
			$peravatar	= $peravasol;
			$perreuurl	= $perreuurlsol;
			$reutoken	= $reutokdst; //Token de agora
			
		}else{//El perfil que esta viendo el browser es el Solicitante de las reuniones
			$perfil 	= 2; //Perfil Solicitante
			$percod 	= $percoddst;
			$pernombre	= $pernomdst;
			$perapelli	= $perapedst;
			$percompan	= $percomdst;
			$percorreo	= $percordst;
			$peravatar	= $peravadst;
			$perreuurl	= $perreuurldst;
			
			$reutoken	= $reutoksol; //Token de agora
		}
		
		$tmpl->setCurrentBlock('browser');
		if($reuestado==2){
			//Hora segun Zona Horaria
			$haux = date('H:i', strtotime('+10800 seconds', strtotime($reuhora))); //Pongo la hora en Huso horario 0
			$haux = date('H:i', strtotime($_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($haux))); //Pongo la hora, segun el Huso horario establecido por el perfil
			$reuhora = $haux;
			
			$reuestdes = "Reunión confirmada para el <b>$reufecha</b> a las <b>$reuhora</b> <br> Mesa N° <b>$mescodigo</b> ";
		}else{
			$reuestdes = "Reunión sin confirmar";
			
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
				
				$reuestdes .= '<br> * Fecha: '.$reufecha.' a las '.$reuhora;
			}
		}
		
		
		
		//Si es un evento de agenda
		if($agereg!=0 && $agereg!=''){
			$percompan = $agetitulo;
			$pernombre = '';
			$perapelli = $agelugar;
			$reuhora=$reuhoraorig;
			$reuestdes = "Evento: <b>$reufecha</b> a las <b>$reuhora</b>";
			$tmpl->setVariable('avatarmic', 'ft-mic');

			$tmpl->setVariable('titulocalendar', $agetitulo);
			
		}else{

			$tmpl->setVariable('avatarmic', 'ft-users');

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
			$tmpl->setVariable('agregarcalendario', 'Agregar al calendario');
		}else if ($IdiomView == 'ING'){
			$tmpl->setVariable('agregarcalendario', 'Add to calendar');
		}else{
			$tmpl->setVariable('agregarcalendario', 'Adicionar ao calendário');
		}

		
		
		$tmpl->setVariable('agereg', $agereg);
		$tmpl->setVariable('reureg'		, $reureg	);
		$tmpl->setVariable('percodigo'	, $percod	);
		$tmpl->setVariable('pernombre'	, $pernombre);
		$tmpl->setVariable('perapelli'	, $perapelli);
		$tmpl->setVariable('percompan'	, $percompan);
		$tmpl->setVariable('percorreo'	, $percorreo);
		$tmpl->setVariable('reufecha'	, $reufecha);	
		$tmpl->setVariable('reuhora'	, $reuhora);	
		
		$tmpl->setVariable('percordst'	, $percordst);	
		$tmpl->setVariable('percorsol'	, $percorsol);
		$tmpl->setVariable('horacalendar',substr($reuhora, 0, 2));
		$tmpl->setVariable('minutoscalendar', $reuhora[3].$reuhora[4]);
		$tmpl->setVariable('horafincalendar',substr($reuhora, 0, 2));
		$minutosfincalendar = $reuhora[3].$reuhora[4];
		$tmpl->setVariable('minutosfincalendar', ($reuhora[3].$reuhora[4]) +30);

		$fechacalendar	= substr($reufecha, 6, 4) . '-' . substr($reufecha, 3, 2) . '-' . substr($reufecha, 0, 2); //Formato calendario (yyyy-mm-dd)
		$tmpl->setVariable('fechacalendar',str_replace ( '-', '', $fechacalendar));		
		if ($orientacionSwitch == 1) {

			$orientacion = 'left';
			$tmpl->setVariable('orientacion', $orientacion);
			$orientacionSwitch = 0;
		} else {
			$orientacion = 'right';
			$tmpl->setVariable('orientacion', $orientacion);
			$orientacionSwitch = 1;
		}
		
		
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
		
		$tmpl->parse('personal-calendar');
	}
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
