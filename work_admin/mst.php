<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('mst.html');
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$workreg = (isset($_POST['workreg']))? trim($_POST['workreg']) : 0;
	
	$estcodigo 	= 1; //Activo por defecto
	$worktitulo 	= '';
	$spkcode	= '';
	$speakers	= '';
		
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	if($workreg!=0){
		$query = "SELECT WORK_REG, WORK_TITULO, WORK_DESCRI, WORK_FCH, WORK_HORINI, WORK_HORFIN, 
						ESTCODIGO, SPKREG, WORK_BBB, WORK_LINK, WORK_PDF
				  FROM WORK_MAEST
				  WHERE WORK_REG=$workreg " ;
		
		$Table = sql_query($query,$conn);		
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			
			$workreg 	= trim($row['WORK_REG']);
			$worktitulo 	= trim($row['WORK_TITULO']);
			$workdescri 	= trim($row['WORK_DESCRI']);
			$workfch 	= BDConvFch($row['WORK_FCH']);
			$haux = date('H:i:s', strtotime('+10800 seconds', strtotime(trim($row['WORK_HORINI']))));
			$workhorini = date('H:i:s', strtotime($_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($haux)));   // pongo en horario 0
		 //Pongo la hora en huso horario argentino

		 	$haux2 = date('H:i:s', strtotime('+10800 seconds', strtotime(trim($row['WORK_HORFIN']))));
			$workhorfin = date('H:i:s', strtotime($_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($haux2)));   // pongo en horario 0
			$estcodigo 	= trim($row['ESTCODIGO']);
			$speakers 	= '0,'.trim($row['SPKREG']).',0';
			$workbbb	= trim($row['WORK_BBB']);
			$worklink 	= trim($row['WORK_LINK']);
			$workpdf 	= trim($row['WORK_PDF']);
			
			$workfch	= substr($workfch,6,4).'-'.substr($workfch,3,2).'-'.substr($workfch,0,2);
			
			$tmpl->setVariable('workreg'		, $workreg		);
			$tmpl->setVariable('worktitulo'	, $worktitulo	);
			$tmpl->setVariable('workdescri'	, $workdescri	);
			$tmpl->setVariable('workfch'		, $workfch		);
			$tmpl->setVariable('workhorini'	, $workhorini	);
			$tmpl->setVariable('workhorfin'	, $workhorfin	);
			$tmpl->setVariable('estcodigo'	, $estcodigo	);
			$tmpl->setVariable('workbbb'	, $workbbb	);
			$tmpl->setVariable('worklink'	, $worklink	);
			$tmpl->setVariable('workpdf'	, $workpdf	);
			
			

		}
	}
	//--------------------------------------------------------------------------------------------------------------
	//Cargo el combo de speakers
	$query="SELECT SPKREG,SPKNOMBRE
			FROM SPK_MAEST 
			WHERE ESTCODIGO=1 
			ORDER BY SPKPOS";
	$Table =sql_query($query,$conn);		
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$spkreg 	= trim($row['SPKREG']);
		$spknombre 	= trim($row['SPKNOMBRE']);

		$tmpl->setCurrentBlock('speakers');
		$tmpl->setVariable('spkreg'		, $spkreg		);
		$tmpl->setVariable('spknombre'	, $spknombre	);
		
		if(strpos($speakers,",$spkreg,")>0){
			$tmpl->setVariable('spkselect'	, 'selected' 	);
		}
		$tmpl->parse('speakers');
	}	
	//Seleccionamos los perfiles
	$query = "	SELECT PERNOMBRE,PERAPELLI,PERCODIGO
				FROM PER_MAEST 
				WHERE ESTCODIGO=1
				ORDER BY PERAPELLI ";
	$Table = sql_query($query, $conn);
	for ($i = 0; $i < $Table->Rows_Count; $i++) {
		$row = $Table->Rows[$i];
		$percod 	= trim($row['PERCODIGO']);
		$pernombre	= trim($row['PERNOMBRE']);
		$perapelli	= trim($row['PERAPELLI']);
		$tmpl->setCurrentBlock('perfiles');

		$perfiles = "SELECT * FROM WORK_PER WHERE PERCODIGO =$percod AND WORK_REG  = $workreg";
		$Table_Perfiles = sql_query($perfiles, $conn);
		if($Table_Perfiles->Rows_Count != -1){
		$tmpl->setVariable('persel', 'selected');
		//var_dump("entro aca");die;
		}
		$tmpl->setVariable('percodigo', $percod);
		$tmpl->setVariable('pernombre', $pernombre);
		$tmpl->setVariable('perapelli', $perapelli);
		$tmpl->parse('perfiles');
	}

	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
