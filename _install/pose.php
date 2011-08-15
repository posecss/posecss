<?php

// --------------------------------------------
// --- Pose CSS v 1.5 [ Fawn ] | file: pose.php
// --- http://posecss.com
// --- Copyright (c) 2010-2011 Jesse Weed | Sublantic
// --- Licensed under the Apache 2.0 License.
// --- http://www.apache.org/licenses/LICENSE-2.0
// ---------------------------------------------

// -----------------------------------------------
// --- There is nothing beyond this point that you
// --- should have any need to edit/change. Please
// --- open pose.config.php for access to the user
// --- editable setting. Checnging anything beyond
// --- this point could break pose. Now you know :)
// -----------------------------------------------

$pose_charset = 'utf-8';

class pose {

	function __construct() {
		
		$this->set_variable_prefix = "@";
		$this->get_variable_prefix = '@';
		$this->variable_postfix = '!';
		$this->function_prefix = '@';
	
		// get contents of css file
		if (@file_get_contents($this->file_name())) :
			$this->css = @file_get_contents($this->file_name()); // Read CSS file
		else :
			$this->css = NULL;
		endif;

		// try to get the pose config file
		if (file_exists('pose.config.php')) :
			require_once('pose.config.php');
			$this->config = TRUE;
		else :
			$this->config = FALSE;
		endif;
	
	}

	function run() {
		
		if ($this->css === NULL) :
			$this->no_file();
		elseif ($this->config === FALSE) :
			$this->no_config();
			$this->variable_override();
			$this->includes();
			$this->plugins();
			$this->whitespace();
			$this->functions();
			$this->variables();
			$this->clean();
			$this->output();
			return $this->css;
		else :
			$this->variable_override();
			$this->variables();
			$this->includes();
			$this->plugins();
			
			if ($this->browser_filter===true) {$this->browser_filter();}
			
			$this->whitespace();
			$this->functions();
			$this->variables();
			$this->clean();
			$this->output();
			return $this->css;	
		endif;
		
		
	}
	
	// ----------------------------
	// Determine name of stylesheet
	// ----------------------------
	function file_name() {
		$uri = explode('.css', $_SERVER['REQUEST_URI']);
		$uri = explode('/',$uri[0]);
		
		if (isset($_GET['css'])) :
		
			$css = $_GET['css'];
		
		elseif (count($uri) > 0) :
			
			$level1 = @$uri[count($uri)-1];	
			$level2 = @$uri[count($uri)-2].'/'.$level1;
			$level3 = @$uri[count($uri)-3].'/'.$level2;
			$level4 = @$uri[count($uri)-4].'/'.$level3;
			$level5 = @$uri[count($uri)-5].'/'.$level4;
			$level6 = @$uri[count($uri)-6].'/'.$level5;
			$level7 = @$uri[count($uri)-7].'/'.$level6;
			$level8 = @$uri[count($uri)-8].'/'.$level7;
			$level9 = @$uri[count($uri)-9].'/'.$level8;
			
			$css = $level1;
			
			if (file_exists($level1.'.css')) : $css = $level1.'.css';
			elseif (file_exists($level2.'.css')) : $css = $level2.'.css';
			elseif (file_exists($level3.'.css')) : $css = $level3.'.css';
			elseif (file_exists($level4.'.css')) : $css = $level4.'.css';
			elseif (file_exists($level5.'.css')) : $css = $level5.'.css';
			elseif (file_exists($level6.'.css')) : $css = $level6.'.css';
			elseif (file_exists($level7.'.css')) : $css = $level7.'.css';
			elseif (file_exists($level8.'.css')) : $css = $level8.'.css';
			elseif (file_exists($level9.'.css')) : $css = $level9.'.css';
			else : $css = NULL;
			endif;			
			
		else :
			$css = NULL;
		endif;
					
		return $css;
		
	}


	// -------------------------------------------
	// What to do if the stylesheet can't be found
	// -------------------------------------------
	function no_file() {
		header("HTTP/1.0 404 Not Found");
		header('Pragma: no-cache');
		exit;	
	}


