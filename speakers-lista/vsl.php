<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC . '/idioma.php';


$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('vsl.html');
DDIdioma($tmpl);


//--------------------------------------------------------------------------------------------------------------
$percodigo = (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';
$pernombre = (isset($_SESSION[GLBAPPPORT . 'PERNOMBRE'])) ? trim($_SESSION[GLBAPPPORT . 'PERNOMBRE']) : '';

$spkreg = (isset($_POST['spkreg'])) ? trim($_POST['spkreg']) : 0;
//logerror($spkreg);
$conn = sql_conectar(); //Apertura de Conexion

$query = "SELECT S.SPKREG,S.SPKNOMBRE,S.SPKDESCRI,S.SPKIMG,S.ESTCODIGO,S.SPKPOS,S.SPKEMPRES,S.SPKCARGO,S.SPKLINKED
          FROM SPK_MAEST S
          WHERE S.SPKREG = $spkreg ";




$Table = sql_query($query, $conn);
for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];
	$spkreg 	= trim($row['SPKREG']);
	$spktitulo 	= trim($row['SPKNOMBRE']);
	$spkdescri  = trim($row['SPKDESCRI']);
	$spkpos     = trim($row['SPKPOS']);
	$spkempres     = trim($row['SPKEMPRES']);
	$spkimg     = trim($row['SPKIMG']);
	$spkcargo     = trim($row['SPKCARGO']);


	
	//$aviimagen  = trim($row['AVIIMAGEN']);


	$query = "SELECT AGETITULO,AGEYOULNK,AGEREG,AGEFCH, AGEHORINI, AGEHORFIN
				FROM AGE_MAEST
				WHERE ESTCODIGO<>3 AND SPKREG CONTAINING '$spkreg' ORDER BY AGEFCH ";
	$Table = sql_query($query, $conn);
	for ($i = 0; $i < $Table->Rows_Count; $i++) {
		$row = $Table->Rows[$i];
		$agetitulo     = trim($row['AGETITULO']);
		$ageyoulnk 	= trim($row['AGEYOULNK']);
		$agereg 	= trim($row['AGEREG']);
		$agefch     = BDConvFch($row['AGEFCH']);
		$agehorini  = substr(trim($row['AGEHORINI']), 0, 5);
		$agehorfin  = substr(trim($row['AGEHORFIN']), 0, 5);

		$haux = date('H:i', strtotime('+10800 seconds', strtotime($agehorini))); //Pongo la hora en Huso horario 0
			$haux = date('H:i', strtotime($_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($haux))); //Pongo la hora, segun el Huso horario establecido por el perfil
			$agehorini = $haux;
			$haux2 = date('H:i', strtotime('+10800 seconds', strtotime($agehorfin))); //Pongo la hora en Huso horario 0
			$haux2 = date('H:i', strtotime($_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($haux2))); //Pongo la hora, segun el Huso horario establecido por el perfil
			$agehorfin = $haux2;
			///

			$agefch	= substr($agefch, 0, 2)  . '/' . substr($agefch, 3, 2) . '/' . substr($agefch, 6, 4); //Formato calendario (yyyy-mm-dd)

		$tmpl->setCurrentBlock('charlas');
		$tmpl->setVariable('agetitulo', $agetitulo);
		$tmpl->setVariable('agereg', $agereg);
		$tmpl->setVariable('agefch', $agefch);
		$tmpl->setVariable('agehorfin', $agehorfin);
		$tmpl->setVariable('agehorini', $agehorini);


		if ($ageyoulnk != '') {
			$tmpl->setVariable('video', '');
			$tmpl->setVariable('ageyoulnk', $ageyoulnk);
			$tmpl->setVariable('verageopc', 1);	
		}else{
			$tmpl->setVariable('video', 'd-none');

		}

		$tmpl->parse('charlas');
	}


	$tmpl->setVariable('spkreg', $spkreg);
	$tmpl->setVariable('spktitulo', $spktitulo);
	$tmpl->setVariable('spkdescri', $spkdescri);
	$tmpl->setVariable('spkempres', $spkempres);
	$tmpl->setVariable('spkcargo', $spkcargo);
	$tmpl->setVariable('spkimg', '../spkimg/' . $spkreg . '/' . $spkimg);
	$tmpl->setvariable('spkpos', $spkpos);

	//$tmpl->setvariable('aviimagen',$aviimagen);

}
//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
$tmpl->show();

?>	
