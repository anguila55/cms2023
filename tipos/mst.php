<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC . '/sigma.php';
	require_once GLBRutaFUNC . '/zdatabase.php';
	require_once GLBRutaFUNC . '/zfvarias.php';
	require_once GLBRutaFUNC . '/idioma.php'; //Idioma	

	$tmpl = new HTML_Template_Sigma();
	$tmpl->loadTemplateFile('mst.html');

	//Diccionario de idiomas
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$pertipo = (isset($_POST['pertipo'])) ? trim($_POST['pertipo']) : 0;
	$estcodigo = 1; //Activo por defecto
	$pertipodesesp = '';


	//--------------------------------------------------------------------------------------------------------------
	$conn = sql_conectar(); //Apertura de Conexion

	if ($pertipo != 0) {
		$query = "	SELECT PERTIPO, PERTIPDESESP, PERTIPDESING
							FROM PER_TIPO
							WHERE PERTIPO=$pertipo AND ESTCODIGO=1";

		$Table = sql_query($query, $conn);
		if ($Table->Rows_Count > 0) {
			$row = $Table->Rows[0];


			$pertipo = trim($row['PERTIPO']);
			$pertipodesesp = trim($row['PERTIPDESESP']);
			$pertipodeing = trim($row['PERTIPDESING']);
			
			$tmpl->setVariable('pertipo', $pertipo);
			$tmpl->setVariable('pertipodesesp', $pertipodesesp);
			$tmpl->setVariable('pertipodeing', $pertipodeing);
			
		}
	}

	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);
	$tmpl->show();

?>	
