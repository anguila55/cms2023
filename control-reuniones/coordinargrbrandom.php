
<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC . '/constants.php';
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';
		
	
	
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	//$trans	= sql_begin_trans($conn);
	

	$whereVenComPerLog = "PERVENCOM IN ('V','A') ";
	$whereVenComPerBsq = "PERVENCOM IN ('C','A') ";


	///// TRAIGO TODOS LOS PARAMETROS DE CONFIGURACION DE REUNIONES
	//Busco los parametros de configuracion
	$query = "	SELECT ZPARAM,ZVALUE FROM ZZZ_CONF WHERE ZPARAM CONTAINING 'SisEvento' ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row= $Table->Rows[$i];
		$params[trim($row['ZPARAM'])] = trim($row['ZVALUE']);
	}
	
	$diasini = $params['SisEventoDiaInicio']; 		 			//Evento - Dia de Inicio
	$diasdur = intval($params['SisEventoDuracionDias']); 
	$diasdur=$diasdur+1;	 	//Evento - Cantidad de Dias de duracion
	$horaini = $params['SisEventoHorario']; 		 			//Evento - Horario de Inicio y Fin
	$horaint = intval($params['SisEventoHorarioIntervalo']); 	//Evento - Intervalo de tiempo (min)

	////// CARGO LOS BLOQUEOS Y FECHAS
	//Cargo los bloqueos de horarios y fechas
	$FechasBloq = array();
	$query = "	SELECT DISFCHBLQ,DISHORBLQ FROM DIS_BLOQ ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row= $Table->Rows[$i];
		$disfchblq = trim($row['DISFCHBLQ']);
		$dishorblq = substr(trim($row['DISHORBLQ']),0,5);
		
		$FechasBloq[$disfchblq][$dishorblq] = 1;
	}
	
	$vhora	= explode('-',$horaini); //Ej: 09:00-15:30 (inicio - fin)
	$hini	= trim($vhora[0]);
	$hfin	= trim($vhora[1]);
	$diaInicial = substr($diasini,6,4).'-'.substr($diasini,3,2).'-'.substr($diasini, 0, 2);
	$finalEvento =  date("Y-m-d", strtotime($diaInicial.' + '.$diasdur.' days'));

	
	

	 //SELECCIONAMOS LOS PERFILES PARA GENERAR LAS REUNIONES Y QUE TIPOS DE PERFILES PUEDEN TENER REUNIONES
	 $query = "	SELECT PERCODIGO,TIPO
	 FROM PER_MAEST 
	 WHERE ESTCODIGO=1
	 ORDER BY PERCODIGO ";
	 $Tablecodigos = sql_query($query, $conn);
	
	 for ($z = 0; $z < $Tablecodigos->Rows_Count; $z++) {
		 $rowcodigos = $Tablecodigos->Rows[$z];
		 $percodsolicitante 	= trim($rowcodigos['PERCODIGO']);
		 $tiposolicitante	= trim($rowcodigos['TIPO']);
		 

		 //CREO EL ARRAY DE INTERESES
	 	 $perfilLog = array();
		  
	 	 // SE CARGA EL ARRAY CON LOS INTERESES PARA LUEGO MATCHEAR
		$query = "	SELECT S.SECCODIGO,S.SECDESCRI,PS.PERVENCOM
					FROM PER_SECT PS
					LEFT OUTER JOIN SEC_MAEST S ON S.SECCODIGO=PS.SECCODIGO
					WHERE PS.PERCODIGO=$percodsolicitante AND S.ESTCODIGO<>3 
						AND (PS.$whereVenComPerLog OR PS.$whereVenComPerBsq)";
		$TableSect = sql_query($query,$conn);
		for($i=0; $i<$TableSect->Rows_Count; $i++){
			$rowSect= $TableSect->Rows[$i];
			$seccodigo = trim($rowSect['SECCODIGO']);
			$secdescri = trim($rowSect['SECDESCRI']);
			$secvalor = trim($rowSect['PERVENCOM']);
			
			$perfilLog[$seccodigo]['EXISTS'] = $secvalor;
			
			//Busco si tiene un siguiente nivel / SubSector
			$querySectSub = "	SELECT SB.SECSUBCOD,SB.SECSUBDES,PSB.PERVENCOM
								FROM PER_SSEC PSB
								LEFT OUTER JOIN SEC_SUB SB ON SB.SECSUBCOD=PSB.SECSUBCOD
								WHERE PSB.PERCODIGO=$percodsolicitante AND SB.SECCODIGO=$seccodigo AND SB.ESTCODIGO<>3
								AND (PSB.$whereVenComPerLog OR PSB.$whereVenComPerBsq) ";
			$TableSSect = sql_query($querySectSub,$conn);
			for($j=0; $j<$TableSSect->Rows_Count; $j++){
				$rowSSect= $TableSSect->Rows[$j];
				$secsubcod = trim($rowSSect['SECSUBCOD']);
				$secsubdes = trim($rowSSect['SECSUBDES']);
				
				$secsubvalor = trim($rowSSect['PERVENCOM']);
				
				$perfilLog[$seccodigo][$secsubcod]['EXISTS'] = $secsubvalor;
			}
		}
		///////////////////////////////////////////////////////////////////////
		/// ARRAY DONDE SE VAN A AGREGAR LOS PERFILES POSIBLES 
		$arrayperfilesreuniones=null;
		 //SELECCIONAMOS LOS PERFILES DE LAS CONTRAPARTES POSIBLES DEL MISMO TIPO DE REUNION Y TIPO DE PERFIL POSIBLE
         $query = "	SELECT PERCODIGO
         FROM PER_MAEST 
         WHERE ESTCODIGO=1 AND TIPO=$tiposolicitante AND PERCODIGO!=$percodsolicitante
         ORDER BY PERCODIGO ";
         $Tablecontraparte = sql_query($query, $conn);
 
         for ($i = 0; $i < $Tablecontraparte->Rows_Count; $i++) {
         $rowcontraparte = $Tablecontraparte->Rows[$i];
         $percodcontraparte	= trim($rowcontraparte['PERCODIGO']);

		 ///// SE CARGA EL ARRAY CON LOS INTERESES PARA MATCHEAR CON EL SOLICITANTE
		 $match = false;
		
			//Busco todos los sectores que tiene el perfil que COMPRA
			$query = "	SELECT S.SECCODIGO,S.SECDESCRI,PS.PERVENCOM
						FROM PER_SECT PS
						LEFT OUTER JOIN SEC_MAEST S ON S.SECCODIGO=PS.SECCODIGO
						WHERE PS.PERCODIGO=$percodcontraparte AND S.ESTCODIGO<>3 
						AND (PS.$whereVenComPerLog OR PS.$whereVenComPerBsq) ";
			$TableSect = sql_query($query,$conn);
			for($n=0; $n<$TableSect->Rows_Count; $n++){
				$rowSect= $TableSect->Rows[$n];
				$seccodigo = trim($rowSect['SECCODIGO']);
				$secdescri = trim($rowSect['SECDESCRI']);
				$secvalor = trim($rowSect['PERVENCOM']);
				
				//Busco si tiene un siguiente nivel / SubSector
				$querySectSub = "	SELECT SB.SECSUBCOD,SB.SECSUBDES,PSB.PERVENCOM
									FROM PER_SSEC PSB
									LEFT OUTER JOIN SEC_SUB SB ON SB.SECSUBCOD=PSB.SECSUBCOD
									WHERE PSB.PERCODIGO=$percodcontraparte AND SB.SECCODIGO=$seccodigo AND sb.ESTCODIGO<>3 
									AND (PSB.$whereVenComPerLog OR PSB.$whereVenComPerBsq) ";
				$TableSSect = sql_query($querySectSub,$conn);
				for($j=0; $j<$TableSSect->Rows_Count; $j++){
					$rowSSect= $TableSSect->Rows[$j];
					$secsubcod = trim($rowSSect['SECSUBCOD']);
					$secsubdes = trim($rowSSect['SECSUBDES']);
					$secsubvalor = trim($rowSSect['PERVENCOM']);
					if($TableCat->Rows_Count==-1){
						
						if(isset($perfilLog[$seccodigo][$secsubcod])){
							if (($perfilLog[$seccodigo][$secsubcod]['EXISTS'] == 'A') || ($perfilLog[$seccodigo][$secsubcod]['EXISTS']!= $secsubvalor))
								{
									$match = true;
									break;
								}
						}
					}
				}
				if($TableSSect->Rows_Count==-1){
					if(isset($perfilLog[$seccodigo])){
						if (($perfilLog[$seccodigo]['EXISTS'] == 'A') || ($perfilLog[$seccodigo]['EXISTS']!= $secvalor))
								{
									$match = true;
									break;
								}
					}
				}
			}

			////////////// VALIDO QUE HAYAN MATCHEADO Y ARMO ARRAY DE LOS PERFILES POSIBLES DE CONTRAPARTE QUE PUEDAN GENERAR REUNION CON EL SOLICITANTE
			
			if($match){
				$arrayperfilesreuniones[]=$percodcontraparte;
			}
         }
		
		$arr_length = count($arrayperfilesreuniones);
		////////// YA QUEDO CONFORMADO EL ARRAY DE PERFILES CON LOS QUE SE PUEDE REUNIR AHORA HAY QUE IR VIENDO LA AGENDA Y HACIENDO LOS CONTROLES
		$fechaaux=$diasini;
		$horaaux=$hini;
		
		$begin = new DateTime($diaInicial);
		$end = new DateTime($finalEvento);

		$interval = DateInterval::createFromDateString('1 day');
		$period = new DatePeriod($begin, $interval, $end);
		$arrayperfilesyausados=null;
		if (count($arrayperfilesreuniones) != count($arrayperfilesyausados)){
			foreach ($period as $dt) {
				if (count($arrayperfilesreuniones) != count($arrayperfilesyausados)){
					while($horaaux < $hfin){
						if (count($arrayperfilesreuniones) != count($arrayperfilesyausados)){
							$fechareu=$dt->format("Y-m-d");
							$horareu=$horaaux;

							if(isset($FechasBloq[$fechareu][$horareu])){
								
							}else{

								
								$horariolibresol=true;
								////////// 1 CONTROL : DISPO SOLICITANTE//////////////////////////
								$query = "	SELECT PERDISFCH,PERDISHOR,DISP_BOOL
								FROM PER_DISP
								WHERE PERCODIGO=$percodsolicitante AND PERDISFCH='$fechareu' AND PERDISHOR='$horareu' AND DISP_BOOL=1
								ORDER BY PERDISFCH,PERDISHOR ";
								$TableDispSol = sql_query($query,$conn);

								if ($TableDispSol->Rows_Count>0){

									$horariolibresol=false;
								}
								//////////////////////////////////////////////////////////////////
								if ($horariolibresol==true){

									////////// 3 CONTROL : REUNIONES SOLICITANTE//////////////////////
									$query = "	SELECT S.REUFECHA,S.REUHORA
									FROM REU_CABE R 
									LEFT OUTER JOIN REU_SOLI S ON S.REUREG=R.REUREG AND R.REUESTADO=S.REUESTADO
									WHERE (R.PERCODSOL=$percodsolicitante OR R.PERCODDST=$percodsolicitante) AND (S.REUFECHA='$fechareu' OR R.REUFECHA='$fechareu') AND (S.REUHORA='$horareu' OR R.REUHORA='$horareu') AND R.REUESTADO IN(1,2) 
									ORDER BY S.REUFECHA,S.REUHORA ";
									$TableReuSol = sql_query($query,$conn);
									if ($TableReuSol->Rows_Count>0){

										$horariolibresol=false;
									}

									//////////////////////////////////////////////////////////////////

								}
								if ($horariolibresol==true){
									for($l=0; $l<$arr_length; $l++){
									
										if (!in_array($arrayperfilesreuniones[$l],$arrayperfilesyausados)){
											////////// 2 CONTROL : DISPO CONTRAPARTE//////////////////////////
											//Busco los dias disponibles del perfil 
											$horariolibrecontraparte=true;
											$query = "	SELECT PERDISFCH,PERDISHOR,DISP_BOOL
											FROM PER_DISP
											WHERE PERCODIGO=$arrayperfilesreuniones[$l] AND PERDISFCH='$fechareu' AND PERDISHOR='$horareu' AND DISP_BOOL=1
											ORDER BY PERDISFCH,PERDISHOR ";
											$TableDispCont = sql_query($query,$conn);
											if ($TableDispCont->Rows_Count>0){

												$horariolibrecontraparte=false;
											}
											//////////////////////////////////////////////////////////////////
											if ($horariolibrecontraparte==true){
												////////// 4 CONTROL : REUNIONES CONTRAPARTE//////////////////////
												$query = "	SELECT S.REUFECHA,S.REUHORA
												FROM REU_CABE R 
												LEFT OUTER JOIN REU_SOLI S ON S.REUREG=R.REUREG AND R.REUESTADO=S.REUESTADO
												WHERE (R.PERCODSOL=$arrayperfilesreuniones[$l] OR R.PERCODDST=$arrayperfilesreuniones[$l]) AND (S.REUFECHA='$fechareu' OR R.REUFECHA='$fechareu') AND (S.REUHORA='$horareu' OR R.REUHORA='$horareu') AND R.REUESTADO IN(1,2) 
												ORDER BY S.REUFECHA,S.REUHORA ";
												$TableReuCont = sql_query($query,$conn);
												if ($TableReuCont->Rows_Count>0){

													$horariolibrecontraparte=false;
												}
							
												//////////////////////////////////////////////////////////////////
											}
											if ($horariolibrecontraparte==true){
												////////// 5 CONTROL : REUNIONES UNICA ENTRE PARTES///////////////
												$query = "	SELECT S.REUFECHA,S.REUHORA
												FROM REU_CABE R 
												LEFT OUTER JOIN REU_SOLI S ON S.REUREG=R.REUREG AND R.REUESTADO=S.REUESTADO
												WHERE ((R.PERCODSOL=$percodsolicitante AND R.PERCODDST=$arrayperfilesreuniones[$l]) OR (R.PERCODSOL=$arrayperfilesreuniones[$l] AND R.PERCODDST=$percodsolicitante)) AND R.REUESTADO IN(1,2) 
												ORDER BY S.REUFECHA,S.REUHORA ";
												$TableReuUnica = sql_query($query,$conn);
												if ($TableReuUnica->Rows_Count>0){
													$arrayperfilesyausados[]=$arrayperfilesreuniones[$l];
													$horariolibrecontraparte=false;
												}
												//////////////////////////////////////////////////////////////////
											}
											if ($horariolibrecontraparte==true){
												$arrayperfilesyausados[]=$arrayperfilesreuniones[$l];
												//////////CREO LAS REUNIONES////////////
												//Genero un ID 
												$query 		= 'SELECT GEN_ID(G_REUNIONES,1) AS ID FROM RDB$DATABASE';
												$TblId		= sql_query($query,$conn);
												$RowId		= $TblId->Rows[0];			
												$reureg 	= trim($RowId['ID']);
												//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
												
												//Inserto reunion cabecera
												$query = " 	INSERT INTO REU_CABE(REUREG,REUFCHREG,PERCODSOL,PERCODDST,REUESTADO,REUFECHA,REUHORA,MESCODIGO,REUTIPO)
															VALUES($reureg,CURRENT_TIMESTAMP,$percodsolicitante,$arrayperfilesreuniones[$l],1,NULL,NULL,NULL,$tiposolicitante) ";
												$err = sql_execute($query,$conn);

												$query = "	INSERT INTO REU_SOLI(REUREG,REUFECHA,REUHORA,REUESTADO)
															VALUES($reureg,'$fechareu','$horareu',1)";
												$err = sql_execute($query,$conn);

												////////////////////////////////////////
												/*if($err == 'SQLACCEPT' && $errcod==0){
													sql_commit_trans($trans);
													$errcod = 0;
													$errmsg = 'Meeting accepted!';      
												}*/
												break;
											}
											
										}
										
									}
								}
							}
							$horaaux = date('H:i', strtotime('+ 30 minutes', strtotime($horaaux.':00')));
							
						}else{
							break;
						}
						
					}
					
				$horaaux=$hini;
				
				}
			}
		}
	
	 }
	 if($err == 'SQLACCEPT' && $errcod==0){
	
		$errcod = 0;
		$errmsg = 'Meeting accepted!';      
	}else{            
		
		$errcod = 2;
		$errmsg = ($errmsg=='')? 'Error!' : $errmsg;
	}	
	
	//--------------------------------------------------------------------------------------------------------------
		
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	

	
	
?>	
