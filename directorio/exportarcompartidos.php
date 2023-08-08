<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
    require_once '../exportar/Classes/PHPExcel.php';
   
    $percodlog 	= (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';

    


    $conn= sql_conectar();//Apertura de Conexion
    
    $query = "	SELECT P.PERNOMBRE AS SOLNOMBRE,P.PERAPELLI AS SOLAPELLI,P.PERCOMPAN AS SOLEMPRESA,P.PERTELEFO AS SOLTELEFONO,P.PERCORREO AS SOLCORREO,P.PERCARGO AS SOLCARGO
					FROM PER_QR Q
					LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=Q.PERQRPER
					WHERE Q.PERCODIGO=$percodlog AND Q.PERQRAGE=0 AND Q.PERQRPER<100000
					ORDER BY Q.PERQRFCH DESC";

    $Table = sql_query($query,$conn);
    //logerror($query);
    $objPHPExcel = new PHPExcel();

    // Agregamos las columnas con sus nombres respectivos

    $objPHPExcel->getActiveSheet()
				->setCellValue('A1', 'Nombre')
                ->setCellValue('B1', 'Apellido')
                ->setCellValue('C1', 'Empresa')
                ->setCellValue('D1', 'Telefono')
                ->setCellValue('E1', 'Correo')
                ->setCellValue('F1', 'Cargo');
                
            
            //Titulo de la tabla en negrita
            $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);

            //Recorremos los datos           
            for($i=0; $i<$Table->Rows_Count; $i++){
                    $row = $Table->Rows[$i];
                    $ii = $i+2;
                    
					$solnombre	= trim($row['SOLNOMBRE']);
                    $solapelli	= trim($row['SOLAPELLI']);
                    $solempresa	= trim($row['SOLEMPRESA']);
                    $soltelefono	= trim($row['SOLTELEFONO']);
                    $solcorreo	= trim($row['SOLCORREO']);
                    $solcargo	= trim($row['SOLCARGO']);
                    
					
                    
                    //Asignamos a cada celda un valor
                    $objPHPExcel->getActiveSheet()->setCellValue('A'.$ii, $solnombre);
                    $objPHPExcel->getActiveSheet()->setCellValue('B'.$ii, $solapelli);
                    $objPHPExcel->getActiveSheet()->setCellValue('C'.$ii, $solempresa);
                    $objPHPExcel->getActiveSheet()->setCellValue('D'.$ii, $soltelefono);
                    $objPHPExcel->getActiveSheet()->setCellValue('E'.$ii, $solcorreo);
                    $objPHPExcel->getActiveSheet()->setCellValue('F'.$ii, $solcargo);
                
                    
            }


    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Compartidos.xlsx"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    //Descarga de archivo
    $objWriter->save('php://output');


    sql_close($conn);
    ?>