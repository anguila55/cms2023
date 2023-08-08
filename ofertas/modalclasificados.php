<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('modalclasificados.html');

	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$expreg 	= (isset($_POST['expreg']))? trim($_POST['expreg']) : 0;
	$expcupreg 	= (isset($_POST['expcupreg']))? trim($_POST['expcupreg']) : 0;
	//$estcodigo = 1; //Activo por defecto
	
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion

	if($expreg!=0 && $expcupreg!=0){
		$query = "	SELECT *
					FROM EXP_CUPO EC
					WHERE EC.EXPREG=$expreg AND EC.EXPCUPREG=$expcupreg " ;
		$Table = sql_query($query,$conn);
		$row = $Table->Rows[0];
		$expreg 	= trim($row['EXPREG']);
		$expcupreg 	= trim($row['EXPCUPREG']);
		$expcuptit 	= trim($row['EXPCUPTIT']);
		$expcupdes 	= trim($row['EXPCUPDES']);
		$expcupval 	= trim($row['EXPCUPVAL']);
		$expcupzon 	= trim($row['EXPCUPZON']);
		$seccodigo 	= trim($row['SECCODIGO']);
		$secsubcod 	= trim($row['SECSUBCOD']);
		$secclase 	= trim($row['SECCLASE']);
		$seclad 	= trim($row['SECLAD']);
		$expcupcar1 	= trim($row['EXPCUPCAR1']);
		$expcupcar2 	= trim($row['EXPCUPCAR2']);
		$expcupcar3 	= trim($row['EXPCUPCAR3']);
		$expcupcar4 	= trim($row['EXPCUPCAR4']);
		$expcupcar5 	= trim($row['EXPCUPCAR5']);
		$expcupcar6 	= trim($row['EXPCUPCAR6']);
		$expcupcar7 	= trim($row['EXPCUPCAR7']);
		$expcupcar8 	= trim($row['EXPCUPCAR8']);
		$expcupcar9 	= trim($row['EXPCUPCAR9']);
		$expcupcar10 	= trim($row['EXPCUPCAR10']);
		$expcupcar11 = trim($row['EXPCUPCAR11']);
		$tmpl->setCurrentBlock('browser');
		$tmpl->setVariable('expreg'	, $expreg);
		$tmpl->setVariable('expcupreg'	, $expcupreg);
		$tmpl->setVariable('expcuptit'	, $expcuptit);
		$tmpl->setVariable('expcupdes'	, $expcupdes);
		$tmpl->setVariable('expcupval'	, $expcupval);
		$tmpl->setVariable('expcupzon'	, $expcupzon);
		$tmpl->setVariable('seccodigo'	, $seccodigo);
		$tmpl->setVariable('secsubcod'	, $secsubcod);
		$tmpl->setVariable('secclase'	, $secclase);
		$tmpl->setVariable('seclad'	, $seclad);

		if ($expcupcar1 == 1){
			$tmpl->setVariable('checkedcar1'	, 'checked');
		}
		if ($expcupcar2 == 1){
			$tmpl->setVariable('checkedcar2'	, 'checked');
		}
		if ($expcupcar3 == 1){
			$tmpl->setVariable('checkedcar3'	, 'checked');
		}
		if ($expcupcar4 == 1){
			$tmpl->setVariable('checkedcar4'	, 'checked');
		}
		if ($expcupcar5 == 1){
			$tmpl->setVariable('checkedcar5'	, 'checked');
		}
		if ($expcupcar6 == 1){
			$tmpl->setVariable('checkedcar6'	, 'checked');
		}
		if ($expcupcar7 == 1){
			$tmpl->setVariable('checkedcar7'	, 'checked');
		}
		if ($expcupcar8 == 1){
			$tmpl->setVariable('checkedcar8'	, 'checked');
		}
		if ($expcupcar9 == 1){
			$tmpl->setVariable('checkedcar9'	, 'checked');
		}
		if ($expcupcar10 == 1){
			$tmpl->setVariable('checkedcar10'	, 'checked');
		}
		if ($expcupcar11 == 1){
			$tmpl->setVariable('checkedcar11'	, 'checked');
		}


		$tmpl->parse('browser');

	}
	$tmpl->setVariable('expreg'	, $expreg);


	
