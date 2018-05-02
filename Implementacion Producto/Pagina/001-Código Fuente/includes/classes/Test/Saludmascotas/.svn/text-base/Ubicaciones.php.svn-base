<?php
class Test_Saludmascotas_Ubicaciones extends Core_Singleton{
	public function getInstance(){
		return self::getInstanceOf(__CLASS__);
	}
	public function testListadoProvincias(){
		Core_Http_Header::ContentType('text/plain');
		$pais = new Saludmascotas_Model_Pais();
		$pais->load(1);
		var_dump($pais, $pais->getListProvincia());
		
	}
}

?>