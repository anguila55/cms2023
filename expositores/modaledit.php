<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('modaledit.html');

	DDIdioma($tmpl);

	$expreg = (isset($_POST['expreg'])) ? trim($_POST['expreg']) : 0;
	$prodreg = (isset($_POST['prodreg'])) ? trim($_POST['prodreg']) : 0;

	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	//Productos
	$productos ="SELECT  PRODREG,PRODNOMBRE, PRODIMG, PRODFOLLETO, PRODDES
	FROM EXP_PROD
	WHERE EXPREG = $expreg AND PRODREG= $prodreg";

	$Table_Productos = sql_query($productos, $conn); 

	for ($prod_index = 0; $prod_index < $Table_Productos->Rows_Count; $prod_index++) {

	$row_prod = $Table_Productos->Rows[$prod_index];
	$prodnombre 	= trim($row_prod['PRODNOMBRE']);
	$prodreg 		= trim($row_prod['PRODREG']);
	$prodimg 		= trim($row_prod['PRODIMG']);
	$prodfolleto 	= trim($row_prod['PRODFOLLETO']);
	$proddes 	= trim($row_prod['PRODDES']);
	$cantidad_productos 	= $prod_index;



	$folderProd =  '../expimg/'.$expreg.'/';

	$tmpl->setVariable('prodnombre'		, $prodnombre);
	$tmpl->setVariable('prodreg'		, $prodreg);
	$tmpl->setVariable('cantidad_productos'		, $cantidad_productos);

	$tmpl->setVariable('prodimg'		, $folderProd.$prodimg);
	$tmpl->setVariable('prodfolleto'	, $prodfolleto);
	$tmpl->setVariable('proddes'	,  $proddes	);
	$tmpl->setVariable('expreg'	,  $expreg	);

	$prodActive = '';
	}

	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
