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

$tmpl->setVariable('percodnotif', $percodigo	);
$tmpl->setVariable('SisNombreEvento', NAME_TITLE );
if ($peradmin!=1){
		header('Location: ../login');	
}

$conn = sql_conectar(); //Apertura de Conexion


$errmsg 	= '';


$query = "	SELECT CUA_TIT,CUA_DES,CUA_INFO,GAF_IMG3,GAF_IMG4
					FROM ADM_GAF
					WHERE GAF_REG=0";

	$Table = sql_query($query, $conn);
	if ($Table->Rows_Count > 0) {
		
			$row = $Table->Rows[0];
			
			$titulo 	= trim($row['CUA_TIT']);
			$descri  	= trim($row['CUA_DES']);
			$imagen 	= trim($row['GAF_IMG3']);
			$imagen2 	= trim($row['GAF_IMG4']);
			$cua_info 	= trim($row['CUA_INFO']);
			
			$arrayinfo = explode(',',$cua_info);

			if ($arrayinfo[0] == '1'){
				$tmpl->setVariable('visible', 'selected');
			}else{
				$tmpl->setVariable('novisible', 'selected');
			}
			if ($arrayinfo[1] == '1'){
				$tmpl->setVariable('visible1', 'selected');
			}else{
				$tmpl->setVariable('novisible1', 'selected');
			}
			if ($arrayinfo[2] == '1'){
				$tmpl->setVariable('visible2', 'selected');
			}else{
				$tmpl->setVariable('novisible2', 'selected');
			}
			if ($arrayinfo[3] == '1'){
				$tmpl->setVariable('visible3', 'selected');
			}else{
				$tmpl->setVariable('novisible3', 'selected');
			}


			
			$tmpl->setVariable('titulo', $titulo);
			$tmpl->setVariable('descri', $descri);
			$tmpl->setVariable('imagen', '../admimg/'.$imagen);
			$tmpl->setVariable('imagen2', '../admimg/'.$imagen2);




			
		}




sql_close($conn);
$tmpl->show();

?>