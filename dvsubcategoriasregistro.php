<?
	if(!isset($_SESSION))  session_start();
	// include($_SERVER["DOCUMENT_ROOT"].'/webcoordinador/func/zglobals.php'); //DEV
	include($_SERVER["DOCUMENT_ROOT"].'/func/zglobals.php'); //PRD
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC . '/constants.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('dvsubcategoriasregistro.html');
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	$categorias		= '';
	$subcategorias 	= '';
	
	if(isset($_POST['categorias'])){
		foreach($_POST['categorias'] as $ind => $data){
			$categorias .= $data['catcodigo'].','; 
		}
		if(trim($categorias)==',') $categorias='';
	}
	
	if(isset($_POST['subcategorias'])){
		foreach($_POST['subcategorias'] as $ind => $data){
			$subcategorias .= $data['catsubcod'].','; 
		}
		if(trim($subcategorias)==',') $subcategorias='';
	}
	//Cargo las Subcategorias
	$query = "	SELECT CATSUBCOD,CATSUBDES,CATSUBDESING,CATCODIGO
				FROM CAT_SUB
				WHERE CATCODIGO IN ($categorias 0) AND ESTCODIGO<>3 ";

	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row= $Table->Rows[$i];
		$catsubcod 		= trim($row['CATSUBCOD']);
		$catsubdes 		= trim($row['CATSUBDES']);
		$catsubdesing 	= trim($row['CATSUBDESING']);
		$catcod 		= trim($row['CATCODIGO']);
		
		$tmpl->setCurrentBlock('subcategorias');
		$tmpl->setVariable('catsubcod'		, $catsubcod	);
		$tmpl->setVariable('catsubdes'		, $catsubdes	);
		$tmpl->setVariable('catsubdesing'	, $catsubdesing	);
		$tmpl->setVariable('catcod'			, $catcod		);
		
		if(strpos($subcategorias,$catsubcod.',')!==false){
			$tmpl->setVariable('catsubchecked'	, 'checked'	);
		}
		
		$tmpl->parse('subcategorias');
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
