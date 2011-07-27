<?php /*es útf8*/
class Frontend_Router_Location extends Frontend_Router_Abstract{
	protected function initialize(){
		$this->addActions(
			'searchProvincia',
			'searchLocalidad',
			'searchBarrio'
		);
	}
	protected function localDispatch(){
	}
	protected function searchProvincia(){
		$post = Core_Http_Post::getParameters('Core_Object');
		$resultados = array();
		$pais = new Saludmascotas_Model_Pais();
		$pais->setId($post->getText());
		if($pais->load()){
			$provincias = $pais->getListProvincia();
			foreach($provincias as $provincia){
				$resultados[] = $provincia->getNombre();
			}
		}
		echo json_encode(array(
			'resultados'=>$resultados,
		));
		die();
		return true;
		die(__FILE__.__LINE__);
	}
	protected function searchLocalidad(){
		
		
		$post = Core_Http_Post::getParameters('Core_Object');
		$resultados = array();
		$searcher = new Saludmascotas_Model_View_LocalidadProvinciaPais();
		$searcher->setProvincia($post->getText());
		if($post->hasPais()&&$post->getPais()){
			$searcher->setIdPais($post->getPais());
		}
		$searcher->setWhere(Db_Helper::equal('id_pais'), Db_Helper::equal('provincia'));
		
		//var_dump($searcher->searchGetSql());
		$res = $searcher->search();
		if($res){
			foreach($res as $entity){
				$resultados[] = $entity->getNombre();
			}
		}
		echo json_encode(array(
			'resultados'=>$resultados,
		));
		die();

		
		$post = Core_Http_Post::getParameters('Core_Object');
		$resultados = array();
		$pais = new Saludmascotas_Model_Provincia();
		$pais->setNombre($post->getText());
		if($pais->load()){
			$localidads = $pais->getListLocalidad();
			foreach($localidads as $localidad){
				$resultados[] = $localidad->getNombre();
			}
		}
		echo json_encode(array(
			'resultados'=>$resultados,
		));
		die();
	}
	protected function searchBarrio(){
		$post = Core_Http_Post::getParameters('Core_Object');
		$resultados = array();
		$searcher = new Saludmascotas_Model_View_BarrioLocalidadProvinciaPais();
		$searcher->setLocalidad($post->getText());
		if($post->hasProvincia()&&$post->getProvincia()){
			$searcher->setProvincia($post->getProvincia());
		}
		if($post->hasPais()&&$post->getPais()){
			$searcher->setIdPais($post->getPais());
		}
		$searcher->setWhere(Db_Helper::equal('id_pais'), Db_Helper::equal('provincia'), Db_Helper::equal('localidad'));
		
		//var_dump($searcher->searchGetSql());
		$res = $searcher->search();
		if($res){
			foreach($res as $entity){
				$resultados[] = $entity->getNombre();
			}
		}
		echo json_encode(array(
			'resultados'=>$resultados,
		));
		die();
	}
	protected function dispatchNode($nodo){//esto es cuando hay algo despues de la url.
		return false;
	}
}
?>