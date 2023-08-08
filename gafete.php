<?php
	if(!isset($_SESSION))  session_start();
	include($_SERVER["DOCUMENT_ROOT"].'/func/zglobals.php'); //PRD
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC . '/idioma.php'; //Idioma
require_once GLBRutaFUNC.'/constants.php';


$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('gafete.html');
DDIdioma($tmpl);

//--------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------
$perusuariogafete = (isset($_GET['ID']))? trim($_GET['ID']):'0'; //Nota desde el home acceso directo	//
$peradmin  = (isset($_SESSION[GLBAPPPORT . 'PERADMIN'])) ? trim($_SESSION[GLBAPPPORT . 'PERADMIN']) : '';


if ($peradmin!=1){
	$tmpl->setVariable('displaybotones', 'd-none');	
	$tmpl->setVariable('admin', '0');	
}else{
	$tmpl->setVariable('admin', '1');	
}


$conn = sql_conectar(); //Apertura de Conexion


/// AGARRO BANNER 
$imgBannerHomeNull	= '../assets-nuevodisenio/img/favion.svg';
$pathimagenes = '../admimg/';

$query = "	SELECT *
	   FROM ADM_IMG
	   WHERE ESTCODIGO=1 AND BANID=10";

$Table = sql_query($query,$conn);

if($Table->Rows_Count>0){
$row = $Table->Rows[0];

$bannerhomeimg 	= trim($row['BANNERS']);


if($bannerhomeimg==''){ 
   $bannerhomeimg = $imgBannerHomeNull;
}else{
   $bannerhomeimg = $pathimagenes.$bannerhomeimg;
}


}else{
$bannerhomeimg = $imgBannerHomeNull;
}

$tmpl->setVariable('bannerhomeimg', $bannerhomeimg);


/// AGARRO BANNER 
$imgBannerHomeNull2	= '../assets-nuevodisenio/img/favion.svg';

$query = "	SELECT *
	   FROM ADM_IMG
	   WHERE ESTCODIGO=1 AND BANID=7";

$Table = sql_query($query,$conn);

if($Table->Rows_Count>0){
$row = $Table->Rows[0];

$bannerhomeimg2 	= trim($row['BANNERS']);


if($bannerhomeimg2==''){ 
   $bannerhomeimg2 = $imgBannerHomeNull;
}else{
   $bannerhomeimg2 = $pathimagenes.$bannerhomeimg2;
}


}else{
$bannerhomeimg2 = $imgBannerHomeNull;
}

$tmpl->setVariable('bannerhomeimg2', $bannerhomeimg2);

$imgAvatarNull = '../app-assets/img/avatar.png';

$query = "	SELECT PERCODIGO,PERNOMBRE,PERAPELLI,PERAVATAR,PERCOMPAN,PERCARGO,QRCODE
				FROM PER_MAEST
				WHERE PERCODIGO=$perusuariogafete				
				ORDER BY PERNOMBRE ";

$Table = sql_query($query, $conn);
for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];
	$percodigo 	= trim($row['PERCODIGO']);
	$pernombre	= trim($row['PERNOMBRE']);
	$perapelli	= trim($row['PERAPELLI']);
	$peravatar	= trim($row['PERAVATAR']);
	$percompan	= trim($row['PERCOMPAN']);
	$percargo	= trim($row['PERCARGO']);
	$qrcode	= trim($row['QRCODE']);

	$tmpl->setCurrentBlock('browser');
	$tmpl->setVariable('percodigo', $percodigo);
	$tmpl->setVariable('pernombre', $pernombre.' '.$perapelli);

	if($peravatar!=''){
		$tmpl->setVariable('peravatar', '../perimg/'.$percodigo.'/'.$peravatar);
	}else{
		$tmpl->setVariable('peravatar', $imgAvatarNull);
	}

	
	$tmpl->setVariable('percompan', $percompan);
	$tmpl->setVariable('percargo', $percargo);
	$tmpl->setVariable('qrcode', '../qrimage/'.$qrcode);
	$tmpl->parse('browser');

}


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
				$tmpl->setVariable('selected1', 'selected');
			}else{
				$tmpl->setVariable('selected2', 'selected');
			}
			if ($arrayinfo[1] == '1'){
				$tmpl->setVariable('selected3', 'selected');
			}else{
				$tmpl->setVariable('selected4', 'selected');
			}
			if ($arrayinfo[2] == '1'){
				$tmpl->setVariable('selected5', 'selected');
			}else{
				$tmpl->setVariable('selected6', 'selected');
			}
			if ($arrayinfo[3] == '1'){
				$tmpl->setVariable('selected7', 'selected');
			}else{
				$tmpl->setVariable('selected8', 'selected');
			}


			
			$tmpl->setVariable('titulo', $titulo);
			$tmpl->setVariable('descri', $descri);
			$tmpl->setVariable('imagen', '../admimg/'.$imagen);
			$tmpl->setVariable('imagen2', '../admimg/'.$imagen2);

			
		}

//--------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
$tmpl->show();

?>	
