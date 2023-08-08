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
$vidreg = (isset($_POST['vidreg'])) ? trim($_POST['vidreg']) : 0;
$estcodigo = 1; //Activo por defecto
$pathimagenes = '../vidimg/';
$imgAvatarNull = '../app-assets/img/avatar.png';
$tmpl->setVariable('expavatar', $imgAvatarNull);
$tmpl->setVariable('imgAvatarNull', $imgAvatarNull);

//--------------------------------------------------------------------------------------------------------------
$conn = sql_conectar(); //Apertura de Conexion

if ($vidreg != 0) {
	$query = "	SELECT *
					FROM VID_MAEST
					WHERE VIDREG =$vidreg AND ESTCODIGO=1";

	$Table = sql_query($query, $conn);
	if ($Table->Rows_Count > 0) {
		$row = $Table->Rows[0];
		$vidreg 	= trim($row['VIDREG']);
		$vidtitulo 	= trim($row['VIDTITULO']);
		$vidtitulocarta 	= trim($row['VIDTITULOCARTA']);
		$vidurl 	= trim($row['VIDURL']);
		$vidurlpdf 	= trim($row['VIDURLPDF']);
		$vidid	 	= trim($row['VIDID']);
		$usnombre 	= trim($row['US_NOMBRE']);
		$usmail 	= trim($row['US_MAIL']);
		$ustelefono = trim($row['US_TEL']);
		$usempresa 	= trim($row['US_EMP']);
		$uspais 	= trim($row['US_PAI']);
		$uslinkedin = trim($row['US_LIN']);
		$usfacebook	= trim($row['US_FAC']);
		$ustwitter 	= trim($row['US_TWI']);
		$usweb 	= trim($row['US_WEB']);
		$vidimg 	= trim($row['VIDIMG']);
		$vidcatego 	= trim($row['VIDCATEGO']);

		$tmpl->setVariable('vidreg', $vidreg);
		$tmpl->setVariable('vidtitulo'	, $vidtitulo);
		$tmpl->setVariable('vidtitulocarta'	, $vidtitulocarta);
		$tmpl->setVariable('vidurl'		, $vidurl);
		$tmpl->setVariable('vidurlpdf'		, $vidurlpdf);
		if ($vidid==1){
			$tmpl->setVariable('vididchecked'	, 'checked');
		}else{
			$tmpl->setVariable('vididchecked'	, '');
		}
		
		$tmpl->setVariable('usnombre'		, $usnombre);
		$tmpl->setVariable('usmail'		, $usmail);
		$tmpl->setVariable('ustelefono'	, $ustelefono);
		$tmpl->setVariable('usempresa'		, $usempresa);
		$tmpl->setVariable('uspais'		, $uspais);
		$tmpl->setVariable('uslinkedin'	, $uslinkedin);
		$tmpl->setVariable('usfacebook'		, $usfacebook);
		$tmpl->setVariable('ustwitter'	, $ustwitter);
		$tmpl->setVariable('usweb'		, $usweb);

		if ($vidimg == '') {
			$vidimg = $imgAvatarNull;
		} else {
			$vidimg = $pathimagenes . $vidreg . '/' . $vidimg;
		}
		$tmpl->setVariable('vidimg'		, $vidimg);
	}
}

//categorias
$categorias ="SELECT  CATREG,CATDESCRI
FROM VID_CAT";

$Table_categorias = sql_query($categorias, $conn); 

for ($index_categorias = 0; $index_categorias < $Table_categorias->Rows_Count; $index_categorias++) {

	$row_cateogoria = $Table_categorias->Rows[$index_categorias];

	$catreg 		= trim($row_cateogoria['CATREG']);
	$catdescri 		= trim($row_cateogoria['CATDESCRI']);

	$tmpl->setCurrentBlock('categorias');

	if($vidcatego == $catdescri){
	$tmpl->setVariable('selected'		,'selected');
	}
	$tmpl->setVariable('catdescri'		,$catdescri);
	$tmpl->setVariable('catreg'			,$catreg);
	$tmpl->parse('categorias');


}

//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
$tmpl->show();

?>	
