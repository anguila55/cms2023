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
    
        $query="SELECT DISTINCT PS.PERNOMBRE AS SOLNOMBRE,PS.PERAPELLI AS SOLAPELLI,PS.PERCOMPAN AS SOLEMPRESA,PS.PERCORREO AS SOLCORREO,
					PD.EXPNOMBRE AS DSTEMPRESA
				FROM EXP_LIKE Q
				LEFT OUTER JOIN PER_MAEST PS ON PS.PERCODIGO=Q.PERCODIGO
                LEFT OUTER JOIN EXP_MAEST PD ON PD.EXPREG=Q.EXPREG
				WHERE Q.PERCODIGO IS NOT NULL
				ORDER BY PD.EXPNOMBRE ";
    $Table = sql_query($query,$conn);
    //logerror($query);
    $objPHPExcel = new PHPExcel();

    // Agregamos las columnas con sus nombres respectivos

    $objPHPExcel->getActiveSheet()
				->setCellValue('A1', 'Nombre Soliticante')
                ->setCellValue('B1', 'Apellido Soliticante')
                ->setCellValue('C1', 'Empresa Solicitante')
                ->setCellValue('D1', 'Correo Solicitante')
                ->setCellValue('E1', 'Empresa Destino');
                
                
            
            //Titulo de la tabla en negrita
            $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);

            //Recorremos los datos           
            for($i=0; $i<$Table->Rows_Count; $i++){
                    $row = $Table->Rows[$i];
                    $ii = $i+2;
                    
					$solnombre	= trim($row['SOLNOMBRE']);
                    $solapelli	= trim($row['SOLAPELLI']);
                    $solempresa	= trim($row['SOLEMPRESA']);
                    $solcorreo	= trim($row['SOLCORREO']);
                    $dstempresa	= trim($row['DSTEMPRESA']);
                    
                    
					
                    
                    //Asignamos a cada celda un valor
                    $objPHPExcel->getActiveSheet()->setCellValue('A'.$ii, $solnombre);
                    $objPHPExcel->getActiveSheet()->setCellValue('B'.$ii, $solapelli);
                    $objPHPExcel->getActiveSheet()->setCellValue('C'.$ii, $solempresa);
                    $objPHPExcel->getActiveSheet()->setCellValue('D'.$ii, $solcorreo);
                    $objPHPExcel->getActiveSheet()->setCellValue('E'.$ii, $dstempresa);
                    
                
                    
            }


    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="ExportarLikes.xlsx"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    //Descarga de archivo
    $objWriter->save('php://output');


    sql_close($conn);
    ?>