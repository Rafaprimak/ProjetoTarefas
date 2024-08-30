<?php

class Tarefa {
	private $id;
	private $id_status;
	private $tarefa;
	private $prazo;
	private $data_cadastrado;
	private $id_categoria;
	private $prioridade;

	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
		return $this;
	}
}

?>