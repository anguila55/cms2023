<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('brw.html');
	
	//Diccionario de idiomas
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	
	$fltdescri = (isset($_POST['fltdescri']))? trim($_POST['fltdescri']):'';
	
	$where = '';
	if($fltdescri!=''){
		$where .= " AND PERTIPDESESP CONTAINING '$fltdescri' ";
	}
	
	$conn= sql_conectar();//Apertura de Conexion
	
	$query = "	SELECT PC.PERCLASE, PC.PERCLADES, PT.PERTIPO, PT.PERTIPDESESP
				FROM PER_TIPO PT
				LEFT OUTER JOIN PER_CLASE PC ON PC.PERTIPO=PT.PERTIPO
				WHERE PC.ESTCODIGO <> 3 AND PT.ESTCODIGO <> 3
				ORDER BY PT.PERTIPDESESP ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){

		$row = $Table->Rows[$i];
		$perclase 	= trim($row['PERCLASE']);
		$perclades 	= trim($row['PERCLADES']);
		$pertipo 	= trim($row['PERTIPO']);
		$pertipodesesp 	= trim($row['PERTIPDESESP']);

		$tmpl->setCurrentBlock('browser');
		$tmpl->setVariable('perclase'	, $perclase);
		$tmpl->setVariable('perclades'	, $perclades);
		$tmpl->setVariable('pertipo'	, $pertipo);
		$tmpl->setVariable('pertipodesesp'	, $pertipodesesp);
		$tmpl->parse('browser');
	}
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