	// -----------------------------------------
	// What to do if config file can't be loaded
	// -----------------------------------------
	function no_config() {
		$this->dir='css'; // --- The directory where pose has been installed (relative to site root)
		$this->domain=$_SERVER['HTTP_HOST']; // --- The websites domain name
		$this->cache=false; // true to make css cacheable. false to disable cache
		$this->cache_expires = date('D, d M Y H:i:s', strtotime('+30 days')).' GMT'; // Future expires date for cache
		$this->charset='utf-8'; // --- The character encoding you want to use for your css files
		$this->coment_remove=false; // true to remove comments from output false to keep
		$this->credit="/* Unable to find config file, loaidng default settings. Things might get weird. */ \n\n";
		$this->plugins_dir='plugins'; // --- The name & location of the plugin directory
		$this->global_include=false; // To include a file in EVERY css file, add the file name here.
		$this->global_include_location='top'; // Whether to include the file at the top or bottom of you css files
		$this->minify=false; // true to minify your css, false to leave it alone
		$this->save=false; // false to display css in brower. true to save to file instead.
		$this->browser_filter = false; // whether to filter out browser specific styles
		$this->allow_variable_override = true;
		if (!strpos($this->domain, "http://")) : $this->domain = 'http://'.$this->domain; endif;
	} // end no_config



	// -----------------------------
	// Filter unused vendor prefixes
	// -----------------------------
	private function browser_filter() {
		
		if (isset($_GET['browser'])) :

			$browser = strtolower($_GET['browser']);
			
			if ($browser == 'moz' || $browser == 'mozilla' || $browser == 'firefox' || $browser == 'gecko') :
				$this->css = preg_replace("(-ms-.*.;\n|-ms-.*.;\r|-ms-.*.;\t|-ms-.*.;)", '', $this->css);
				$this->css = preg_replace("(-o-.*.;\n|-o-.*.;\r|-o-.*.;\t|-o-.*.;)", '', $this->css);
				$this->css = preg_replace("(-webkit-.*.;\n|-webkit-.*.;\r|-webkit-.*.;\t|-webkit-.*.;)", '', $this->css);
			elseif($browser == 'ms' || $browser == 'ie' || $browser == 'trident') :
				$this->css = preg_replace("(-moz-.*.;\n|-moz-.*.;\r|-moz-.*.;\t|-moz-.*.;)", '', $this->css);
				$this->css = preg_replace("(-o-.*.;\n|-o-.*.;\r|-o-.*.;\t|-o-.*.;)", '', $this->css);
				$this->css = preg_replace("(-webkit-.*.;\n|-webkit-.*.;\r|-webkit-.*.;\t|-webkit-.*.;)", '', $this->css);
			elseif($browser == 'o' || $browser == 'opera' || $browser == 'presto') :
				$this->css = preg_replace("(-moz-.*.;\n|-moz-.*.;\r|-moz-.*.;\t|-moz-.*.;)", '', $this->css);
				$this->css = preg_replace("(-ms-.*.;\n|-ms-.*.;\r|-ms-.*.;\t|-ms-.*.;)", '', $this->css);
				$this->css = preg_replace("(-webkit-.*.;\n|-webkit-.*.;\r|-webkit-.*.;\t|-webkit-.*.;)", '', $this->css);
			elseif($browser == 'webkit' || $browser == 'chrome' || $browser == 'safari') :
				$this->css = preg_replace("(-moz-.*.;\n|-moz-.*.;\r|-moz-.*.;\t|-moz-.*.;)", '', $this->css);
				$this->css = preg_replace("(-ms-.*.;\n|-ms-.*.;\r|-ms-.*.;\t|-ms-.*.;)", '', $this->css);
				$this->css = preg_replace("(-o-.*.;\n|-o-.*.;\r|-o-.*.;\t|-o-.*.;)", '', $this->css);
			endif;
		
		// Should we try to guess the users browser type?
		elseif ($this->browser_guess === true) :
		
			$browser = strtolower($_SERVER['HTTP_USER_AGENT']); // Get user agent data

			// Try to determine browser type
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
			
			// If browser is known
			if ($browser == 'moz' || $browser == 'mozilla' || $browser == 'firefox' || $browser == 'gecko') :
				$this->css = preg_replace("(-ms-.*.;\n|-ms-.*.;\r|-ms-.*.;\t|-ms-.*.;)", '', $this->css);
				$this->css = preg_replace("(-o-.*.;\n|-o-.*.;\r|-o-.*.;\t|-o-.*.;)", '', $this->css);
				$this->css = preg_replace("(-webkit-.*.;\n|-webkit-.*.;\r|-webkit-.*.;\t|-webkit-.*.;)", '', $this->css);
			elseif($browser == 'ms' || $browser == 'ie' || $browser == 'trident') :
				$this->css = preg_replace("(-moz-.*.;\n|-moz-.*.;\r|-moz-.*.;\t|-moz-.*.;)", '', $this->css);
				$this->css = preg_replace("(-o-.*.;\n|-o-.*.;\r|-o-.*.;\t|-o-.*.;)", '', $this->css);
				$this->css = preg_replace("(-webkit-.*.;\n|-webkit-.*.;\r|-webkit-.*.;\t|-webkit-.*.;)", '', $this->css);
			elseif($browser == 'o' || $browser == 'opera' || $browser == 'presto') :
				$this->css = preg_replace("(-moz-.*.;\n|-moz-.*.;\r|-moz-.*.;\t|-moz-.*.;)", '', $this->css);
				$this->css = preg_replace("(-ms-.*.;\n|-ms-.*.;\r|-ms-.*.;\t|-ms-.*.;)", '', $this->css);
				$this->css = preg_replace("(-webkit-.*.;\n|-webkit-.*.;\r|-webkit-.*.;\t|-webkit-.*.;)", '', $this->css);
			elseif($browser == 'webkit' || $browser == 'chrome' || $browser == 'safari') :
				$this->css = preg_replace("(-moz-.*.;\n|-moz-.*.;\r|-moz-.*.;\t|-moz-.*.;)", '', $this->css);
				$this->css = preg_replace("(-ms-.*.;\n|-ms-.*.;\r|-ms-.*.;\t|-ms-.*.;)", '', $this->css);
				$this->css = preg_replace("(-o-.*.;\n|-o-.*.;\r|-o-.*.;\t|-o-.*.;)", '', $this->css);
			endif;
		
		endif;

	} // end browser_filter()

