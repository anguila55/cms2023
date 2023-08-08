<?php include('../val/valuser.php');
include('../exportar/Classes/fpdf.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC.'/constants.php';
$PERADMIN = (isset($_SESSION[GLBAPPPORT . 'PERADMIN'])) ? trim($_SESSION[GLBAPPPORT . 'PERADMIN']) : '';

//verificamos is es administrador
$image1 = "../assets-nuevodisenio/img/logo.jpg";

if ($PERADMIN != 1) {
  header('Location: ../index');
}
$conn= sql_conectar();//Apertura de Conexion
$errcod 	= 0;
	$errmsg 	= 'Exportacion exitosa';
	$err 		= 'SQLACCEPT';
	//------------------------------
$percodlog 	= (isset($_GET['ID']))? $_GET['ID']:0;
$diaactual = date('m/d/Y');
$where = '';
$relacion = '';
$fltbuscar=3;

$query = "	SELECT ZPARAM,ZVALUE FROM ZZZ_CONF WHERE ZPARAM CONTAINING 'SisEvento' ";
	$Table = sql_query($query, $conn);
	for ($i = 0; $i < $Table->Rows_Count; $i++) {
		$row = $Table->Rows[$i];
		$params[trim($row['ZPARAM'])] = trim($row['ZVALUE']);
	}

	$diasini = $params['SisEventoDiaInicio']; 		 			//Evento - Dia de Inicio
	$diasdur = intval($params['SisEventoDuracionDias']); 
	
	// $diaInicial =  substr($diasini, 0, 2) + 1 - 1;
	$diaInicial = substr($diasini, 0, 2).'-'.substr($diasini,3,2).'-'.substr($diasini,6,4);
	$finalEvento =  date("d/m/Y", strtotime($diaInicial.' + '.$diasdur.' days'));
	

/// AGARRO BANNER 
  $imgBannerHomeNull	= '../assets-nuevodisenio/img/bannerhome.jpg';
  $pathimagenes = '../admimg/';

 $query = "	SELECT *
		 FROM ADM_IMG
		 WHERE ESTCODIGO=1 AND BANID=2";

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




$query = "	SELECT P.PERCOMPAN,P.PERNOMBRE,P.PERAPELLI,P.PERCODIGO,P.TIMOFFSET,P.TIMREG2
				FROM PER_MAEST P
				WHERE ESTCODIGO=1 AND PERCODIGO=$percodlog
				ORDER BY PERCOMPAN ASC,UPPER(PERNOMBRE) ";
$Table = sql_query($query, $conn);

for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];
  $pernomlog	= utf8_decode(trim($row['PERNOMBRE']));
	$perapelog	= utf8_decode(trim($row['PERAPELLI']));
	$percompanlog	= utf8_decode(trim($row['PERCOMPAN']));
  $timeoffset	= trim($row['TIMOFFSET']);
  $timdescri	= trim($row['TIMREG2']);
}

if($fltbuscar == 3){ //Ver reuniones que envie y me enviaron, pero ya estan confirmadas
  $relacion = ' P.PERCODIGO=R.PERCODDST ';
  $where .= "  (R.PERCODDST=$percodlog OR R.PERCODSOL=$percodlog) AND R.REUESTADO!=5 AND R.REUESTADO=2 AND R.REUFECHA>='$diaactual' ";
}

$pdf = new FPDF();
    $pdf->AliasNbPages();
//Add a new page
$pdf->AddPage();
 // Add logo to page
 //$pdf->Image('https://upload.wikimedia.org/wikipedia/commons/thumb/1/12/Google_Photos_icon_%282020%29.svg/1200px-Google_Photos_icon_%282020%29.svg.png',10,5,25,'PNG');
        
 // Set font family to Arial bold 
 $pdf->SetFont('Helvetica','B',20);
   
 // Move to the right
 $pdf->Cell(80);
   
 // Header
 //$pdf->Cell( 40, 40, $pdf->Image($image1, 0,0,100,100,'jpg'), 1, 0, 'C' );
