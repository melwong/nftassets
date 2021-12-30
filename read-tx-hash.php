<?php 
   $str = $_POST['hash'];
   $file = fopen("txhash.txt","w");
   echo fwrite($file, $str);
   fclose($file);
?>