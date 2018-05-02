<?
abstract class Mysql_Db_Model_Table_Abstract extends Db_Model_Table_Abstract{
	protected function translateError($error){
		switch($error->getCode()){
			case 1062:{
				return $this->translateDuplicatedKey($error);
				break;
			}
			case 1146:{
				return $this->translateInexistentTable($error);
				break;
			}
			case 1451:{
				return $this->translateForeignKeyFails($error);
				break;
			}
		}
		return $error->getCode().'- '.$error->getDescription();
	}
	protected function translateForeignKeyFails($error){
		$re = '/Table \'(?P<nombre_tabla>[^\']+)\' doesn\'t exist/';
		$re = '/.*a foreign key constraint fails [(][`](?P<nombre_tabla_destino>.*?)[`], CONSTRAINT [`](?P<nombre_de_la_fk>.*?)[`] FOREIGN KEY (?P<campos_referenciantes>[(].*?[)]) REFERENCES (?P<tabla_referenciada>[`].*?[`]) [(](?P<campos_referenciados>.*?)[)]/';
//			  'w: a foreign key constraint fails  (  `  app_inta/inta_usuario        ` , CONSTRAINT  `  fk_usuario_agencia      `  FOREIGN KEY (`id_agencia`) REFERENCES `inta_agencia` (`id`))';
		$return = 'No de puede completar la operación, existen entidades asociadas';
//		echo Core_Helper::DebugVars($error->getDescription());
		if(preg_match($re, $error->getDescription(), $matches)){
			//$return = $this->translateInexistentTableName($matches['nombre_tabla']);
			//echo Core_Helper::DebugVars($matches);
			$meta = c(new Core_Object())->setData(array(
				'tabla_referenciada'=>$matches['tabla_referenciada'],
				'nombre_tabla_destino'=>$matches['nombre_tabla_destino'],
			));
			$error->setMeta($meta);
			$return = 'No se puede completar la operacion sobre la tabla '.$matches['tabla_referenciada'].' existen entidades asociadas en '.$matches['nombre_tabla_destino'];
		}
		return $return;
	}
	protected function translateInexistentTable($error){
		$re = '/Table \'(?P<nombre_tabla>[^\']+)\' doesn\'t exist/';
		$return = 'Tabla inexistente';
		if(preg_match($re, $error->getDescription(), $matches)){
			$meta = c(new Core_Object())->setData(array(
				'nombre_tabla'=>$matches['nombre_tabla'],
			));
			$error->setMeta($meta);
			$return = $this->translateInexistentTableName($matches['nombre_tabla']);
			if(!$return)
				$return = 'La tabla '.$this->getDb()->nameToString($matches['nombre_tabla']).' no existe';
		}
		return $return;
	}
	protected function translateInexistentTableName($tablename){
		return '';
	}
	protected function getTableKeys(){
		$sql = 'show keys in '.$this->getDb()->nameToString($this->getDbTableName());
		$res = $this->getDb()->exec($sql);
		$keys = new Core_Collection();
		if($res){
			$i = 1;
			while($fila = $this->getDb()->fetchAssociative($res)){
				$fila2 = array();
				foreach($fila as $key=>$value)
					$fila2[strtolower($key)]=$value;
				$keys->addItem(
					c(new Core_Object())
					->setData($fila2)
					->setIndex($i++)
				);
			}
			//var_dump($fila, $item, $item->getTable());
			;
		}
		return $keys;
	}
	protected function getTableKey($key_name=null, $column_name=null, $index=null){
		$keys = $this->getTableKeys();
		if(isset($key_name))
			$keys = $keys->addFilterEq('key_name', $key_name);
		if(isset($column_name))
			$keys = $keys->addFilterEq('column_name', $column_name);
		if(isset($index))
			$keys = $keys->addFilterEq('index', $index);
		if(!$keys->count())
			return null;
		return $keys->getFirst();
	}
	protected function translateDuplicatedKey($error){
		$re = '/Duplicate entry \'(?P<valor>[^\']+)\' for key (?P<nro_campo>[0-9]+)/';
		$return = 'campo duplicado';
		if(preg_match($re, $error->getDescription(), $matches)){
			$return = $this->translateDuplicatedKeyNumber($matches['nro_campo'], $matches['valor']);
			if(!$return){
				$meta = c(new Core_Object())->setData(array(
					'nro_campo'=>$matches['nro_campo'],
					'valor'=>$matches['valor'],
					'key'=>$this->getTableKey(null, null, $matches['nro_campo']),
				));
				$error->setMeta($meta);
				if($meta->getKey()){
					$key_name = ' `'.$meta->getKey()->getKeyName().'` de la columna `'.$meta->getKey()->getColumnName().'`';
				}
				$return = 'La clave Nº'.$matches['nro_campo'].$key_name.' con el valor \''.$matches['valor'].'\' esta duplicado';
			}
		}
		return $return;
	}
	protected function translateDuplicatedKeyNumber($number,$valor){
		return '';
	}
}
?>