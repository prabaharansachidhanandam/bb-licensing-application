<?php
// The message
$subject = 'User login to electritex.com';
$message = $_POST['message'];

// In case any of our lines are larger than 70 characters, we should use wordwrap()
//$message = wordwrap($message, 70, "\r\n");

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <prabaharan@bigger-brains.com>' . "\r\n";
$headers .= 'Cc: chip@bigger-brains.com' . "\r\n";

// Send
mail('andersonoffice@electritex.com', $subject , $message, $headers);
?>