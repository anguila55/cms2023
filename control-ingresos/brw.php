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
	
	$percodlog = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernomlog = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$perapelog = (isset($_SESSION[GLBAPPPORT.'PERAPELLI']))? trim($_SESSION[GLBAPPPORT.'PERAPELLI']) : '';


	$conn = sql_conectar(); //Apertura de Conexion
	//Link de Reunion
	$urlmeet	= 'https://b2b.btoolbox.com/bigbluebutton/api/';
	$secreto	= 'sSV96OTbkVHIoMCNEPR6MfcxXs7xw9ENx0TTuhI2eA'; 
	$pernombre = $pernombre_controlingresos;
	$perapelli = $perapelli_controlingresos;
	$percodigo = $percodigo_controlingresos;


	//////////////// charlas populares  ///////////////////////// 
	$query = "	SELECT 	PD.PERCODIGO AS PERCODDST, PD.PERNOMBRE AS PERNOMDST, PD.PERAPELLI AS PERAPEDST,PD.PERCOMPAN AS PERCOMPANDST,
						PS.PERCODIGO AS PERCODSOL, PS.PERNOMBRE AS PERNOMSOL, PS.PERAPELLI AS PERAPESOL,PS.PERCOMPAN AS PERCOMPANSOL,
						R.REUESTADO,R.REUFECHA,R.REUHORA,R.REUREG
				FROM REU_CABE R
				LEFT OUTER JOIN PER_MAEST PD ON PD.PERCODIGO=R.PERCODDST
				LEFT OUTER JOIN PER_MAEST PS ON PS.PERCODIGO=R.PERCODSOL
				WHERE R.REUESTADO=2 AND (PERCODDST!=PERCODSOL)
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
		$agefchorden	= substr($reufecha, 6, 4) . substr($reufecha, 3, 2) . substr($reufecha, 0, 2); //Formato calendario (yyyy-mm-dd)


				

				$query1  = "	SELECT FIRST 1  PERQRITM
				FROM PER_QR
				WHERE PERCODIGO=$percoddst AND PERQRPER=$reureg AND PERQRAGE=25 ";
				$Table1 	= sql_query($query1, $conn);

				// Do bad things to the votes array
				if(isset($Table1->Rows[0])){ 
					$row1 = $Table1->Rows[0];
				
				$tmpl->setVariable('conectado1reu', '{conectado}');
				} else{

					$tmpl->setVariable('conectado1reu', 'No {conectado}');

				}

				$query2  = "	SELECT FIRST 1 PERQRITM
				FROM PER_QR
				WHERE PERCODIGO=$percodsol AND PERQRPER=$reureg AND PERQRAGE=25 ";
				$Table2 	= sql_query($query2, $conn);

				// Do bad things to the votes array
				if(isset($Table2->Rows[0])){ 
					$row2 = $Table2->Rows[0];
				
				$tmpl->setVariable('conectado2reu', '{conectado}');
				} else{

					$tmpl->setVariable('conectado2reu', 'No {conectado}');

				}
				$meetingID 	= md5('ReuN'.$reureg);
				$userName 	= convBBBdatos($pernombre).'+'.convBBBdatos($perapelli);
				$apifun 	='join';
				$attendeePW = md5($percoddst.$pernomdst.$perapedst);
				$userID		= $percodigo;
				$campos 	= "meetingID=$meetingID&password=mp&fullName=$userName&userID=$userID&redirect=true"; 
				$data 		= $apifun.$campos.$secreto;
				$keyjoin	= sha1($data);
				$urljoin 	= $urlmeet.$apifun.'?'.$campos.'&checksum='.$keyjoin;

				$passacc 	= md5($percodlog.$pernomlog.$perapelog);
				$campos1 	= "meetingID=$meetingID&password=mp&fullName=Organizador&userID=99999&redirect=true"; //para obtener xml &redirect=false
				$data1 		= $apifun.$campos1.$secreto;
				$keyjoin1	= sha1($data1);
				$urljoin1 	= $urlmeet.$apifun.'?'.$campos1.'&checksum='.$keyjoin1;
				$tmpl->setVariable('urljoin1'	, $urljoin1);

				$haux = date('H:i', strtotime('+10800 seconds', strtotime($reuhora))); //Pongo la hora en Huso horario 0
				$haux = date('H:i', strtotime($_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($haux))); //Pongo la hora, segun el Huso horario establecido por el perfil
				$reuhora = $haux;
				$tmpl->setCurrentBlock('populares');
				$tmpl->setVariable('meetingID', $meetingID);
				$tmpl->setVariable('fechareu', $reufecha);
				$tmpl->setvariable('horareu', $reuhora);
				$tmpl->setVariable('perfil1reu', $pernomdst.' '.$perapedst.' ('.$percompandst.')');
				$tmpl->setVariable('perfil2reu', $pernomsol.' '.$perapesol.' ('.$percompansol.')');
				$tmpl->setvariable('agefchorden', $agefchorden);
				$tmpl->setVariable('linkreu1', $urljoin);
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
