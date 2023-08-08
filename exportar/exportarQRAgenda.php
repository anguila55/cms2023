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
    
    $zip_file= "file/all_qragenda.zip";
    touch($zip_file);

    //open zip file
    $zip = new ZipArchive;
    $this_zip= $zip->open($zip_file);

    if ($this_zip){

        $folder = opendir("./../qrimage");

        if ($folder){

            while (false !== ($image = readdir($folder))){
                
                if ($image !== "." && $image !== ".." && (strpos($image, '_AGE_') !== false)){
                    //var_dump($image);die;
                    
                    $file_with_path= "../qrimage/".$image;
                    $zip->addFile($file_with_path,$image);

                }

            }
            closedir($folder);
            
        }

        if (file_exists($zip_file)){

            $demo_name="qragenda-imagenes.zip";
            header('Content-Description: File Transfer');
            header('Content-type: application/zip');
            header('Content-Disposition: attachment; filename="'.$demo_name.'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
           

            readfile($zip_file);

            //delete zip file after download
           unlink($zip_file);
        }
    }

    ?>