<?php

define("DBDEBUG", 0);

class db {

  var $cursor;
  var $resultado;
  var $conexion;
  private $lastRes = NULL;
  private $lastError = NULL;
  private $Errno = NULL;
  private $query;

	function __construct($link=NULL) {
		if($link == NULL){ //add compatibility to old virtualpol DB way
			$link=conectar();
		}
		if (!$link){
			$this->debug("class db, db(): Error al conectar con la DB ".mysql_error(),1);
		}else{
			$this->conexion=$link;
			$this->debug("class db, db(): Conexión a DB establecida");
		}
	}


	public function print_query(){
		echo $this->query;
	}
	
	public function getQuery(){ return $this->query; }

	public function consult($consulta){
		return $this->consulta($consulta);
	}

	function consulta($consulta){
		$this->query=$consulta;
		$this->debug("class db, consulta(): ".$consulta);
		$this->libera();
		$this->resultado = mysql_query ($consulta, $this->conexion);
		if ($this->resultado == false) {
			$this->debug("class db, consulta(): Error en la consulta (".$this->query.") ".mysql_errno($this->conexion)." : ".mysql_error($this->conexion),1);
			$res = false;
		}else{
			if (strtolower (substr (ltrim ($consulta), 0, 6)) == 'select') {
				$res=mysql_num_rows ($this->resultado);
			}else{
				$res=@mysql_affected_rows ($this->resultado);
			}
		}
		$this->lastRes=$res;
		return $res;
	}


	private function conexion() {
		return $this->conexion;
	}

	public function resultado() {
		return $this->resultado;
	}

	public function libera () {
		@mysql_free_result($this->resultado);
		@reset ($this->cursor);
	}

	function cursor() {
		$this->cursor=mysql_fetch_array ($this->resultado, MYSQL_ASSOC);
		return $this->cursor;
	}

	function cursorn() {
		$this->cursor=mysql_fetch_array ($this->resultado, MYSQL_NUM);
		return $this->cursor;
	}


	private function debug($debugtext, $type=0){
		if(DBDEBUG == 1){
			if($type==1){
				$this->lastError=mysql_error();
				$this->Errno=mysql_errno($this->conexion);
			}
			echo "<!-- $debugtext -->\n";
		}
	}
	
	public function getLastError(){ return $this->lastError; }
	public function getLastRes(){ return $this->lastRes; }
	public function getErrno(){ return $this->Errno; }

	function desconectar() {
		@mysql_close($this->conexion);
	}

	function __destruct() {
		$this->debug("class db, __destruct(): Desconectamos DB.");
		$this->desconectar();
	}
}
?>
