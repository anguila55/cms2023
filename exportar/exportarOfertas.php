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
    
        $query="SELECT DISTINCT PS.PERNOMBRE AS SOLNOMBRE,PS.PERAPELLI AS SOLAPELLI,PS.PERCOMPAN AS SOLEMPRESA,PS.PERCORREO AS SOLCORREO,
					PD.EXPNOMBRE AS DSTEMPRESA, Q.GAMEFCH AS FECHAEMPRESA
				FROM GAME_PTS Q
				LEFT OUTER JOIN PER_MAEST PS ON PS.PERCODIGO=Q.PERCODIGO
                LEFT OUTER JOIN EXP_MAEST PD ON PD.EXPREG=Q.VALOR
				WHERE PS.PERCODIGO IS NOT NULL AND PD.EXPREG= Q.VALOR AND Q.TIPO=3 $and
				ORDER BY Q.GAMEFCH ";
    $Table = sql_query($query,$conn);
    //logerror($query);
    $objPHPExcel = new PHPExcel();

    // Agregamos las columnas con sus nombres respectivos

    $objPHPExcel->getActiveSheet()
				->setCellValue('A1', 'Nombre Soliticante')
                ->setCellValue('B1', 'Apellido Soliticante')
                ->setCellValue('C1', 'Empresa Solicitante')
                ->setCellValue('D1', 'Correo Solicitante')
                ->setCellValue('E1', 'Nombre Expositor')
                ->setCellValue('F1', 'Fecha');
                
            
            //Titulo de la tabla en negrita
            $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);

            //Recorremos los datos           
            for($i=0; $i<$Table->Rows_Count; $i++){
                    $row = $Table->Rows[$i];
                    $ii = $i+2;
                    
					$solnombre	= trim($row['SOLNOMBRE']);
                    $solapelli	= trim($row['SOLAPELLI']);
                    $solempresa	= trim($row['SOLEMPRESA']);
                    $solcorreo	= trim($row['SOLCORREO']);
                    $dstempresa	= trim($row['DSTEMPRESA']);
                    $fechaempresa	= trim($row['FECHAEMPRESA']);
                    
					
                    
                    //Asignamos a cada celda un valor
                    $objPHPExcel->getActiveSheet()->setCellValue('A'.$ii, $solnombre);
                    $objPHPExcel->getActiveSheet()->setCellValue('B'.$ii, $solapelli);
                    $objPHPExcel->getActiveSheet()->setCellValue('C'.$ii, $solempresa);
                    $objPHPExcel->getActiveSheet()->setCellValue('D'.$ii, $solcorreo);
                    $objPHPExcel->getActiveSheet()->setCellValue('E'.$ii, $dstempresa);
                    $objPHPExcel->getActiveSheet()->setCellValue('F'.$ii, $fechaempresa);
                
                    
            }


    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="ExportarOfertas.xlsx"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    //Descarga de archivo
    $objWriter->save('php://output');


    sql_close($conn);
    ?>