<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/class.phpmailer.php';
	require_once GLBRutaFUNC.'/class.smtp.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
	require_once GLBRutaFUNC.'/constants.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('brw.html');
	DDIdioma($tmpl);
	
	//Diccionario de idiomas
	
	$agefch    = (isset($_POST['fecha']))? trim($_POST['fecha']) : 0;
	$mesas = (isset($_POST['mesas']))? $_POST['mesas'] : 0;

	$conn = sql_conectar(); //Apertura de Conexion


	//var_dump($usuarios);die;

	$arraylength = sizeof($mesas);
	for ($i = 0; $i < $arraylength; $i++) {
				$tmpl->setCurrentBlock('mesas');
					$query = "	SELECT MESCODIGO,MESNUMERO
					FROM MES_MAEST 
					WHERE ESTCODIGO<>3 AND MESCODIGO = $mesas[$i]
					 ";
					$Table = sql_query($query, $conn);
					for ($j = 0; $j < $Table->Rows_Count; $j++) {
					$row = $Table->Rows[$j];
					$mescodigo 	= trim($row['MESCODIGO']);
					$mesnumero	= trim($row['MESNUMERO']);
					
					// $tmpl->setVariable('mescodigo', $mescodigo);
					$tmpl->setVariable('mesnumero', $mesnumero);
					$tmpl->parse('mesas');
			}
	}

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


	$tdia	= $agefch; //Formato: 2018-12-31
	
	$vhora	= explode('-',$horaini); //Ej: 09:00-15:30 (inicio - fin)
	$hini	= trim($vhora[0]);
	$hfin	= trim($vhora[1]);
	
	$vhini 	= explode(':',$hini);
	$vhfin 	= explode(':',$hfin);
	$minini = intval($vhini[0])*60 + intval($vhini[1]); //Guardo la duracion en minutos (inicio) 
	$minfin = intval($vhfin[0])*60 + intval($vhfin[1]); //Guardo la duracion en minutos (fin)
	$fecha 		= date('d/m/Y', strtotime($tdia));
	$minaux = $minini;
		while($minaux <= $minfin){
			$hora 		= date('H:i', strtotime('+'.($minaux-$minini).' minutes', strtotime($hini.':00')));
			$fechabd 	= $fecha;
			$horabd		= $hora;
			
			//Hora segun Zona Horaria
			$haux = date('H:i', strtotime('+10800 seconds', strtotime($hora))); //Pongo la hora en Huso horario 0
			$haux = date('H:i', strtotime($_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($haux))); //Pongo la hora, segun el Huso horario establecido por el perfil
			$zhora = $haux;
		$tmpl->setCurrentBlock('horarios');

			for ($k = 0; $k < $arraylength; $k++) {
				//Busco si ya tengo reunion para la hora confirmada o sin confirmar
				
				// VER SI ES MESA FIJA O FLOTANTE
				$query = "	SELECT ESTCODIGO,PERCODIGO FROM MES_MAEST WHERE MESCODIGO=$mesas[$k]";
					$TableTipoMesa = sql_query($query,$conn);
					if ($TableTipoMesa->Rows_Count>0){
						$row= $TableTipoMesa->Rows[0];
						$tipomesa 	= trim($row['ESTCODIGO']);
						$usuariomesa 	= trim($row['PERCODIGO']);
					}
				// VER SI ES MESA FIJA O FLOTANTE

				
					
				$tmpl->setCurrentBlock('reuniones');
				if ($k==0){
					$tmpl->setVariable('displaybotonreunion', 'd-none');
					$tmpl->setVariable('displaybotonpendiente', 'd-none');
					$tmpl->setVariable('displaybotoneliminarconfirmada', 'd-none');
					$tmpl->setVariable('displaybotoneliminarpendiente', 'd-none');
					$tmpl->setVariable('displaynombrereunion', '');
					$tmpl->setVariable('displaynombreestadoreunion', 'd-none');
					$tmpl->setVariable('variablereunion', $zhora);
				}else{
					$usuarioreunion="Horario Libre";
					$tmpl->setVariable('displaybotonreunion', '');
					$tmpl->setVariable('displaynombrereunion', 'd-none');
					$tmpl->setVariable('displaynombreestadoreunion', 'd-none');
					$tmpl->setVariable('displaybotonpendiente', 'd-none');
					$tmpl->setVariable('displaybotoneliminarconfirmada', 'd-none');
					$tmpl->setVariable('displaybotoneliminarpendiente', 'd-none');
					$tmpl->setVariable('solicitante', $mesas[$k]);
					$tmpl->setVariable('fechasol', $agefch);
					$tmpl->setVariable('horasol', $horabd);
					$query = "	SELECT DISFCHBLQ,DISHORBLQ FROM DIS_BLOQ WHERE DISFCHBLQ='$agefch' AND DISHORBLQ='$horabd' ";
					$TableBloq = sql_query($query,$conn);
					if ($TableBloq->Rows_Count>0){
						$usuarioreunion="Horario bloqueado por el evento";
						$tmpl->setVariable('displaybotonreunion', 'd-none');
						$tmpl->setVariable('displaynombreestadoreunion', 'd-none');
						$tmpl->setVariable('displaynombrereunion', '');
					}

					if ( ($tipomesa==1) && ($usuariomesa!==0) ){

					$query = "	SELECT PERDISFCH,PERDISHOR,DISP_BOOL
								FROM PER_DISP
								WHERE PERCODIGO=$usuariomesa AND PERDISFCH='$agefch' AND PERDISHOR='$horabd' AND DISP_BOOL=1
								ORDER BY PERDISFCH,PERDISHOR ";
					$TableDispSol = sql_query($query,$conn);
					
					if ($TableDispSol->Rows_Count>0){
						$usuarioreunion="Horario No Disponible";
						$tmpl->setVariable('displaybotonreunion', 'd-none');
						$tmpl->setVariable('displaynombreestadoreunion', 'd-none');
						$tmpl->setVariable('displaynombrereunion', '');
					}
					
					$query = "	SELECT R.REUREG,R.REUFECHA,R.REUHORA,R.PERCODDST,R.PERCODSOL,R.REUESTADO,PS.PERCODIGO AS PERCODSOL,PS.PERCOMPAN AS SOLEMPRESA,PS.PERNOMBRE AS SOLNOMBRE,PS.PERAPELLI AS SOLAPELLI,
					PD.PERCODIGO AS PERCODDST,PD.PERCOMPAN AS DSTEMPRESA,PD.PERNOMBRE AS DSTNOMBRE,PD.PERAPELLI AS DSTAPELLI
					FROM REU_CABE R 
					LEFT OUTER JOIN REU_SOLI S ON S.REUREG=R.REUREG AND R.REUESTADO=S.REUESTADO
					LEFT OUTER JOIN PER_MAEST PS ON R.PERCODSOL=PS.PERCODIGO
					LEFT OUTER JOIN PER_MAEST PD ON R.PERCODDST=PD.PERCODIGO
					WHERE (R.PERCODSOL = $usuariomesa OR R.PERCODDST = $usuariomesa) AND (S.REUFECHA='$agefch' OR R.REUFECHA='$agefch') AND (S.REUHORA='$horabd' OR R.REUHORA='$horabd') AND R.REUESTADO IN(1,2) 
					ORDER BY S.REUFECHA,S.REUHORA ";
					$TableReuConf = sql_query($query,$conn);
					
					$reuestado='';
					if ($TableReuConf->Rows_Count>0){
						
						$row= $TableReuConf->Rows[0];
						$reureg 	= trim($row['REUREG']);
						$percoddst 	= trim($row['PERCODDST']);
						$percodsol 	= trim($row['PERCODSOL']);
						$reuestado 	= trim($row['REUESTADO']);
						$solempresa	= trim($row['SOLEMPRESA']);
						$solnombre	= trim($row['SOLNOMBRE']);
						$solapelli	= trim($row['SOLAPELLI']);
						$dstempresa	= trim($row['DSTEMPRESA']);
						$dstnombre	= trim($row['DSTNOMBRE']);
						$dstapelli	= trim($row['DSTAPELLI']);


						if ($percoddst==$usuariomesa){

							$usuarioreunion=$solempresa.' (' .$solnombre.' '.$solapelli.')'.' - '.$dstempresa.' (' .$dstnombre.' '.$dstapelli.')';
						}else{
							$usuarioreunion=$dstempresa.' (' .$dstnombre.' '.$dstapelli.')'.' - '.$solempresa.' (' .$solnombre.' '.$solapelli.')';
						}
						
						if ($reuestado==1){
							$reuestado='Pendiente';
							$tmpl->setVariable('reureg', $reureg);
							$tmpl->setVariable('pendsolicitante', $percodsol);
							$tmpl->setVariable('pendcontraparte', $percoddst);
							$tmpl->setVariable('displaybotonpendiente', '');
							$tmpl->setVariable('displaybotoneliminarconfirmada', 'd-none');
							$tmpl->setVariable('displaybotoneliminarpendiente', '');
							$tmpl->setVariable('displaynombreestadoreunion', '');
						}else{
							$tmpl->setVariable('reureg', $reureg);
							$tmpl->setVariable('displaynombreestadoreunion', '');
							$tmpl->setVariable('displaybotoneliminarconfirmada', '');
							$tmpl->setVariable('displaybotoneliminarpendiente', 'd-none');
							$reuestado='Confirmada';
						}
						$tmpl->setVariable('displaybotonreunion', 'd-none');
						$tmpl->setVariable('displaynombrereunion', '');
					}

					}else{
						// ME FIJO SI HAY REUNIONES EN LA MESA FLOTANTE
						$query = "	SELECT R.REUREG,R.REUFECHA,R.REUHORA,R.PERCODDST,R.PERCODSOL,R.REUESTADO,PS.PERCODIGO AS PERCODSOL,PS.PERCOMPAN AS SOLEMPRESA,PS.PERNOMBRE AS SOLNOMBRE,PS.PERAPELLI AS SOLAPELLI,
						PD.PERCODIGO AS PERCODDST,PD.PERCOMPAN AS DSTEMPRESA,PD.PERNOMBRE AS DSTNOMBRE,PD.PERAPELLI AS DSTAPELLI
						FROM REU_CABE R 
						LEFT OUTER JOIN REU_SOLI S ON S.REUREG=R.REUREG AND R.REUESTADO=S.REUESTADO
						LEFT OUTER JOIN MES_DISP MS ON R.REUREG=MS.REUREG
						LEFT OUTER JOIN PER_MAEST PS ON R.PERCODSOL=PS.PERCODIGO
						LEFT OUTER JOIN PER_MAEST PD ON R.PERCODDST=PD.PERCODIGO
						WHERE MS.MESCODIGO=$mesas[$k] AND (S.REUFECHA='$agefch' OR R.REUFECHA='$agefch') AND (S.REUHORA='$horabd' OR R.REUHORA='$horabd') AND R.REUESTADO IN(1,2) 
						ORDER BY S.REUFECHA,S.REUHORA ";
						$TableReuConf = sql_query($query,$conn);

						$TableReuConf = sql_query($query,$conn);
					
					$reuestado='';
					if ($TableReuConf->Rows_Count>0){
						
						$row= $TableReuConf->Rows[0];
						$reureg 	= trim($row['REUREG']);
						$percoddst 	= trim($row['PERCODDST']);
						$percodsol 	= trim($row['PERCODSOL']);
						$reuestado 	= trim($row['REUESTADO']);
						$solempresa	= trim($row['SOLEMPRESA']);
						$solnombre	= trim($row['SOLNOMBRE']);
						$solapelli	= trim($row['SOLAPELLI']);
						$dstempresa	= trim($row['DSTEMPRESA']);
						$dstnombre	= trim($row['DSTNOMBRE']);
						$dstapelli	= trim($row['DSTAPELLI']);



						if ($percoddst==$usuariomesa){

							$usuarioreunion=$solempresa.' (' .$solnombre.' '.$solapelli.')'.' - '.$dstempresa.' (' .$dstnombre.' '.$dstapelli.')';
						}else{
							$usuarioreunion=$dstempresa.' (' .$dstnombre.' '.$dstapelli.')'.' - '.$solempresa.' (' .$solnombre.' '.$solapelli.')';
						}
						
						if ($reuestado==1){
							$reuestado='Pendiente';
							$tmpl->setVariable('reureg', $reureg);
							$tmpl->setVariable('pendsolicitante', $percodsol);
							$tmpl->setVariable('pendcontraparte', $percoddst);
							$tmpl->setVariable('displaybotonpendiente', '');
							$tmpl->setVariable('displaybotoneliminarconfirmada', 'd-none');
							$tmpl->setVariable('displaybotoneliminarpendiente', '');
							$tmpl->setVariable('displaynombreestadoreunion', '');
						}else{
							$tmpl->setVariable('reureg', $reureg);
							$tmpl->setVariable('displaynombreestadoreunion', '');
							$tmpl->setVariable('displaybotoneliminarconfirmada', '');
							$tmpl->setVariable('displaybotoneliminarpendiente', 'd-none');
							$reuestado='Confirmada';
						}
						$tmpl->setVariable('displaybotonreunion', 'd-none');
						$tmpl->setVariable('displaynombrereunion', '');
					}

					}
					$tmpl->setVariable('variablereunion', $usuarioreunion);
					$tmpl->setVariable('variableestadoreunion', 'Estado: '.$reuestado.' - Acción: ');
				}
				
				$tmpl->parse('reuniones');
			}

		$tmpl->parse('horarios');
		$minaux += $horaint + $horadescanso; //Incremento el intervalo
		$horaid++;
}
	
	

	
	
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);

	$tmpl->show();
	
	//--------------------------------------------------------------------------------------------------------------
?>	
