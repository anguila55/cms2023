<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
	require_once GLBRutaFUNC . '/constants.php';
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('datatable.html');
	
	//Diccionario de idiomas
	DDIdioma($tmpl);


	$conn = sql_conectar(); //Apertura de Conexion

	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$peradmin = (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';

	if ($peradmin!=1){
		header('Location: ../login');	
	}

	$tmpl->setVariable('percodnotif', $percodigo	);
	$tmpl->setVariable('SisNombreEvento', NAME_TITLE );

	//fechas
	$fechas = (isset($_GET['ID']))? trim($_GET['ID']):''; //Nota desde el home acceso directo
	if($fechas!=''){
		$vaux = explode('||',$fechas);
		if(count($vaux)>1){
			$desde = $vaux[0];
			$hasta = $vaux[1];
			if($desde > $hasta){
	
			}
			$tmpl->setVariable('desde'	, $desde);
			$tmpl->setVariable('hasta'	, $hasta);
		}
	}





	//////////////// cantidad asistentes realizadas ///////////////////////// 
	$query = "	SELECT COUNT(PERCODIGO ) Z 
					FROM PER_MAEST 
					WHERE ESTCODIGO=1";	
	$Table = sql_query($query, $conn);	
	$row = $Table->Rows[0];
	$cantidadasistentes 		= trim($row['Z']);
	$tmpl->setVariable('cantidadasistentes'	, $cantidadasistentes);

	//////////////// cantidad de usuarios logueados  ///////////////////////// 
	$query = "	SELECT COUNT(PERINGLOG ) E 
					FROM PER_MAEST 
					WHERE (PERINGLOG IS NOT NULL) AND ESTCODIGO=1";

	$Table = sql_query($query, $conn);	
	$row = $Table->Rows[0];
	$usuarioslogueados 		= trim($row['E']);

	$tmpl->setVariable('usuarioslogueados'	, $usuarioslogueados);


	
	///////////////// usuarios activos//////////////////
	//////////////// cantidad de usuarios logueados  ///////////////////////// 
	///Las personas que se hayan logueado al menos 3 horas antes. Porque no estamos guardando la duracion del logueo. Las 3 horas son son a modo de ejemplo
	$new_time = date("Y-m-d H:i:s", strtotime('-3 hours'));
	$queryonline = "	SELECT COUNT(PERULTLOG) A
					FROM PER_MAEST 
					WHERE (PERINGLOG IS NOT NULL) AND (PERULTLOG>='$new_time') AND ESTCODIGO=1";	
	$Table = sql_query($queryonline, $conn);	
	$row = $Table->Rows[0];
	$perfilesenlinea		= trim($row['A']);

	$perfilesenlineaporcentaje = ($perfilesenlinea / $cantidadasistentes)*100;
	$tmpl->setVariable('perfilesenlinea'	, $perfilesenlinea);
	$tmpl->setVariable('perfilesenlineaporcentaje'	, round($perfilesenlineaporcentaje));

	//////////////// cantidad asistentes pendientes de confirmacion ///////////////////////// 
	$query = "	SELECT COUNT(PERCODIGO ) R
					FROM PER_MAEST 
					WHERE ESTCODIGO=8";	
	$Table = sql_query($query, $conn);	
	$row = $Table->Rows[0];
	$cantidadasistentessinconfirmar 		= trim($row['R']);
	$tmpl->setVariable('cantidadasistentessinconfirmar'	, $cantidadasistentessinconfirmar);

	//////////////// cantidad asistentes mail sin confirmar ///////////////////////// 
	$query = "	SELECT COUNT(PERCODIGO ) S 
					FROM PER_MAEST 
					WHERE ESTCODIGO=9";	
	$Table = sql_query($query, $conn);	
	$row = $Table->Rows[0];
	$cantidadasistentesmail 		= trim($row['S']);
	$tmpl->setVariable('cantidadasistentesmail'	, $cantidadasistentesmail);

	$arrayregistros=['ACTIVOS','MAIL SIN CONFIRMAR', 'PENDIENTES DE LIBERACION'];
	$asistentesregistros=[$cantidadasistentes,$cantidadasistentesmail,$cantidadasistentessinconfirmar];
	$arrayregistrosJSON = json_encode($arrayregistros);
	$asistentesregistrosJSON = json_encode($asistentesregistros);
	$tmpl->setvariable('arrayregistrosJSON'		, $arrayregistrosJSON);
	$tmpl->setvariable('asistentesregistrosJSON'		, $asistentesregistrosJSON);
	//////////////// cantidad de participantes ///////////////////////// 
	$arraytipoparticipantes=null;
	$canttipoparticipantes=null;
	$query = "	SELECT  PM.PERCLASE, COUNT(PM.PERCLASE) F
					FROM PER_MAEST PM
					WHERE PM.ESTCODIGO=1
					GROUP BY PM.PERCLASE
					ORDER BY COUNT(PM.PERCLASE) DESC";		
	$Table = sql_query($query, $conn);
	
	for ($i = 0; $i < $Table->Rows_Count; $i++) {
		$row = $Table->Rows[$i];
		$tipoparticipantes 		= trim($row['PERCLASE']);
		$cantidadparticipantes 		= trim($row['F']);
		$querydes = "	SELECT PC.PERTIPO,PC.ESTCODIGO AS CLASEESTADO,PT.ESTCODIGO AS TIPOESTADO,PC.PERCLADES,PT.PERTIPDES$IdiomView AS PERTIPDES
									FROM PER_CLASE PC
									LEFT OUTER JOIN PER_TIPO PT ON PC.PERTIPO=PT.PERTIPO
									WHERE PC.PERCLASE=$tipoparticipantes";	
				$Tabledes = sql_query($querydes, $conn);
				
				if(isset($Tabledes->Rows[0])){ 
				$rowdes = $Tabledes->Rows[0];
				$pertipdes 		= trim($rowdes['PERTIPDES']);
				$perclades 		= trim($rowdes['PERCLADES']);
				$claseestado 		= trim($rowdes['CLASEESTADO']);
				$tipoestado 		= trim($rowdes['TIPOESTADO']);
				}else{

					$pertipdes='Tipo Eliminado';
				}
				if ($claseestado!=1){
					$perclades='Clase Eliminada';
					if ($tipoestado!=1){
						$pertipdes='Tipo Eliminado';
					}
				}
				if ($tipoestado!=1){
					$pertipdes='Tipo Eliminado';
					if ($claseestado!=1){
						$perclades='Clase Eliminada';
					}
				}
		$arraytipoparticipantes[]=$perclades.' - '.$pertipdes;
		
		$canttipoparticipantes[]=$cantidadparticipantes;

	}
	$tipoparticipantesJSON = json_encode($arraytipoparticipantes);
	$cantparticipantesJSON = json_encode($canttipoparticipantes);
	$tmpl->setvariable('tipoparticipantesJSON'		, $tipoparticipantesJSON);
	$tmpl->setvariable('cantparticipantesJSON'		, $cantparticipantesJSON);


	$arraycharlas=null;
	$participantescharlas=null;
	//////////////// charlas populares  ///////////////////////// 

	if(!$desde && !$hasta){
		$andgame = "";
	}
	elseif($desde && !$hasta){
		$andgame = "AND GAMEFCH > '$desde'";
	}elseif(!$desde && $hasta){
		$andgame = "AND GAMEFCH < '$hasta'";
	}else{
		$andgame = "AND GAMEFCH BETWEEN '$desde' AND '$hasta'";
	}


	$query = "SELECT DISTINCT VALOR, COUNT (VALOR) P
					FROM GAME_PTS  
					WHERE TIPO=1 $andgame
					GROUP BY  VALOR
					ORDER BY  COUNT (VALOR) DESC";	


	$Table = sql_query($query, $conn);
	for ($i = 0; $i < $Table->Rows_Count; $i++) {
		$row = $Table->Rows[$i];
		$perqrper 		= trim($row['VALOR']);
		$cantidadperqrper 		= trim($row['P']);
				$querycharla = "	SELECT AGETITULO
									FROM AGE_MAEST 
									WHERE AGEREG= ($perqrper)";	
				$Tablecharla = sql_query($querycharla, $conn);	
				$rowcharla = $Tablecharla->Rows[0];

				$agetitulo 		= trim($rowcharla['AGETITULO']);
				
				$tmpl->setCurrentBlock('populares');
				$tmpl->setVariable('agereg', $i);
				$tmpl->setVariable('agetitulo', $agetitulo);
				$tmpl->setvariable('cantidadperqrper', $cantidadperqrper);
				$agetitulo = (strlen($agetitulo) > 20) ? substr($agetitulo,0,17).'...' : $agetitulo;
				$arraycharlas[]=$agetitulo;
				$participantescharlas[]=$cantidadperqrper;
				$tmpl->parse('populares');
				
		
	}
	
	$arraycharlasJSON = json_encode($arraycharlas);
	$participantescharlasJSON = json_encode($participantescharlas);
	$tmpl->setvariable('arraycharlasJSON'		, $arraycharlasJSON);
	$tmpl->setvariable('participantescharlasJSON'		, $participantescharlasJSON);
	/////////////////////////////////////////////////////////////////////////////////////
	//////////////// cantidad reuniones confirmadas  ///////////////////////// 



	
	if(!$desde && !$hasta){
		$and = "";
	}
	elseif($desde && !$hasta){
		$and = "AND REUFCHREG > '$desde'";
	}elseif(!$desde && $hasta){
		$and = "AND REUFCHREG < '$hasta'";
	}else{
		$and = "AND REUFCHREG BETWEEN '$desde' AND '$hasta'";
	}

	/////////////// Reuniones x paises///////////
	$arrayreupaises='';
	$arraycantreupaises=null;
	$query = "	SELECT PS.PAICODIGO AS PAISSOL, PD.PAICODIGO AS PAISDST
					FROM REU_CABE R
					LEFT OUTER JOIN PER_MAEST PS ON R.PERCODSOL=PS.PERCODIGO
            		LEFT OUTER JOIN PER_MAEST PD ON R.PERCODDST=PD.PERCODIGO
					WHERE (R.REUESTADO=2 OR R.REUESTADO=1) AND (R.PERCODDST!=R.PERCODSOL) $and";	
	$Table = sql_query($query, $conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$paissol 		= trim($row['PAISSOL']);
		$paisdes 		= trim($row['PAISDST']);

		if ($paissol==''){
			$paissol='0';
		} 
	    if ($paisdes==''){
			$paisdes='0';
		}
		$arrayreupaises.="'".$paissol."',";
		$arrayreupaises.="'".$paisdes."',";
		$arraycantreupaises[]=$paissol;
		$arraycantreupaises[]=$paisdes;
		
		
	}
	$arrayreupaises .= '0';
	$cantidaddereunionespaises= array_count_values($arraycantreupaises);
	
	//Listado de Paises
	$queryreupais = " SELECT PAICODIGO,PAIDESCRI
				FROM TBL_PAIS
				WHERE PAICODIGO IN ($arrayreupaises)
				ORDER BY PAIDESCRI";
	
	$Tablereupais = sql_query($queryreupais,$conn);
	for($i=0; $i<$Tablereupais->Rows_Count;$i++){
		$row= $Tablereupais->Rows[$i];
		$codigopaisreunion 		= trim($row['PAICODIGO']);
		$paisreunion 		= trim($row['PAIDESCRI']);
		$tmpl->setCurrentBlock('reunionespais');
		if (array_key_exists($codigopaisreunion, $cantidaddereunionespaises)) {
			$cantidadreunionespais=$cantidaddereunionespaises[$codigopaisreunion];
		}else{
			$cantidadreunionespais=0;
		}
		$tmpl->setVariable('cantidadreunionespais'	, $cantidadreunionespais);
		$tmpl->setVariable('paisreunion'	, $paisreunion);
		$tmpl->parse('reunionespais');	
	}
	//////////////// Reuniones x sectores /////////
	$arrayreusectores='';
	$arraycantreusectores=null;
	$query = "	SELECT (   SELECT CAST(LIST(SECT.SECDESCRI)  AS VARCHAR(10000))
                   		FROM PER_SECT PZ
                    	LEFT OUTER JOIN SEC_MAEST SECT ON SECT.SECCODIGO=PZ.SECCODIGO
                    	WHERE PZ.PERCODIGO=PS.PERCODIGO) AS SECTORESSOL,
						(   SELECT CAST(LIST(SECT.SECDESCRI)  AS VARCHAR(10000))
                   		FROM PER_SECT PK
                    	LEFT OUTER JOIN SEC_MAEST SECT ON SECT.SECCODIGO=PK.SECCODIGO
                    	WHERE PK.PERCODIGO=PD.PERCODIGO) AS SECTORESDST
					FROM REU_CABE R
					LEFT OUTER JOIN PER_MAEST PS ON R.PERCODSOL=PS.PERCODIGO
            		LEFT OUTER JOIN PER_MAEST PD ON R.PERCODDST=PD.PERCODIGO
					WHERE (R.REUESTADO=2 OR R.REUESTADO=1) AND (R.PERCODDST!=R.PERCODSOL) $and";	
	$Table = sql_query($query, $conn);

	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$sectoressol 		= trim($row['SECTORESSOL']);
		$sectoresdst 		= trim($row['SECTORESDST']);
		if ($sectoressol==''){
			$sectoressol='Sin sector';
		} 
	    if ($sectoresdst==''){
			$sectoresdst='Sin sector';
		}
		$sectoressolictadosseparados = explode(',', $sectoressol);
		$sectoresdestinoseparados = explode(',', $sectoresdst);
		$arrayreusectores.=$sectoressol.',';
		$arrayreusectores.=$sectoresdst.',';
		foreach ($sectoressolictadosseparados as $key => $value) {
			$arraycantreusectores[]=$value;
		}
		foreach ($sectoresdestinoseparados as $key => $value1) {
			$arraycantreusectores[]=$value1;
		}
		
	}
	$arrayreusectores .= '0';
	$cantidaddereunionessectores= array_count_values($arraycantreusectores);
	
	//var_dump($cantidaddereunionessectores);die;
	
	foreach ($cantidaddereunionessectores as $key => $value) {
		$tmpl->setCurrentBlock('reunionessectores');

		$tmpl->setVariable('cantidadreunionessectores'	, $value);
		$tmpl->setVariable('sectorreunion'	, $key);
		$tmpl->parse('reunionessectores');	
	}
	
		
	
	
	///////////////////////////////////////////////

	$query = "	SELECT COUNT(REUREG ) T
					FROM REU_CABE
					WHERE REUESTADO=2 AND (PERCODDST!=PERCODSOL) $and";	
	$Table = sql_query($query, $conn);
	$row = $Table->Rows[0];
	$reunionesconfirmadas 		= trim($row['T']);

	$tmpl->setVariable('reunionesconfirmadas'	, $reunionesconfirmadas);

	//////////////// cantidad reuniones enviadas/solicitadas  ///////////////////////// 
	$query = "	SELECT COUNT(REUREG ) U
					FROM REU_CABE 
					WHERE (REUESTADO=1) AND (PERCODDST!=PERCODSOL) $and";	
	$Table = sql_query($query, $conn);	
	$row = $Table->Rows[0];
	$reunionespendientes 		= trim($row['U']);

	$tmpl->setVariable('reunionespendientes', $reunionespendientes);

	//////////////// cantidad canceladas  ///////////////////////// 
	$query = "	SELECT COUNT(REUREG ) V
					FROM REU_CABE 
					WHERE REUESTADO=3 $and";	
	$Table = sql_query($query, $conn);	
	$row = $Table->Rows[0];
	$reunionescanceladas 		= trim($row['V']);

	$tmpl->setVariable('reunionescanceladas'	, $reunionescanceladas);

	$reunionessinconfirmar = ($reunionesconfirmadas / ($reunionesconfirmadas + $reunionespendientes))*100;
	$tmpl->setVariable('reunionessinconfirmar'	, round($reunionessinconfirmar));

	$reunionesconcretadas=0;
	//////////////// LISTADO DE REUNIONES ////////////////////////////////
	if(!$desde && !$hasta){
		$where = "WHERE R.REUESTADO!=3 AND R.REUESTADO!=5";
	}
	elseif($desde && !$hasta){
		$where = "WHERE R.REUESTADO!=3 AND R.REUESTADO!=5 AND R.REUFCHREG > '$desde'";
	}elseif(!$desde && $hasta){
		$where = "WHERE R.REUESTADO!=3 AND R.REUESTADO!=5 AND R.REUFCHREG < '$hasta'";
	}else{
		$where = "WHERE R.REUESTADO!=3 AND R.REUESTADO!=5 AND R.REUFCHREG BETWEEN '$desde' AND '$hasta'";
	}
	
	$query="SELECT R.REUREG, R.REUFCHREG, 
						R.PERCODSOL,R.PERCODDST,
						R.REUESTADO,R.REUFECHA,R.REUHORA,
						PS.PERCOMPAN AS SOLEMPRESA,PS.PERNOMBRE AS SOLNOMBRE,PS.PERAPELLI AS SOLAPELLI,
						PD.PERCOMPAN AS DSTEMPRESA,PD.PERNOMBRE AS DSTNOMBRE,PD.PERAPELLI AS DSTAPELLI
            FROM (REU_CABE R
            LEFT OUTER JOIN PER_MAEST PS ON R.PERCODSOL=PS.PERCODIGO
            LEFT OUTER JOIN PER_MAEST PD ON R.PERCODDST=PD.PERCODIGO) $where
            ORDER BY R.REUFCHREG";
