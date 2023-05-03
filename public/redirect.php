<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/20/15
 * Time: 1:26 PM
 */

if (isset($_GET['url'])) {
    $url = $_GET['url'];
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    echo $data;
}