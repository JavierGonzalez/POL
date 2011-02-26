<?php
class consulta {
	///////////// PROPIEDADES
	public $id; //id en la db
	public $nombre; //titulo de la consulta
	public $descripcion; //enunciado de la consulta
	public $tipo; //sondeo, referendum, parlamento, etc
	public $respuestas[0]; //array de posibles respuestas
	private $votos[0]; //array de usuario y opcion votada
	public $resultados[0]; //array de votos por respuesta
	public $restriccion_tipo; //nivel, autonomia, partido
	public $restriccion; //si hay restriccion, cual
	public $inicio; //hora de inicio de la consulta
	public $duracion; //tiempo que dura la consulta
	public $creador; //id del usuario que creo la consulta
	public $activa; //si la consulta sigue activa o no
	public $votos_visibles //si se deben conocer los resultados al instante
	
	//////////////////// FUNCIONES
	function __construct($id){
		//inicializa todas las propiedades
	}
	function ha_votado($user_id){ //devuelve 1 si el usuario ya ha votado
		if (array_key_exists($user_id, $this->votos)){
			return "1";
		}
		else
		{
		return "0";
		}
	}
	function votar($user_id, $resp){ //añade el voto en el array de votos
		if (($this->ha_votado($user_id == 0) && (in_array($resp, $this->respuestas)) && ($this->activa == 1)){ //si no ha votado, y la respuesta es valida, y la votacion sigue activa
			$votos[$user_id] = $resp;
		}
	}
	
		