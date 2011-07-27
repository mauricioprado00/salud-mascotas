<?
class Admin_MedioVerificacion_Block_XmlList extends Jqgrid_Block_XmlList{
	protected function loadData($page, $rows, $sidx, $sord, $comparator){
		/*hay que hacer que consulte la base de datos
			sidx = columna de ordenacion
			sord = orden de ordenacion (asc/desc)
		*/
		//$medio_verificacion = new Inta_Model_MedioVerificacion();
		$medio_verificacion = new Inta_Model_MedioVerificacion();
		$medio_verificacion = new Inta_Model_View_MedioVerificacion();
		if($comparator!=null){
			$medio_verificacion->setWhere($comparator);
		}
		$datos = array();
		$total_items = $medio_verificacion->searchCount();
		$cantidad_paginas = ceil($total_items/$rows);
		
		//aca consultariamos la base de datos
//		$left = $total_items - ($page-1) * $rows;
//		$left = $left>$rows?$rows:$left;
//		for($i=0;$i<$left;$i++){
//			$obj = new Core_Object();
//			$obj->setId($i+($page-1)*$rows)
//				->setUsername('nombre de usuario')
//				->setNombre('nombre')
//				->setApellido('apellido')
//				->setActivo(true)
//				->setPrivilegios(rand(0,100)<50?'Total':'Vista')
//				->setUltimoAcceso('22/12/2008')
//			;
//			$datos[] = $obj;
//		}
		
		//$datos = $medio_verificacion->search(null,'ASC',null,0,true,array('id', 'username', 'nombre', 'apellido', 'activo', 'privilegios', 'ultimo_acceso'));
		$datos = $medio_verificacion->search($sidx,$sord,$rows,$rows*($page-1),get_class($medio_verificacion));
		//echo $medio_verificacion->searchGetSql($sidx,$sord,$rows,$rows*($page-1),get_class($medio_verificacion));
		//aca termina la consulta a la base
		
		
		$this->setPage($page);
		$this->setRecords($total_items);
		$this->setTotal($cantidad_paginas);
		//$this->setDataResult($datos);
		return($datos);
	}
}
?>