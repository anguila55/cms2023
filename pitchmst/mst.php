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
$pitchreg = (isset($_POST['pitchreg'])) ? trim($_POST['pitchreg']) : 0;
$estcodigo = 1; //Activo por defecto
$pathimagenes = '../pitchimg/';
$imgAvatarNull = '../app-assets/img/avatar.png';
$tmpl->setVariable('expavatar', $imgAvatarNull);
$tmpl->setVariable('imgAvatarNull', $imgAvatarNull);

//--------------------------------------------------------------------------------------------------------------
$conn = sql_conectar(); //Apertura de Conexion

if ($pitchreg != 0) {
	$query = "	SELECT *
					FROM PITCH_MAEST
					WHERE PITCH_REG =$pitchreg AND ESTCODIGO=1";

	$Table = sql_query($query, $conn);
	if ($Table->Rows_Count > 0) {
		$row = $Table->Rows[0];
		$pitchreg 	= trim($row['PITCH_REG']);
		$pitchemp 	= trim($row['PITCH_EMP']);
		$pitchdes 	= trim($row['PITCH_DES']);
		$pitchurl 	= trim($row['PITCH_URL']);
		$pitchurlpdf 	= trim($row['PITCH_URLPDF']);
		$pitchid	 	= trim($row['PITCH_ID']);
		$pitchvid 	= trim($row['PITCH_VID']);
		$pitchimg 	= trim($row['PITCH_IMG']);

		$tmpl->setVariable('pitchreg', $pitchreg);
		$tmpl->setVariable('pitchemp'	, $pitchemp);
		$tmpl->setVariable('pitchdes'	, $pitchdes);
		$tmpl->setVariable('pitchurl'		, $pitchurl);
		$tmpl->setVariable('pitchurlpdf'		, $pitchurlpdf);
		$tmpl->setVariable('pitchid'	, $pitchid);
		$tmpl->setVariable('pitchvid'		, $pitchvid);
	

		if ($pitchimg == '') {
			$pitchimg = $imgAvatarNull;
		} else {
			$pitchimg = $pathimagenes . $pitchreg . '/' . $pitchimg;
		}
		$tmpl->setVariable('pitchimg'		, $pitchimg);
	}
}
//Seleccionamos los perfiles
$query = "	SELECT PERNOMBRE,PERAPELLI,PERCODIGO
FROM PER_MAEST 
WHERE ESTCODIGO=1
ORDER BY PERAPELLI ";
$Table = sql_query($query, $conn);
for ($i = 0; $i < $Table->Rows_Count; $i++) {
$row = $Table->Rows[$i];
$percod 	= trim($row['PERCODIGO']);
$pernombre	= trim($row['PERNOMBRE']);
$perapelli	= trim($row['PERAPELLI']);
$tmpl->setCurrentBlock('perfiles');

$perfiles = "SELECT * FROM PITCH_PER WHERE PERCODIGO =$percod AND PITCH_REG  = $pitchreg";
$Table_Perfiles = sql_query($perfiles, $conn);
if($Table_Perfiles->Rows_Count != -1){
$tmpl->setVariable('persel', 'selected');
//var_dump("entro aca");die;
}
$tmpl->setVariable('percodigo', $percod);
$tmpl->setVariable('pernombre', $pernombre);
$tmpl->setVariable('perapelli', $perapelli);
$tmpl->parse('perfiles');
}

//categorias
$categorias ="SELECT  CATREG,CATDESCRI
FROM PIT_CAT";

$Table_categorias = sql_query($categorias, $conn); 

for ($index_categorias = 0; $index_categorias < $Table_categorias->Rows_Count; $index_categorias++) {

	$row_cateogoria = $Table_categorias->Rows[$index_categorias];

	$catreg 		= trim($row_cateogoria['CATREG']);
	$catdescri 		= trim($row_cateogoria['CATDESCRI']);

	$tmpl->setCurrentBlock('categorias');

	if($pitchid == $catreg){
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