	// --------------------
	// Hande include files
	// --------------------

	private function includes() {

		$this->css=str_replace('include >', 'include>', $this->css);

		if (preg_match_all('(include>.*.;)',$this->css,$include)) :

			// Loop through include files and get contents
			foreach(array_reverse($include[0]) as $inc){
				$inc_name[]=$inc;
				$inc=str_replace('include>', '', $inc);
				$inc=str_replace(';', '', $inc);
				$inc=trim($inc);
				
				if (@file_get_contents($inc)) :
					$inc_file[] = @file_get_contents($inc);
				elseif (@file_get_contents($this->dir.'/'.$inc)) :
					$inc_file[] = @file_get_contents($this->dir.'/'.$inc);
				else :
					$error = '/* ERROR! FAILED TO LOAD "'.
					strtoupper($inc).'" (FILE NOT FOUND). FILE WILL BE SKIPPED. */'."\n";
					$this->css = preg_replace("(include>$inc;|(include> $inc;))", $error, $this->css);
					$inc_file[] = '';
				endif;
				
			}
				
			$inc_num=count($inc_name);
			
			for ($i=0; $i<$inc_num; $i++) {
				$this->css=str_replace($inc_name[$i], $inc_file[$i], $this->css);
			}

		endif;

	} // end includes()


	// --------------------
	// Hande plugins
	// --------------------
	private function plugins() {

		$this->css=str_replace('plugin >', 'plugin>', $this->css);

		if (preg_match_all('(plugin>.*.;)',$this->css,$plugins)) {
			
		
			$plugin_name = array();
			$plugin_file = array();
		
			foreach(array_reverse($plugins[0]) as $plugin){
				$var = $plugin;
				$plugin_name[]=$plugin;
				$plugin=str_replace(' (', '(', $plugin);
				$plugin=str_replace('plugin>', '', $plugin);
				$plugin=str_replace(';', '', $plugin);
				$plugin=trim($plugin);
				$plugin=explode('(', $plugin);
				$value=str_replace(')', '', $plugin[1]);
				$plugin=str_replace('(', '', $plugin[0]);
			
				$plugin_location="$this->domain/$this->dir/$this->plugins_dir/$plugin/plugin.php?values=$value";
				
				$plugin_dir="$this->dir/$this->plugins_dir";				
				
				if ($this->domain == 'yourdomain.com') :
					echo $plugin_location;
					$error = '/* ERROR! COULD NOT LOAD "'.
					strtoupper($plugin).'" PLUGIN. MAKE SURE YOUR DOMAIN IS SET CORRECTLY IN THE CONFIG FILE */'."\n";
					$this->css = str_replace($var, $error, $this->css);
				else :

					if (file_exists("$this->plugins_dir/$plugin/plugin.php")) :
						$plugin_file[]=file_get_contents(str_replace(' ', '%20', $plugin_location));
					else :
						$error = '/* ERROR! COULD NOT LOAD "'.
						strtoupper($plugin).'" PLUGIN. THE DIRECTORY WAS NOT BE FOUND IN "'.$plugin_dir.'"  */'."\n";
					
						$this->css = str_replace($var, $error, $this->css);
					endif;
				
				endif;
			
				
			}
			
			
			$plugin_num=count($plugin_name);
			
			if (count($plugin_file) > 0) :
				for ($i=0; $i<$plugin_num; $i++) {
					$this->css=str_replace($plugin_name[$i], $plugin_file[$i], $this->css);
				}
			endif;
		}

	} // end plugins()


