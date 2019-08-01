<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/


define("DBDEBUG", 0);

class db {

  private $cursor = NULL;
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
	
	public function sql_num($consulta){
		return $this->consulta($consulta);
	}

	public function sql($consulta){
		if($this->cursor == NULL){
			$this->debug("class db, sql(): Primera llamada");
			if($this->consulta($consulta) > 0){
				return $this->cursor();
			}else{
				return false;
			}
		}else{
			$this->debug("class db, sql(): Siguientes llamadas");
			return $this->cursor();
		}
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
				$this->libera();
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
		//@reset ($this->cursor);
		$this->freeCursor();
	}

	private function freeCursor(){
		$this->cursor = NULL;
	}

	function cursor() {
		$this->cursor=mysql_fetch_array ($this->resultado, MYSQL_ASSOC);
		if($this->cursor == false){
			$this->libera();
			return false;
		}else{
			return $this->cursor;
		}
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
