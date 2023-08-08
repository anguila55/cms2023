<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC . '/idioma.php'; //Idioma	


$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('brw.html');
//Diccionario de idiomas
DDIdioma($tmpl);


//--------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------
$percodlog 	= (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';
$pernombre 	= (isset($_SESSION[GLBAPPPORT . 'PERNOMBRE'])) ? trim($_SESSION[GLBAPPPORT . 'PERNOMBRE']) : '';
$perapelli 	= (isset($_SESSION[GLBAPPPORT . 'PERAPELLI'])) ? trim($_SESSION[GLBAPPPORT . 'PERAPELLI']) : '';
$perusuacc 	= (isset($_SESSION[GLBAPPPORT . 'PERUSUACC'])) ? trim($_SESSION[GLBAPPPORT . 'PERUSUACC']) : '';
$percorreo 	= (isset($_SESSION[GLBAPPPORT . 'PERCORREO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCORREO']) : '';
$perusareu 	= (isset($_SESSION[GLBAPPPORT . 'PERUSAREU'])) ? trim($_SESSION[GLBAPPPORT . 'PERUSAREU']) : '';
$pertipolog = (isset($_SESSION[GLBAPPPORT . 'PERTIPO'])) ? trim($_SESSION[GLBAPPPORT . 'PERTIPO']) 	  : '';
$perclaselog = (isset($_SESSION[GLBAPPPORT . 'PERCLASE'])) ? trim($_SESSION[GLBAPPPORT . 'PERCLASE'])   : '';

$pathimagenes = '../expimg/';
$pathimagenes2 = '../perimg/';
$imgAvatarNull = '../app-assets/img/avatar.png';

$fltbuscar 		= (isset($_POST['fltbuscar']))? trim($_POST['fltbuscar']) : '';
$empresa 		= (isset($_POST['empresa']))? trim($_POST['empresa']) : '';
$medida 		= (isset($_POST['medida']))? trim($_POST['medida']) : 0;

$tmpl->setVariable('avatarresponsive'	, 'avatar-sponsor');
$tmpl->setVariable('alturacupon'	, 'height:200px');



$conn = sql_conectar(); //Apertura de Conexion
$where = '';
$where1 = '';

if ($fltbuscar!='' || $empresa!=''){

	if($fltbuscar!=''){
		$where .= " AND (EC.EXPCUPTIT CONTAINING '$fltbuscar' OR EC.EXPCUPDES CONTAINING '$fltbuscar' OR EC.EXPCUPVAL CONTAINING '$fltbuscar') ";

	}
	if($empresa!=''){
		$where .= " AND EM.EXPNOMBRE CONTAINING '$empresa' ";
	}

}

$encontre = false;
if ($pertipolog == 65){

	$querybusco = "SELECT EXPREG 
					FROM EXP_PER
					WHERE PERCODIGO = $percodlog";

	$Tablebusco = sql_query($querybusco,$conn);	
	for($i=0; $i<$Tablebusco->Rows_Count; $i++){
		$row = $Tablebusco->Rows[$i];
		$expregbusco 	= trim($row['EXPREG']);
		$encontre=true;
	}


	if ($encontre)
	{

	$query = "	SELECT PM.PERAVATAR,PM.PERNOMBRE, PM.PERAPELLI,PM.PERCODIGO, EM.EXPCATEGO ,EC.EXPCUPREG, EC.EXPCUPTIT, EC.EXPCUPDES, EC.EXPCUPVAL,EC.EXPCUPZON,EC.EXPCUPFCH
						FROM CUP_PER EX
						LEFT OUTER JOIN PER_MAEST PM ON PM.PERCODIGO = EX.PERCODIGO
						LEFT OUTER JOIN EXP_CUPO EC ON EC.EXPCUPREG=EX.EXPCUPREG
						LEFT OUTER JOIN EXP_MAEST EM ON EC.EXPREG = EM.EXPREG 
						WHERE EM.ESTCODIGO=1 AND EC.ESTCODIGO<>3 AND EC.EXPREG=$expregbusco 
						ORDER BY EC.EXPCUPREG DESC";
		
		$Table = sql_query($query,$conn);


		for($i=0; $i<$Table->Rows_Count; $i++){
			$row = $Table->Rows[$i];
			$expreg 	= $expregbusco;
			$expcatego 	= trim($row['EXPCATEGO']);
			// logerror($encreg);
			$expcupreg 	= trim($row['EXPCUPREG']);
			$expcuptit 	= trim($row['EXPCUPTIT']);
			$expcupdes 	= trim($row['EXPCUPDES']);
			// logerror($encpretip);
			$expnombre 	= trim($row['PERNOMBRE']);
			$expnombre2 = trim($row['PERAPELLI']);
			$expnombre = $expnombre.' '.$expnombre2;
			$expcupval 	= trim($row['EXPCUPVAL']);
			$expavatar 	= trim($row['PERAVATAR']);
			$expcupfch 	= trim($row['EXPCUPFCH']);
			$expcupzon 	= trim($row['EXPCUPZON']);
			$percodigopost = trim($row['PERCODIGO']);

			if ($expavatar == '') {
				$expavatar = $imgAvatarNull;
			} else {
				$expavatar = $pathimagenes2 . $percodigopost . '/' . $expavatar;
			}
			
			$tmpl->setCurrentBlock('cupones');
			if ($medida == 100){
				$tmpl->setVariable('avatarresponsive'	, 'avatar-sponsor1');
				$tmpl->setVariable('alturacupon'	, 'height:300px');
				
	
			}
		
			$tmpl->setVariable('expreg'	, $expreg);


			if ($expcupval == 'Destacado')
			{
				$tmpl->setVariable('expcupval'	, '');
			}else{
				$tmpl->setVariable('expcupval'	, 'd-none');
			}

			$date1 = new DateTime('now');
			$date2 = new DateTime($expcupfch);
			$diff = $date1->diff($date2);
			// will output 2 days
			
			if ($diff->days > 0){
				$hacetanto = 'Publicado hace '.  $diff->days . ' días ';
			}else{
				$hacetanto = 'Publicado hoy';
			}

			


			$tmpl->setVariable('expcupzon', $expcupzon);
			$tmpl->setVariable('expavatar', $expavatar);
			$tmpl->setVariable('expnombre', $expnombre);
			$tmpl->setVariable('expcupreg'	, $expcupreg);
			$tmpl->setVariable('expcuptit'	, $expcuptit);
			$tmpl->setVariable('expcupdes'	, $expcupdes);
			$tmpl->setVariable('expcatego'	, $expcatego);
			$tmpl->setVariable('expcupfch'	, $hacetanto);
			$tmpl->setVariable('percodigopost'	, $percodigopost);

			$tmpl->setVariable('verpostular'	, 'd-none');
			$tmpl->setVariable('verperfil'	, '');

			$tmpl->parse('cupones');
		}

		}

}else{




$query = "	SELECT EC.EXPREG, EM.EXPREG,EM.EXPAVATAR,EM.EXPNOMBRE, EM.EXPCATEGO ,EC.EXPCUPREG, EC.EXPCUPTIT, EC.EXPCUPDES, EC.EXPCUPVAL,EC.EXPCUPZON,EC.EXPCUPFCH
						FROM EXP_MAEST EM
						LEFT OUTER JOIN EXP_CUPO EC ON EC.EXPREG=EM.EXPREG
						LEFT OUTER JOIN CUP_PER EX ON EX.EXPCUPREG=EC.EXPCUPREG
						LEFT OUTER JOIN PER_MAEST PM ON PM.PERCODIGO=$percodlog
						WHERE EM.ESTCODIGO=1 AND EC.ESTCODIGO<>3 AND EX.PERCODIGO=$percodlog
						ORDER BY EC.EXPCUPFCH DESC, EM.EXPPOS ASC,EM.EXPNOMBRE ";
		
		$Table = sql_query($query,$conn);
		for($i=0; $i<$Table->Rows_Count; $i++){
			$row = $Table->Rows[$i];
			$expreg 	= trim($row['EXPREG']);
			$expcatego 	= trim($row['EXPCATEGO']);
			// logerror($encreg);
			$expcupreg 	= trim($row['EXPCUPREG']);
			$expcuptit 	= trim($row['EXPCUPTIT']);
			$expcupdes 	= trim($row['EXPCUPDES']);
			// logerror($encpretip);
			$expnombre 	= trim($row['EXPNOMBRE']);
			$expcupval 	= trim($row['EXPCUPVAL']);
			$expavatar 	= trim($row['EXPAVATAR']);
			$expcupfch 	= trim($row['EXPCUPFCH']);
			$expcupzon 	= trim($row['EXPCUPZON']);

			if ($expavatar == '') {
				$expavatar = $imgAvatarNull;
			} else {
				$expavatar = $pathimagenes . $expreg . '/' . $expavatar;
			}
			
			$tmpl->setCurrentBlock('cupones');
			if ($medida == 100){
				$tmpl->setVariable('avatarresponsive'	, 'avatar-sponsor1');
				$tmpl->setVariable('alturacupon'	, 'height:300px');
				
	
			}
		
			$tmpl->setVariable('expreg'	, $expreg);


			if ($expcupval == 'Destacado')
			{
				$tmpl->setVariable('expcupval'	, '');
			}else{
				$tmpl->setVariable('expcupval'	, 'd-none');
			}

			$date1 = new DateTime('now');
			$date2 = new DateTime($expcupfch);
			$diff = $date1->diff($date2);
			// will output 2 days
			
			if ($diff->days > 0){
				$hacetanto = 'Publicado hace '.  $diff->days . ' días ';
			}else{
				$hacetanto = 'Publicado hoy';
			}

			


			$tmpl->setVariable('expcupzon', $expcupzon);
			$tmpl->setVariable('expavatar', $expavatar);
			$tmpl->setVariable('expnombre', $expnombre);
			$tmpl->setVariable('expcupreg'	, $expcupreg);
			$tmpl->setVariable('expcuptit'	, $expcuptit);
			$tmpl->setVariable('expcupdes'	, $expcupdes);
			$tmpl->setVariable('expcatego'	, $expcatego);
			$tmpl->setVariable('expcupfch'	, $hacetanto);

			$tmpl->setVariable('verpostular'	, '');
			$tmpl->setVariable('verperfil'	, 'd-none');

			$tmpl->parse('cupones');
		}

	}

sql_close($conn);
$tmpl->show();

?>	




