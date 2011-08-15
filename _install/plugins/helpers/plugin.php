<?php

$file=explode(',', $_GET['values']);

foreach ($file as $dir) {
include(trim($dir.'.css'));
}


?>