//  $pdf->Cell( 50, 20, $pdf->Image($image1, 0,0,100,100), 1, 0, 'C' );
$pdf->Image( $bannerhomeimg ,0,0,210,'JPG');
$pdf->Ln(2);
$pdf->SetFont('Helvetica','',15);
$pdf->Cell(0,95,utf8_decode(NAME_TITLE),0,0,'C');
$pdf->Ln(6);
$pdf->SetFont('Helvetica','',10);
$pdf->Cell(0,95,$diasini.' - '.$finalEvento,0,0,'C');
  // Line break
  $pdf->Ln(6);
  $pdf->Line(20, 75, 210-20, 75);
   
 // Line break
 $pdf->Ln(55);
$pdf->SetFont('Helvetica','',12);
$pdf->Cell(0,10,$percompanlog.' - '.$pernomlog.' '.$perapelog,1,1,'C');
   
//Reuniones que solicitaron
$query = "	SELECT 	PD.PERCODIGO AS PERCODDST, PD.PERNOMBRE AS PERNOMDST, PD.PERAPELLI AS PERAPEDST, PD.PERCOMPAN AS PERCOMDST, PD.PERCARGO AS PERCARDST, PD.PERCORREO AS PERCORDST, PD.PERAVATAR AS PERAVADST,
          PS.PERCODIGO AS PERCODSOL, PS.PERNOMBRE AS PERNOMSOL, PS.PERAPELLI AS PERAPESOL, PS.PERCOMPAN AS PERCOMSOL, PS.PERCARGO AS PERCARSOL, PS.PERCORREO AS PERCORSOL, PS.PERAVATAR AS PERAVASOL,
          R.REUESTADO,R.REUFECHA,R.REUHORA,R.REUREG,
          R.AGEREG,A.AGETITULO,A.AGELUGAR,
          PD.PERREUURL AS PERREUURLDST,PS.PERREUURL AS PERREUURLSOL,
          M.MESNUMERO,R.REULINK,R.REUTOKSOL,R.REUTOKDST,R.REUTIPO
      FROM REU_CABE R
      LEFT OUTER JOIN PER_MAEST PD ON PD.PERCODIGO=R.PERCODDST
      LEFT OUTER JOIN PER_MAEST PS ON PS.PERCODIGO=R.PERCODSOL
      LEFT OUTER JOIN AGE_MAEST A ON A.AGEREG=R.AGEREG
      LEFT OUTER JOIN MES_MAEST M ON M.MESCODIGO=R.MESCODIGO
      WHERE $where AND (PERCODDST!=PERCODSOL)
      ORDER BY R.REUESTADO ASC,R.REUFECHA,R.REUHORA,R.REUREG";
    
$Table = sql_query($query,$conn);


