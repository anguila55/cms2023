<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('disponibilidad.html');
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	//$percodigo = (isset($_POST['percodigo']))? trim($_POST['percodigo']) : 0;
	$agefch = (isset($_POST['agefch']))? trim($_POST['agefch']) : 0;
	
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	//Busco los parametros de configuracion
	$query = "	SELECT ZPARAM,ZVALUE FROM ZZZ_CONF WHERE ZPARAM CONTAINING 'SisEvento' ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row= $Table->Rows[$i];
		$params[trim($row['ZPARAM'])] = trim($row['ZVALUE']);
	}


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
	
	$diasini = $params['SisEventoDiaInicio']; 		 			//Evento - Dia de Inicio
	$diasdur = intval($params['SisEventoDuracionDias']); 	 	//Evento - Cantidad de Dias de duracion
	$horaini = $params['SisEventoHorario']; 		 			//Evento - Horario de Inicio y Fin
	$horaint = intval($params['SisEventoHorarioIntervalo']); 	//Evento - Intervalo de tiempo (min)
	$horadescanso = intval($params['SisEventoDescanso']); //Evento - Intervalo de tiempo (min)

	
	$tdia	= $agefch; //Formato: 2018-12-31
	$fechavariable = explode('-',$tdia); //Ej: 09:00-15:30 (inicio - fin)
	$fechafinal = trim($fechavariable[0]).trim($fechavariable[1]).trim($fechavariable[2]);
	
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
			$horaids = $horaid.$fechafinal;
			$tmpl->setVariable('hora'	, $zhora	);
			$tmpl->setVariable('fechabd', $fechabd	);
			$tmpl->setVariable('horabd'	, $horabd	);
			//$array[]=['horaid'=>$horaid,'hora'=>$zhora,'fechabd'=>$fechabd,'horabd'=>$horabd,'horachecked'=>1];
			//Busco si la info ya esta checkeada
			if(isset($_POST['dataDisponibilidad'])){
				$array[]=['horaids'=>$horaids,'hora'=>$zhora,'fechabd'=>$fechabd,'horabd'=>$horabd,'horachecked'=>1,'horadisabled'=>0];
				
				foreach($_POST['dataDisponibilidad'] as $ind => $data){
					
					if($fecha == $data['fecha'] && $hora == $data['hora'] && $data['dispbool'] == 1){
						
						$tmpl->setVariable('horachecked'	, 'checked'	);
						
						$array[$horaid-1]['horachecked']=0;
						
					}
					
				}
				
				
			}else{
				$tmpl->setVariable('horachecked'	, 'checked'	);
				$array[]=['horaids'=>$horaids,'hora'=>$zhora,'fechabd'=>$fechabd,'horabd'=>$horabd,'horachecked'=>1,'horadisabled'=>0];
			}

			//Controlo si esta bloqueado el horario
			if(isset($FechasBloq[$fechabd][$horabd])){
				$array[$horaid-1]['horadisabled']=1;
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
