<?php

class theme{
	private $html;
	private $search 	= array();
	private $replace 	= array();
	private $incsearch 	= array();
	private $incfile 	= array();	


	public function putfile($file){
		$this->html.=file_get_contents(THEME."/".$file.THEME_MIME);
	}

	public function addvar($search, $replace){
		$this->search[]=$search;
		$this->replace[]=$replace;
	}

	public function concvar($search, $replace){
		$key = array_search($search, $this->search);
		if($key === FALSE){
			$this->addvar($search, $replace);
		}else{
			$this->replace[$key].=$replace;
		}
	}

	public function incfile($search, $file){
		$this->incsearch[]=$search;
		$this->incfile[]='{INCLUDE:'.$file.THEME_MIME.'}';
	}

	public function return_html(){
		$this->process_vars();
		return $this->html;
	}

	private function process_vars(){
		// esta funciones es mejorable,
		// quizas se pueda hacer todo de una pasada evitando
		// ciclos y consumo de ram 

		//parseamos includes desde php
		$this->html=str_replace($this->incsearch, $this->incfile, $this->html);

		//parseamos los includes
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
		unset($found); //ayudamos al recolector de basura
		$this->html=str_replace($this->search,$this->replace,$this->html); //Las variables asignadas desde la app

		//ahora las variables del sistema
		$this->html=str_replace("{THEMEPATH}",THEME,$this->html);

		//por ultimo, variables inexistentes, fuera
		$this->html=preg_replace("/\{[^\}]+\}/","",$this->html);
	}

}//end class

?>
