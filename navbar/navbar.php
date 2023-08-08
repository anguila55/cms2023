<?php include('../val/valuser.php'); ?>
<?

require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC . '/idioma.php'; //Idioma	
require_once GLBRutaFUNC . '/constants.php'; //Idioma	

$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('../navbar/navbar.html');
DDIdioma($tmpl);

$menu = (isset($_POST['menu'])) ? $_POST['menu'] : '';
$pernombre = (isset($_SESSION[GLBAPPPORT . 'PERNOMBRE'])) ? trim($_SESSION[GLBAPPPORT . 'PERNOMBRE']) : '';
$perapelli = (isset($_SESSION[GLBAPPPORT . 'PERAPELLI'])) ? trim($_SESSION[GLBAPPPORT . 'PERAPELLI']) : '';
$peravatar = (isset($_SESSION[GLBAPPPORT . 'PERAVATAR'])) ? trim($_SESSION[GLBAPPPORT . 'PERAVATAR']) : '';
$percodigo = (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';
$peradmin  = (isset($_SESSION[GLBAPPPORT . 'PERADMIN'])) ? trim($_SESSION[GLBAPPPORT . 'PERADMIN']) : '';

if($menu == 'activechat'){
	$tmpl->setVariable('displaychatdesplegable', 'd-none');
}else{
	$tmpl->setVariable('displaychatdesplegable', '');
}

$tmpl->setVariable('pernombre', $pernombre);
$tmpl->setVariable('perapelli', $perapelli);
$tmpl->setVariable('peravatar', $peravatar);
$tmpl->setVariable('percodnotif', $percodigo);

$tmpl->setVariable($menu, "active");
if ($peradmin != 1) {
	$tmpl->setVariable('viewadmin', 'none');
} else {
	$tmpl->setVariable('viewadmin', 'inline-block');
}




$conn = sql_conectar(); //Apertura de Conexion

$query = "	SELECT PERCODIGO,QRCODE
				FROM PER_MAEST
				WHERE PERCODIGO=$percodigo				
				ORDER BY PERNOMBRE ";
$Table = sql_query($query, $conn);
for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];
	$percodigo 	= trim($row['PERCODIGO']);
	$qrreg	= trim($row['QRCODE']);

	$tmpl->setVariable('percodigo', $percodigo);
	$tmpl->setVariable('qrreg', $qrreg);
}

$query2 = " SELECT ZVALUE FROM ZZZ_CONF WHERE ZPARAM = 'TipoEvento'";
$Table2 = sql_query($query2, $conn);
for ($i = 0; $i < $Table2->Rows_Count; $i++) {
	$row = $Table2->Rows[$i];
	$tipoevento = trim($row['ZVALUE']);
	if ($tipoevento != 'false') {

		$tmpl->setVariable('mostrarqr', 'd-flex');
	} else {
		$tmpl->setVariable('mostrarqr', 'd-none');
	}
}
$query3 = "	SELECT PERCODIGO
			FROM ADM_AYUDA
			WHERE AYU_ID=0";

$Table3 = sql_query($query3,$conn);

for($i=0; $i<$Table3->Rows_Count; $i++){
	$row3 = $Table3->Rows[$i];

	$ayuperfil 	= trim($row3['PERCODIGO']);

	$tmpl->setVariable('chatuser', $ayuperfil);
	

}

$query3 = " SELECT VISIBILIDAD FROM MAP_TABLE WHERE ESTCODIGO = 4";
$Table2 = sql_query($query3, $conn);
for ($i = 0; $i < $Table2->Rows_Count; $i++) {
	$row = $Table2->Rows[$i];
	$visibilidad = trim($row['VISIBILIDAD']);
	if ($visibilidad == 1) {

		$tmpl->setVariable('mostrarmapa', '');
	} else {
		$tmpl->setVariable('mostrarmapa', 'd-none');
	}
}

$query4  = "	SELECT PERNOMBRE,PERAPELLI,PERCOMPAN,PERCODIGO,SUM(SINLEER) AS CHATSINLEER, PERAVATAR, RECIBFCH
				FROM (
				SELECT  PD.PERNOMBRE,PD.PERAPELLI,PD.PERCOMPAN,PD.PERCODIGO,0 AS SINLEER,PD.PERAVATAR,
					(SELECT MAX(T.CHAFCHREG) FROM TBL_CHAT T WHERE T.ESTCODIGO=1 AND T.PERCODIGO=PD.PERCODIGO AND T.PERCODDST=$percodigo)  AS RECIBFCH
				FROM TBL_CHAT C
				LEFT OUTER JOIN PER_MAEST PD ON PD.PERCODIGO=C.PERCODDST
				WHERE C.ESTCODIGO=1 AND C.PERCODIGO=$percodigo 
				UNION
				SELECT  PD.PERNOMBRE,PD.PERAPELLI,PD.PERCOMPAN,PD.PERCODIGO,
						(SELECT COUNT(*) FROM TBL_CHAT T WHERE T.ESTCODIGO=1 AND T.CHALEIDO=0 AND T.PERCODIGO=PD.PERCODIGO AND T.PERCODDST=$percodigo)  AS SINLEER,
						PD.PERAVATAR,
						(SELECT MAX(T.CHAFCHREG) FROM TBL_CHAT T WHERE T.ESTCODIGO=1 AND T.PERCODIGO=PD.PERCODIGO AND T.PERCODDST=$percodigo)  AS RECIBFCH
				FROM TBL_CHAT C
				LEFT OUTER JOIN PER_MAEST PD ON PD.PERCODIGO=C.PERCODIGO
				WHERE C.ESTCODIGO=1 AND C.PERCODDST=$percodigo
				)
				GROUP BY 1,2,3,4,6,7
				ORDER BY 7 DESC, 5 DESC ";


