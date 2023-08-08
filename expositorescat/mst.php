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
		$query = "	SELECT CATREG, CATDESCRI,CATVALOR,CATVALORMAX,CATIMG,CATVID,CATTXT,CATVIS,CATPROD,CATPER
					FROM EXP_CAT
					WHERE CATREG=$catreg";

		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			$catreg = trim($row['CATREG']);
			$catdescri = trim($row['CATDESCRI']);
			$catvalor = trim($row['CATVALOR']);
			$catvalormax = trim($row['CATVALORMAX']);
			$catimg = trim($row['CATIMG']);
			$catvid = trim($row['CATVID']);
			$cattxt = trim($row['CATTXT']);
			$catvis = trim($row['CATVIS']);
			$catprod = trim($row['CATPROD']);
			$catper = trim($row['CATPER']);
		
			
			$tmpl->setVariable('catreg'		, $catreg	);
			$tmpl->setVariable('catdescri'	, $catdescri	);
			$tmpl->setVariable('catvalor'	, $catvalor	);
			$tmpl->setVariable('catvalormax'	, $catvalormax	);
			$tmpl->setVariable('catimg'	, $catimg	);
			$tmpl->setVariable('catvid'	, $catvid	);
			$tmpl->setVariable('cattxt'	, $cattxt	);
			$tmpl->setVariable('catvis'	, $catvis	);
			$tmpl->setVariable('catprod'	, $catprod	);
			$tmpl->setVariable('catper'		, $catper	);

			if($catvis == 1){
				$tmpl->setVariable('selected-grande'	, 'selected'	);

			}
			if($catvis == 2){
				$tmpl->setVariable('selected-mediana'	, 'selected'	);

			}
			
			
			
		}
	}
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
