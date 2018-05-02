<?php /*es útf8*/
class Frontend_Router_Animal extends Frontend_Router_Abstract{
	protected function initialize(){
		$this->addActions(
			'searchRaza'
		);
	}
	protected function localDispatch(){
	}
	protected function searchRaza(){
		$post = Core_Http_Post::getParameters('Core_Object');
		$resultados = array();
		$resultados[] = 'sin raza';
		$especie = new Saludmascotas_Model_Especie();
		$especie->setId($post->getText());
		if($especie->load()){
			$razas = $especie->getListRaza();
			foreach($razas as $raza){
				$resultados[] = $raza->getNombre();
			}
		}
		echo json_encode(array(
			'resultados'=>$resultados,
		));
		die();
		return true;
		die(__FILE__.__LINE__);
	}
	protected function dispatchNode($nodo){//esto es cuando hay algo despues de la url.
		return false;
	}
}
?>