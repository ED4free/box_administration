<?php

function is_connected()
{
  $connected = @fsockopen("www.example.com", 80);
  if ($connected){
    $is_conn = true;
    fclose($connected);
  }else{
    $is_conn = false;
  }
  return $is_conn; 
}
$is_conn = is_connected();
?>
