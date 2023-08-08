<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	

			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('mst.html');
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$agereg = (isset($_POST['agereg']))? trim($_POST['agereg']) : 0;
	$estcodigo = 1; //Activo por defecto
	$agetitulo = '';
	$spkcode='';
	$spkcode2=0;
	
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	

		if($agereg!=0){

		$query = "SELECT AGEREG, AGETITULO, AGETITING, AGEDESCRI, AGEDESING, AGELUGAR, AGEFCH, AGEHORINI, AGEHORFIN, 
						ESTCODIGO, SPKREG, AGEPREHAB, AGEYOULNK, AGEYOULNKING, AGEYOULNKPOR
				  FROM AGE_MAEST
				  WHERE AGEREG=$agereg " ;
		
		$Table = sql_query($query,$conn);
		
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			
			$agereg 	= trim($row['AGEREG']);
			$agetitulo 	= trim($row['AGETITULO']);
			$agedescri 	= trim($row['AGEDESCRI']);
			$agetituloing 	= trim($row['AGETITING']);
			$agedescriing 	= trim($row['AGEDESING']);
			$agelugar  	= trim($row['AGELUGAR']);
			$agefch 	= BDConvFch($row['AGEFCH']);
			
			$haux = date('H:i:s', strtotime('+10800 seconds', strtotime(trim($row['AGEHORINI']))));
			$agehorini = date('H:i:s', strtotime($_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($haux)));   // pongo en horario 0
			 //Pongo la hora en huso horario argentino
	
			 $haux2 = date('H:i:s', strtotime('+10800 seconds', strtotime(trim($row['AGEHORFIN']))));
			$agehorfin = date('H:i:s', strtotime($_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($haux2))); 


			$estcodigo 	= trim($row['ESTCODIGO']);
			$spkcode 	= trim($row['SPKREG']);
			$ageprehab 	= trim($row['AGEPREHAB']);
			$ageyoulnk 	= trim($row['AGEYOULNK']);
			$ageyoulnking 	= trim($row['AGEYOULNKING']);
			$ageyoulnkpor 	= trim($row['AGEYOULNKPOR']);
			
			$spkcode2 = explode(',',$spkcode);
			
			$agefch	= substr($agefch,6,4).'-'.substr($agefch,3,2).'-'.substr($agefch,0,2);
			
			$tmpl->setVariable('agereg'		, $agereg		);
			$tmpl->setVariable('agetitulo'	, $agetitulo	);
			$tmpl->setVariable('agedescri'	, $agedescri	);
			$tmpl->setVariable('agetituloing'	, $agetituloing	);
			$tmpl->setVariable('agedescriing'	, $agedescriing	);
			$tmpl->setVariable('agefch'		, $agefch		);
			$tmpl->setVariable('agehorini'	, $agehorini	);
			$tmpl->setVariable('agehorfin'	, $agehorfin	);
			$tmpl->setVariable('estcodigo'	, $estcodigo	);
			$tmpl->setVariable('spkcode'	, $spkcode		);
			$tmpl->setVariable('ageyoulnk'	, $ageyoulnk	);
			$tmpl->setVariable('ageyoulnking'	, $ageyoulnking	);
			$tmpl->setVariable('ageyoulnkpor'	, $ageyoulnkpor	);
			
			$tmpl->setVariable('ageprehab'.$ageprehab	, 'selected'	);

			switch($agelugar){
				case 'Auditorio Agrícola': $opcion=1; break;
				case 'Auditorio Ganadero': $opcion=2; break;
				case 'Auditorio Institucional': $opcion=3; break;
				case 'Auditorio Prensa': $opcion=4; break;
				case 'Carpa Internacional': $opcion=5; break;
				case 'Centro de Expertos': $opcion=6; break;
				case 'Dinámicas a campo': $opcion=7; break;
				case 'Espacio de Remates': $opcion=8; break;
				case 'Espacio Agtech': $opcion=9; break;
				case 'Pistas de Test-Drive': $opcion=10; break;
				case 'Sector de Ganadería': $opcion=11; break;
				case 'Tecnódromo': $opcion=12; break;
				
				$tmpl->setVariable('agelugsalap','selected');
			}
			
			
		}
		

	}


	//query y edicion de salas.

	$query = "SELECT CATDESCRI
	FROM AGE_CAT
	WHERE ESTCODIGO<>3  
	ORDER BY CATDESCRI";

	$Table = sql_query($query,$conn);
    for($i=0; $i<$Table->Rows_Count; $i++){
    $row = $Table->Rows[$i];
    $salasdes 	= trim($row['CATDESCRI']);

	$tmpl->setCurrentBlock('salas');
			$tmpl->setVariable('salacod'	, $salascod 		);
			$tmpl->setVariable('salades'	, $salasdes	);

			if($agelugar==$salasdes){
				$tmpl->setVariable('salasel', 'selected' );
			}

			$tmpl->parse('salas');
		}


	$query="SELECT * FROM SPK_MAEST WHERE ESTCODIGO=1 ORDER BY SPKPOS";
		$execute =sql_query($query,$conn);

		for($i=0; $i<$execute->Rows_Count; $i++){


			$row = $execute->Rows[$i];
			$spkreg = trim($row['SPKREG']);
			$spktitulo = trim($row['SPKNOMBRE']);
			$spkdescri = trim($row['SPKDESCRI']);
			$spkpos  = trim($row['SPKPOS']);
			$spkimg  = trim($row['SPKIMG']);
			$estcodigo  = trim($row['ESTCODIGO']);

			$tmpl->setCurrentBlock('speakers');
				$tmpl->setVariable('spkreg'		, $spkreg		);
				$tmpl->setVariable('spktitulo'	, $spktitulo	);
				$tmpl->setVariable('spkdescri'	, $spkdescri	);
				$tmpl->setVariable('spkimg'		, $spkimg		);
				$tmpl->setVariable('spkpos'		, $spkpos		);
				$tmpl->setVariable('estcodigo'	, $estcodigo	);

					 $spkcode3='';
					if($spkcode2!='')$spkcode3=count($spkcode2);

					 for($j=0; $j<$spkcode3; $j++)
					{

						if($spkcode2[$j] == $spkreg){

							$tmpl->setVariable('spkselect'	, 'selected' 	);
						}

					}

			$tmpl->parse('speakers');
		

		}
		
	
		
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