	// ---------------------------
	// --- Handle whitespace ----
	// ---------------------------
	private function whitespace() {

		$this->css=trim($this->css);
		$this->css=str_replace("\r", "", $this->css);
		$this->css=str_replace("\t", "[tab]", $this->css);
		$this->css=str_replace("\n", "[newline]", $this->css);
		$this->css=str_replace("}", "}\n", $this->css);
		$this->dir="$this->dir/";
		$this->dir=str_replace('//', '/', $this->dir);
		$this->domain=str_replace('http://', '', $this->domain).'/';
		$this->domain=str_replace('//', '/', $this->domain);
		$this->domain="http://$this->domain";

	} // end whitespace()


	// ---------------------------------------------------
	// --- Override local variables with $_GET values ----
	// ---------------------------------------------------

	function variable_override() {
	
		if ($this->allow_get_variables === true) :
			foreach ($_GET as $key => $value) {
				//preg_replace("($key.{.*)", '--new--', $this->css);	
				$var = $this->set_variable_prefix.$key;
				$this->css = preg_replace("($var{.*.})", $var.'{'.$value.'}', $this->css);	
			}
	
		endif;

	} // end variable_override()

	// -------------------------
	// --- Handle variables ----
	// -------------------------
	private function variables() {
		
		preg_match_all("($this->set_variable_prefix.*.{.*)",$this->css,$variable);
	
		foreach ($variable[0] as $match) {
	
			if (!strpos($match, '(')) :
				$match = str_replace('}', '', $match);
				$split = explode('{', $match);
				$name = str_replace($this->set_variable_prefix, $this->get_variable_prefix, trim($split[0]).$this->variable_postfix);
				$value = trim($split[1]);
				
				$this->css=str_replace($name,$value,$this->css);

			endif;
		}
	} // end variables()


	// ---------------------
	// --- Handle functions ----
	// ---------------------
	private function functions() {

		preg_match_all("(function.*.\(.*.\).{.*.}|function.*.\(\).{.*.})",$this->css,$variable);
		
		foreach ($variable[0] as $match) {

			$match = str_replace('function', '', $match);
			$match = str_replace('function ', '', $match);
			
			$name = explode('{', $match);
			$full_function=$name[0]; // //  function name + variables
			$function_value = str_replace('}', '', $name[1]); // value of function
			$split = explode('(', $full_function); // split function & get name & varaiables
			$variables=str_replace(')', '', $split[1]); // comma separated variables
			$function_variables=explode(',', $variables); // array of values
			$function = $this->function_prefix.trim($split[0]); // name of function
			
			// ------------------------------
			// --- functions with variables ----
			// ------------------------------
			preg_match_all("($function\(.*.\).*.)",$this->css,$functions);
			
			//print_r($functions);
			
			//unset($functions[0][0]);
			
			//print_r($functions);
			
			foreach ($functions[0] as $m) {
			
				$m=str_replace(') ;', ')', $m);
				$m=str_replace(');', ')', $m);
				$m=explode(')', $m);
				$match_name = $m[0].')'; // name of match
				$m=explode('(', $m[0]); // split at ( to get variables
				$m=$m[1]; // comma separated match variables
				$match_variables=explode(',', $m); // match variables as array
				
				$final_value=$function_value;
				
				
				for ($i=0; $i<count($match_variables); $i++) {
					@$final_value=str_replace(trim($function_variables[$i]),trim($match_variables[$i]),$final_value);
				}
		
				$this->css=str_replace($match_name,$final_value,$this->css);
		
			}
			
			// ---------------------------------
			// --- functions without variables ----
			// ---------------------------------
			if (count($functions[0])===0) {
			
			preg_match_all("($function*.\()",$this->css,$functions);
				
			foreach ($functions[0] as $m) {
				$m=explode(')', $m);
				$match_name = $m[0].')'; // name of match
				$m=explode('(', $m[0]); // split at ( to get variables
				$m=$m[1]; // comma separated match variables
				$match_variables=explode(',', $m); // match variables as array
				$final_value=$function_value;
			
				for ($i=0; $i<count($match_variables); $i++) {
					$final_value=str_replace(trim($function_variables[$i]),trim($match_variables[$i]),$final_value);
				}
		
				$this->css=str_replace($match_name,$final_value,$this->css);
				
			}
			
			}
			
		}

	} // End of functions


