<?php
session_start();

// Generate random CAPTCHA code
$captcha = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);

// Store CAPTCHA in session
$_SESSION['captcha'] = $captcha;

// Generate CAPTCHA image
$im = imagecreatetruecolor(100, 30);
$bg_color = imagecolorallocate($im, 255, 255, 255); // Background color
$text_color = imagecolorallocate($im, 0, 0, 0); // Text color
imagefilledrectangle($im, 0, 0, 100, 30, $bg_color);
imagestring($im, 5, 10, 5, $captcha, $text_color);

// Output image
header('Content-type: image/png');
imagepng($im);
imagedestroy($im);
