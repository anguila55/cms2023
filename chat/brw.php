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
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$perapelli = (isset($_SESSION[GLBAPPPORT.'PERAPELLI']))? trim($_SESSION[GLBAPPPORT.'PERAPELLI']) : '';
	$perusuacc = (isset($_SESSION[GLBAPPPORT.'PERUSUACC']))? trim($_SESSION[GLBAPPPORT.'PERUSUACC']) : '';
	$perpasacc = (isset($_SESSION[GLBAPPPORT.'PERCORREO']))? trim($_SESSION[GLBAPPPORT.'PERCORREO']) : '';
	$peradmin = (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';
	$peravatar = (isset($_SESSION[GLBAPPPORT.'PERAVATAR']))? trim($_SESSION[GLBAPPPORT.'PERAVATAR']) : '';
	$btnsectores 		= (isset($_SESSION[GLBAPPPORT.'SECTORES']))? trim($_SESSION[GLBAPPPORT.'SECTORES']) : '';
	$btnsubsectores 	= (isset($_SESSION[GLBAPPPORT.'SUBSECTORES']))? trim($_SESSION[GLBAPPPORT.'SUBSECTORES']) : '';
	$btncategorias 		= (isset($_SESSION[GLBAPPPORT.'CATEGORIAS']))? trim($_SESSION[GLBAPPPORT.'CATEGORIAS']) : '';
	$btnsubcategorias 	= (isset($_SESSION[GLBAPPPORT.'SUBCATEGORIAS']))? trim($_SESSION[GLBAPPPORT.'SUBCATEGORIAS']) : '';
	$pertipo 	= (isset($_SESSION[GLBAPPPORT.'PERTIPO']))? trim($_SESSION[GLBAPPPORT.'PERTIPO']) : '';
	$perclase 	= (isset($_SESSION[GLBAPPPORT.'PERCLASE']))? trim($_SESSION[GLBAPPPORT.'PERCLASE']) : '';
	$timoffset 			=(isset($_SESSION[GLBAPPPORT.'TIMOFFSET']))? trim($_SESSION[GLBAPPPORT.'TIMOFFSET']) : '';
//--------------------------------------------------------------------------------------------------------------

$pathimagenes = '../perimg/';
$imgAvatarNull = '../app-assets/img/avatar.png';

$conn = sql_conectar(); //Apertura de Conexion

//--------------------------------------------------------------------------------------------------------------
//Registracion del usuario
//2:##:Usuario2:##:NULL:##:NULL:##:REGISTRACIONUSUARIOCHAT
$chatregister = "$percodigo:##:$pernombre:##:NULL:##:NULL:##:REGISTRACIONUSUARIOCHAT";
$tmpl->setVariable('percodigo', $percodigo);
$tmpl->setVariable('pernombre', $pernombre);
$tmpl->setVariable('chatregister', $chatregister);

$percoddst 	= (isset($_POST['percoddst'])) ? trim($_POST['percoddst']) : 0;
$fltbuscar 	= (isset($_POST['fltbuscar'])) ? trim($_POST['fltbuscar']) : '';

if ($percoddst != 0) {
	$tmpl->setVariable('scriptloadchat', "loadChatData($percoddst);");
}

$where='';
if($fltbuscar!=''){
	$where = " AND (PD.PERAPELLI CONTAINING ('$fltbuscar') OR PD.PERNOMBRE CONTAINING ('$fltbuscar') OR PD.PERCOMPAN CONTAINING ('$fltbuscar')
					OR C.CHATEXTO CONTAINING ('$fltbuscar'))";
}

