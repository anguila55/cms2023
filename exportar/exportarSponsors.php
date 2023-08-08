<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
    require_once GLBRutaFUNC.'/constants.php';
    require_once 'Classes/PHPExcel.php';
	$peradmin = (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';
    //verificamos is es administrador
    if($peradmin!=1){
		header('Location: ../index');
    }
    
    $conn= sql_conectar();//Apertura de Conexion
    
        $query="SELECT DISTINCT Q.EXPNOMBRE AS SOLNOMBRE,P.CATDESCRI AS SOLAPELLI, Q.EXPREG AS SOLEMPRESA
				FROM EXP_MAEST Q
                LEFT OUTER JOIN EXP_CAT P ON P.CATREG=Q.EXPCATEGO
				WHERE Q.EXPREG IS NOT NULL AND Q.ESTCODIGO=1
				ORDER BY Q.EXPNOMBRE ";
    $Table = sql_query($query,$conn);
    //logerror($query);
    $objPHPExcel = new PHPExcel();

    // Agregamos las columnas con sus nombres respectivos

    $objPHPExcel->getActiveSheet()
				->setCellValue('A1', 'Nombre Sponsor')
                ->setCellValue('B1', 'Categoria Sponsor')
                ->setCellValue('C1', 'Link Stand');
                
                
            
            //Titulo de la tabla en negrita
            $objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);

            //Recorremos los datos           
            for($i=0; $i<$Table->Rows_Count; $i++){
                    $row = $Table->Rows[$i];
                    $ii = $i+2;
                    
					$solnombre	= trim($row['SOLNOMBRE']);
                    $solapelli	= trim($row['SOLAPELLI']);
                    $solempresa	= trim($row['SOLEMPRESA']);
                    $linkempresa = URL_WEB.'sponsor/bsq?id='.$solempresa;
                    
                    
					
                    
                    //Asignamos a cada celda un valor
                    $objPHPExcel->getActiveSheet()->setCellValue('A'.$ii, $solnombre);
                    $objPHPExcel->getActiveSheet()->setCellValue('B'.$ii, $solapelli);
                    $objPHPExcel->getActiveSheet()->setCellValue('C'.$ii, $linkempresa);

                    
                
                    
            }


    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="ExportarSponsors.xlsx"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    //Descarga de archivo
    $objWriter->save('php://output');


    sql_close($conn);
    ?>