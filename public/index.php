<?php

$valid = true;
$error = '';

if ( ! version_compare( PHP_VERSION, '5.6.4', '>=' ) ) {
    $error .= "<strong>ERROR:</strong> PHP 5.6.4 or higher is required.<br />";
    $valid = false;
}
if ( ! extension_loaded( 'mbstring' ) ) {
    $error .= "<strong>ERROR:</strong> The requested PHP extension mbstring is missing from your system.<br />";
    $valid = false;
}

if ( ! empty( ini_get( 'open_basedir' ) ) ) {
    $error .= "<strong>ERROR:</strong> Please disable the <strong>open_basedir</strong> setting to continue.<br />";
    $valid = false;
}

if ( ! $valid ) {
    echo '<pre>';
    echo $error;
    echo '</pre>';
    exit();
}

require 'main.php';