//--------------------------------------------------------------------------------------------------------------
//Busco los perfiles del chat
$query  = "	SELECT PERNOMBRE,PERAPELLI,PERCOMPAN,PERCODIGO,SUM(SINLEER) AS CHATSINLEER, PERAVATAR, RECIBFCH
				FROM (
				SELECT  PD.PERNOMBRE,PD.PERAPELLI,PD.PERCOMPAN,PD.PERCODIGO,0 AS SINLEER,PD.PERAVATAR,
					(SELECT MAX(T.CHAFCHREG) FROM TBL_CHAT T WHERE T.ESTCODIGO=1 AND T.PERCODIGO=PD.PERCODIGO AND T.PERCODDST=$percodigo)  AS RECIBFCH
				FROM TBL_CHAT C
				LEFT OUTER JOIN PER_MAEST PD ON PD.PERCODIGO=C.PERCODDST
				WHERE C.ESTCODIGO=1 AND C.PERCODIGO=$percodigo $where
				UNION
				SELECT  PD.PERNOMBRE,PD.PERAPELLI,PD.PERCOMPAN,PD.PERCODIGO,
						(SELECT COUNT(*) FROM TBL_CHAT T WHERE T.ESTCODIGO=1 AND T.CHALEIDO=0 AND T.PERCODIGO=PD.PERCODIGO AND T.PERCODDST=$percodigo)  AS SINLEER,
						PD.PERAVATAR,
						(SELECT MAX(T.CHAFCHREG) FROM TBL_CHAT T WHERE T.ESTCODIGO=1 AND T.PERCODIGO=PD.PERCODIGO AND T.PERCODDST=$percodigo)  AS RECIBFCH
				FROM TBL_CHAT C
				LEFT OUTER JOIN PER_MAEST PD ON PD.PERCODIGO=C.PERCODIGO
				WHERE C.ESTCODIGO=1 AND C.PERCODDST=$percodigo $where
				)
				GROUP BY 1,2,3,4,6,7
				ORDER BY 7 DESC, 5 DESC ";
//logerror($query);
$Table 	= sql_query($query, $conn);

if ($Table->Rows_Count<0)
{
	$tmpl->setVariable('nohaycharlas'	, 'd-none');
}

$percoddst = 0;
for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];
	$pernom 	= trim($row['PERNOMBRE']);
	$perape 	= trim($row['PERAPELLI']);
	$compan 	= trim($row['PERCOMPAN']);
	$codigo 	= trim($row['PERCODIGO']);
	$chatsinleer = trim($row['CHATSINLEER']);
	$peravatar =  trim($row['PERAVATAR']);

	

	if ($i == 0) $percoddst = $codigo;
	
	$tmpl->setCurrentBlock('charlas');
	$query1  = "	SELECT FIRST 1 CHAFCHREG
				FROM TBL_CHAT
				WHERE ESTCODIGO=1 AND ( (PERCODIGO=$codigo AND PERCODDST=$percodigo) OR (PERCODIGO=$percodigo AND PERCODDST=$codigo) )
				ORDER BY CHAFCHREG DESC ";
	$Table1 	= sql_query($query1, $conn);

		// Do bad things to the votes array
		if(isset($Table1->Rows[0])){ 
			$row = $Table1->Rows[0];
	$fechacorreo = trim($row['CHAFCHREG']);	
	//$agefch	= substr($fechacorreo, 11, 2) . ':' . substr($fechacorreo, 14, 2);

	$horareg 	= substr($fechacorreo,11,8);
			
	//Hora segun Zona Horaria
	$haux = date('H:i', strtotime('+10800 seconds', strtotime($horareg))); //Pongo la hora en Huso horario 0
	$haux = date('H:i', strtotime($timoffset.' seconds', strtotime($haux))); //Pongo la hora, segun el Huso horario establecido por el perfil
	$horareg = $haux;	

	$tmpl->setVariable('fechacorreo', $horareg);
		} 

	 
	
		if($peravatar!=''){
			if(strpos($peravatar, "https://") !== false){

				$tmpl->setVariable('charlaavatar'	, $peravatar);
			
			}else{
				$tmpl->setVariable('charlaavatar'	, $pathimagenes.$codigo.'/'.$peravatar);
			}
			
		}else{
			$tmpl->setVariable('charlaavatar'	, $imgAvatarNull);
		}
	$tmpl->setVariable('charlapercodigo', $codigo);
	$tmpl->setVariable('mostrarcartel', 'display: none;');
	$tmpl->setVariable('charlapernombre', $pernom);
	$tmpl->setVariable('charlaperapelli', $perape);
	$tmpl->setVariable('charlapercompan', $compan);
	
	if ($chatsinleer==0){
		$tmpl->setVariable('viewcharlasinleer', 'display:none;');
		$tmpl->setVariable('charlasinleer', '');

	}else{
		$tmpl->setVariable('charlasinleer', $chatsinleer);
	}

	
	$tmpl->parse('charlas');
}

//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
$tmpl->show();
//--------------------------------------------------------------------------------------------------------------

?>	
