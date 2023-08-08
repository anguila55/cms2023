<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
    require_once 'Classes/PHPExcel.php';
	$peradmin = (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';
    //verificamos is es administrador
    if($peradmin!=1){
		header('Location: ../index');
    }
    
    $conn= sql_conectar();//Apertura de Conexion
    
        $query="SELECT PS.PERNOMBRE AS SOLNOMBRE,PS.PERAPELLI AS SOLAPELLI,Q.CONTFCH AS SOLEMPRESA,Q.PERCONTROL AS SOLCORREO,PS.PERCODIGO
				FROM CONT_INGRESO Q
				LEFT OUTER JOIN PER_MAEST PS ON PS.PERCODIGO=Q.PERCODIGO
				WHERE Q.PERCODIGO IS NOT NULL
				ORDER BY Q.CONTFCH ";
    $Table = sql_query($query,$conn);
    //logerror($query);
    $objPHPExcel = new PHPExcel();

    // Agregamos las columnas con sus nombres respectivos

    $objPHPExcel->getActiveSheet()
    ->setCellValue('A1', 'ID Ingresante')
    ->setCellValue('B1', 'Nombre Ingresante')
    ->setCellValue('C1', 'Apellido Ingresante')
    ->setCellValue('D1', 'Fecha Ingresante')
    ->setCellValue('E1', 'Controlador');

                
                
            
            //Titulo de la tabla en negrita
            $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);

            //Recorremos los datos           
            for($i=0; $i<$Table->Rows_Count; $i++){
                    $row = $Table->Rows[$i];
                    $ii = $i+2;
                    
                    $codigonombre	= trim($row['PERCODIGO']);
					          $solnombre	= trim($row['SOLNOMBRE']);
                    $solapelli	= trim($row['SOLAPELLI']);
                    $solempresa	= BDConvFch($row['SOLEMPRESA']);
                    $solcorreo	= trim($row['SOLCORREO']);

                    
                    
                    $haux = date('H:i', strtotime('+10800 seconds', strtotime(substr($solempresa, 10, 6)))); //Pongo la hora en Huso horario 0
                    $haux = date('H:i', strtotime($_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($haux))); //Pongo la hora, segun el Huso horario establecido por el perfil
                    $agehorini = $haux;
                    $newDate	= substr($solempresa, 6, 4) . '-' . substr($solempresa, 3, 2) . '-' . substr($solempresa, 0, 2).' '.$agehorini; //Formato calendario (yyyy-mm-dd)
					
                    
                    //Asignamos a cada celda un valor
                    $objPHPExcel->getActiveSheet()->setCellValue('A'.$ii, $codigonombre);
                    $objPHPExcel->getActiveSheet()->setCellValue('B'.$ii, $solnombre);
                    $objPHPExcel->getActiveSheet()->setCellValue('C'.$ii, $solapelli);
                    $objPHPExcel->getActiveSheet()->setCellValue('D'.$ii, $newDate);
                    $objPHPExcel->getActiveSheet()->setCellValue('E'.$ii, $solcorreo);
                    
                
                    
            }


    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="ExportarIngresos.xlsx"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    //Descarga de archivo
    $objWriter->save('php://output');


    sql_close($conn);
    ?>