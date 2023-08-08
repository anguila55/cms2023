<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';	 
	require_once GLBRutaFUNC.'/constants.php';
		
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('bsq.html');
	//--------------------------------------------------------------------------------------------------------------
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo 			= (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre 			= (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$perapelli 			= (isset($_SESSION[GLBAPPPORT.'PERAPELLI']))? trim($_SESSION[GLBAPPPORT.'PERAPELLI']) : '';
	$perusuacc 			= (isset($_SESSION[GLBAPPPORT.'PERUSUACC']))? trim($_SESSION[GLBAPPPORT.'PERUSUACC']) : '';
	$perpasacc 			= (isset($_SESSION[GLBAPPPORT.'PERCORREO']))? trim($_SESSION[GLBAPPPORT.'PERCORREO']) : '';
	$pertipo 			= (isset($_SESSION[GLBAPPPORT.'PERTIPO']))? trim($_SESSION[GLBAPPPORT.'PERTIPO']) : '';
	$peradmin 			= (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';
	$peravatar 			= (isset($_SESSION[GLBAPPPORT.'PERAVATAR']))? trim($_SESSION[GLBAPPPORT.'PERAVATAR']) : '';
	$perusadis 			= (isset($_SESSION[GLBAPPPORT.'PERUSADIS']))? trim($_SESSION[GLBAPPPORT.'PERUSADIS']) : '';
	$peradmin 			= (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';
	$peravatar 			= (isset($_SESSION[GLBAPPPORT.'PERAVATAR']))? trim($_SESSION[GLBAPPPORT.'PERAVATAR']) : '';
	$btnsectores 		= (isset($_SESSION[GLBAPPPORT.'SECTORES']))? trim($_SESSION[GLBAPPPORT.'SECTORES']) : '';
	$btnsubsectores 	= (isset($_SESSION[GLBAPPPORT.'SUBSECTORES']))? trim($_SESSION[GLBAPPPORT.'SUBSECTORES']) : '';
	$btncategorias 		= (isset($_SESSION[GLBAPPPORT.'CATEGORIAS']))? trim($_SESSION[GLBAPPPORT.'CATEGORIAS']) : '';
	$btnsubcategorias 	= (isset($_SESSION[GLBAPPPORT.'SUBCATEGORIAS']))? trim($_SESSION[GLBAPPPORT.'SUBCATEGORIAS']) : '';
	
	$tmpl->setVariable('percodnotif', $percodigo	);
	$tmpl->setVariable('pernombre'	, $pernombre	);
	$tmpl->setVariable('perapelli'	, $perapelli	);
	$tmpl->setVariable('perusuacc'	, $perusuacc	);
	$tmpl->setVariable('perpasacc'	, $perpasacc	);
	$tmpl->setVariable('peravatar'	, $peravatar	);
		
	//Nombre del Evento
	//var_dump($pertipo);die;
	if ($pertipo == 74 || $pertipo == 73)
	{
		$tmpl->setVariable('sacocartel'	, 'sacocartel();');
	}else{
		
	}
	$tmpl->setVariable('SisNombreEvento', NAME_TITLE );

	$conn= sql_conectar();//Apertura de Conexion

	$hoy 	= date('m/d/Y');
	$cantdias =0;
	$query="SELECT DISTINCT A.WORK_FCH FROM WORK_MAEST A WHERE A.ESTCODIGO=1 AND A.WORK_FCH>='$hoy'";
					//logerror($query);
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$agefch	= trim($row['WORK_FCH']);
		$agefecha	= BDConvFch($row['WORK_FCH']);
		

		
		
		$tmpl->setCurrentBlock('browserfecha');

		if (($IdiomView=='ING')){

			setlocale(LC_ALL,"en_US");
		}else{
			setlocale(LC_TIME, 'es_ES', 'Spanish_Spain', 'Spanish');
		}

		
		$agefecha	= substr($agefecha, 0, 2) .'.'. substr($agefecha, 3, 2) .'.'. substr($agefecha, 6, 4); //Formato 

		$date = DateTime::createFromFormat("Y-m-d", $agefch);


		$tmpl->setVariable('agefecha'	, $agefecha);
		if ($i==0){

			$tmpl->setVariable('agefechainicial'	, $agefecha);
		}
		$tmpl->setVariable('agefch'	, utf8_encode(strftime("%A %d %B",$date->getTimestamp())));
		$tmpl->setVariable('idfecha'	, $i);
		$cantdias++;
		$tmpl->parse('browserfecha');

		
	}
	$tmpl->setVariable('cantdias'	, $cantdias);

/////////////////NOMBRE BANNERS/////////////////////
$queryparam = " SELECT PARCODIGO,PARNOM$IdiomView AS PARNOMBRE
FROM PAR_MAEST 
WHERE PARCODIGO='mesasredondas'";
$Tableparam = sql_query($queryparam, $conn);
$rowparam = $Tableparam->Rows[0];
$parnombre = trim($rowparam['PARNOMBRE']);
$paneladmin = trim($rowparam['PARCODIGO']);
$tmpl->setVariable('nombre'.$paneladmin, $parnombre);

	//--------------------------------------------------------------------------------------------------------------
	//$conn= sql_conectar();//Apertura de Conexion
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
