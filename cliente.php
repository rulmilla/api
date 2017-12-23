<?php

class API {
	private $usuario = "";
	private $clave = "";
	private $url = "http://1.1.1.1/api";
	private $sessionid = '';
	private $respuesta = array();
	private $json = '';
	private $accion = '';

	function __construct($usuario = null, $clave = null, $url = null) {
		$this->conexion();
	}
	
	function conexion() {
		$this->accion = "conexion";
		$this->envia();
		$this->sessionid = $this->json['sessionid'];
	}
	
	function desconexion() {
		$this->accion = "desconexion";
		$this->envia();
		unset($this->sessionid);
		return $this->json;
	}

	function envia($json = null) {
		$json = array();
		$json['accion'] = $this->accion;
		if($this->sessionid != null) $json['sessionid'] = $this->sessionid;
		//~ print_r($json);
		$postfields = "json=".json_encode($json);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->url."/api.php");

		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
		if($postfields) {
			//~ $headers[] = "Content-Type: application/aesjson-jd; charset=utf-8";
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
			curl_setopt($ch, CURLOPT_HEADER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}
		
		$this->respuesta = array();
		$this->respuesta["text"] = curl_exec($ch);
		$this->respuesta["info"] = curl_getinfo($ch);
		$this->json = json_decode(substr($this->respuesta['text'], strpos($this->respuesta['text'], '{')), 1);
		return $this->json;
		//~ echo "\n<br/>------------------------------------------------<br/>\n";
		//~ print_r($json);
		//~ print_r($respuesta['text']);
		return $this->respuesta;
	}
	
	function accion($accion) {
		$this->accion = $accion;
		return $this->envia();
	}
}

$a = new API();

$r = $a->accion('lala1');
print_r($r);

$r = $a->desconexion();
print_r($r);

?>
