<?php
class SESSION {
	private $json = array();
	private $ssessionid = '';
	private $remoteip = '';
	private $errores = array();
	private $msg = '';
	private $ruta_sesiones = './sesiones/';
	private $vida_sesion = '300';
	
	function __construct($json) {
		$this->json = json_decode($json, 1);
		$this->remoteip = $_SERVER['REMOTE_ADDR'];
		$this->sessionid = $this->json['sessionid'];

		if(isset($this->json['accion'])) {
			$accion = $this->json['accion'];
			if(method_exists($this, $this->json['accion'])) $this->$accion();
		}
		
		$this->limpia_sesiones();
	}
	
	function limpia_sesiones() {
		$files = scandir($this->ruta_sesiones);
		unset($files[0]);
		unset($files[1]);
		foreach($files as $filename) {		
			$file = $this->ruta_sesiones.$filename;
			if((filemtime($file) + $this->vida_sesion) <= time())
				unlink($file);
		}
	}
	
	function error($error, $die = false) {
		$this->errores[] = $error;		
		if($die == true) {
			print_r($this->errores);
			die;
		}
	}
	
	function conexion() {
		if(isset($this->sessionid)) error("Sesion existe", true);

		$this->sessionid = md5($_SERVER['REMOTE_ADDR'].time());
		$this->remoteip = $_SERVER['REMOTE_ADDR'];
		file_put_contents($this->ruta_sesiones.$this->sessionid, $json);

		$this->msg = "conectado";
		$this->responde();
	}
	
	function desconexion() {
		if(file_exists($this->ruta_sesiones.$this->sessionid)) {
			$datos['file'] = "./sesiones/".$this->sessionid;
			unlink("./sesiones/".$this->sessionid);
		}

		$this->msg = "desconectado";
		$this->responde();
	}
	
	function responde() {
		$datos = array(
			'msg' => $this->msg,
			'sessionid' => $this->sessionid,
			'remoteip' => $this->remoteip
		);

		$json = json_encode($datos);
		echo $json;
	}
	
	function lala1() {
		$this->msg = "respuestalala1";
		$this->responde();
	}
}

$s = new SESSION($_REQUEST['json']);
?>