	private function clean() {

		// ---------------------------------
		// --- Remove definition blocks ----
		// ---------------------------------
		
		$this->css = preg_replace('!<define>.*?</define>!s', '', $this->css);
		$this->css=str_replace("[tab];", ";", $this->css);
		$this->css=str_replace("[newline];", ";", $this->css);
		$this->css=str_replace(";;", ";", $this->css);
		
			// ------------------------
			// --- Remove comments ----
			// ------------------------
			if ($this->coment_remove===true) {
	//			$this->css=str_replace("*/", "*/ \n", $this->css);
				$this->css=preg_replace('(\/\*.*.\*\/)', '', $this->css);
			}

		if ($this->minify===true) {

			// ------------------------
			// --- Remove comments ----
			// ------------------------
			if ($this->coment_remove==='minify') {
				$this->css=str_replace("*/", "*/ \n", $this->css);
				$this->css=preg_replace('(\/\*.*.\*\/)', '', $this->css);
			}

			$this->css=str_replace(';[newline];[newline]', ";[newline]", $this->css);		
			$this->css=str_replace('[newline]', "", $this->css);
			$this->css=str_replace("\n", "", $this->css);
			$this->css=str_replace(", ", ",", $this->css);
			$this->css=str_replace(" ,", ",", $this->css);
			$this->css=str_replace("} ", "}", $this->css);
			$this->css=str_replace(" }", "}", $this->css);
			$this->css=str_replace("{ ", "{", $this->css);
			$this->css=str_replace(" {", "{", $this->css);
			$this->css=str_replace("( ", "(", $this->css);
			$this->css=str_replace(" (", "(", $this->css);
			$this->css=str_replace(") ", ")", $this->css);
			$this->css=str_replace(" )", ")", $this->css);
			$this->css=str_replace(" :", ":", $this->css);
			$this->css=str_replace(": ", ":", $this->css);
			$this->css=str_replace("; ", ";", $this->css);
			$this->css=str_replace(" ;", ";", $this->css);
			$this->css=str_replace('[tab]', "", $this->css);
			$this->css=str_replace("\t", "", $this->css);
			$this->css=str_replace("::", ":", $this->css);
		} else {			
			$this->css=str_replace(';[newline];[newline]', ";[newline]", $this->css);
			$this->css=str_replace('[newline][newline]', "[newline]", $this->css);
			$this->css=str_replace('[newline][tab][newline]', "[newline]", $this->css);
			$this->css=str_replace('[tab][tab]', "[tab]", $this->css);
			$this->css=str_replace('[newline]', "\n", $this->css);
			$this->css=str_replace('[tab]', "\t", $this->css);
			$this->css=str_replace("\t\t", "\t", $this->css);
		}

	} // end clean()


	// ----------------------------
	// --- Output final styles ----
	// ----------------------------
	private function output () {

		if ($this->credit===false) {
			$this->css=trim($this->css);
		} else {
			$this->css=$this->credit.trim($this->css);
		}
	
	// ---------------------
	// --- Save to file ----
	// ---------------------
	
		if ($this->save===true) {
	
		header("Content-type: text/css; charset=$this->charset");
		header('Pragma: no-cache');
	
		if (file_exists($this->save_location)) {
			$fh = fopen($this->save_location, 'w') or die("/* Could not open $this->save_location */");
			fwrite($fh, $this->css);
			fclose($fh);
			return "/* Success! Your stylesheet has been saved to $this->save_location. */";
			
		} else {
			
			$handle = fopen($this->save_location, 'w') or die("/* Error! The file \"$this->save_location\" does not exists and could not be created */");
			fclose($handle);
	
			if (file_exists($this->save_location)) {
				$fh = fopen($this->save_location, 'w') or die("/* Error! Could not open $this->save_location */");
				fwrite($fh, $this->css);
				fclose($fh);
				return "/* Success! Your stylesheet has been saved to $this->save_location. */";
			}
	
		}
	
	
	// ------------------------
	// --- Send to browser ----
	// ------------------------
		} else {
		
			header("Content-type: text/css; charset=$this->charset");
			
			if ($this->cache===true) {
				header("Expires: $this->cache_expires");
				header('Expires-Active: On');
			} else {
				header('Pragma: no-cache');
			}
		
		}

	} // end output()

}


	$pose = new pose();
	$file = $pose->file_name();	



if ( $file !== NULL ) {	

	echo $pose->run($file);
	
} else {
	header("Content-type: text/css; charset=$pose_charset");
	header('Pragma: no-cache');
	echo "/* No input file was specified. Pose has nothing to process */";
	exit;
}

// ------------------------
// --- End of pose.php ----
// ------------------------