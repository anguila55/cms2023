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
	$percontraparte = (isset($_POST['percontraparte']))? trim($_POST['percontraparte']) : 0;
    $reureg = (isset($_POST['reureg']))? trim($_POST['reureg']) : 0;


    $conn= sql_conectar();//Apertura de Conexion
    $tmpl->setVariable('reureg', $reureg);
    $tmpl->setVariable('disabledselect', '');
    $tmpl->setVariable('editartiporeunion', 'd-none');
    if ($reureg!=0){

        $tmpl->setVariable('disabledselect', 'disabled');
        $tmpl->setVariable('editartiporeunion', '');
        
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

          //Seleccionamos los perfiles
          $query = "	SELECT PERNOMBRE,PERAPELLI,PERCODIGO,PERCOMPAN
          FROM PER_MAEST 
          WHERE ESTCODIGO=1 AND PERCODIGO=$percontraparte
          ORDER BY UPPER(PERCOMPAN) ";
          $Table = sql_query($query, $conn);
  
          for ($i = 0; $i < $Table->Rows_Count; $i++) {
          $row = $Table->Rows[$i];
          $percod 	= trim($row['PERCODIGO']);
          $pernombre	= trim($row['PERNOMBRE']);
          $perapelli	= trim($row['PERAPELLI']);
          $percompan	= trim($row['PERCOMPAN']);

          $tmpl->setCurrentBlock('perclases');
          $tmpl->setVariable('perclasel', 'selected');
          $tmpl->setVariable('perclacod', $percod);
          $tmpl->setVariable('perclades', $percompan.' - ' .$pernombre.' '.$perapelli);
          $tmpl->parse('perclases');

          }

    }else{

         //Seleccionamos los perfiles
        $query = "	SELECT PERNOMBRE,PERAPELLI,PERCODIGO,TIPO,PERCOMPAN
        FROM PER_MAEST 
        WHERE ESTCODIGO=1
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
        $tmpl->setVariable('tiporeunion', $tiporeunion);
        $tmpl->parse('perfiles');
        }

    }
   

	

	sql_close($conn);
	
	$tmpl->show();
	
?>	
