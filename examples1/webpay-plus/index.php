<?php
if(!isset($_SESSION))  session_start();
include($_SERVER["DOCUMENT_ROOT"].'/func/zglobals.php'); //PRD
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC.'/sigma.php';	
require_once GLBRutaFUNC.'/zdatabase.php';
require_once GLBRutaFUNC.'/zfvarias.php';

require '../../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Inicializamos el objeto Transaction
|--------------------------------------------------------------------------
*/
$commerceCode='597042475150';
$apiKeySecret='2e59270681a688f29f4f56d0dc33e453';
$transaction = (new Transbank\Webpay\WebpayPlus\Transaction)->configureForProduction($commerceCode, $apiKeySecret); 
//$transaction = (new Transbank\Webpay\WebpayPlus\Transaction); 
// Por simplicidad de este ejemplo, este es nuestro "controlador" que define que vamos a hacer dependiendo del parametro ?action= de la URL.
$action = $_GET['action'] ?? null;
if (!$action) {
    exit('Debe indicar la acción a realizar');
}
$percodigo = (isset($_GET['P']))? trim($_GET['P']):0; 
$perclase = (isset($_GET['C']))? trim($_GET['C']):0; 

$tikvalor=0;
$errnum	= '';
if ($percodigo==0 || $perclase==0) {
    exit('Hubo un error con su perfil, reintente logueandose nuevamente...');
}

$tikvalor=$perclase;
/*
|--------------------------------------------------------------------------
| Crear transacción
|--------------------------------------------------------------------------
/ Apenas entramos esta página, con fines demostrativos,
*/

if ($_GET['action'] === 'create') {
    //$createResponse = $transaction->create('buyOrder123', uniqid(), 1500, 'http://digital.expoceres.com.mx/examples/index.php');
	$createResponse = $transaction->create('OrderArquisur'.$percodigo, uniqid(), $tikvalor, 'https://www.arquisur2021.org/examples1/webpay-plus/index.php?action=result&P='.$percodigo.'&C='.$perclase);

    // Acá guardar el token recibido ($createResponse->getToken()) en tu base de datos asociado a la orden o
    // lo que se esté pagando en tu sistema
    file_put_contents('./log_'.date("j.n.Y").$percodigo.'_'.$tikvalor.'.log', $createResponse->getToken(), FILE_APPEND);

    //Redirigimos al formulario de Webpay por GET, enviando a la URL recibida con el token recibido.
    $redirectUrl = $createResponse->getUrl().'?token_ws='.$createResponse->getToken();
   
    header('Location: '.$redirectUrl, true, 302);
    exit;
}
/*
|--------------------------------------------------------------------------
| Confirmar transacción
|--------------------------------------------------------------------------
/ Esto se debería ejecutar cuando el usario finaliza el proceso de pago en el formulario de webpay.
*/
if ($_GET['action'] === 'result') {
    if (userAbortedOnWebpayForm()) {
        
        cancelOrder();
        $errnum = '?E=6002';
        echo "<script>top.window.location = '../../comptickerr".$errnum."'</script>";
        exit;
    }
    if (anErrorOcurredOnWebpayForm()) {
        cancelOrder();
        $errnum = '?E=6003';
        echo "<script>top.window.location = '../../comptickerr".$errnum."'</script>";
        exit;
    }
    if (theUserWasRedirectedBecauseWasIdleFor10MinutesOnWebapayForm()) {
        cancelOrder();
        $errnum = '?E=6004';
        echo "<script>top.window.location = '../../comptickerr".$errnum."'</script>";
        exit;
    }
    //Por último, verificamos que solo tengamos un token_ws. Si no es así, es porque algo extraño ocurre.
    if (!isANormalPaymentFlow()) { // Notar que dice ! al principio.
        cancelOrder();
        $errnum = '?E=6005';
        echo "<script>top.window.location = '../../comptickerr".$errnum."'</script>";
        exit;
    }

    // Acá ya estamos seguros de que tenemos un flujo de pago normal. Si no, habría "muerto" en los checks anteriores.
    $token = $_GET['token_ws'] ?? $_POST['token_ws'] ?? null; // Obtener el token de un flujo normal
    $percodigo = (isset($_GET['P']))? trim($_GET['P']):0; 
    $response = $transaction->commit($token);

   
   
    if ($response->isApproved()) {
        //Si el pago está aprobado (responseCode == 0 && status === 'AUTHORIZED') entonces aprobamos nuestra compra
        // Código para aprobar compra acá
        approveOrder($response,$percodigo);
        
    } else {

        
        cancelOrder();
        $errnum = '?E=6001';
        echo "<script>top.window.location = '../../comptickerr".$errnum."'</script>";
    }

    return;
}

function cancelOrder($response = null)
{
 
    
}

