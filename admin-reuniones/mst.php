<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
	require_once GLBRutaFUNC.'/constants.php'; //constantes

	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('mst.html');
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	$peradminlog = (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';
    $persolicitante = (isset($_POST['persolicitante']))? trim($_POST['persolicitante']) : 0;
	$fechasolicitante = (isset($_POST['fechasolicitante']))? trim($_POST['fechasolicitante']) : '';
    $horasolicitante = (isset($_POST['horasolicitante']))? trim($_POST['horasolicitante']) : '';
    $tmpl->setVariable('fechasolicitante', $fechasolicitante);
    $tmpl->setVariable('horasolicitante', $horasolicitante);

    $conn= sql_conectar();//Apertura de Conexion
    $tmpl->setVariable('reureg', $reureg);
    $tmpl->setVariable('editartiporeunion', 'd-none');

        $tmpl->setVariable('disabledselect', 'disabled');
       // $tmpl->setVariable('editartiporeunion', '');
       $tiporeunion=0;
         //Seleccionamos los perfiles
         $query = "	SELECT PERNOMBRE,PERAPELLI,PERCODIGO,TIPO,PERCOMPAN
         FROM PER_MAEST 
         WHERE ESTCODIGO=1 AND PERCODIGO=$persolicitante
         ORDER BY UPPER(PERCOMPAN) ";
         $Table = sql_query($query, $conn);
 
         for ($i = 0; $i < $Table->Rows_Count; $i++) {
         $row = $Table->Rows[$i];
         $percod 	= trim($row['PERCODIGO']);
         $pernombre	= trim($row['PERNOMBRE']);
         $perapelli	= trim($row['PERAPELLI']);
         $tiporeunion	= trim($row['TIPO']);
         $percompan	= trim($row['PERCOMPAN']);

         $tmpl->setCurrentBlock('perfiles');
         $tmpl->setVariable('percodigo', $percod);
         $tmpl->setVariable('pernombre', $pernombre);
         $tmpl->setVariable('perapelli', $perapelli);
         $tmpl->setVariable('percompan', $percompan);
         $tmpl->setVariable('pertipsel', 'selected');
         $tmpl->setVariable('tiporeunion', $tiporeunion);
         $tmpl->parse('perfiles');

         }

	
	$where='';
	if ($tiporeunion==2){

		$where .= " ";
		
	}else{

		$where .= " AND (TIPO=$tiporeunion OR TIPO=2)  ";
	}
		
	
	//Cargo la contraparte
	$queryperfiles = "	SELECT PERNOMBRE,PERAPELLI,PERCODIGO,TIPO,PERCOMPAN
	FROM PER_MAEST 
	WHERE ESTCODIGO=1 AND PERCODIGO!=$persolicitante $where
	ORDER BY UPPER(PERCOMPAN) ";
	$Tableperfiles = sql_query($queryperfiles,$conn);

	for($j=0; $j<$Tableperfiles->Rows_Count; $j++){
		
		$rowperfiles= $Tableperfiles->Rows[$j];
		$percod 		= trim($rowperfiles['PERCODIGO']);
		$pernombre		= trim($rowperfiles['PERNOMBRE']);
		$perapelli		= trim($rowperfiles['PERAPELLI']);
		$tiporeunion	= trim($rowperfiles['TIPO']);
		$percompan	= trim($rowperfiles['PERCOMPAN']);
        $tmpl->setCurrentBlock('perfilescontraparte');
        $tmpl->setVariable('percodigocontraparte', $percod);
        $tmpl->setVariable('pernombrecontraparte', $pernombre);
        $tmpl->setVariable('perapellicontraparte', $perapelli);
        $tmpl->setVariable('percompancontraparte', $percompan);
        $tmpl->setVariable('tiporeunioncontraparte', $tiporeunion);
        $tmpl->parse('perfilescontraparte');
	
	}
	sql_close($conn);
	
	$tmpl->show();
	
?>	
