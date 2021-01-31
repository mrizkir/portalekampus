<?php

/**
* untuk menghandle html
*
*/

class HTML {
	/**
	* isi dari file
	*
	*/
	public $content;
	
	public function __construct () {
		
	}	
	
	/**
	* export to file
	*/	
	public function toFile ($file) {		
// 		echo $this->content;
		$f=fopen ($file, 'w');
		if (fwrite ($f,$this->content) === false) {
			echo 'tidak bisa menulis';
		}
	}	
}

?>