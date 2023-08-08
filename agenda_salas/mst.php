<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('mst.html');
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$catreg = (isset($_POST['catreg']))? trim($_POST['catreg']) : 0;
	$percodexp = 0;
	$precatego='';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	

	

//categorias
$query =$query = "SELECT  CATDESCRI, CATREG
FROM AGE_CAT
WHERE CATREG='$catreg'";

$Table_categorias = sql_query($query, $conn); 

for ($index_categorias = 0; $index_categorias < $Table_categorias->Rows_Count; $index_categorias++) {

	$row_cateogoria = $Table_categorias->Rows[$index_categorias];

	$catreg 		= trim($row_cateogoria['CATREG']);
	$catdescri 		= trim($row_cateogoria['CATDESCRI']);

	$tmpl->setCurrentBlock('categorias');

	$tmpl->setVariable('catdescri'		,$catdescri);
	$tmpl->setVariable('catreg'			,$catreg);
	$tmpl->parse('categorias');

}

	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
