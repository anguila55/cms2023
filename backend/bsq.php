<?
if (!isset($_SESSION))  session_start();
// include($_SERVER["DOCUMENT_ROOT"] . '/cms/func/zglobals.php'); //DEV
include($_SERVER["DOCUMENT_ROOT"] . '/func/zglobals.php'); //PRD
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC . '/constants.php';
require_once GLBRutaFUNC.'/idioma.php';//Idioma

$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('bsq.html');
DDIdioma($tmpl);

//Diccionario de idiomas
//var_dump(DDIdioma($tmpl));

// Agregarmos una variable CaptCha

// Fin Agregarmos una variable CaptCha




$percodigo = (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';
$perusuacc = (isset($_POST['perusuacc'])) ? trim($_POST['perusuacc']) : '';
$perpasacc = (isset($_POST['perpasacc'])) ? trim($_POST['perpasacc']) : '';
$peradmin  = (isset($_SESSION[GLBAPPPORT . 'PERADMIN'])) ? trim($_SESSION[GLBAPPPORT . 'PERADMIN']) : '';
$tmpl->setVariable('SisNombreEvento', NAME_TITLE );

if ($peradmin!=1){
		header('Location: ../login');	
}

$conn = sql_conectar(); //Apertura de Conexion

$pathimagenes = '../perimg/';
$pathimg = 'avimg';
$errmsg 	= '';

$query2 = " SELECT ZVALUE FROM ZZZ_CONF WHERE ZPARAM = 'TipoEvento'";
$Table2 = sql_query($query2, $conn);
for ($i = 0; $i < $Table2->Rows_Count; $i++) {
	$row = $Table2->Rows[$i];
	$tipoevento = trim($row['ZVALUE']);
	if ($tipoevento != 'false') {

		$tmpl->setVariable('mostrarqr', '');
	} else {
		$tmpl->setVariable('mostrarqr', 'd-none');
	}
}
$tmpl->setVariable('displaysuperadmin', '');

	$arraypaneles=null;

	$queryparam = " SELECT PARREG,PARCODIGO,PARVALOR,PARDESCRI,PARNOM$IdiomView AS PARNOMBRE
					FROM PAR_MAEST 
					WHERE (PARSEC = 0 OR PARSEC = 2)
					ORDER BY PARREG ";
	$Tableparam = sql_query($queryparam, $conn);
	for ($i = 0; $i < $Tableparam->Rows_Count; $i++) {
		$rowparam = $Tableparam->Rows[$i];
		$parreg = trim($rowparam['PARREG']);
		$paneladmin = trim($rowparam['PARCODIGO']);
		$panelvalor = trim($rowparam['PARVALOR']);
		$parnombre = trim($rowparam['PARNOMBRE']);
		if (!in_array($percodigo, $arraysuperadmin)){
			$tmpl->setVariable('displaysuperadmin', 'd-none');
			$arraypaneles[]=$panelvalor;
			if ($panelvalor == 'true') {

				$tmpl->setVariable('display'.$paneladmin, '');
			} else {
				$tmpl->setVariable('display'.$paneladmin, 'd-none');
			}
		}

		$tmpl->setVariable('nombre'.$paneladmin, $parnombre);
	}
	if (!in_array($percodigo, $arraysuperadmin)){
		$tmpl->setVariable('displaysuperadmin', 'd-none');
		if ($arraypaneles[1]=='false' && $arraypaneles[2]=='false' && $arraypaneles[3]=='false' && $arraypaneles[4]=='false' && $arraypaneles[5]=='false' && $arraypaneles[30]=='false' && $arraypaneles[31]=='false' ){
			$tmpl->setVariable('displayconfiguracion', 'd-none');
		}
		if ($arraypaneles[8]=='false' && $arraypaneles[9]=='false' && $arraypaneles[10]=='false'){
			$tmpl->setVariable('displayconfigimagenes', 'd-none');
		}
		if ($arraypaneles[11]=='false' && $arraypaneles[12]=='false' && $arraypaneles[13]=='false'){
			$tmpl->setVariable('displayconfigcontenido', 'd-none');
		}
		if ($arraypaneles[14]=='false' && $arraypaneles[15]=='false' && $arraypaneles[16]=='false' && $arraypaneles[17]=='false'){
			$tmpl->setVariable('displayconfigreuniones', 'd-none');
		}
		if ($arraypaneles[18]=='false' && $arraypaneles[19]=='false' && $arraypaneles[20]=='false'){
			$tmpl->setVariable('displayconfigmetricas', 'd-none');
		}
		if ($arraypaneles[21]=='false' && $arraypaneles[22]=='false' && $arraypaneles[23]=='false' && $arraypaneles[24]=='false' && $arraypaneles[25]=='false'){
			$tmpl->setVariable('displayconfigprograma', 'd-none');
		}
		if ($arraypaneles[26]=='false' && $arraypaneles[27]=='false'){
			$tmpl->setVariable('displayconfigencuestas', 'd-none');
		}

	}


$tmpl->show();

?>