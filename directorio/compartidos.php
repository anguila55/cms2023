<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
	
	
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('compartidos.html');
	//--------------------------------------------------------------------------------------------------------------
	//Diccionario de idiomas
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------
	$percodigo 	= (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre 	= (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$perapelli 	= (isset($_SESSION[GLBAPPPORT.'PERAPELLI']))? trim($_SESSION[GLBAPPPORT.'PERAPELLI']) : '';
	$perusuacc 	= (isset($_SESSION[GLBAPPPORT.'PERUSUACC']))? trim($_SESSION[GLBAPPPORT.'PERUSUACC']) : '';
	$percompan 	= (isset($_SESSION[GLBAPPPORT.'PERCOMPAN']))? trim($_SESSION[GLBAPPPORT.'PERCOMPAN']) : '';
	$peradmin 	= (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';
	$percorreo 	= (isset($_SESSION[GLBAPPPORT.'PERCORREO']))? trim($_SESSION[GLBAPPPORT.'PERCORREO']) : '';
	$perusareu 	= (isset($_SESSION[GLBAPPPORT.'PERUSAREU']))? trim($_SESSION[GLBAPPPORT.'PERUSAREU']) : '';
	$pertipolog = (isset($_SESSION[GLBAPPPORT.'PERTIPO']))? trim($_SESSION[GLBAPPPORT.'PERTIPO']) 	  : '';
	$perclaselog= (isset($_SESSION[GLBAPPPORT.'PERCLASE']))? trim($_SESSION[GLBAPPPORT.'PERCLASE'])   : '';


		
	$pathimagenes = '../perimg/';
	$imgAvatarNull = '../app-assets/img/avatar.png';
		
	
	$conn= sql_conectar();//Apertura de Conexion
	
		
	$query = "	SELECT P.PERCODIGO,P.PERNOMBRE,P.PERAPELLI,P.PERCOMPAN,P.PERTELEFO,P.PERAVATAR,P.PERCORREO,P.PERCARGO
					FROM PER_QR Q
					LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=Q.PERQRPER
					WHERE Q.PERCODIGO=$percodigo AND Q.PERQRAGE=0 AND Q.PERQRPER<100000
					ORDER BY Q.PERQRFCH DESC";
	
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$percodigo 	= trim($row['PERCODIGO']);
		$percargo 	= trim($row['PERCARGO']);
		$pernombre	= trim($row['PERNOMBRE']);
		$perapelli	= trim($row['PERAPELLI']);
		$percompan	= trim($row['PERCOMPAN']);
		$peravatar	= trim($row['PERAVATAR']);
		$pertelefo		= trim($row['PERTELEFO']);
		$percorreo	= trim($row['PERCORREO']);
		
	
		
		
			$tmpl->setCurrentBlock('browser1');
			$tmpl->setVariable('percodigo'	, $percodigo	);
			$tmpl->setVariable('percargo'	, $percargo		);
			$tmpl->setVariable('pernombre'	, $pernombre	);
			$tmpl->setVariable('perapelli'	, $perapelli	);
			$tmpl->setVariable('percompan'	, $percompan	);
			$tmpl->setVariable('pertelefo'	, $pertelefo		);
			$tmpl->setVariable('percorreo'		, $percorreo		);
			

			if($peravatar!=''){
				if(strpos($peravatar, "https://") !== false){

					$tmpl->setVariable('peravatar'	, $peravatar);
				
				}else{
					$tmpl->setVariable('peravatar'	, $pathimagenes.$percodigo.'/'.$peravatar);
				}
				
			}else{
				$tmpl->setVariable('peravatar'	, $imgAvatarNull);
			}
			
			$tmpl->parse('browser1');
		
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
