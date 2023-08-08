<?php include('../val/valuser.php'); 
      include('../phpqrcode/qrlib.php');?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma
	require_once GLBRutaFUNC.'/constants.php';	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('bsq.html');
	//--------------------------------------------------------------------------------------------------------------
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo 			= (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre 			= (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$perapelli 			= (isset($_SESSION[GLBAPPPORT.'PERAPELLI']))? trim($_SESSION[GLBAPPPORT.'PERAPELLI']) : '';
	$perusuacc 			= (isset($_SESSION[GLBAPPPORT.'PERUSUACC']))? trim($_SESSION[GLBAPPPORT.'PERUSUACC']) : '';
	$perpasacc 			= (isset($_SESSION[GLBAPPPORT.'PERCORREO']))? trim($_SESSION[GLBAPPPORT.'PERCORREO']) : '';
	$peradmin 			= (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';
	$peravatar 			= (isset($_SESSION[GLBAPPPORT.'PERAVATAR']))? trim($_SESSION[GLBAPPPORT.'PERAVATAR']) : '';
	$btnsectores 		= (isset($_SESSION[GLBAPPPORT.'SECTORES']))? trim($_SESSION[GLBAPPPORT.'SECTORES']) : '';
	$btnsubsectores 	= (isset($_SESSION[GLBAPPPORT.'SUBSECTORES']))? trim($_SESSION[GLBAPPPORT.'SUBSECTORES']) : '';
	$btncategorias 		= (isset($_SESSION[GLBAPPPORT.'CATEGORIAS']))? trim($_SESSION[GLBAPPPORT.'CATEGORIAS']) : '';
	$btnsubcategorias 	= (isset($_SESSION[GLBAPPPORT.'SUBCATEGORIAS']))? trim($_SESSION[GLBAPPPORT.'SUBCATEGORIAS']) : '';
	
	$tmpl->setVariable('percodnotif'	, $percodigo	);
	$tmpl->setVariable('pernombre'	, $pernombre	);
	$tmpl->setVariable('perapelli'	, $perapelli	);
	$tmpl->setVariable('perusuacc'	, $perusuacc	);
	$tmpl->setVariable('perpasacc'	, $perpasacc	);
	$tmpl->setVariable('peravatar'	, $peravatar	);
	
$pathimagenes = '../expimg/';
$imgAvatarNull = '../app-assets/img/avatar.png';
$tmpl->setVariable('expavatar', $imgAvatarNull);
$tmpl->setVariable('imgAvatarNull', $imgAvatarNull);

$pathqrimagenes = '../qrimage/';
	if (!file_exists($pathqrimagenes)) {
		mkdir($pathqrimagenes);
	}
	//Nombre del Evento
	$tmpl->setVariable('SisNombreEvento', NAME_TITLE );
	//--------------------------------------------------------------------------------------------------------------
	$conn = sql_conectar(); //Apertura de Conexion

 // outputs image directly into browser, as PNG stream
// QRcode::png('https://www.google.com.ar');
// how to save PNG codes to server
    
$tempDir = $pathqrimagenes;

 
$codeContents = 'Codigo de muestra gonzalo1';

// we need to generate filename somehow, 
// with md5 or with database ID used to obtains $codeContents...
$fileName = $percodigo.'_PER_'.md5($codeContents).'.png';

$pngAbsoluteFilePath = $tempDir.$fileName;
$urlRelativeFilePath = 'https://demo.btoolbox.com/qrimage/'.$fileName;

// generating
if (!file_exists($pngAbsoluteFilePath)) {
	define('IMAGE_WIDTH',1024);
	define('IMAGE_HEIGHT',1024);
	QRcode::png($codeContents, $pngAbsoluteFilePath,QR_ECLEVEL_H, 4);
	echo 'File generated!';
	echo '<hr />';
} else {
	echo 'File already generated! We can use this cached file to speed up site on common codes!';
	echo '<hr />';
}

echo 'Server PNG File: '.$pngAbsoluteFilePath;
echo '<hr />';

// displaying
echo '<img src="'.$urlRelativeFilePath.'" />';



//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
$tmpl->show();

?>