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
    $mesasolicitante = (isset($_POST['mesasolicitante']))? trim($_POST['mesasolicitante']) : 0;
	$fechasolicitante = (isset($_POST['fechasolicitante']))? trim($_POST['fechasolicitante']) : '';
    $horasolicitante = (isset($_POST['horasolicitante']))? trim($_POST['horasolicitante']) : '';
    $tmpl->setVariable('fechasolicitante', $fechasolicitante);
    $tmpl->setVariable('horasolicitante', $horasolicitante);

    $conn= sql_conectar();//Apertura de Conexion
   
    $tmpl->setVariable('mesasolicitante', $mesasolicitante);
        
      
       $tiporeunion=1;

       // VER TIPO DE MESA
       // VER SI ES MESA FIJA O FLOTANTE
				$query = "	SELECT ESTCODIGO,PERCODIGO FROM MES_MAEST WHERE MESCODIGO=$mesasolicitante";
                $TableTipoMesa = sql_query($query,$conn);
                if ($TableTipoMesa->Rows_Count>0){
                    $row= $TableTipoMesa->Rows[0];
                    $tipomesa 	= trim($row['ESTCODIGO']);
                    $usuariomesa 	= trim($row['PERCODIGO']);  
                }

                if ( ($tipomesa==1) && ($usuariomesa!==0) ){
                    $tmpl->setVariable('disabledselect', 'disabled');
                     //Seleccionamos los perfiles
                    $query = "	SELECT PERNOMBRE,PERAPELLI,PERCODIGO,TIPO,PERCOMPAN
                    FROM PER_MAEST 
                    WHERE ESTCODIGO=1 AND PERCODIGO=$usuariomesa
                    ORDER BY UPPER(PERCOMPAN) ";
                    $Table = sql_query($query, $conn);
            
                    for ($i = 0; $i < $Table->Rows_Count; $i++) {
                    $row = $Table->Rows[$i];
                    $percod 	= trim($row['PERCODIGO']);
                    $pernombre	= trim($row['PERNOMBRE']);
                    $perapelli	= trim($row['PERAPELLI']);
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

                }else{
                    $tmpl->setVariable('disabledselect', '');
                    $query = "	SELECT PERNOMBRE,PERAPELLI,PERCODIGO,TIPO,PERCOMPAN
                    FROM PER_MAEST 
                    WHERE ESTCODIGO=1 AND (TIPO=$tiporeunion OR TIPO=2)
                    ORDER BY UPPER(PERCOMPAN) ";
                    $Table = sql_query($query, $conn);
            
                    for ($i = 0; $i < $Table->Rows_Count; $i++) {
                    $row = $Table->Rows[$i];
                    $percod 	= trim($row['PERCODIGO']);
                    $pernombre	= trim($row['PERNOMBRE']);
                    $perapelli	= trim($row['PERAPELLI']);
                    $percompan	= trim($row['PERCOMPAN']);

                    $queryMesaFija = "	SELECT ESTCODIGO,PERCODIGO FROM MES_MAEST WHERE PERCODIGO=$percod";
                    $TableMesaFija = sql_query($queryMesaFija,$conn);

                  
                    if ($TableMesaFija->Rows_Count>0){

                    }else{
                        $tmpl->setCurrentBlock('perfiles');
                        $tmpl->setVariable('percodigo', $percod);
                        $tmpl->setVariable('pernombre', $pernombre);
                        $tmpl->setVariable('perapelli', $perapelli);
                        $tmpl->setVariable('percompan', $percompan);
                        $tmpl->setVariable('pertipsel', 'selected');
                        $tmpl->setVariable('tiporeunion', $tiporeunion);
                        $tmpl->parse('perfiles');
                    }

                   

                    }
                }
            // VER SI ES MESA FIJA O FLOTANTE
        // SI ES FIJA ASIGNAR EL USUARIO, SI ES FLOTANTE LIBERAR A TODOS LOS PERFILES NO DIGITALES, NO INCLUIR A LOS QUE TIENE MESA FIJA 
            
        
	
	$where='';
	if ($tiporeunion==2){

		$where .= " ";
		
	}else{

		$where .= " AND (TIPO=$tiporeunion OR TIPO=2)  ";
	}
		
    if ( ($tipomesa==1) && ($usuariomesa!==0) ){
        $where .= " AND PERCODIGO!=$usuariomesa  ";
    }

//Cargo la contraparte
$queryperfiles = "	SELECT PERNOMBRE,PERAPELLI,PERCODIGO,TIPO,PERCOMPAN
FROM PER_MAEST 
WHERE ESTCODIGO=1 $where
ORDER BY UPPER(PERCOMPAN) ";
$Tableperfiles = sql_query($queryperfiles,$conn);

for($j=0; $j<$Tableperfiles->Rows_Count; $j++){
    
    $rowperfiles= $Tableperfiles->Rows[$j];
    $percod 		= trim($rowperfiles['PERCODIGO']);
    $pernombre		= trim($rowperfiles['PERNOMBRE']);
    $perapelli		= trim($rowperfiles['PERAPELLI']);
    $percompan	= trim($rowperfiles['PERCOMPAN']);

    $queryMesaFija = "	SELECT ESTCODIGO,PERCODIGO FROM MES_MAEST WHERE PERCODIGO=$percod";
    $TableMesaFija = sql_query($queryMesaFija,$conn);

    if ($TableMesaFija->Rows_Count>0){

    }else{
        $tmpl->setCurrentBlock('perfilescontraparte');
        $tmpl->setVariable('percodigocontraparte', $percod);
        $tmpl->setVariable('pernombrecontraparte', $pernombre);
        $tmpl->setVariable('perapellicontraparte', $perapelli);
        $tmpl->setVariable('percompancontraparte', $percompan);
        $tmpl->setVariable('tiporeunioncontraparte', $tiporeunion);
        $tmpl->parse('perfilescontraparte');
    }

   

    }

   
	
	sql_close($conn);
	
	$tmpl->show();
	
?>	
