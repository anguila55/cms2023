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
    
     //fechas
     $fechas = (isset($_GET['ID']))? trim($_GET['ID']):''; //Nota desde el home acceso directo
        
     if($fechas!=''){
         $vaux = explode('||',$fechas);
         if(count($vaux)>1){
             $desde = $vaux[0];
             $hasta = $vaux[1];
         }
     }
     if(!$desde && !$hasta){
         $and = "";
     }
     elseif($desde && !$hasta){
         $and = "AND GAMEFCH > '$desde'";
     }elseif(!$desde && $hasta){
         $and = "AND GAMEFCH < '$hasta'";
     }else{
         $and = "AND GAMEFCH BETWEEN '$desde' AND '$hasta'";
     }

    $conn= sql_conectar();//Apertura de Conexion
    
        $query="SELECT PERCODIGO, GAMEID, TIPO, VALOR, GAMEFCH, PUNTOS
				FROM GAME_PTS
        WHERE PERCODIGO IS NOT NULL $and
				ORDER BY TIPO ";
    $Table = sql_query($query,$conn);
    //logerror($query);
    $objPHPExcel = new PHPExcel();

    // Agregamos las columnas con sus nombres respectivos

    $objPHPExcel->getActiveSheet()
				->setCellValue('A1', 'Codigo')
                ->setCellValue('B1', 'Tipo')
                ->setCellValue('C1', 'Valor')
                ->setCellValue('D1', 'Fecha')
                ->setCellValue('E1', 'Puntos');
                
            
            //Titulo de la tabla en negrita
            $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);

            //Recorremos los datos           
            for($i=0; $i<$Table->Rows_Count; $i++){
                    $row = $Table->Rows[$i];
                    $ii = $i+2;
                    
					$codigo	= trim($row['PERCODIGO']);
                    $tipo	= trim($row['TIPO']);
                    $valor	= trim($row['VALOR']);
                    $fecha	= trim($row['GAMEFCH']);
                    $puntos	= trim($row['PUNTOS']);
                    
					
                    
                    //Asignamos a cada celda un valor
                    $objPHPExcel->getActiveSheet()->setCellValue('A'.$ii, $codigo);
                    $objPHPExcel->getActiveSheet()->setCellValue('B'.$ii, $tipo);
                    $objPHPExcel->getActiveSheet()->setCellValue('C'.$ii, $valor);
                    $objPHPExcel->getActiveSheet()->setCellValue('D'.$ii, $fecha);
                    $objPHPExcel->getActiveSheet()->setCellValue('E'.$ii, $puntos);
                
                    
            }


    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="ExportarGaming.xlsx"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    //Descarga de archivo
    $objWriter->save('php://output');


    sql_close($conn);
    ?>