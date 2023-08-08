<?
header('Cache-Control: no cache'); //no cache
session_cache_limiter('private_no_expire'); // works
session_start();
include($_SERVER["DOCUMENT_ROOT"] . '/func/zglobals.php'); //PRD
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC.'/sigma.php';
require_once GLBRutaFUNC.'/zdatabase.php';
require_once GLBRutaFUNC.'/zfvarias.php';
require_once GLBRutaFUNC.'/idioma.php'; //Idioma	
require_once GLBRutaFUNC.'/constants.php'; //Idioma	

$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('index2.html');

//Diccionario de idiomas
//var_dump(DDIdioma($tmpl));

// Agregarmos una variable CaptCha

// Fin Agregarmos una variable CaptCha




$percodigo = (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';
$perusuacc = (isset($_POST['perusuacc'])) ? trim(SQL_replace($_POST['perusuacc'])) : '';
$perpasacc = (isset($_POST['perpasacc'])) ? trim(SQL_replace($_POST['perpasacc'],true)) : '';


$_SESSION['success'] = true;

validateLogin($percodigo);


$conn = sql_conectar(); //Apertura de Conexion

$pathimagenes = '../perimg/';
$pathimg = 'avimg';
$errmsg 	= '';


if ($percodigo == '') {
	if ($perusuacc == '' || $perpasacc == '') {
		header('Location: login');
		exit;
	} else {

		$perpasacc = md5('BenVido' . $perpasacc . 'PassAcceso' . $perusuacc);
		$perpasacc = 'B#SD' . md5(substr($perpasacc, 1, 10) . 'BenVidO' . substr($perpasacc, 5, 8)) . 'E##$F';

		$query = " 	SELECT P.PERCODIGO,P.PERNOMBRE,P.PERAPELLI,P.PERCORREO,P.PERUSUACC,P.PERPASACC,P.ESTCODIGO,P.PERADMIN,P.PERAVATAR,
								P.PERUSADIS,P.PERUSAREU,P.PERUSAMSG,P.PERTIPO,P.PERCLASE,P.PERIDIOMA,P.PERCOMPAN,P.PERCARGO,P.TIMOFFSET,P.PERINGLOG
						FROM PER_MAEST P
						WHERE P.PERUSUACC='$perusuacc' AND P.PERPASACC='$perpasacc' AND P.ESTCODIGO!=3";

		$Table = sql_query($query, $conn);
		if ($Table->Rows_Count > 0) {
			$row = $Table->Rows[0];
			$percodigo = trim($row['PERCODIGO']);
			$estcodigo = trim($row['ESTCODIGO']);


			if ($estcodigo == 9) { //Correo sin verificar
				$_SESSION[GLBAPPPORT . 'PERCODIGO'] = '';
				$_SESSION[GLBAPPPORT . 'PERNOMBRE'] = '';
				$_SESSION[GLBAPPPORT . 'PERAPELLI'] = '';
				$_SESSION[GLBAPPPORT . 'PERCORREO'] = '';
				$_SESSION[GLBAPPPORT . 'PERCOMPAN'] = '';
				$_SESSION[GLBAPPPORT . 'PERCARGO'] = '';
				$_SESSION[GLBAPPPORT . 'PERUSUACC'] = '';
				$_SESSION[GLBAPPPORT . 'PERPASACC'] = '';
				$_SESSION[GLBAPPPORT . 'PERADMIN'] = '';
				$_SESSION[GLBAPPPORT . 'PERAVATAR'] = '';
				$_SESSION[GLBAPPPORT . 'PERUSADIS'] = '';
				$_SESSION[GLBAPPPORT . 'PERUSAREU'] = '';
				$_SESSION[GLBAPPPORT . 'PERUSAMSG'] = '';
				$_SESSION[GLBAPPPORT . 'PERTIPO'] = '';
				$_SESSION[GLBAPPPORT . 'PERCLASE'] = '';
				$_SESSION[GLBAPPPORT . 'TIMOFFSET'] = '';

				header('Location: registermail');
				exit;
			} else if ($estcodigo == 8) { //Perfil sin liberar
				$_SESSION[GLBAPPPORT . 'PERCODIGO'] = '';
				$_SESSION[GLBAPPPORT . 'PERNOMBRE'] = '';
				$_SESSION[GLBAPPPORT . 'PERAPELLI'] = '';
				$_SESSION[GLBAPPPORT . 'PERCORREO'] = '';
				$_SESSION[GLBAPPPORT . 'PERCOMPAN'] = '';
				$_SESSION[GLBAPPPORT . 'PERCARGO'] = '';
				$_SESSION[GLBAPPPORT . 'PERUSUACC'] = '';
				$_SESSION[GLBAPPPORT . 'PERPASACC'] = '';
				$_SESSION[GLBAPPPORT . 'PERADMIN'] = '';
				$_SESSION[GLBAPPPORT . 'PERAVATAR'] = '';
				$_SESSION[GLBAPPPORT . 'PERUSADIS'] = '';
				$_SESSION[GLBAPPPORT . 'PERUSAREU'] = '';
				$_SESSION[GLBAPPPORT . 'PERUSAMSG'] = '';
				$_SESSION[GLBAPPPORT . 'PERTIPO'] = '';
				$_SESSION[GLBAPPPORT . 'PERCLASE'] = '';
				$_SESSION[GLBAPPPORT . 'TIMOFFSET'] = '';

				header('Location: registerwait');
				exit;
			} else { //Perfil OK

				$_SESSION[GLBAPPPORT . 'PERCODIGO'] 	= $percodigo;
				$_SESSION[GLBAPPPORT . 'PERNOMBRE'] 	= trim($row['PERNOMBRE']);
				$_SESSION[GLBAPPPORT . 'PERAPELLI'] 	= trim($row['PERAPELLI']);
				$_SESSION[GLBAPPPORT . 'PERCORREO'] 	= strtoupper(trim($row['PERCORREO']));
				$_SESSION[GLBAPPPORT . 'PERCOMPAN'] 	= trim($row['PERCOMPAN']);
				$_SESSION[GLBAPPPORT . 'PERCARGO'] 	= trim($row['PERCARGO']);
				$_SESSION[GLBAPPPORT . 'PERUSUACC'] 	= strtoupper(trim($row['PERUSUACC']));
				$_SESSION[GLBAPPPORT . 'PERPASACC'] 	= trim($row['PERPASACC']);
				$_SESSION[GLBAPPPORT . 'PERADMIN'] 	= trim($row['PERADMIN']);
				$_SESSION[GLBAPPPORT . 'PERUSADIS'] 	= trim($row['PERUSADIS']);
				$_SESSION[GLBAPPPORT . 'PERUSAREU'] 	= trim($row['PERUSAREU']);
				$_SESSION[GLBAPPPORT . 'PERUSAMSG'] 	= trim($row['PERUSAMSG']);
				$_SESSION[GLBAPPPORT . 'PERTIPO'] 	= trim($row['PERTIPO']);
				$_SESSION[GLBAPPPORT . 'PERCLASE'] 	= trim($row['PERCLASE']);
				$_SESSION[GLBAPPPORT . 'PERIDIOMA']  = trim($row['PERIDIOMA']);
				$_SESSION[GLBAPPPORT . 'TIMOFFSET'] 	= trim($row['TIMOFFSET']);

				$peringlog = trim($row['PERINGLOG']);
				if($peringlog==''){
					$query = "	UPDATE PER_MAEST SET PERINGLOG=CURRENT_TIMESTAMP 
								WHERE PERCODIGO=$percodigo ";
					$err	= sql_execute($query,$conn);
				}
				
				//Marco la fecha de ingreso como estampa de ultimo login
				$query = "	UPDATE PER_MAEST SET PERULTLOG=CURRENT_TIMESTAMP 
							WHERE PERCODIGO=$percodigo ";
				$err	= sql_execute($query,$conn);

				init_login($perusuacc, $perpasacc);

				if (trim($row['PERAVATAR']) != '') {
					$_SESSION[GLBAPPPORT . 'PERAVATAR'] =  $pathimagenes . $percodigo . '/' . trim($row['PERAVATAR']);
				} else {
					$_SESSION[GLBAPPPORT . 'PERAVATAR'] =  '../app-assets/img/avatar.png';
				}

				//Busco los parametro de clasificacion
				$query = "	SELECT 1
								FROM SEC_MAEST
								WHERE ESTCODIGO<>3 ";
				$Table = sql_query($query, $conn);
				if ($Table->Rows_Count > 0) {
					$_SESSION[GLBAPPPORT . 'SECTORES'] = 1;
				} else {
					$_SESSION[GLBAPPPORT . 'SECTORES'] = 0;
				}

				$query = "	SELECT 1
								FROM SEC_SUB
								WHERE ESTCODIGO<>3 ";
				$Table = sql_query($query, $conn);
				if ($Table->Rows_Count > 0) {
					$_SESSION[GLBAPPPORT . 'SUBSECTORES'] = 1;
				} else {
					$_SESSION[GLBAPPPORT . 'SUBSECTORES'] = 0;
				}

				$query = "	SELECT 1
								FROM CAT_MAEST
								WHERE ESTCODIGO<>3 ";
				$Table = sql_query($query, $conn);
				if ($Table->Rows_Count > 0) {
					$_SESSION[GLBAPPPORT . 'CATEGORIAS'] = 1;
				} else {
					$_SESSION[GLBAPPPORT . 'CATEGORIAS'] = 0;
				}

				$query = "	SELECT 1
								FROM CAT_SUB
								WHERE ESTCODIGO<>3 ";
				$Table = sql_query($query, $conn);
				if ($Table->Rows_Count > 0) {
					$_SESSION[GLBAPPPORT . 'SUBCATEGORIAS'] = 1;
				} else {
					$_SESSION[GLBAPPPORT . 'SUBCATEGORIAS'] = 0;
				}
			}
		} else {

			header('Location: loginerror');

			exit;
		}
	}
}



//Se coloca aqui para que tome el idioma
require_once GLBRutaFUNC . '/idioma.php'; //Idioma
DDIdioma($tmpl);

//Parametros del Sistema
//Busco los parametros de configuracion
$query = "	SELECT ZPARAM,ZVALUE FROM ZZZ_CONF ";
$Table = sql_query($query, $conn);
for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];
	$_SESSION['PARAMETROS'][trim($row['ZPARAM'])] = trim($row['ZVALUE']);
}

