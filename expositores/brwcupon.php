<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma

	$tmpl= new HTML_Template_Sigma();
	$tmpl->loadTemplateFile('brwcupon.html');

	//Diccionario de idiomas
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$expreg = (isset($_POST['expreg']))? trim($_POST['expreg']) : 0;
	//logerror($encreg);
	//$fltdescri = (isset($_POST['fltdescri']))? trim($_POST['fltdescri']):'';

	// $where = '';
	// if($fltdescri!=''){
	// 	$where .= " AND ENCTITULO CONTAINING '$fltdescri' ";
	// }

	$conn= sql_conectar();//Apertura de Conexion

	
	$query="SELECT EXPREG,EXPCUPREG,EXPCUPTIT,EXPCUPDES,EXPCUPVAL FROM EXP_CUPO WHERE EXPREG=$expreg AND ESTCODIGO<>3 AND EXPCUPREG>296 ORDER BY EXPCUPREG ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		
		$expcupreg 	= trim($row['EXPCUPREG']);
		$expcuptit 	= trim($row['EXPCUPTIT']);
		//logerror($encpreitm);
		$expcupdes  = trim($row['EXPCUPDES']);
		$expcupval  = trim($row['EXPCUPVAL']);



		$tmpl->setCurrentBlock('browser');
		$tmpl->setVariable('expcupreg'		, $expcupreg);
		$tmpl->setVariable('expcuptit'		, $expcuptit);
		$tmpl->setVariable('expcupdes'		, $expcupdes);
		$tmpl->setVariable('expcupval'	, $expcupval);

		$tmpl->parse('browser');
	}
	//Enviamos el cpdogp
	$tmpl->setVariable('expreg'	, $expreg);
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);
	$tmpl->show();

?>
