<?php

class Controller
{

	public function model($model){
		if(file_exists('app/models/' . $model . '.php')){
			require_once 'app/models/' . $model . '.php';
			return new $model();
		}else 
			return null;
	}

	protected function view($view, $data = []){
		if(file_exists('app/views/' . $view . '.php')){
			//not sure about _once here...
			require_once 'app/views/' . $view . '.php';
		}
		else
			echo "Can't load view $view: file not found!";
	}

	protected function fromCache($cachefile, $cachetime){
		// TOP of your script
		// Serve from the cache if it is younger than $cachetime
		$cachefile = 'cache/'.$cachefile;
		if (file_exists($cachefile) && (time() - $cachetime < filemtime($cachefile))) {
			include($cachefile);
			echo "<!-- Cached ".date('jS F Y H:i', filemtime($cachefile))." -->";
			return true;
		}
		ob_start(); // start the output buffer
		return false;
	}

	protected function toCache($cachefile){
		$cachefile = 'cache/'.$cachefile;
		// Your normal PHP script and HTML content here
		// BOTTOM of your script
		$this->makePath($cachefile);
		//maybe add file locking...
		$fp = fopen($cachefile, 'w'); // open the cache file for writing
		fwrite($fp, ob_get_contents()); // save the contents of output buffer to the file
		fclose($fp); // close the file
		ob_end_flush(); // Send the output to the browser
	}

	protected function makePath($path){
		$folders = explode('/', $path);
		unset($folders[count($folders)-1]);
		$path = '';
		foreach ($folders as $folder) {
			if ($path!='')
				$path .= '/';
			$path .= $folder;
			if (!is_dir($path))
				mkdir($path);
		}

	}

}
?>