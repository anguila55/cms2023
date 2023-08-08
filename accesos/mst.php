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
	$accreg = (isset($_POST['accreg']))? trim($_POST['accreg']) : 0;
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion

	if($accreg!=0){
		$query = "	SELECT * FROM ACC_MAEST WHERE ACCREG = $accreg";

		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			$accreg 		= trim($row['ACCREG']);
			$acctitulo 		= trim($row['ACCTITULO']);
			$acchref 		= trim($row['ACCHREF']);
			$accicono 		= trim($row['ACCICONO']);

			$tmpl->setVariable('accreg', $accreg );
			$tmpl->setVariable('acctitulo', trim($acctitulo, "{}") );
			$tmpl->setVariable('acchref', $acchref );
			$tmpl->setVariable('accicono', $accicono );
	}

}




	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);
	$tmpl->show();

?>