//Habilito las opciones del Menu
if (json_decode($_SESSION['PARAMETROS']['MenuActividades']) == false) {
	$tmpl->setVariable('ParamMenuActividades', 'display:;');
}
if (json_decode($_SESSION['PARAMETROS']['MenuAgenda']) == false) {
	$tmpl->setVariable('ParamMenuAgenda', 'display:;');
}
if (json_decode($_SESSION['PARAMETROS']['MenuMensajes']) == false) {
	$tmpl->setVariable('ParamMenuMensajes', 'display:;');
}
if (json_decode($_SESSION['PARAMETROS']['MenuNoticias']) == false) {
	$tmpl->setVariable('ParamMenuNoticias', 'display:;');
}
if (json_decode($_SESSION['PARAMETROS']['MenuExportar']) == false) {
	$tmpl->setVariable('ParamMenuExportar', 'display:;');
}
if (json_decode($_SESSION['PARAMETROS']['MenuEncuesta']) == false) {
	$tmpl->setVariable('ParamMenuEncuesta', 'display:none;');
}

//--------------------------------------------------------------------------------------------------------------
$percodigo 			= (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';
$pernombre 			= (isset($_SESSION[GLBAPPPORT . 'PERNOMBRE'])) ? trim($_SESSION[GLBAPPPORT . 'PERNOMBRE']) : '';
$perapelli 			= (isset($_SESSION[GLBAPPPORT . 'PERAPELLI'])) ? trim($_SESSION[GLBAPPPORT . 'PERAPELLI']) : '';
$perusuacc 			= (isset($_SESSION[GLBAPPPORT . 'PERUSUACC'])) ? trim($_SESSION[GLBAPPPORT . 'PERUSUACC']) : '';
$perpasacc 			= (isset($_SESSION[GLBAPPPORT . 'PERCORREO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCORREO']) : '';
$peradmin 			= (isset($_SESSION[GLBAPPPORT . 'PERADMIN'])) ? trim($_SESSION[GLBAPPPORT . 'PERADMIN']) : '';
$peravatar 			= (isset($_SESSION[GLBAPPPORT . 'PERAVATAR'])) ? trim($_SESSION[GLBAPPPORT . 'PERAVATAR']) : '';
$btnsectores 		= (isset($_SESSION[GLBAPPPORT . 'SECTORES'])) ? trim($_SESSION[GLBAPPPORT . 'SECTORES']) : '';
$btnsubsectores 	= (isset($_SESSION[GLBAPPPORT . 'SUBSECTORES'])) ? trim($_SESSION[GLBAPPPORT . 'SUBSECTORES']) : '';
$btncategorias 		= (isset($_SESSION[GLBAPPPORT . 'CATEGORIAS'])) ? trim($_SESSION[GLBAPPPORT . 'CATEGORIAS']) : '';
$btnsubcategorias 	= (isset($_SESSION[GLBAPPPORT . 'SUBCATEGORIAS'])) ? trim($_SESSION[GLBAPPPORT . 'SUBCATEGORIAS']) : '';
$pertipo 			= (isset($_SESSION[GLBAPPPORT . 'PERTIPO'])) ? trim($_SESSION[GLBAPPPORT . 'PERTIPO']) : '';
$perclase 			= (isset($_SESSION[GLBAPPPORT . 'PERCLASE'])) ? trim($_SESSION[GLBAPPPORT . 'PERCLASE']) : '';

$tmpl->setVariable('percodnotif', $percodigo);
$tmpl->setVariable('pernombre', $pernombre);
$tmpl->setVariable('perapelli', $perapelli);
$tmpl->setVariable('perusuacc', $perusuacc);
$tmpl->setVariable('perpasacc', $perpasacc);
$tmpl->setVariable('peravatar', str_replace('../', '', $peravatar));



//Nombre del Evento
// if (isset($_SESSION['PARAMETROS'])) {

// 	$tmpl->setVariable('SisNombreEvento', $_SESSION['PARAMETROS']['SisNombreEvento']);
// }
$tmpl->setVariable('SisNombreEvento', NAME_TITLE );

if ($peradmin != 1) $tmpl->setVariable('viewadmin', 'none');
if ($btnsectores != 1) $tmpl->setVariable('btnsectores', 'display:;');
if ($btnsubsectores != 1) $tmpl->setVariable('btnsubsectores', 'display:;');
if ($btncategorias != 1) $tmpl->setVariable('btncategorias', 'display:none;');
if ($btnsubcategorias != 1) $tmpl->setVariable('btnsubcategorias', 'display:none;');

if ($peradmin!=1){
	$hoyfecha = date('Y-m-d');
	
		$queryBloqueo = "SELECT PERBLOQ
						FROM PER_CLASE
						WHERE PERCLASE=$perclase AND ESTCODIGO=1";
	
		$TableBloqueo = sql_query($queryBloqueo, $conn);
	
		if ($TableBloqueo->Rows_Count > 0) {
			$rowBloqueo = $TableBloqueo->Rows[0];
			$perbloq = $rowBloqueo['PERBLOQ'];
	
	
	
			if ($perbloq > $hoyfecha) {
				header('Location: msgprox');
			}
	
		}
	}
/// POP UP INICIO
$query = "	SELECT PERPOP2
		 FROM PER_MAEST
		 WHERE PERPOP2=0 AND PERCODIGO=$percodigo";

$Table = sql_query($query, $conn);

if ($Table->Rows_Count > 0) {

    $queryinfo = "	SELECT POP_URL
    FROM ADM_POP WHERE POP_REG=0";

$Tableinfo = sql_query($queryinfo,$conn);


if($Tableinfo->Rows_Count>0){
$rowinfo = $Tableinfo->Rows[0];

$popupinfo 	= trim($rowinfo['POP_URL']);

$arrayinfo = explode(',',$popupinfo);


if ($arrayinfo[0] === '1'){

   
    if ( ($arrayinfo[1] === '0') && ($arrayinfo[2] === '0') && ($arrayinfo[3] === '0') )
    {
        $tmpl->setVariable('activepopupinicio', '');
    }else{
       
        $tmpl->setVariable('activepopupinicio', 'showPopUpInicio();');
    }

   
}else{
    $tmpl->setVariable('activepopupinicio', '');
}

}

       
   
}else {
    $tmpl->setVariable('activepopupinicio', '');
}


///POP UP
$query = "	SELECT PERPOP
		 FROM PER_MAEST
		 WHERE PERPOP=0 AND PERCODIGO=$percodigo";

$Table = sql_query($query, $conn);

if ($Table->Rows_Count > 0) {

    $querypopup = "	SELECT ESTCODIGO
		 FROM ADM_POP
		 WHERE ESTCODIGO=1 AND POP_REG=1";

    $Tablepopup = sql_query($querypopup, $conn);

    if ($Tablepopup->Rows_Count > 0) {

        $tmpl->setVariable('activepopup', 'showPopUp();');
    } else {
        $tmpl->setVariable('activepopup', '');
    }
} else {
    $tmpl->setVariable('activepopup', '');
}


sql_close($conn);


DDIdioma($tmpl);

$tmpl->show();
