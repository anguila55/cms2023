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
	
	$estadoreunion = (isset($_POST['estadoreunion']))? trim($_POST['estadoreunion']) : 0;
	$usuarios = (isset($_POST['usuarios']))? $_POST['usuarios'] : 0;

	$conn = sql_conectar(); //Apertura de Conexion

	$where='';
	$usuarios = implode(', ', $usuarios);
	if($usuarios!=''){
		$where .= " AND (PS.PAICODIGO IN ($usuarios) OR PD.PAICODIGO IN ($usuarios) ) ";
	}
	if ($estadoreunion==1){
		$where .= " AND R.REUESTADO=1 ";
		$tmpl->setVariable('displaytipo1', '');
		$tmpl->setVariable('btnselect2', 'shadow');


	}else{
		$where .= " AND R.REUESTADO=2 ";
		$tmpl->setVariable('displaytipo1', '');
		$tmpl->setVariable('btnselect3', 'shadow');

	}

	


	//////////////// charlas populares  ///////////////////////// 
	$query = "	SELECT 	PD.PERCODIGO AS PERCODDST, PD.PERNOMBRE AS PERNOMDST, PD.PERAPELLI AS PERAPEDST,PD.PERCOMPAN AS PERCOMPANDST,
						PS.PERCODIGO AS PERCODSOL, PS.PERNOMBRE AS PERNOMSOL, PS.PERAPELLI AS PERAPESOL,PS.PERCOMPAN AS PERCOMPANSOL,
						R.REUESTADO,R.REUFECHA,R.REUHORA,R.REUREG,R.REUTIPO
				FROM REU_CABE R
				LEFT OUTER JOIN PER_MAEST PD ON PD.PERCODIGO=R.PERCODDST
				LEFT OUTER JOIN PER_MAEST PS ON PS.PERCODIGO=R.PERCODSOL
				WHERE (PERCODDST!=PERCODSOL) $where
				ORDER BY R.REUFECHA,R.REUHORA ";
	$Table = sql_query($query, $conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		
		$percoddst 		= trim($row['PERCODDST']);
		$pernomdst		= trim($row['PERNOMDST']);
		$perapedst		= trim($row['PERAPEDST']);		
		$percodsol 		= trim($row['PERCODSOL']);
		$percompandst		= trim($row['PERCOMPANDST']);
		$percompansol 		= trim($row['PERCOMPANSOL']);
		if ($percodsol==''){

			$percodsol=$percoddst;
		}
		$pernomsol		= trim($row['PERNOMSOL']);
		$perapesol		= trim($row['PERAPESOL']);		
		$reufecha		= BDConvFch($row['REUFECHA']);
		$reuhora		= substr(trim($row['REUHORA']),0,5);
		$reuhoraorig	= substr(trim($row['REUHORA']),0,5);
		$reuestado		= trim($row['REUESTADO']);
		$reureg			= trim($row['REUREG']);
		$reutipo			= trim($row['REUTIPO']);
		
		
		
		$agefchorden	= substr($reufecha, 6, 4) . substr($reufecha, 3, 2) . substr($reufecha, 0, 2); //Formato calendario (yyyy-mm-dd)


				$haux = date('H:i', strtotime('+10800 seconds', strtotime($reuhora))); //Pongo la hora en Huso horario 0
				$haux = date('H:i', strtotime($_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($haux))); //Pongo la hora, segun el Huso horario establecido por el perfil
				$reuhora = $haux;

		if ($estadoreunion==1){

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
				$reufechaconf=$reufecha;
				
			}

		}
				$tmpl->setCurrentBlock('populares');

				if ($estadoreunion==1){
					$tmpl->setVariable('botoneditar', 'd-none');
					$tmpl->setVariable('botoneliminarconf', 'd-none');
					 $tmpl->setVariable('displaytipo', '');
					$tmpl->setVariable('botonconfirmar', '');
					$tmpl->setVariable('botoneliminarpend', '');
					$tmpl->setVariable('fechasoli', $reufechaconf);
					
				}else{
					$tmpl->setVariable('botoneditar', '');
					$tmpl->setVariable('botoneliminarconf', '');
					$tmpl->setVariable('botonconfirmar', 'd-none');
					$tmpl->setVariable('botoneliminarpend', 'd-none');
					$tmpl->setVariable('displaytipo', '');
				}
				$tmpl->setvariable('reutipo2', $reutipo);
				if ($reutipo==0){

					$tmpl->setVariable('reutipo', 'Virtual');
				}else{

					$tmpl->setVariable('reutipo', 'Presencial');
				}
				$tmpl->setVariable('percontraparte', $percoddst);
				$tmpl->setvariable('persolicitante', $percodsol);
				$tmpl->setVariable('fechareu', $reufecha);
				$tmpl->setvariable('horareu', $reuhora);
				
				$tmpl->setVariable('perfil1reu', $percompandst.' - ' .$pernomdst.' '.$perapedst);
				$tmpl->setVariable('perfil2reu', $percompansol.' - ' .$pernomsol.' '.$perapesol);
				$tmpl->setvariable('agefchorden', $agefchorden);
				$tmpl->setVariable('reureg', $reureg);
				$tmpl->parse('populares');
	}
	
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);

	$tmpl->show();
	
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
		
		return $textofin;
	}
	//--------------------------------------------------------------------------------------------------------------
?>	
