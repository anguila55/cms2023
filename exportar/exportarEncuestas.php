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
    $fechas = (isset($_GET['ID']))? trim($_GET['ID']):''; //Nota desde el home acceso directo

    

    $conn= sql_conectar();//Apertura de Conexion
    
        $query="SELECT PS.PERCODIGO AS PERCODSOL,PS.PERCOMPAN AS SOLEMPRESA,PS.PERNOMBRE AS SOLNOMBRE,PS.PERAPELLI AS SOLAPELLI,PS.PERCORREO AS SOLCORREO,PM.PERCODIGO AS PERCODDST,PM.PERCOMPAN AS DSTEMPRESA,PM.PERNOMBRE AS DSTNOMBRE,PM.PERAPELLI AS DSTAPELLI,PM.PERCORREO AS DSTCORREO, EP.ENCPREGUN AS TIPOEMPRESA, Q.ENCVALRES AS RESPEMPRESA,Q.REUREG AS REUGEMPRESA,PL.PERCODIGO AS CODIGORESPONDIO
				FROM ENC_RESP Q
                LEFT OUTER JOIN REU_CABE Z ON Z.REUREG=Q.REUREG
                LEFT OUTER JOIN PER_MAEST PS ON Z.PERCODSOL=PS.PERCODIGO
                LEFT OUTER JOIN PER_MAEST PM ON Z.PERCODDST=PM.PERCODIGO
                LEFT OUTER JOIN PER_MAEST PL ON PL.PERCODIGO=Q.PERCODIGO
                LEFT OUTER JOIN ENC_PREG EP ON EP.ENCPREITM=Q.ENCPREITM
				WHERE PS.PERCODIGO IS NOT NULL
				ORDER BY Q.ENCREG ";
    $Table = sql_query($query,$conn);
    //logerror($query);
    $objPHPExcel = new PHPExcel();

    // Agregamos las columnas con sus nombres respectivos

    $objPHPExcel->getActiveSheet()
				->setCellValue('A1', 'Nombre Respondio')
                ->setCellValue('B1', 'Apellido Respondio')
                ->setCellValue('C1', 'Empresa Respondio')
                ->setCellValue('D1', 'Correo Respondio')
                ->setCellValue('E1', 'Nombre Contraparte')
                ->setCellValue('F1', 'Apellido Contraparte')
                ->setCellValue('G1', 'Empresa Contraparte')
                ->setCellValue('H1', 'Correo Contraparte')
                ->setCellValue('I1', 'Id Reunion')
                ->setCellValue('J1', 'Pregunta')
                ->setCellValue('K1', 'Respuesta');
                
                
            
            //Titulo de la tabla en negrita
            $objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getFont()->setBold(true);

            //Recorremos los datos           
            for($i=0; $i<$Table->Rows_Count; $i++){
                    $row = $Table->Rows[$i];
                    $ii = $i+2;
                    
                    $percodigosol	= trim($row['PERCODSOL']);
                    $percodigodst	= trim($row['PERCODDST']);
					$solnombre	= trim($row['SOLNOMBRE']);
                    $solapelli	= trim($row['SOLAPELLI']);
                    $solempresa	= trim($row['SOLEMPRESA']);
                    $solcorreo	= trim($row['SOLCORREO']);
                    $dstempresa	= trim($row['DSTEMPRESA']);
					$dstnombre	= trim($row['DSTNOMBRE']);
					$dstapelli	= trim($row['DSTAPELLI']);
					$dstcorreo	= trim($row['DSTCORREO']);
                    $reuregempresa	= trim($row['REUGEMPRESA']);
                    $tipoempresa	= trim($row['TIPOEMPRESA']);
                    $respempresa	= trim($row['RESPEMPRESA']);
                    $percodigorespondio	= trim($row['CODIGORESPONDIO']);

                    if($percodigosol==$percodigorespondio){
                        $nombrerespondio=$solnombre;
                        $apellidorespondio=$solapelli;
                        $empresarespondio=$solempresa;
                        $correorespondio=$solcorreo;
                        $nombrerespondio1=$dstnombre;
                        $apellidorespondio1=$dstapelli;
                        $empresarespondio1=$dstempresa;
                        $correorespondio1=$dstcorreo;
                    }else{
                        $nombrerespondio=$dstnombre;
                        $apellidorespondio=$dstapelli;
                        $empresarespondio=$dstempresa;
                        $correorespondio=$dstcorreo;
                        $nombrerespondio1=$solnombre;
                        $apellidorespondio1=$solapelli;
                        $empresarespondio1=$solempresa;
                        $correorespondio1=$solcorreo;
                    }
                   
                    
					
                    
                    //Asignamos a cada celda un valor
                    $objPHPExcel->getActiveSheet()->setCellValue('A'.$ii, $nombrerespondio);
                    $objPHPExcel->getActiveSheet()->setCellValue('B'.$ii, $apellidorespondio);
                    $objPHPExcel->getActiveSheet()->setCellValue('C'.$ii, $empresarespondio);
                    $objPHPExcel->getActiveSheet()->setCellValue('D'.$ii, $correorespondio);
                    $objPHPExcel->getActiveSheet()->setCellValue('E'.$ii, $nombrerespondio1);
                    $objPHPExcel->getActiveSheet()->setCellValue('F'.$ii, $apellidorespondio1);
                    $objPHPExcel->getActiveSheet()->setCellValue('G'.$ii, $empresarespondio1);
                    $objPHPExcel->getActiveSheet()->setCellValue('H'.$ii, $correorespondio1);
                    $objPHPExcel->getActiveSheet()->setCellValue('I'.$ii, $reuregempresa);
                    $objPHPExcel->getActiveSheet()->setCellValue('J'.$ii, $tipoempresa);
                    $objPHPExcel->getActiveSheet()->setCellValue('K'.$ii, $respempresa);
                    
                
                    
                
                    
            }


    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="ExportarEncuestas.xlsx"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    //Descarga de archivo
    $objWriter->save('php://output');


    sql_close($conn);
    ?>