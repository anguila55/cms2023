<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('mst.html');

	//Diccionario de idiomas
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$catreg = (isset($_POST['seccodigo']))? trim($_POST['seccodigo']) : 0;
	$estcodigo = 1; //Activo por defecto
	$secdescri = '';
	
	
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	if($catreg!=0){
		$query = "	SELECT CATREG, CATDESCRI
					FROM PRE_CAT
					WHERE CATREG=$catreg";

		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			$catreg = trim($row['CATREG']);
			$catdescri = trim($row['CATDESCRI']);
			
			$tmpl->setVariable('catreg'		, $catreg	);
			$tmpl->setVariable('catdescri'	, $catdescri	);	
			
		}
	}
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
