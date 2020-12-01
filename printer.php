
    <?php
/* 
            <input type="hidden" name="TERMINAL_ID" value="0000000001">
            <input type="hidden" name="TRANSACTION_ID" value="5101579446">
            <input type="hidden" name="AMOUNT" value="59000.00">
            <input type="hidden" name="DESCRIPTION" value="Akwa Ibom State College of Education school Fee Payment 5">
            <input type="hidden" name="EMAIL" value="yomdoc@gmail.com">
            <input type="hidden" name="CURRENCY_CODE" value="NGN">
            <input type="hidden" name="RESPONSE_URL" value="http://live/v5/epcore/general/Payment/finish.php">
            <input type="hidden" name="CHECKSUM" value="b916f3268e4bc956e22aa9ee68c04aea"> 
            <input type="hidden" name="FULL_NAME" value="Yomi Dorcas Ekpeno"> 
            <input type="hidden" name="LOGO_URL" value="http://live/nacos/Files/UserImages/School/logo.png">
            <input type="hidden" name="PHONENO" value="08066096978">
            
             */
// building array of variables
$content = http_build_query(array(
    'TERMINAL_ID' => '0000000001',
    'TRANSACTION_ID' => '9272263649',
    'AMOUNT' => '59000.00',
    'DESCRIPTION' => 'Akwa Ibom State College of Education school Fee Payment 5',
    'EMAIL' => 'yomdoc@gmail.com',
    'CURRENCY_CODE' => 'NGN',
    'RESPONSE_URL' => 'http://live/v5/epcore/general/Payment/finish.php',
    'CHECKSUM' => '443b28f84fbd7ad09b662099bdf4c590',
    'FULL_NAME' => 'Yomi Dorcas Ekpeno',
    'LOGO_URL' => 'http://live/nacos/Files/UserImages/School/logo.png',
    'PHONENO' => '08066096978'
    ));
// creating the context change POST to GET if that is relevant 
$context = stream_context_create(array(
    'http' => array(
        'method' => 'POST',
        'content' => $content, )));

$result = file_get_contents('https://demo.etranzact.com/webconnect/v3/caller.jsp', null, $context);
//dumping the reuslt
exit($result);

?>
