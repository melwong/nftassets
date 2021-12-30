<?php 
	//$site = get_site_url();
	include('qrcode/qrlib.php');
    $param = $_GET['id']; 
    
	//Mel
	//$param2 = $_GET['url']; 
	//$param3 = (isset($_GET['item_id'])?$_GET['item_id']:"0"); 
    
    // remember to sanitize that - it is user input!
    
    // we need to be sure ours script does not output anything!!!
    // otherwise it will break up PNG binary!
    
    //ob_start("callback");
    
    //Assign id value to the codeText variable to be stored in the QR
    $codeText = $param;
    
    // end of processing here
    //$debugLog = ob_get_contents();
    //ob_end_clean();
    
    // outputs image directly into browser, as PNG stream
	// The parameters below basically mean QRcode::png("text to insert in QR", don't save QR as an image file, QR Error Checking Level where L is the worst in checking for error while H is the best, 4 is the zoom level where the bigger the number the larger the QR code size is, last parameter is 1 where the bigger the number the larger the margin around the code (default = 4)
    //Mel: 20/07/19.
	QRcode::png($codeText, false, QR_ECLEVEL_L, 4, 4);
	//QRcode::png($codeText, false, QR_ECLEVEL_H, 4, 1);