//sectores
$sectores ="SELECT  SECCODIGO,SECDESCRI
FROM SEC_MAEST 
WHERE ESTCODIGO<>3";

$Table_sectores = sql_query($sectores, $conn); 

for ($index_sectores = 0; $index_sectores < $Table_sectores->Rows_Count; $index_sectores++) {

	$row_sectores = $Table_sectores->Rows[$index_sectores];

	$seccod 		= trim($row_sectores['SECCODIGO']);
	$secdescri 		= trim($row_sectores['SECDESCRI']);

	$tmpl->setCurrentBlock('sectores');

	if($seccod == $seccodigo){
	$tmpl->setVariable('selected'		,'selected');
	}
	$tmpl->setVariable('secdescri'		,$secdescri);
	$tmpl->setVariable('seccodigo'			,$seccod);
	$tmpl->parse('sectores');


}

//familia
$familia ="SELECT  SECSUBCOD,SECSUBDES,SECCODIGO
FROM SEC_SUB 
WHERE ESTCODIGO<>3";

$Table_familia = sql_query($familia, $conn); 

for ($index_familia = 0; $index_familia < $Table_familia->Rows_Count; $index_familia++) {

	$row_familia = $Table_familia->Rows[$index_familia];

	$secsubcod2 		= trim($row_familia['SECSUBCOD']);
	$secsubdes 		= trim($row_familia['SECSUBDES']);
	$seccodigo2 		= trim($row_familia['SECCODIGO']);

	$tmpl->setCurrentBlock('familia');

	if($secsubcod == $secsubcod2){
	$tmpl->setVariable('selectedfamilia'		,'selected');
	}
	$tmpl->setVariable('secsubdes'		,$secsubdes);
	$tmpl->setVariable('secsubcod'			,$secsubcod2);
	$tmpl->setVariable('seccodigo2'			,$seccodigo2);
	$tmpl->parse('familia');


}

//clases
$clase ="SELECT  SECSUBCOD,SECCLASE,SECCLASEDES
FROM SEC_CLASE ";

$Table_clase = sql_query($clase, $conn); 

for ($index_clase = 0; $index_clase < $Table_clase->Rows_Count; $index_clase++) {

	$row_clase = $Table_clase->Rows[$index_clase];

	$secsubcod3 		= trim($row_clase['SECSUBCOD']);
	$secclasedes 		= trim($row_clase['SECCLASEDES']);
	$secclase2 		= trim($row_clase['SECCLASE']);

	$tmpl->setCurrentBlock('clases');

	if($secclase2 == $secclase){
	$tmpl->setVariable('selectedclase'		,'selected');
	}
	$tmpl->setVariable('secclasedes'		,$secclasedes);
	$tmpl->setVariable('secclase'			,$secclase2);
	$tmpl->setVariable('secsubcod2'			,$secsubcod3);
	$tmpl->parse('clases');


}


//ladrillos
$ladrillos ="SELECT  SECLAD,SECCLASE,SECLADDES
FROM SEC_LAD ";

$Table_ladrillos = sql_query($ladrillos, $conn); 

for ($index_ladrillos = 0; $index_ladrillos < $Table_ladrillos->Rows_Count; $index_ladrillos++) {

	$row_ladrillos = $Table_ladrillos->Rows[$index_ladrillos];

	$secclase3 		= trim($row_ladrillos['SECCLASE']);
	$secladdes 		= trim($row_ladrillos['SECLADDES']);
	$seclad2 		= trim($row_ladrillos['SECLAD']);

	$tmpl->setCurrentBlock('ladrillos');

	if($seclad == $seclad2){
	$tmpl->setVariable('selectedladrillo'		,'selected');
	}
	$tmpl->setVariable('secladdes'		,$secladdes);
	$tmpl->setVariable('seclad'			,$seclad2);
	$tmpl->setVariable('secclase2'			,$secclase3);
	$tmpl->parse('ladrillos');


}

	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
