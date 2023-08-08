<?
	if(!isset($_SESSION))  session_start();
	// include($_SERVER["DOCUMENT_ROOT"].'/webcoordinador/func/zglobals.php'); //DEV
	include($_SERVER["DOCUMENT_ROOT"].'/func/zglobals.php'); //PRD
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/constants.php';

	
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('registermail.html');
	
	$tmpl->setVariable('SisNombreEvento', NAME_TITLE );


	$conn = sql_conectar(); //Apertura de Conexion
	$query2 = " SELECT ZDESCRI FROM ZZZ_CONF WHERE ZPARAM = 'SisCorreoNombre'";
$Table2 = sql_query($query2, $conn);
for ($i = 0; $i < $Table2->Rows_Count; $i++) {
	$row = $Table2->Rows[$i];
	$textconfirmar = trim($row['ZDESCRI']);
	
		
		$tmpl->setVariable('textconfirmar'	, nl2br($textconfirmar));
	
}
$query = "	SELECT BANNERS
			FROM ADM_IMG WHERE BANID='10'";

$Table = sql_query($query,$conn);

for($i=0; $i<$Table->Rows_Count; $i++){
	$row = $Table->Rows[$i];
	$file 	= trim($row['BANNERS']);
	$tmpl->setVariable('file', $file);

}


sql_close($conn);
	//--------------------------------------------------------------------------------------------------------------
	$tmpl->show();
	
?>	
