<?
class Core_Helper extends Core_Singleton{
	public function getInstance(){
		return self::getInstanceOf(__CLASS__);
	}
	public function LookUpLayout(){
		$bt = debug_backtrace();
		foreach($bt as $bti){
			if($bti['object'] instanceof Base_Layout)
				return $bti['object'];
		}
		return Core_App::getInstance()->getLayout();
	}
	public static function max_execution_time($seg=null,$min=null,$horas=null){
		$min = $min + $horas * 60;
		$seg = $seg + $min * 60;
		return(ini_set('max_execution_time', $seg));
	}
    /**
    Validate an email address.
    Provide email address (raw input)
    Returns true if the email address has the email
    address format and the domain exists.
    */
    public static function emailValido($email){//}, $linuxServer = false) {
    	$windows = (isset($_ENV["OS"])&&preg_match('(windows)',strtolower($_ENV["OS"])));
    	$linuxServer = !$windows;
        $isValid = true;
        $atIndex = strrpos($email, "@");
        if (is_bool($atIndex) && !$atIndex) {
            $isValid = false;
        }
        else {
            $domain = substr($email, $atIndex+1);
            $local = substr($email, 0, $atIndex);
            $localLen = strlen($local);
            $domainLen = strlen($domain);
            if ($localLen < 1 || $localLen > 64) {
            // local part length exceeded
                $isValid = false;
            }
            else if ($domainLen < 1 || $domainLen > 255) {
                // domain part length exceeded
                    $isValid = false;
                }
                else if ($local[0] == '.' || $local[$localLen-1] == '.') {
                    // local part starts or ends with '.'
                        $isValid = false;
                    }
                    else if (preg_match('/\\.\\./', $local)) {
                        // local part has two consecutive dots
                            $isValid = false;
                        }
                        else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
                            // character not valid in domain part
                                $isValid = false;
                            }
                            else if (preg_match('/\\.\\./', $domain)) {
                                // domain part has two consecutive dots
                                    $isValid = false;
                                }
                                else if(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local))) {
                                    // character not valid in local part unless
                                    // local part is quoted
                                        if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local))) {
                                            $isValid = false;
                                        }
                                    }

            if ($linuxServer && $isValid && !(checkdnsrr($domain,"MX") ||  checkdnsrr($domain,"A"))) {
            // domain not found in DNS
                $isValid = false;
            }
        }
        return $isValid;
    }
	public static function LoadValidationTranslation(){
		$translate = new Zend_Translate(
		    array(
		        'adapter' => 'array',
		        'content' => array(
		        	Zend_Validate_Alnum::INVALID  => 'tipo de dato incorrecto en campo %fieldname%',
		        	Zend_Validate_Alnum::NOT_ALNUM  => /*utf8_decode*/('%fieldname% debe contener caracteres alfabéicos o numéricos'),
		        	Zend_Validate_Alnum::STRING_EMPTY  => /*utf8_encode*/('%fieldname% no debe estar vacío'),
		        	
			        Zend_Validate_Alpha::INVALID      => 'tipo de dato incorrecto en campo %fieldname%',
			        Zend_Validate_Alpha::NOT_ALPHA    => /*utf8_decode*/("%fieldname% debe contener caracteres alfabéticos"),
			        Zend_Validate_Alpha::STRING_EMPTY => /*utf8_decode*/('%fieldname% no debe estar vacío'),
		
		        	
		        	Zend_Validate_Between::NOT_BETWEEN        => "%fieldname% debe estar entre '%min%' and '%max%', inclusivamente",
		        	Zend_Validate_Between::NOT_BETWEEN_STRICT => "%fieldname% debe estar entre '%min%' and '%max%'",
		        	
			        Zend_Validate_Date::INVALID        => 'tipo de dato incorrecto en campo %fieldname%',
			        Zend_Validate_Date::INVALID_DATE   => /*utf8_decode*/("'%value%' del campo %fieldname% no es una fecha válida"),
			        Zend_Validate_Date::FALSEFORMAT    => "'%value%' del campo %fieldname% no tiene un formato correcto",
			        
			        Zend_Validate_Digits::NOT_DIGITS   => /*utf8_decode*/("%fieldname% debe contener solo dígitos"),
			        Zend_Validate_Digits::STRING_EMPTY => /*utf8_decode*/("%fieldname% no debe estar vacío"),
			        Zend_Validate_Digits::INVALID      => 'tipo de dato incorrecto en campo %fieldname%',
			        
			        Zend_Validate_EmailAddress::INVALID            => 'tipo de dato incorrecto en campo %fieldname%',
			        Zend_Validate_EmailAddress::INVALID_FORMAT     => /*utf8_decode*/("%fieldname% debe ser un email válida"),
			        Zend_Validate_EmailAddress::INVALID_HOSTNAME   => /*utf8_decode*/("%fieldname% debe ser un email válida"),
			        Zend_Validate_EmailAddress::INVALID_MX_RECORD  => /*utf8_decode*/("%fieldname% debe ser un email válida"),
			        Zend_Validate_EmailAddress::INVALID_SEGMENT    => /*utf8_decode*/("%fieldname% debe ser un email válida"),
			        Zend_Validate_EmailAddress::DOT_ATOM           => /*utf8_decode*/("%fieldname% debe ser un email válida"),
			        Zend_Validate_EmailAddress::QUOTED_STRING      => /*utf8_decode*/("%fieldname% debe ser un email válida"),
			        Zend_Validate_EmailAddress::INVALID_LOCAL_PART => /*utf8_decode*/("%fieldname% debe ser un email válida"),
			        Zend_Validate_EmailAddress::LENGTH_EXCEEDED    => /*utf8_decode*/("%fieldname% debe ser un email válida"),
			        
			        Zend_Validate_Float::INVALID   => 'tipo de dato incorrecto en campo %fieldname%',
			        Zend_Validate_Float::NOT_FLOAT => /*utf8_decode*/("%fieldname% debe ser un número decimal"),
			        
			        Zend_Validate_GreaterThan::NOT_GREATER => "%fieldname% debe ser mayor a '%min%'",
			        
			        Zend_Validate_Hex::INVALID => 'tipo de dato incorrecto en campo %fieldname%',
			        Zend_Validate_Hex::NOT_HEX => /*utf8_decode*/("%fieldname% debe contener un número hexadecimal"),
		
			        Zend_Validate_Identical::NOT_SAME      => "Los campos %fieldname% no coinciden",
			        //Zend_Validate_Identical::MISSING_TOKEN => 'No token was provided to match against',
		
		        	Zend_Validate_InArray::NOT_IN_ARRAY => /*utf8_decode*/("%fieldname% no es una opción válida"),
		        	
			        Zend_Validate_Int::INVALID => 'tipo de dato incorrecto en campo %fieldname%',
			        Zend_Validate_Int::NOT_INT => /*utf8_decode*/("%fieldname% debe ser un número entero"),
		
		        	Zend_Validate_LessThan::NOT_LESS => "%fieldname% debe ser menor a '%max%'",
		        	
		            Zend_Validate_NotEmpty::IS_EMPTY => /*utf8_decode*/('%fieldname% no debe estar vacío'),
		            Zend_Validate_NotEmpty::INVALID => 'tipo de dato incorrecto en campo %fieldname%',
		            
			        Zend_Validate_Regex::INVALID   => 'tipo de dato incorrecto en campo %fieldname%',
			        Zend_Validate_Regex::NOT_MATCH => "%fieldname% no tiene el formato correcto",
			        //Zend_Validate_Regex::ERROROUS  => "There was an internal error while using the pattern '%pattern%'",
			        
			        Zend_Validate_StringLength::INVALID   => 'tipo de dato incorrecto en campo %fieldname%',
			        Zend_Validate_StringLength::TOO_SHORT => "%fieldname% debe tener %min% caracteres como mínimo",
			        Zend_Validate_StringLength::TOO_LONG  => "%fieldname% debe contener %max% caractéres como máximo",
		        ),
		        'locale' => 'es'
		    )
		);
		Zend_Validate::setDefaultTranslator($translate);
	}
    public static function DebugVars(){
		$args = func_get_args();
		$pre = new Core_Html_Tag_Custom('pre');
		$pre
			->setStyle('margin-left: 257px; background-color: white;')
		;
		ob_start();
		if(count($args)==1)$args=array_pop($args);
		var_dump($args);
		$c = ob_get_contents();
		ob_end_clean();
		$c = htmlentities($c);
		$boton = new Core_Html_Tag_Custom('input');
		$boton->setType('button')->setValue('quitar')->setOnclick('javascript:jQuery(this).parents("pre:first").remove();');
		$c = $boton->getHtml() . $c .$boton->getHtml();
		$pre->setInnerHtml($c);
		return $pre->getHtml();
	}
	function Cast($o, $class){ 
		return(unserialize(preg_replace('/O:[0-9]+:"'.get_class($o).'":/', 'O:'.strlen($class).':"'.$class.'":', serialize($o), 1)));
	}

}
?>