$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$reureg 	= trim($row['REUREG']);
		$percoddst 		= trim($row['PERCODDST']);
		$percodsol 		= trim($row['PERCODSOL']);
		$solempresa	= trim($row['SOLEMPRESA']);
		$solnombre	= trim($row['SOLNOMBRE']);
		$solapelli	= trim($row['SOLAPELLI']);
		$dstempresa	= trim($row['DSTEMPRESA']);
		$dstnombre	= trim($row['DSTNOMBRE']);
		$dstapelli	= trim($row['DSTAPELLI']);
		$reuestado 	= trim($row['REUESTADO']);
		$reufecha		= BDConvFch($row['REUFECHA']);
		$reuhora		= substr(trim($row['REUHORA']),0,5);
		$reudstcon=FALSE;
		$reusolcon=FALSE;

		if ($reuestado==2){
			$query1  = "	SELECT FIRST 1  PERQRITM
				FROM PER_QR
				WHERE PERCODIGO=$percoddst AND PERQRPER=$reureg AND PERQRAGE=25 ";
				$Table1 	= sql_query($query1, $conn);

				// Do bad things to the votes array
				if(isset($Table1->Rows[0])){ 
					$row1 = $Table1->Rows[0];
				
					$reudstcon=TRUE;
				} 

				$query2  = "	SELECT FIRST 1 PERQRITM
				FROM PER_QR
				WHERE PERCODIGO=$percodsol AND PERQRPER=$reureg AND PERQRAGE=25 ";
				$Table2 	= sql_query($query2, $conn);

				// Do bad things to the votes array
				if(isset($Table2->Rows[0])){ 
					$row2 = $Table2->Rows[0];
				
					$reusolcon=TRUE;
				}

				if ($reusolcon==TRUE && $reudstcon==TRUE){
					$reunionesconcretadas++;
				}
		}
		
		switch($reuestado){
			case 1: $reuestado='PENDIENTE'; break;
			case 2: $reuestado='CONFIRMADA'; break;
			case 3: $reuestado='CANCELADA'; break;
			case 5: $reuestado='ELIMINADA'; break;
		}	
		
		$tmpl->setCurrentBlock('reuniones');
		$tmpl->setVariable('reunionfecha'	, $reufecha.' '.$reuhora);
		$tmpl->setVariable('reunionempresasol'	, $solempresa);
		$tmpl->setVariable('reunionnomsol'	, $solnombre.' '.$solapelli);
		$tmpl->setVariable('reunionempresadst'	, $dstempresa);
		$tmpl->setVariable('reunionnomdst'	, $dstnombre.' '.$dstapelli);
		$tmpl->setVariable('reunionestado'	, $reuestado);
		$tmpl->parse('reuniones');	
	}
	//////////////////////////////////////////////////////////////////////

	//////////////////// REUNIONES CONCRETADAS////////////////////////////
	$tmpl->setVariable('reunionesconcretadas'	, $reunionesconcretadas);
	//////////////////////////////////////////////////////////////////////



	$arraystands=null;
	$participantesstands=null;

	///////////////// Sponsors ////////////////////

	
	$query = "	SELECT E.EXPNOMBRE, C.CATDESCRI,E.EXPREG
			FROM EXP_MAEST E
			LEFT OUTER JOIN EXP_CAT C ON C.CATREG = E.EXPCATEGO
			WHERE E.ESTCODIGO=1
			ORDER BY C.CATVALOR, E.EXPPOS ASC";

	$Table = sql_query($query, $conn);
	for ($i = 0; $i < $Table->Rows_Count; $i++) {

		$row = $Table->Rows[$i];
		$expreg 	= trim($row['EXPREG']);
		$expnombre 	= trim($row['EXPNOMBRE']);
		$catdescri 	= trim($row['CATDESCRI']);

    $Table = sql_query($query,$conn);
			$queryvisitas = "	SELECT COUNT(VALOR) C 
						FROM GAME_PTS  
						WHERE TIPO=2 $andgame AND ($expreg =VALOR)";
	
			$Tablevisitas = sql_query($queryvisitas, $conn);	
			$rowvisitas = $Tablevisitas->Rows[0];
			$visitasstand1 		= trim($rowvisitas['C']);


			if(!$desde && !$hasta){
				$andgame2 = "";
			}
			elseif($desde && !$hasta){
				$andgame2 = "AND Q.GAMEFCH > '$desde'";
			}elseif(!$desde && $hasta){
				$andgame2 = "AND Q.GAMEFCH < '$hasta'";
			}else{
				$andgame2 = "AND Q.GAMEFCH BETWEEN '$desde' AND '$hasta'";
			}

			$queryproductos = "SELECT COUNT(Q.VALOR) G 
						FROM GAME_PTS Q
						LEFT OUTER JOIN EXP_PROD P ON P.EXPREG=$expreg
						WHERE (TIPO=9) AND (P.PRODREG = Q.VALOR) $andgame2";	
			$Tableproductos = sql_query($queryproductos, $conn);
			$rowproductos = $Tableproductos->Rows[0];
			$descargasproductos		= trim($rowproductos['G']);

		$tmpl->setCurrentBlock('sponsors');
		$tmpl->setVariable('expnombre'		, $expnombre);
		$catdescri = (strlen($catdescri) > 20) ? substr($catdescri,0,17).'...' : $catdescri;
		$tmpl->setVariable('catdescri'	, $catdescri);
		$tmpl->setVariable('visitasstand1'		, $visitasstand1);
		$tmpl->setVariable('descargasproductos'		, $descargasproductos);
		$expnombre = (strlen($expnombre) > 20) ? substr($expnombre,0,17).'...' : $expnombre;

		$arraystands[]=$expnombre;
		$participantesstands[]=$visitasstand1;
		$tmpl->parse('sponsors');
		
	}

