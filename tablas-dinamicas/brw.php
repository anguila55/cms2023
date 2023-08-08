<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
	require_once GLBRutaFUNC.'/constants.php';//Idioma	
	require 'PHPivot.php';
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('brw.html');
	DDIdioma($tmpl);
	
	//Diccionario de idiomas
	$filas = (isset($_POST['filas']))? $_POST['filas'] : '';
	$columnas = (isset($_POST['columnas']))? $_POST['columnas'] : '';
	$valor    = (isset($_POST['valor']))? trim($_POST['valor']) : '';
	$filtro = (isset($_POST['filtro']))? $_POST['filtro'] : '';
	$filtronombre    = (isset($_POST['filtronombre']))? trim($_POST['filtronombre']) : '';


	$conn = sql_conectar(); //Apertura de Conexion


	$arraypivot=null;
	$query = "	SELECT TPS.PAIDESCRI AS PAISSOL, TPD.PAIDESCRI AS PAISDST,PS.PERCOMPAN AS EMPRESASOL, PD.PERCOMPAN AS EMPRESADST, R.REUESTADO, PS.PERNOMBRE AS NOMBRESOL, PS.PERAPELLI AS APELLISOL, PD.PERNOMBRE AS NOMBREDST, PD.PERAPELLI AS APELLIDST, PCS.PERCLADES AS CLASESOL, PCD.PERCLADES AS CLASEDST
					FROM REU_CABE R
					LEFT OUTER JOIN PER_MAEST PS ON R.PERCODSOL=PS.PERCODIGO
            		LEFT OUTER JOIN PER_MAEST PD ON R.PERCODDST=PD.PERCODIGO
					LEFT OUTER JOIN TBL_PAIS TPS ON TPS.PAICODIGO=PS.PAICODIGO
					LEFT OUTER JOIN TBL_PAIS TPD ON TPD.PAICODIGO=PD.PAICODIGO
					LEFT OUTER JOIN PER_CLASE PCS ON PCS.PERCLASE=PS.PERCLASE
					LEFT OUTER JOIN PER_CLASE PCD ON PCD.PERCLASE=PD.PERCLASE
					WHERE (R.REUESTADO=2 OR R.REUESTADO=1) AND (R.PERCODDST!=R.PERCODSOL)";	
	$Table = sql_query($query, $conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$paissol 		= trim($row['PAISSOL']);
		$paisdes 		= trim($row['PAISDST']);
		$empresasol 		= trim($row['EMPRESASOL']);
		$empresades 		= trim($row['EMPRESADST']);
		$reuestado 		= trim($row['REUESTADO']);
		$nombresol 		= trim($row['NOMBRESOL']);
		$apellisol 		= trim($row['APELLISOL']);
		$nombredst 		= trim($row['NOMBREDST']);
		$apellidst 		= trim($row['APELLIDST']);
		$clasesol 		= trim($row['CLASESOL']);
		$clasedst 		= trim($row['CLASEDST']);

		if ($paissol==''){
			$paissol='Sin Pais';
		} 
	    if ($paisdes==''){
			$paisdes='Sin Pais';
		}
		if ($empresasol==''){
			$empresasol='Sin Empresa';
		} 
	    if ($empresades==''){
			$empresades='Sin Empresa';
		}
		if ($reuestado==1){
			$reuestado='Pendiente';
		}else{
			$reuestado='Confirmada';
		}
		$arraypivot[]=['Pais'=>$paissol,'Empresa'=>$empresasol,'Tiporeunion'=>$reuestado,'Nombre'=>$nombresol.' '.$apellisol,'Clase'=>$clasesol];
		$arraypivot[]=['Pais'=>$paisdes,'Empresa'=>$empresades,'Tiporeunion'=>$reuestado,'Nombre'=>$nombredst.' '.$apellidst,'Clase'=>$clasedst];	
	}
	if ($filtronombre!=''){
		$pruebatablapivot = PHPivot::create($arraypivot)
            ->setPivotRowFields($filas)
			->setPivotColumnFields($columnas)
            ->setPivotValueFields($valor,PHPivot::PIVOT_VALUE_COUNT, PHPivot::DISPLAY_AS_VALUE, $valor)
            ->addFilter($filtro,$filtronombre, PHPivot::COMPARE_EQUAL) //Filter out blanks/unknown genre
            ->setIgnoreBlankValues()
            ->generate();
		
	}else{

		$pruebatablapivot = PHPivot::create($arraypivot)
            ->setPivotRowFields($filas)
			->setPivotColumnFields($columnas)
            ->setPivotValueFields($valor,PHPivot::PIVOT_VALUE_COUNT, PHPivot::DISPLAY_AS_VALUE, $valor)
            ->addFilter($filtro,$filtronombre, PHPivot::COMPARE_NOT_EQUAL) //Filter out blanks/unknown genre
            ->setIgnoreBlankValues()
            ->generate();
	}
    


	$tmpl->setVariable('pruebatablapivot'	, $pruebatablapivot->toHtml());

	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);

	$tmpl->show();
	
	//--------------------------------------------------------------------------------------------------------------
?>	
