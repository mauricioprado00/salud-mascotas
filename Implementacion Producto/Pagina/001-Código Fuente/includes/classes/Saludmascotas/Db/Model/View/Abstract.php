<?
abstract class Saludmascotas_Db_Model_View_Abstract extends Db_Model_View_Abstract{
	protected function init(){
		parent::init();
		$this->setDB(Saludmascotas_Db::getInstance());
	}
	protected function addTableByModel($model, $join_condition=null, $alias=null, $prefix_field_with_alias=true, $fields=null, $join_method='left'){
		if(!isset($fields)||!is_array($fields)||!count($fields)){
			$fields = $model->getTableColumns();
		}
		if($alias&&$prefix_field_with_alias){
			$new_fields = array();
			foreach($fields as $field){
				$new_fields[$alias.'_'.$field] = $alias.'.'.$field;
			}
			$fields = $new_fields;
		}
		return $this->addTable($model->getDbTableName(), $join_condition, $alias, $fields, $join_method);
	}
	protected function addViewByModel(Db_Model_View_Abstract $view, $alias, $prefix_field_with_alias=true, $fields=array(), $join_condition=null, $join_method='left'){
		if(!isset($fields)||!is_array($fields)||!count($fields)){
			$fields = $view->getTableColumns();
		}
		if($alias&&$prefix_field_with_alias){
			$new_fields = array();
			foreach($fields as $field){
				$new_fields[$alias.'_'.$field] = $alias.'.'.$field;
			}
			$fields = $new_fields;
		}
		return $this->addView($view, $alias, $fields, $join_condition, $join_method);
	}
}
?>