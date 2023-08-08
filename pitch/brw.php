<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC.'/idioma.php';//Idioma
	

$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('brw.html');

DDIdioma($tmpl);
//--------------------------------------------------------------------------------------------------------------
	
//--------------------------------------------------------------------------------------------------------------
$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
//--------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------
//Guardo la session
$conn = sql_conectar(); //Apertura de Conexion
sql_close($conn);
//--------------------------------------------------------------------------------------------------------------

$filtronombre 	= (isset($_POST['filtronombre']))? $_POST['filtronombre'] : '';
//--------------------------------------------------------------------------------------------------------------
$imgAvatarNull = '../app-assets/img/avatar.png';
$pathimagenes = '../pitchimg/';
$conn = sql_conectar(); //Apertura de Conexion
$liqueo=0;
$percodexp='';
$pertipo=0;


$where = '';

if (is_numeric($filtronombre))
	{
		if($filtronombre=='0'){
			//$where .= " AND PRECATEGO = 'SPONSOR' ";
		}else{

			$numero = intval($filtronombre);
		
			$query = "	SELECT CATREG
				FROM PIT_CAT WHERE CATREG = $numero
			 	";
		$Table = sql_query($query,$conn);
		for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$catdescri 	= trim($row['CATREG']);
		$where .= " AND PITCH_ID = '$catdescri' ";
		}


		}
	}else{

		$where .= "";

	}


$query="SELECT P.PITCH_REG,P.PITCH_EMP,P.PITCH_DES,P.PITCH_URL,P.PITCH_URLPDF,P.PITCH_VID,P.PITCH_ID,P.PITCH_IMG, PC.CATDESCRI
		FROM PITCH_MAEST P
		LEFT OUTER JOIN PIT_CAT PC ON PC.CATREG=P.PITCH_ID 
		WHERE P.ESTCODIGO=1 $where
		ORDER BY P.PITCH_REG ASC"; 
$Table = sql_query($query,$conn);

$colvid=0;
for($i=0; $i<$Table->Rows_Count; $i++){
 	$row = $Table->Rows[$i];
 	$pitchreg 	= trim($row['PITCH_REG']);
	$pitchemp 	= trim($row['PITCH_EMP']);
	$pitchdes 	= trim($row['PITCH_DES']);
	$pitchurl 	= trim($row['PITCH_URL']);
	$pitchurlpdf	 	= trim($row['PITCH_URLPDF']);
	$pitchvid 	= trim($row['PITCH_VID']);
	$pitchid 	= trim($row['PITCH_ID']);
	$pitchimg = trim($row['PITCH_IMG']);
	$catdescrip = trim($row['CATDESCRI']);
	
	//Busco el primer perfil asignado al expositor
	$queryPer = "	SELECT FIRST 1 E.PERCODIGO,P.PERNOMBRE,P.PERAPELLI, P.PERTIPO
					FROM PITCH_PER E
					LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=E.PERCODIGO
					WHERE E.PITCH_REG=$pitchreg ";
	$TablePer = sql_query($queryPer, $conn);
	for ($j = 0; $j < $TablePer->Rows_Count; $j++) {
		$rowPer = $TablePer->Rows[$j];
		
		$percodexp 	= trim($rowPer['PERCODIGO']);
		$pernombre 	= trim($rowPer['PERNOMBRE']);
		$perapelli 	= trim($rowPer['PERAPELLI']);
		$pertipo 	= trim($rowPer['PERTIPO']);
	}
	

	 $tmpl->setCurrentBlock('videos');
	 
	if ($pitchvid==''){

		$tmpl->setVariable('displayvideo'		, 'display:none;');

	}
	if ($pitchurlpdf==''){

		$tmpl->setVariable('displaypdf'		, 'display:none;');

	}
	if ($pitchurl==''){

		$tmpl->setVariable('displayurl'		, 'display:none;');

	}
	if ($percodexp==''){

		$tmpl->setVariable('displayusuario'		, 'display:none;');

	}
	$tmpl->setVariable('hashtag'		, $catdescrip);
	
	
	$queryliqueo = "SELECT PITCHREG
					FROM PITCH_LIKE 
					WHERE PITCHREG=$pitchreg AND PERCODIGO=$percodigo ";				
		$Tableliqueo = sql_query($queryliqueo,$conn);
		if($Tableliqueo->Rows_Count>0){
			$liqueo = 1;
		}	
		

		if($liqueo==1){

			$tmpl->setVariable('colorlike'	, 'color: #00005f!important;');
			$liqueo=0;
		}else{

			$tmpl->setVariable('colorlike'	, 'color: #FFFFFF!important;');
		}


 	$tmpl->setVariable('pitchreg', $pitchreg);
	$tmpl->setVariable('pitchemp'	, $pitchemp);
	$tmpl->setVariable('pitchdes'	, nl2br($pitchdes));
	$tmpl->setVariable('pitchurl'		, $pitchurl);
	$tmpl->setVariable('pitchurlpdf'		, $pitchurlpdf);
	$tmpl->setVariable('pitchid'	, $pitchid);
	$tmpl->setVariable('pitchvid'		, $pitchvid);
	$tmpl->setVariable('percodexp'		, $percodexp);
	$tmpl->setVariable('pertipo'		, $pertipo);
	

	if ($pitchimg == '') {
		$pitchimg = $imgAvatarNull;
	} else {
		$pitchimg = $pathimagenes . $pitchreg . '/' . $pitchimg;
	}
	$tmpl->setVariable('pitchimg'		, $pitchimg);
	
$tmpl->parse('videos');

}


//--------------------------------------------------------------------------------------------------------------

sql_close($conn);
$tmpl->show();

?>	