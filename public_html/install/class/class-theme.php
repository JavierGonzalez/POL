<?php

class theme{
	private $html;
	private $search = array();
	private $replace = array();
	
	public function add($file){
		$this->html.=file_get_contents(THEME."/".$file.THEME_MIME);
	}

	public function addvar($search, $replace){
		$this->search[]=$search;
		$this->replace[]=$replace;
	}

	public function return_html(){
		$this->process_vars();
		return $this->html;
	}

	private function process_vars(){
		$found=array();
		while(ereg('\{INCLUDE\:([^}]+'.THEME_MIME.')\}',$this->html,$found)){
			if(file_exists(THEME."/".$found[1])){
				$this->html=str_replace(
					$found[0],
					file_get_contents(THEME."/".$found[1]),
					$this->html
				);
			}else{
				$this->html=str_replace($found[0], "", $this->html);
			}
		}
		unset($found);
		$this->html=str_replace($this->search,$this->replace,$this->html);
	}

}//end class

?>
