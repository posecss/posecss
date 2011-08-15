<?php

$var = $_GET['values'];
$var = str_replace(', ', ',', $var);
$var = str_replace('= ', '=', $var);
$var = str_replace(' =', '=', $var);
$var=explode(',', $var);


$file=$var[0];

$var1 = str_replace('=', ' =', $var[1]);
$var2 = str_replace('=', ' =', $var[2]);

if (substr_count($var1, 'extras')) {
$extras=explode('=', $var[1]);
$extras=trim($extras[1]);
$columns=explode('=', $var[2]);
$columns=trim($columns[1]);
} else {
$extras=explode('=', $var[2]);
$extras=trim($extras[1]);
$columns=explode('=', $var[1]);
$columns=trim($columns[1]);
}

if ($file=='960gs') {
	if ($columns=='24') {
		include(trim("960gs/960_24_col.css"));
	} else {
		include(trim("960gs/960.css"));
	}
} else if ($file=='blueprint') {
	include(trim("blueprint/blueprint.css"));
	if ($extras=='true') {
		include(trim("blueprint/buttons.css"));
	}
} else if ($file=='bluetrip') {
	include(trim("bluetrip/bluetrip.css"));
	if ($extras=='true') {

	}
} else if ($file=='52framework') {
	include(trim("52framework/grid.css"));
	if ($extras=='true') {
		include(trim("52framework/general.css"));
	}
}