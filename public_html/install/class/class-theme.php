
class theme{
	private $html;
	private $vars = array();
	
	public function add($file){
		$this->html.=file_get_contents(THEME."/".$file.THEME_MIME);
	}

	public function addvar($variable, $value){
		$this->vars[$variable]=$value;
	}
}