function approveOrder($response,$percodigo)
{
  
    ///CODIGO DE AUTORIZACION
    $valor1=$response->getAuthorizationCode();
    ///ID DE SESION
    $valor2=$response->getSessionId();
    ////CANTIDAD DE CUOTAS
    $valor3=$response->getInstallmentsNumber();
    /////PRECIO DE CUOTAS
    $valor4=$response->getInstallmentsAmount();
    //// PRECIO TOTAL
    $valor5=$response->getAmount();
    //// ULT 4 NRO DE TARJETA
    $valor6=$response->getCardDetail();
    $valor9=$valor6['card_number'];
    //// NRO DE ORDEN DE COMPRA
    $valor7=$response->getBuyOrder();
    //// FECHA DE TRANSACCION
    $valor8=$response->getTransactionDate();
    $percodigo=$percodigo;
    $conn= sql_conectar();//Apertura de Conexion
    $query = " 	INSERT INTO PER_TICK (PERCODIGO,PERTIKREG,TIKCODIGO,TIKTOKEN ,TIKPAYMET,TIKCUOTAS,TIKUSERID,TIKESTCOD,TIKFCHREG,TIKTARJETA,TIKORDEN,TIKMONEDA,TIKDATE,TIKTIPO) 
						VALUES($percodigo,GEN_ID(TIK_REGISTRO,1),$valor5,'$valor1','$valor2',$valor3,$percodigo,1,CURRENT_TIMESTAMP,'$valor9','$valor7','Pesos Chilenos','$valor8',1)";
	$err = sql_execute($query,$conn);
    sql_close($conn);
	

    echo "<script>top.window.location = '../../enviomailtransaccion.php?ID=$percodigo'</script>";

  
    
    
}

function userAbortedOnWebpayForm()
{
    $tokenWs = $_GET['token_ws'] ?? $_POST['token_ws'] ?? null;
    $tbkToken = $_GET['TBK_TOKEN'] ?? $_POST['TBK_TOKEN'] ?? null;
    $ordenCompra = $_GET['TBK_ORDEN_COMPRA'] ?? $_POST['TBK_ORDEN_COMPRA'] ?? null;
    $idSesion = $_GET['TBK_ID_SESION'] ?? $_POST['TBK_ID_SESION'] ?? null;
    
    // Si viene TBK_TOKEN, TBK_ORDEN_COMPRA y TBK_ID_SESION es porque el usuario abortó el pago

    return $tbkToken && $ordenCompra && $idSesion && !$tokenWs;
}

function anErrorOcurredOnWebpayForm()
{
    $tokenWs = $_GET['token_ws'] ?? $_POST['token_ws'] ?? null;
    $tbkToken = $_GET['TBK_TOKEN'] ?? $_POST['TBK_TOKEN'] ?? null;
    $ordenCompra = $_GET['TBK_ORDEN_COMPRA'] ?? $_POST['TBK_ORDEN_COMPRA'] ?? null;
    $idSesion = $_GET['TBK_ID_SESION'] ?? $_POST['TBK_ID_SESION'] ?? null;

    // Si viene token_ws, TBK_TOKEN, TBK_ORDEN_COMPRA y TBK_ID_SESION es porque ocurrió un error en el formulario de pago
    return $tokenWs && $ordenCompra && $idSesion && $tbkToken;
}

function theUserWasRedirectedBecauseWasIdleFor10MinutesOnWebapayForm()
{
    $tokenWs = $_GET['token_ws'] ?? $_POST['token_ws'] ?? null;
    $tbkToken = $_GET['TBK_TOKEN'] ?? $_POST['TBK_TOKEN'] ?? null;
    $ordenCompra = $_GET['TBK_ORDEN_COMPRA'] ?? $_POST['TBK_ORDEN_COMPRA'] ?? null;
    $idSesion = $_GET['TBK_ID_SESION'] ?? $_POST['TBK_ID_SESION'] ?? null;

    // Si viene solo TBK_ORDEN_COMPRA y TBK_ID_SESION es porque el usuario estuvo 10 minutos sin hacer nada en el
    // formulario de pago y se canceló la transacción automáticamente (por timeout)
    return $ordenCompra && $idSesion && !$tokenWs && !$tbkToken;
}

function isANormalPaymentFlow()
{
    $tokenWs = $_GET['token_ws'] ?? $_POST['token_ws'] ?? null;
    $tbkToken = $_GET['TBK_TOKEN'] ?? $_POST['TBK_TOKEN'] ?? null;
    $ordenCompra = $_GET['TBK_ORDEN_COMPRA'] ?? $_POST['TBK_ORDEN_COMPRA'] ?? null;
    $idSesion = $_GET['TBK_ID_SESION'] ?? $_POST['TBK_ID_SESION'] ?? null;

    // Si viene solo token_ws es porque es un flujo de pago normal
    return $tokenWs && !$ordenCompra && !$idSesion && !$tbkToken;
}