$arraystandsJSON = json_encode($arraystands);
$participantesstandsJSON = json_encode($participantesstands);
$tmpl->setvariable('arraystandsJSON'		, $arraystandsJSON);
$tmpl->setvariable('participantesstandsJSON'		, $participantesstandsJSON);

	//////////////////// Chats Enviados y recibidos /////////////////////////////////

	if(!$desde && !$hasta){
		$wherechat = "";
	}
	elseif($desde && !$hasta){
		$wherechat = "WHERE C.CHAFCHREG > '$desde'";
	}elseif(!$desde && $hasta){
		$wherechat = "WHERE C.CHAFCHREG < '$hasta'";
	}else{
		$wherechat = "WHERE C.CHAFCHREG BETWEEN '$desde' AND '$hasta'";
	}

	$query = "	SELECT C.PERCODIGO, COUNT(C.PERCODIGO ) F
					FROM TBL_CHAT C $wherechat
					GROUP BY C.PERCODIGO
					ORDER BY COUNT(C.PERCODIGO) DESC";

	$Table = sql_query($query, $conn);
	for ($i = 0; $i < $Table->Rows_Count; $i++) {
		$row = $Table->Rows[$i];
		$chatsol 		= trim($row['PERCODIGO']);
		$cantidadchats 		= trim($row['F']);
			//////////////////// Chats Enviados y recibidos /////////////////////////////////
		$cantidadchatsrecibidos =0;
		$queryrecibidos = "	SELECT C.PERCODDST, COUNT(C.PERCODDST ) G
		FROM TBL_CHAT C $wherechat
		WHERE C.PERCODDST=$chatsol
		GROUP BY C.PERCODDST
		ORDER BY COUNT(C.PERCODDST) DESC";		
		$Tablerecibidos = sql_query($queryrecibidos, $conn);
		if ($Tablerecibidos->Rows>0){
			$rowrecibidos = $Tablerecibidos->Rows[0];
			$cantidadchatsrecibidos 		= trim($rowrecibidos['G']);
			if ($cantidadchatsrecibidos ==''){
				$cantidadchatsrecibidos =0;
			}
		}else{
			$cantidadchatsrecibidos =0;
		}
		

		$querydes = "	SELECT PS.PERNOMBRE AS SOLNOMBRE,PS.PERAPELLI AS SOLAPELLI
									FROM PER_MAEST PS
									WHERE PS.PERCODIGO=$chatsol";	
				$Tabledes = sql_query($querydes, $conn);	
				$rowdes = $Tabledes->Rows[0];
				$solnombre	= trim($rowdes['SOLNOMBRE']);
				$solapelli	= trim($rowdes['SOLAPELLI']);
				$tmpl->setCurrentBlock('chatssol');

				$tmpl->setVariable('solnombre'		, $solnombre.' '.$solapelli);
				$tmpl->setVariable('solcantidad'	, $cantidadchats);
				$tmpl->setVariable('cantidadchatsrecibidos'	, $cantidadchatsrecibidos);
				$tmpl->parse('chatssol');
				
	}

	/////////////////////////////////////////////////////////////////////
	//////////////////// Chats Enviados y recibidos /////////////////////////////////
	$query = "	SELECT C.PERCODDST, COUNT(C.PERCODDST ) F
					FROM TBL_CHAT C $wherechat
					GROUP BY C.PERCODDST
					ORDER BY COUNT(C.PERCODDST) DESC";		
	$Table = sql_query($query, $conn);
	for ($i = 0; $i < $Table->Rows_Count; $i++) {
		$row = $Table->Rows[$i];
		$chatdst 		= trim($row['PERCODDST']);
		$cantidadchatsdst 		= trim($row['F']);
		$querydes = "	SELECT PD.PERNOMBRE AS DSTNOMBRE,PD.PERAPELLI AS DSTAPELLI
									FROM PER_MAEST PD
									WHERE PD.PERCODIGO=$chatdst";	
				$Tabledes = sql_query($querydes, $conn);	
				$rowdes = $Tabledes->Rows[0];
				$dstnombre	= trim($rowdes['DSTNOMBRE']);
				$dstapelli	= trim($rowdes['DSTAPELLI']);
				$tmpl->setCurrentBlock('chatsdst');
				$tmpl->setVariable('dstnombre'		, $dstnombre.' '.$dstapelli);
				$tmpl->setVariable('dstcantidad'	, $cantidadchatsdst);
				$tmpl->parse('chatsdst');
				
	}

	/////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////
	if(!$desde && !$hasta){
		$wheregame = "";
	}
	elseif($desde && !$hasta){
		$wheregame = "WHERE GP.GAMEFCH > '$desde'";
	}elseif(!$desde && $hasta){
		$wheregame = "WHERE GP.GAMEFCH < '$hasta'";
	}else{
		$wheregame = "WHERE GP.GAMEFCH BETWEEN '$desde' AND '$hasta'";
	}

		/*--------------------------------- RANKING --------------------------------*/
		$query = "SELECT PM.PERAPELLI, PM.PERNOMBRE, SUM(GP.PUNTOS) AS P
		FROM GAME_PTS GP LEFT OUTER JOIN PER_MAEST PM ON PM.PERCODIGO = GP.PERCODIGO $wheregame GROUP BY GP.PERCODIGO, PM.PERAPELLI, PM.PERNOMBRE
		ORDER BY P DESC";

		$Table = sql_query($query, $conn);
		for ($i = 0; $i < $Table->Rows_Count; $i++) {
		$row = $Table->Rows[$i];
		$percodigo 	= trim($row['PERCODIGO']);
		$puntos 	= trim($row['P']);
		$perapelli	= trim($row['PERAPELLI']);
		$pernombre	= trim($row['PERNOMBRE']);

		$tmpl->setCurrentBlock('ranking');
		$tmpl->setVariable('puntos', $puntos);
		$tmpl->setVariable('posicion', $i+1);
		$tmpl->setVariable('pernombre', $pernombre.' '.$perapelli);
		$tmpl->parse('ranking');
		}

	//////////////// cantidad encuestas realizadas ///////////////////////// 
	$query = "	SELECT COUNT(DISTINCT PERCODIGO ) W
					FROM ENC_RESP 
					WHERE ESTCODIGO=1";	
	$Table = sql_query($query, $conn);	
	$row = $Table->Rows[0];
	$enccompletadas 		= trim($row['W']);
	$tmpl->setVariable('enccompletadas'	, $enccompletadas);

	/////////////// Encuestas x paises///////////
	$arrayencpaises='';
	$arraycantencpaises=null;
	$query = "	SELECT DISTINCT PS.PERCODIGO, PS.PAICODIGO AS PAISSOL
					FROM ENC_RESP R
					LEFT OUTER JOIN PER_MAEST PS ON R.PERCODIGO=PS.PERCODIGO
					WHERE R.ESTCODIGO=1";	
	$Table = sql_query($query, $conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$paissol 		= trim($row['PAISSOL']);

		if ($paissol==''){
			$paissol='0';
		}
		$arrayencpaises.="'".$paissol."',";
		$arraycantencpaises[]=$paissol;
		
		
	}
	$arrayencpaises .= '0';
	$cantidaddeencuestaspaises= array_count_values($arraycantencpaises);
	
	//Listado de Paises
	$queryencpais = " SELECT PAICODIGO,PAIDESCRI
				FROM TBL_PAIS
				WHERE PAICODIGO IN ($arrayencpaises)
				ORDER BY PAIDESCRI";
	
	$Tableencpais = sql_query($queryencpais,$conn);
	for($i=0; $i<$Tableencpais->Rows_Count;$i++){
		$row= $Tableencpais->Rows[$i];
		$codigopaisencuesta 		= trim($row['PAICODIGO']);
		$paisencuesta 		= trim($row['PAIDESCRI']);
		$tmpl->setCurrentBlock('encuestaspaises');
		if (array_key_exists($codigopaisencuesta, $cantidaddeencuestaspaises)) {
			$cantidadencuestaspais=$cantidaddeencuestaspaises[$codigopaisencuesta];
		}else{
			$cantidadencuestaspais=0;
		}
		$tmpl->setVariable('cantidadencuestaspais'	, $cantidadencuestaspais);
		$tmpl->setVariable('paisencuesta'	, $paisencuesta);
		$tmpl->parse('encuestaspaises');	
	}

	///////////Respuestas encuestas por pregunta
	$query = "	SELECT EP.ENCPREGUN, R.ENCVALRES, PS.PERNOMBRE, PS.PERAPELLI
					FROM ENC_RESP R
					LEFT OUTER JOIN ENC_PREG EP ON R.ENCPREITM=EP.ENCPREITM
					LEFT OUTER JOIN PER_MAEST PS ON R.PERCODIGO=PS.PERCODIGO
					WHERE R.ESTCODIGO=1 AND EP.ENCPREGUN!=''
					ORDER BY R.ENCPREITM";	
	$Table = sql_query($query, $conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$encpregunta 		= trim($row['ENCPREGUN']);
		$encrespuesta 		= trim($row['ENCVALRES']);
		$encnombreperfil 		= trim($row['PERNOMBRE']);
		$encapelliperfil 		= trim($row['PERAPELLI']);

		$tmpl->setCurrentBlock('encrespuestas');
		$tmpl->setVariable('encpregunta'	, $encpregunta);
		$tmpl->setVariable('encrespuesta'	, $encrespuesta);
		$tmpl->setVariable('encusuario'	, $encnombreperfil.' '.$encapelliperfil);
		$tmpl->parse('encrespuestas');
		
		
	}

	$arrayencuestapregunta=null;
	$cantidadrespuesta=null;
	///////////Respuestas por pregunta grafico
	$query = "	SELECT R.ENCVALRES, COUNT( R.ENCVALRES) T
					FROM ENC_RESP R
					WHERE R.ESTCODIGO=1 AND R.ENCPREITM=26
					GROUP BY R.ENCVALRES
					ORDER BY COUNT(R.ENCVALRES) DESC";	
	$Table = sql_query($query, $conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$cantrespuestaspreg 		= trim($row['T']);
		$nombrespuestapreg 		= trim($row['ENCVALRES']);

		$arrayencuestapregunta[]=$cantrespuestaspreg;
		$cantidadrespuesta[]=$nombrespuestapreg;
		
	}
	
	$arrayencuestapreguntaJSON = json_encode($arrayencuestapregunta);
	$cantidadrespuestaJSON = json_encode($cantidadrespuesta);
	$tmpl->setvariable('arrayencuestapreguntaJSON'		, $cantidadrespuestaJSON);
	$tmpl->setvariable('cantidadrespuestaJSON'		, $arrayencuestapreguntaJSON);
	
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);
	$tmpl->show();
	
?>	
