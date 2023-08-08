<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('brw.html');
	DDIdioma($tmpl);

	
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	
	$fltdescri = (isset($_POST['fltdescri']))? trim($_POST['fltdescri']):'';
	//Filtro de busqueda por titulo
	$where = '';
	if($fltdescri!=''){
		$where .= " AND AVITITULO CONTAINING '$fltdescri' ";
	}
	
	$conn= sql_conectar();//Apertura de Conexion
	
	$query = "	SELECT PREREG, PRETITULO, PREDESCRI, PREIMG, PREURL, PERCODIGO, PRECATEGO, EXPREG,PREESTADO,PREN_ORD,PREFECHA
				FROM PREN_MAEST
				WHERE PREESTADO<>3 $where
				ORDER BY PREN_ORD ASC, PREFECHA DESC ";
				
	//logerror($query);			
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$prereg 		= trim($row['PREREG']);
		$pretitulo 		= trim($row['PRETITULO']);
		$predescri 		= trim($row['PREDESCRI']);
		$preimg		    = trim($row['PREIMG']);
		$percodigo     	= trim($row['PERCODIGO']);
		$preurl    		= trim($row['PREURL']);
		$precatego    	= trim($row['PRECATEGO']);
		$expreg     	= trim($row['EXPREG']);
		$preestado    	= trim($row['PREESTADO']);
		$prefecha    	= BDConvFch($row['PREFECHA']);
		$prepos    	= trim($row['PREN_ORD']);

		$prefecha	= substr($prefecha,0,2).'-'.substr($prefecha,3,2).'-'.substr($prefecha,6,4);
		
		$tmpl->setCurrentBlock('browser');
		
		$tmpl->setVariable('prereg'			, $prereg);
		$tmpl->setVariable('pretitulo'		, $pretitulo);
		$tmpl->setVariable('predescri'		, $predescri);
		$tmpl->setvariable('preimg'			, $preimg);
		$tmpl->setvariable('percodigopre'	, $percodigo);
		$tmpl->setvariable('preurl'			, $preurl);
		$tmpl->setvariable('precatego'		, $precatego);
		$tmpl->setvariable('expreg'			, $expreg);
		$tmpl->setvariable('preestado'		, $preestado);
		$tmpl->setvariable('prefecha'		, $prefecha);

		if ($prepos == 999999){

			$prepos=' - ';

		}
		$tmpl->setvariable('prepos'			, $prepos);
		
		$tmpl->parse('browser');
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
