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


class pose {

	function __construct($file) {
	
		$this->css = file_get_contents($file); // Read CSS file

		if (file_exists('pose.config.php')) {
			require_once('pose.config.php');
		}
	
	}

	function run() {
		$this->includes();
		$this->plugins();
		$this->whitespace();
		$this->variables();
		$this->mixins();
		$this->clean();
		$this->output();
		return $this->css;
	}


	// --------------------
	// Hande !include files
	// --------------------

	private function includes() {

		$this->css=str_replace('!include :', '!include:', $this->css);

		if (preg_match_all('(!include:.*.;)',$this->css,$include)) {

			// Loop through !include files and get contents
			foreach(array_reverse($include[0]) as $inc){
				$inc_name[]=$inc;
				$inc=str_replace('!include:', '', $inc);
				$inc=str_replace(';', '', $inc);
				$inc=trim($inc);
				$inc_file[]=file_get_contents($inc);
			}
				
			$inc_num=count($inc_name);
			
			for ($i=0; $i<$inc_num; $i++) {
				$this->css=str_replace($inc_name[$i], $inc_file[$i], $this->css);
			}

		}

	} // end include()


	// --------------------
	// Hande !plugin files
	// --------------------
	private function plugins() {

		$this->css=str_replace('!plugin :', '!plugin:', $this->css);

		if (preg_match_all('(!plugin:.*.;)',$this->css,$plugins)) {
		
			$plugin_name = array();
			$plugin_file = array();
		
			foreach(array_reverse($plugins[0]) as $plugin){
				$plugin_name[]=$plugin;
				$plugin=str_replace(' (', '(', $plugin);
				$plugin=str_replace('!plugin:', '', $plugin);
				$plugin=str_replace(';', '', $plugin);
				$plugin=trim($plugin);
				$plugin=explode('(', $plugin);
				$value=str_replace(')', '', $plugin[1]);
				$plugin=str_replace('(', '', $plugin[0]);
			
				$plugin_location="$this->domain/$this->dir/$this->plugins_dir/$plugin/plugin.php?values=$value";
			
				if (file_exists("$this->plugins_dir/$plugin/plugin.php")) {
				$plugin_file[]=file_get_contents(str_replace(' ', '%20', $plugin_location));
//				$plugin_file[]=$plugin_location;
				} else {
				$plugin_file[]="/* Error! The file '$this->plugins_dir/$plugin/plugin.php' could not be found */";
				}
			}
			
			
			$plugin_num=count($plugin_name);
			
			
			for ($i=0; $i<$plugin_num; $i++) {
				$this->css=str_replace($plugin_name[$i], $plugin_file[$i], $this->css);
			}
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

	} // whitespace()


	// -------------------------
	// --- Handle variables ----
	// -------------------------
	private function variables() {
		preg_match_all("(!.*.\{.*)",$this->css,$variable);
	
		foreach ($variable[0] as $match) {
	
			if (!strpos($match, '(')) {
				$match = str_replace('}', '', $match);
				$split = explode('{', $match);
				$name = trim($split[0]).'!';
				$value = trim($split[1]);
				$this->css=str_replace($name,$value,$this->css);

			}
		}
	}

	// ---------------------
	// --- Handle mixins ----
	// ---------------------
	private function mixins() {

		preg_match_all("(!.*.\(.*.\).{.*.}|!.*.\(\).{.*.})",$this->css,$variable);
		
		foreach ($variable[0] as $match) {
		
			$name = explode('{', $match);
			$full_mixin=$name[0]; // //  mixin name + variables
			$mixin_value = str_replace('}', '', $name[1]); // value of mixin
			$split = explode('(', $full_mixin); // split mixin & get name & varaiables
			$variables=str_replace(')', '', $split[1]); // comma separated variables
			$mixin_variables=explode(',', $variables); // array of values
			$mixin = $split[0]; // name of mixin
		
			// ------------------------------
			// --- Mixins with variables ----
			// ------------------------------
			preg_match_all("($mixin\(.*.\).;*.)",$this->css,$mixins);
			
			unset($mixins[0][0]);
			
			foreach ($mixins[0] as $m) {
				$m=str_replace(') ;', ')', $m);
				$m=str_replace(');', ')', $m);
				$m=explode(')', $m);
				$match_name = $m[0].')'; // name of match
				$m=explode('(', $m[0]); // split at ( to get variables
				$m=$m[1]; // comma separated match variables
				$match_variables=explode(',', $m); // match variables as array
			
				$final_value=$mixin_value;
			
				for ($i=0; $i<count($match_variables); $i++) {
					$final_value=str_replace(trim($mixin_variables[$i]),trim($match_variables[$i]),$final_value);
				}
		
				$this->css=str_replace($match_name,$final_value,$this->css);
		
			}
			
			// ---------------------------------
			// --- Mixins without variables ----
			// ---------------------------------
			if (count($mixins[0])===0) {
			
			preg_match_all("($mixin*.\()",$this->css,$mixins);
				
			foreach ($mixins[0] as $m) {
				$m=str_replace(') ;', ');', $m);
				$m=explode(');', $m);
				$match_name = $m[0].');'; // name of match
				$m=explode('(', $m[0]); // split at ( to get variables
				$m=$m[1]; // comma separated match variables
				$match_variables=explode(',', $m); // match variables as array
			
				$final_value=$mixin_value;
			
				for ($i=0; $i<count($match_variables); $i++) {
					$final_value=str_replace(trim($mixin_variables[$i]),trim($match_variables[$i]),$final_value);
				}
		
				$this->css=str_replace($match_name,$final_value,$this->css);
				
			}
			
			}
			
		} // End of mixins

	}


	private function clean() {

		// ---------------------------------
		// --- Remove definition blocks ----
		// ---------------------------------
		$this->css = preg_replace('!<define>.*?</define>!s', '', $this->css);
		$this->css=str_replace("[tab];", ";", $this->css);
		$this->css=str_replace("[newline];", ";", $this->css);
		$this->css=str_replace(";;", ";", $this->css);

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


if (isset($_GET['css'])) {

	if (file_exists($_GET['css'])) {
		$pose = new pose($_GET['css']);
		echo $pose->run();
	} else {
		header("Content-type: text/css; charset=$pose_charset");
		header('Pragma: no-cache');
		echo "/* No input file was specified. Pose has nothing to process */";
		exit;
	}

} else {
	header("Content-type: text/css; charset=$pose_charset");
	header('Pragma: no-cache');
	echo "/* No input file was specified. Pose has nothing to process */";
	exit;
}

// ------------------------
// --- End of pose.php ----
// ------------------------