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
        $and = "AND Q.GAMEFCH > '$desde'";
    }elseif(!$desde && $hasta){
        $and = "AND Q.GAMEFCH < '$hasta'";
    }else{
        $and = "AND Q.GAMEFCH BETWEEN '$desde' AND '$hasta'";
    }

    $conn= sql_conectar();//Apertura de Conexion
    
        $query="SELECT DISTINCT PS.PERNOMBRE AS SOLNOMBRE,PS.PERAPELLI AS SOLAPELLI,PD.PRETITULO AS DSTEMPRESA, Q.GAMEFCH AS FECHAEMPRESA
				FROM GAME_PTS Q
				LEFT OUTER JOIN PER_MAEST PS ON PS.PERCODIGO=Q.PERCODIGO
                LEFT OUTER JOIN PREN_MAEST PD ON PD.PREREG=Q.VALOR
				WHERE PS.PERCODIGO IS NOT NULL AND Q.TIPO=4 AND PD.PREREG= Q.VALOR $and
				ORDER BY Q.GAMEFCH ";
    $Table = sql_query($query,$conn);
    //logerror($query);
    $objPHPExcel = new PHPExcel();

    // Agregamos las columnas con sus nombres respectivos

    $objPHPExcel->getActiveSheet()
				->setCellValue('A1', 'Nombre Soliticante')
                ->setCellValue('B1', 'Apellido Soliticante')
                ->setCellValue('C1', 'Nombre Charla')
                ->setCellValue('D1', 'Fecha');
                
            
            //Titulo de la tabla en negrita
            $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true);

            //Recorremos los datos           
            for($i=0; $i<$Table->Rows_Count; $i++){
                    $row = $Table->Rows[$i];
                    $ii = $i+2;
                    
					$solnombre	= trim($row['SOLNOMBRE']);
                    $solapelli	= trim($row['SOLAPELLI']);
                    $dstempresa	= trim($row['DSTEMPRESA']);
                    $fechaempresa	= trim($row['FECHAEMPRESA']);
                    
					
                    
                    //Asignamos a cada celda un valor
                    $objPHPExcel->getActiveSheet()->setCellValue('A'.$ii, $solnombre);
                    $objPHPExcel->getActiveSheet()->setCellValue('B'.$ii, $solapelli);
                    $objPHPExcel->getActiveSheet()->setCellValue('C'.$ii, $dstempresa);
                    $objPHPExcel->getActiveSheet()->setCellValue('D'.$ii, $fechaempresa);
                
                    
            }


    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="ExportarNotas.xlsx"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    //Descarga de archivo
    $objWriter->save('php://output');


    sql_close($conn);
    ?>