$Table4 	= sql_query($query4, $conn);
$chatsuma = 0;
$tmpl->setVariable('numerocharlas', 'd-none');
for ($i = 0; $i < $Table4->Rows_Count; $i++) {
	$row = $Table4->Rows[$i];
	$chatsinleer = trim($row['CHATSINLEER']);
	$chatsuma = $chatsuma + (int)$chatsinleer;
	$tmpl->setVariable('numerocharlas', '');
}

$tmpl->setVariable('chatsuma', $chatsuma);

$arraypaneles=null;
$queryparam = " SELECT PARREG,PARCODIGO,PARVALOR,PARDESCRI,PARNOM$IdiomView AS PARNOMBRE
				FROM PAR_MAEST 
				WHERE PARSEC = 1
				ORDER BY PARREG ";
$Tableparam = sql_query($queryparam, $conn);
for ($i = 0; $i < $Tableparam->Rows_Count; $i++) {
	$rowparam = $Tableparam->Rows[$i];
	$parreg = trim($rowparam['PARREG']);
	$paneladmin = trim($rowparam['PARCODIGO']);
	$panelvalor = trim($rowparam['PARVALOR']);
	$parnombre = trim($rowparam['PARNOMBRE']);
	$arraypaneles[]=$panelvalor;
	if ($panelvalor == 'true') {

		$tmpl->setVariable('display'.$paneladmin, '');
	} else {
		$tmpl->setVariable('display'.$paneladmin, 'd-none');
	}
	$tmpl->setVariable('nombre'.$paneladmin, $parnombre);
}
$tmpl->setVariable('displayindex', 'd-none');
if ($arraypaneles[0]=='false'){
	$tmpl->setVariable('displayindex', '');
}
$tmpl->setVariable('displaystandunico', 'd-none');
$tmpl->setVariable('displayofertasunico', 'd-none');
$tmpl->setVariable('displaymapaunico', 'd-none');
$tmpl->setVariable('displayactividadesunico', 'd-none');
$tmpl->setVariable('displayvideosunico', 'd-none');
$tmpl->setVariable('displayspeakers-listaunico', 'd-none');
$tmpl->setVariable('displaymesasredondasunico', 'd-none');
$tmpl->setVariable('displaypitchunico', 'd-none');
$tmpl->setVariable('displayencuestasunico', 'd-none');

if ($arraypaneles[2]=='false' && $arraypaneles[3]=='false' && $arraypaneles[4]=='false'){
	$tmpl->setVariable('displaydropexpositores', 'd-none');
}
if ($arraypaneles[6]=='false' && $arraypaneles[7]=='false' && $arraypaneles[8]=='false'){
	$tmpl->setVariable('displaydropactividades', 'd-none');
}
if ($arraypaneles[10]=='false' && $arraypaneles[11]=='false' && $arraypaneles[12]=='false'){
	$tmpl->setVariable('displaydropcocreate', 'd-none');
}
/////
if ($arraypaneles[2]=='true' && $arraypaneles[3]=='false' && $arraypaneles[4]=='false'){
	$tmpl->setVariable('displaydropexpositores', 'd-none');
	$tmpl->setVariable('displaystandunico', '');
}
if ($arraypaneles[2]=='false' && $arraypaneles[3]=='true' && $arraypaneles[4]=='false'){
	$tmpl->setVariable('displaydropexpositores', 'd-none');
	$tmpl->setVariable('displayofertasunico', '');
}
if ($arraypaneles[2]=='false' && $arraypaneles[3]=='false' && $arraypaneles[4]=='true'){
	$tmpl->setVariable('displaydropexpositores', 'd-none');
	$tmpl->setVariable('displaymapaunico', '');
}
/////
if ($arraypaneles[6]=='true' && $arraypaneles[7]=='false' && $arraypaneles[8]=='false'){
	$tmpl->setVariable('displaydropactividades', 'd-none');
	$tmpl->setVariable('displayactividadesunico', '');
}
if ($arraypaneles[6]=='false' && $arraypaneles[7]=='true' && $arraypaneles[8]=='false'){
	$tmpl->setVariable('displaydropactividades', 'd-none');
	$tmpl->setVariable('displayvideosunico', '');
}
if ($arraypaneles[6]=='false' && $arraypaneles[7]=='false' && $arraypaneles[8]=='true'){
	$tmpl->setVariable('displaydropactividades', 'd-none');
	$tmpl->setVariable('displayspeakers-listaunico', '');
}
/////
if ($arraypaneles[10]=='true' && $arraypaneles[11]=='false' && $arraypaneles[12]=='false'){
	$tmpl->setVariable('displaydropcocreate', 'd-none');
	$tmpl->setVariable('displaymesasredondasunico', '');
}
if ($arraypaneles[10]=='false' && $arraypaneles[11]=='true' && $arraypaneles[12]=='false'){
	$tmpl->setVariable('displaydropcocreate', 'd-none');
	$tmpl->setVariable('displaypitchunico', '');
}
if ($arraypaneles[10]=='false' && $arraypaneles[11]=='false' && $arraypaneles[12]=='true'){
	$tmpl->setVariable('displaydropcocreate', 'd-none');
	$tmpl->setVariable('displayencuestasunico', '');	
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
$tmpl->show();

?>