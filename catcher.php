

<?php


//echo get_browser();

$browser = strtolower($_SERVER['HTTP_USER_AGENT']);

if (strpos($browser, "webkit") || strpos($browser, "safari") || strpos($browser, "chrome")) :
	$browser = 'webkit';
elseif (strpos($browser, "opera") || strpos($browser, "presto")) :
	$browser = 'opera';
elseif (strpos($browser, "msie") || strpos($browser, "ms") || strpos($browser, "ie")) :
	$browser = 'ie';
elseif (strpos($browser, "firefox") || strpos($browser, "gecko") || strpos($browser, "mozilla")) :
	$browser = 'mozilla';
else :
	$browser = 'unknown';
endif;

echo $browser;




class pose {
	
	
	function __construct() {
	
$this->css = '-moz-border-radius: 100px;
-ms-border-radius: 100px;
-o-border-radius: 100px;
-webkit-border-radius: 100px;
border-radius: 100px;';

	
	}
	
	function whitespace() {

		$this->css=trim($this->css);
		$this->css=str_replace("\r", "", $this->css);
		$this->css=str_replace("\t", "[tab]", $this->css);
		$this->css=str_replace("\n", "[newline]", $this->css);
		$this->css=str_replace("}", "}\n", $this->css);

	} // end whitespace()

	function vendor_filter($browser) {
		
		$this->css = $this->css;
			
		if ($browser == 'moz' || $browser == 'mozilla' || $browser == 'firefox') :
			$this->css = preg_replace("(-ms-.*.;\n|-ms-.*.;\r|-ms-.*.;\t|-ms-.*.;)", '', $this->css); // Filter IE
			$this->css = preg_replace("(-o-.*.;\n|-o-.*.;\r|-o-.*.;\t|-o-.*.;)", '', $this->css); // Filter Opera
			$this->css = preg_replace("(-webkit-.*.;\n|-webkit-.*.;\r|-webkit-.*.;\t|-webkit-.*.;)", '', $this->css); // Filter Webkit
		elseif($browser=='ms') :
			$this->css = preg_replace("(-moz-.*.;\n|-moz-.*.;\r|-moz-.*.;\t|-moz-.*.;)", '', $this->css); // Filter Firefox
			$this->css = preg_replace("(-o-.*.;\n|-o-.*.;\r|-o-.*.;\t|-o-.*.;)", '', $this->css); // Filter Opera
			$this->css = preg_replace("(-webkit-.*.;\n|-webkit-.*.;\r|-webkit-.*.;\t|-webkit-.*.;)", '', $this->css); // Filter Webkit
		elseif($browser=='o') :
			$this->css = preg_replace("(-moz-.*.;\n|-moz-.*.;\r|-moz-.*.;\t|-moz-.*.;)", '', $this->css); // Filter Firefox
			$this->css = preg_replace("(-ms-.*.;\n|-ms-.*.;\r|-ms-.*.;\t|-ms-.*.;)", '', $this->css); // Filter IE
			$this->css = preg_replace("(-webkit-.*.;\n|-webkit-.*.;\r|-webkit-.*.;\t|-webkit-.*.;)", '', $this->css); // Filter Webkit
		elseif($browser=='webkit') :
			$this->css = preg_replace("(-moz-.*.;\n|-moz-.*.;\r|-moz-.*.;\t|-moz-.*.;)", '', $this->css); // Filter Firefox
			$this->css = preg_replace("(-ms-.*.;\n|-ms-.*.;\r|-ms-.*.;\t|-ms-.*.;)", '', $this->css); // Filter IE
			$this->css = preg_replace("(-o-.*.;\n|-o-.*.;\r|-o-.*.;\t|-o-.*.;)", '', $this->css); // Filter Opera
		endif;

	}
	
}



$pose = new pose();


echo $pose->vendor_filter('moz');


//$var = $pose->set_variables();



/*
echo $text.'<br>';

echo '<br><br><h3>Output</h3>';

$var = 'foo';

$output = str_replace("!$var!", 'foo', $text);

echo $output;
*/