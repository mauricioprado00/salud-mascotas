<?
abstract class Core_Http_Post{
	public static function hasParameters(){
		return(isset($_POST) && is_array($_POST) && count($_POST));
	}
	public static function replaceParameters($new=null){
		if(is_object($new)){
			if(is_a($new, 'Core_Object')){
				$_POST = $new->getData();
			}
			else{
				$arr = array();
				foreach($new as $key=>$value){
					$arr[$key] = $value;
				}
				$_POST = $arr;
			}
		}
		elseif(is_array($new)){
			$_POST = $new;
		}
		else 
			$_POST = null;
	}
	public static function getParameters($modo=null, $filter_parameters=null){
		switch(strtolower($modo)){
			case 'array':{
				//$arr_parameters = explode('&', $ret);
				if(!isset($filter_parameters))
					$ret = $_POST;
				else{
					$ret = array();
					foreach($_POST as $var=>$value){
						if(!isset($filter_parameters) || in_array($var, $filter_parameters))
							$ret[$var] = $value;
					}
				}
				break;
			}
			case 'object':{
				//$arr_parameters = explode('&', $ret);
				$ret = new StdClass;
				foreach($_POST as $var=>$value){
					if(!isset($filter_parameters) || in_array($var, $filter_parameters))
						$ret->$var = $value;
				}
				break;
			}
			case 'core_object':{
				//$arr_parameters = explode('&', $ret);
				$ret = new Core_Object;
				foreach($_POST as $var=>$value){
					if(!isset($filter_parameters) || in_array($var, $filter_parameters))
						$ret->setData($var, $value);
				}
				break;
			}
		}
		return($ret);
	}
}
?>