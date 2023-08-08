<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('coordinar.html');
	DDIdioma($tmpl);


	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$percoddst = (isset($_POST['percoddst']))? trim($_POST['percoddst']) : 0;
	$tiporeunion = (isset($_POST['tiporeunion']))? trim($_POST['tiporeunion']) : 0;
	
	$agefch = (isset($_POST['agefch']))? trim($_POST['agefch']) : 0;
	$tmpl->setVariable('percoddst'	, $percoddst	);

	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	//Busco los parametros de configuracion
	$query = "	SELECT ZPARAM,ZVALUE FROM ZZZ_CONF WHERE ZPARAM CONTAINING 'SisEvento' ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row= $Table->Rows[$i];
		$params[trim($row['ZPARAM'])] = trim($row['ZVALUE']);
	}
	
	$diasini = $params['SisEventoDiaInicio']; 		 			//Evento - Dia de Inicio
	$diasdur = intval($params['SisEventoDuracionDias']); 	 	//Evento - Cantidad de Dias de duracion
	$horaini = $params['SisEventoHorario']; 		 			//Evento - Horario de Inicio y Fin
	$horaint = intval($params['SisEventoHorarioIntervalo']); 	//Evento - Intervalo de tiempo (min)
	$horadescanso = intval($params['SisEventoDescanso']); //Evento - Intervalo de tiempo (min)

	//Busco si ya tengo reunion solicitada sin confirmar
	$query = "	SELECT S.REUFECHA,S.REUHORA
				FROM REU_CABE R 
				LEFT OUTER JOIN REU_SOLI S ON S.REUREG=R.REUREG
				WHERE R.PERCODSOL=$percodigo AND R.PERCODDST=$percoddst AND R.REUESTADO=1 AND S.REUFECHA='$agefch'
				ORDER BY S.REUFECHA,S.REUHORA ";
	$TableReu = sql_query($query,$conn);
	
	
	//Busco los dias disponibles del perfil
	$query = "	SELECT PERDISFCH,PERDISHOR,DISP_BOOL
				FROM PER_DISP
				WHERE PERCODIGO=$percoddst AND PERDISFCH='$agefch' AND DISP_BOOL=1
				ORDER BY PERDISFCH,PERDISHOR ";
	$TableDisp = sql_query($query,$conn);
	//var_dump($TableDisp);die;
	
	//Busco los dias disponibles del perfil 
	$query = "	SELECT PERDISFCH,PERDISHOR,DISP_BOOL
				FROM PER_DISP
				WHERE PERCODIGO=$percodigo AND PERDISFCH='$agefch' AND DISP_BOOL=1
				ORDER BY PERDISFCH,PERDISHOR ";
	$TableDispSol = sql_query($query,$conn);
	//var_dump($TableDispSol);die;
	
	//Busco si ya tengo reunion para la hora confirmada o sin confirmar
	$query = "	SELECT S.REUFECHA,S.REUHORA
				FROM REU_CABE R 
				LEFT OUTER JOIN REU_SOLI S ON S.REUREG=R.REUREG AND R.REUESTADO=S.REUESTADO
				WHERE (R.PERCODSOL=$percodigo OR R.PERCODDST=$percodigo) AND S.REUFECHA='$agefch' AND R.REUESTADO IN(1,2) 
				ORDER BY S.REUFECHA,S.REUHORA ";
	$TableReuConf = sql_query($query,$conn);
	
	//Busco si el solicitado tiene reuniones confirmadas o sin confirmar
	$query = "	SELECT S.REUFECHA,S.REUHORA
				FROM REU_CABE R 
				LEFT OUTER JOIN REU_SOLI S ON S.REUREG=R.REUREG AND R.REUESTADO=S.REUESTADO
				WHERE (R.PERCODSOL=$percoddst OR R.PERCODDST=$percoddst) AND S.REUFECHA='$agefch' AND R.REUESTADO IN(1,2) 
				ORDER BY S.REUFECHA,S.REUHORA ";
	$TableReuSolConf = sql_query($query,$conn);
	
	
	//$vdia 	= explode('/',$diasini);

	//Cargo los bloqueos de horarios y fechas
	$FechasBloq = array();
	$query = "	SELECT DISFCHBLQ,DISHORBLQ FROM DIS_BLOQ ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row= $Table->Rows[$i];
		$disfchblq = BDConvFch($row['DISFCHBLQ']);
		$dishorblq = substr(trim($row['DISHORBLQ']),0,5);
		
		$FechasBloq[$disfchblq][$dishorblq] = 1;
	}

	$tdia	= $agefch; //Formato: 2018-12-31
	
	$vhora	= explode('-',$horaini); //Ej: 09:00-15:30 (inicio - fin)
	$hini	= trim($vhora[0]);
	$hfin	= trim($vhora[1]);
	
	$vhini 	= explode(':',$hini);
	$vhfin 	= explode(':',$hfin);
	$minini = intval($vhini[0])*60 + intval($vhini[1]); //Guardo la duracion en minutos (inicio) 
	$minfin = intval($vhfin[0])*60 + intval($vhfin[1]); //Guardo la duracion en minutos (fin)
	
	//$selectedTime = "9:15:00";
	//$endTime = strtotime("+15 minutes", strtotime($selectedTime));
	//echo date('h:i:s', $endTime);
	$array=null;
	$horaid = 1;

		
		$fecha 		= date('d/m/Y', strtotime($tdia));

		$tmpl->setVariable('fecha'		, $fecha	);
		
		$minaux = $minini;
		while($minaux <= $minfin){
			$hora 		= date('H:i', strtotime('+'.($minaux-$minini).' minutes', strtotime($hini.':00')));
			$fechabd 	= $fecha;
			$horabd		= $hora;
			
			//Hora segun Zona Horaria
			$haux = date('H:i', strtotime('+10800 seconds', strtotime($hora))); //Pongo la hora en Huso horario 0
			$haux = date('H:i', strtotime($_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($haux))); //Pongo la hora, segun el Huso horario establecido por el perfil
			$zhora = $haux;
			
			$tmpl->setCurrentBlock('horas');
			$tmpl->setVariable('horaid'	, $horaid	);
			$tmpl->setVariable('hora'	, $zhora		);
			$tmpl->setVariable('fechabd', $fechabd	);
			$tmpl->setVariable('horabd'	, $horabd	);
			
			//Busco si el que acepta la reunion esta disponible
			$dispExists=1;
			for($j=0; $j<$TableDisp->Rows_Count; $j++){
				$rowDisp= $TableDisp->Rows[$j]; 
				$perdisfch = BDConvFch($rowDisp['PERDISFCH']);
				$dispbool 	= trim($rowDisp['DISP_BOOL']);
				$perdishor = substr(trim($rowDisp['PERDISHOR']),0,5);
				if($fecha == $perdisfch && $hora == $perdishor && $dispbool==1){
					$dispExists=0;
				}
			}
			
			//Busco si tiene disponibilidad el usuario solicitante
			if($dispExists==1){
				$dispExists=1;
				for($j=0; $j<$TableDispSol->Rows_Count; $j++){
					$rowDispSol= $TableDispSol->Rows[$j]; 
					$perdisfch = BDConvFch($rowDispSol['PERDISFCH']);
					$dispbool 	= trim($rowDispSol['DISP_BOOL']);
					$perdishor = substr(trim($rowDispSol['PERDISHOR']),0,5);
					if($fecha == $perdisfch && $hora == $perdishor && $dispbool==1){
						$dispExists=2;
						
					}
				}
			}
			
			//Busco si en mis horarios disponibles, no tengo ninguna reunion confirmada o sin confirmar
			if($dispExists==1){
				for($j=0; $j<$TableReuConf->Rows_Count; $j++){
					$rowReuConf= $TableReuConf->Rows[$j]; 
					$reufecha = BDConvFch($rowReuConf['REUFECHA']);
					$reuhora = substr(trim($rowReuConf['REUHORA']),0,5);
					if($fecha == $reufecha && $hora == $reuhora){
						$dispExists=3;
					}
				}
			}
			
			//Busco si en los horarios del solicitado disponibles, si no tiene ninguna reunion confirmada o sin confirmar
			if($dispExists==1){
				for($j=0; $j<$TableReuSolConf->Rows_Count; $j++){
					$rowReuSolConf= $TableReuSolConf->Rows[$j]; 
					$reufecha = BDConvFch($rowReuSolConf['REUFECHA']);
					$reuhora = substr(trim($rowReuSolConf['REUHORA']),0,5);
					if($fecha == $reufecha && $hora == $reuhora){
						$dispExists=4;
					}
				}
			}

			// BUSCO EN CASO DE EVENTO PRESENCIAL O HIBRIDO SI HAY HORARIO DE MESA FLOTANTE
			if($tiporeunion == 1){ 
				
				$buscoflotante = 0;
				/// BUSCO MESA FIJA
				// BUSCO LA MESA DEL RECEPTOR 
				$querymesa = " 	SELECT MESCODIGO 
				FROM MES_MAEST 
				WHERE PERCODIGO=$percoddst AND ESTCODIGO<>3";
	
				$TableMesa = sql_query($querymesa,$conn);
				if($TableMesa->Rows_Count>0){
					$rowmesa = $TableMesa->Rows[0];
					$mesnumero 	= trim($rowmesa['MESCODIGO']);
				}else{
	
				// BUSCO MESA SOLICITANTE	
				$querymesa2 = " 	SELECT MESCODIGO 
				FROM MES_MAEST 
				WHERE PERCODIGO=$percodigo AND ESTCODIGO<>3";
	
				$TableMesa2 = sql_query($querymesa2,$conn);
	
				if($TableMesa2->Rows_Count>0){
				$rowmesa2 = $TableMesa2->Rows[0];
				$mesnumero 	= trim($rowmesa2['MESCODIGO']);	
				}else{

				$reufechacheck 	= ConvFechaBD($fechabd);
				$reuhoracheck 	= VarNullBD($horabd  , 'S');

				$buscoflotante = 1;	
					//BUSCO MESA FLOTANTE 
				$queryflotante = " 	SELECT MESCODIGO 
				FROM MES_MAEST
				WHERE ESTCODIGO=2";
	
				$TableFlotante = sql_query($queryflotante,$conn);
	
				for($m=0; $m<$TableFlotante->Rows_Count; $m++){
	
					$rowFlotante= $TableFlotante->Rows[$m];
					$mesnumero = trim($rowFlotante['MESCODIGO']);

				
					// BUSCO SI ESTA LIBRE EL HORARIO
							$queryhorariomesa = " 	SELECT MESCODIGO 
							FROM MES_DISP
							WHERE MESCODIGO=$mesnumero AND MESDISFCH=$reufechacheck AND MESDISHOR=$reuhoracheck";
	
							$TableHM = sql_query($queryhorariomesa,$conn);
							if($TableHM->Rows_Count>0){
								$rowmesa = $TableHM->Rows[0];
								$mesnumero 	= trim($rowmesa['MESCODIGO']);
							}else{
							$buscoflotante =2;
							 break;
							}
					
	
					}

					if($buscoflotante == 1){
						
						/// NO HAY FLOTANTE DISPONIBLE
						$dispExists=5;

					}
	
				}
				}

			}
			//
			
			if($dispExists==0){
				$tmpl->setVariable('datacolor'		, 'danger'	);
				$tmpl->setVariable('datajackcolor'	, 'danger'	);
				$tmpl->setVariable('horadisabled'	, 'disabled checked');
			}else if($dispExists==2){
				$tmpl->setVariable('datacolor'		, 'danger'	);
				$tmpl->setVariable('datajackcolor'	, 'danger'	);
				$tmpl->setVariable('horadisabled'	, 'disabled checked');
			}else if($dispExists==3){ //Tengo reuniones en horario
				$tmpl->setVariable('datacolor'		, 'warning'	);
				$tmpl->setVariable('datajackcolor'	, 'warning'	);
				$tmpl->setVariable('horadisabled'	, 'disabled checked');
			}else if($dispExists==4){ //Tengo reuniones en horario
				$tmpl->setVariable('datacolor'		, 'warning'	);
				$tmpl->setVariable('datajackcolor'	, 'warning'	);
				$tmpl->setVariable('horadisabled'	, 'disabled checked');
			}else if($dispExists==5){ //No hay mesas disponibles
				$tmpl->setVariable('datacolor'		, 'warning'	);
				$tmpl->setVariable('datajackcolor'	, 'warning'	);
				$tmpl->setVariable('horadisabled'	, 'disabled checked');
			}else{
				$tmpl->setVariable('datacolor'		, 'success'	);
				$tmpl->setVariable('datajackcolor'	, 'success'	);
			}
			
			$reuExists=false;
			for($j=0; $j<$TableReu->Rows_Count; $j++){
				$rowReu= $TableReu->Rows[$j]; 
				$reufecha = BDConvFch($rowReu['REUFECHA']);
				$reuhora = substr(trim($rowReu['REUHORA']),0,5);
				if($fecha == $reufecha && $hora == $reuhora){
					$reuExists=true;
				}
			}
			
			if(!$reuExists){
				for($j=0; $j<$TableReuConf->Rows_Count; $j++){
					$rowReuConf= $TableReuConf->Rows[$j]; 
					$reufecha = BDConvFch($rowReuConf['REUFECHA']);
					$reuhora = substr(trim($rowReuConf['REUHORA']),0,5);
					if($fecha == $reufecha && $hora == $reuhora){
						$reuExists=true;
					}
				}
			}
			
			if($reuExists){
				$tmpl->setVariable('horadisabled'	, 'disabled checked');
				if(isset($FechasBloq[$fechabd][$horabd])){
					$array[]=['horaid'=>$horaid,'hora'=>$zhora,'fechabd'=>$fechabd,'horabd'=>$horabd,'dispExists'=>$dispExists,'reuExists'=>1,'horadisabled'=>1];
				}else{

					$array[]=['horaid'=>$horaid,'hora'=>$zhora,'fechabd'=>$fechabd,'horabd'=>$horabd,'dispExists'=>$dispExists,'reuExists'=>0,'horadisabled'=>0];

				}


			}else{
				
				if(isset($FechasBloq[$fechabd][$horabd])){
					$array[]=['horaid'=>$horaid,'hora'=>$zhora,'fechabd'=>$fechabd,'horabd'=>$horabd,'dispExists'=>$dispExists,'reuExists'=>1,'horadisabled'=>1];
				}else{

					$array[]=['horaid'=>$horaid,'hora'=>$zhora,'fechabd'=>$fechabd,'horabd'=>$horabd,'dispExists'=>$dispExists,'reuExists'=>1,'horadisabled'=>0];


				}

			}

			
			$tmpl->parse('horas');
			
			$minaux += $horaint + $horadescanso; //Incremento el intervalo
			$horaid++;
		}
		
		$myJSON = json_encode($array);
		echo $myJSON;
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	
	
?>	