if ($Table->Rows_Count != -1){

 
for($i=0; $i<$Table->Rows_Count; $i++){
  $row = $Table->Rows[$i];
  $percoddst 		= trim($row['PERCODDST']);
  $pernomdst		= trim($row['PERNOMDST']);
  $perapedst		= trim($row['PERAPEDST']);
  $percomdst		= trim($row['PERCOMDST']);
  $percardst		= trim($row['PERCARDST']);
  $percordst		= trim($row['PERCORDST']);
  $peravadst		= trim($row['PERAVADST']);		
  $percodsol 		= trim($row['PERCODSOL']);
  $pernomsol		= trim($row['PERNOMSOL']);
  $perapesol		= trim($row['PERAPESOL']);
  $percomsol		= trim($row['PERCOMSOL']);
  $percarsol		= trim($row['PERCARSOL']);
  $percorsol		= trim($row['PERCORSOL']);
  $peravasol		= trim($row['PERAVASOL']);
  $agereg			= trim($row['AGEREG']);
  $agetitulo		= trim($row['AGETITULO']);
  $agelugar		= trim($row['AGELUGAR']);		
  $reufecha		= BDConvFch($row['REUFECHA']);
  
  $reuhora		= substr(trim($row['REUHORA']),0,5);
  $reuhoraorig	= substr(trim($row['REUHORA']),0,5);
  $reuestado		= trim($row['REUESTADO']);
  $reureg			= trim($row['REUREG']);
  $mesnumero  	= trim($row['MESNUMERO']);
  $perreuurldst 	= trim($row['PERREUURLDST']);
  $perreuurlsol 	= trim($row['PERREUURLSOL']);
  $reulink 		= trim($row['REULINK']);
  $reutoksol 		= trim($row['REUTOKSOL']);
  $reutokdst 		= trim($row['REUTOKDST']);
  $reutipo 		= trim($row['REUTIPO']);
  $perfil			= 0;




  if ($reutipo == 0){
    $nombretiporeunion='Virtual';
    $mesnumero=URL_WEB;
  }else{
    
    $nombretiporeunion='Presencial';

  }

  
  

  
  //El perfil que esta viendo el browser es el Destino de las reuniones
  if($percoddst==$percodlog){
    $perfil 	= 1; //Perfil Destino
    $percod 	= $percodsol;
    $pernombre	= utf8_decode($pernomsol);
    $perapelli	= utf8_decode($perapesol);
    $percompan	= utf8_decode($percomsol);
    $percargo	= utf8_decode($percarsol);
    $percorreo	= $percorsol;
    $peravatar	= $peravasol;
    $perreuurl	= $perreuurlsol;
    $reutoken	= $reutokdst; //Token de agora
    $reuestconf='Recibida';
    
  }else{//El perfil que esta viendo el browser es el Solicitante de las reuniones
    $perfil 	= 2; //Perfil Solicitante
    $percod 	= $percoddst;
    $pernombre	= utf8_decode($pernomdst);
    $perapelli	= utf8_decode($perapedst);
    $percompan	= utf8_decode($percomdst);
    $percargo	= utf8_decode($percardst);
    $percorreo	= $percordst;
    $peravatar	= $peravadst;
    $perreuurl	= $perreuurldst;
    
    $reutoken	= $reutoksol; //Token de agora
    $reuestconf='Enviada';

  }
  

  
  if($reuestado==2){
    //Hora segun Zona Horaria
    $haux = date('H:i', strtotime('+10800 seconds', strtotime($reuhora))); //Pongo la hora en Huso horario 0
    $haux = date('H:i', strtotime($timeoffset.' seconds', strtotime($haux))); //Pongo la hora, segun el Huso horario establecido por el perfil
    $reuhora = $haux;
    $reuhoraconf=$reuhora;
    $reufechaconf=$reufecha;
    $reuzonaconf='('.$timdescri.')';
    $reuestconf='Confirmada';

    $reuestdes = "Confirmed meeting in <b>$reufecha</b> at <b>$reuhora</b> <br> Table N° <b>$mescodigo</b> ";
  }
  $horacalendar=substr($reuhora, 0, 2);
		$minutoscalendar=$reuhora[3].$reuhora[4];
  $pdf->Cell(20,10,"FECHA/DATE: ".$reufechaconf,0,1);
  $pdf->Cell(45,10,"HORA/TIME: ".$horacalendar.":".$minutoscalendar ,0,1);
  $pdf->Cell(45,10,"CONTRAPARTE/COUNTERPART: ".$perapelli.' '.$pernombre,0,1);
  $pdf->Cell(45,10,"EMPRESA/COMPANY: ".$percompan,0,1);
  $pdf->Cell(45,10,"TIPO/TYPE: ".$nombretiporeunion,0,1);
  $pdf->Cell(45,10,"MESA/TABLE: ".utf8_decode($mesnumero),0,1);
  $pdf->Ln(10);
}
}else{
  $pdf->Cell(20,10,"NO TIENE REUNIONES CONFIRMADAS",0,1);

  $pdf->Ln(10);
}

 
$file = $perapelog.$pernomlog.'.pdf';
$pdf->Output($file,'D');

//--------------------------------------------------------------------------------------------------------------
sql_close($conn);


?>