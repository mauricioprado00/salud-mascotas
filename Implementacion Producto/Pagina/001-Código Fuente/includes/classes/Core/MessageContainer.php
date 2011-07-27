<?
class Core_MessageContainer extends Core_Object{
	const MENSAJE_SUCCESS = 'success';
	const MENSAJE_INFORMACION = 'info';
	const MENSAJE_WARNING = 'warning';
	const MENSAJE_ERROR = 'error';
	private $session_context = null;
	public function __construct($enable_session=false, $session_context=null){
		$session_context = isset($session_context)?$session_context:'default';
		$this->session_context = $session_context;
		$session_messages = Core_Session::getVarMulticontext('session_messages', array(__CLASS__, $session_context));
		$this->setSessionMessages($session_messages);
	}
	function addMessage($message, $to_session=false){
		return($this->addInfoMessage($message, $to_session));
	}
	public function addErrorMessage($message, $to_session=false){
		return($this->_addMessage($message,'error', $to_session));
	}
	public function addSuccessMessage($message, $to_session=false){
		return($this->_addMessage($message,'success', $to_session));
	}
	public function addWarningMessage($message, $to_session=false){
		return($this->_addMessage($message,'warning', $to_session));
	}
	public function addInfoMessage($message, $to_session=false){
		return($this->_addMessage($message,'info', $to_session));
	}
	public function addLightMessage($message, $to_session=false){
		return($this->_addMessage($message,'light', $to_session));
	}
	public function addShieldMessage($message, $to_session=false){
		return($this->_addMessage($message,'shield', $to_session));
	}
	public function addMessageMessage($message, $to_session=false){
		return($this->_addMessage($message,'message', $to_session));
	}
	private function _addMessage($message, $tipo, $to_session=false){
		if($to_session){
			$this->appSessionMessages(array($tipo,$message));
			Core_Session::setVarMulticontext('session_messages', $this->getSessionMessages(), array(__CLASS__, $this->session_context));
		}
		else
			$this->appMessages(array($tipo,$message));
		return($this);
	}
	protected function getAllMessages($session_inclusive=true){
		return $this->getMessages($session_inclusive);
	}
	protected function getErrorMessages($session_inclusive=true){
		return($this->getMessages('error', $session_inclusive));
	}
	protected function getSuccessMessages($session_inclusive=true){
		return($this->getMessages('success', $session_inclusive));
	}
	protected function getWarningMessages($session_inclusive=true){
		return($this->getMessages('warning', $session_inclusive));
	}
	protected function getInfoMessages($session_inclusive=true){
		return($this->getMessages('info', $session_inclusive));
	}
	protected function getLightMessages($session_inclusive=true){
		return($this->getMessages('light', $session_inclusive));
	}
	protected function getShieldMessages($session_inclusive=true){
		return($this->getMessages('shield', $session_inclusive));
	}
	protected function getMessageMessages($session_inclusive=true){
		return($this->getMessages('message', $session_inclusive));
	}
	public function getMessages($tipo=null, $session_inclusive=true, $clear_session_messages_in_object=true, $clear_session_messages_in_session=true){
		if(!$session_inclusive){
			if(!$this->hasMessages())
				return null;
		}
		elseif(!$this->hasMessages() && !$this->hasSessionMessages()){
			return null;
		}
		$ret = array();
		$arr_messages = array();
		
		if($session_inclusive){
			$arr_messages[] = $this->getData('session_messages');
			if($clear_session_messages_in_object)
				$this->unsetData('session_messages');
			if($clear_session_messages_in_session){
				Core_Session::setVarMulticontext('session_messages', null, array(__CLASS__, $this->session_context));
			}	
		}
		$arr_messages[] = $this->getData('messages');
		//var_dump($arr_messages);die(__FILE__.__LINE__);
		//$messages = $this->getData('messages');
		foreach($arr_messages as $messages){
			if(!$messages)continue;
			foreach($messages as $message){
				if($tipo==null||$message[0]==$tipo){
					$m = new Core_Object();
					$m->setMessage($message[1]);
					$m->setTipo($message[0]);
					$m->setIsError($message[0]=='error');
					$m->setIsInfo($message[0]=='info');
					$m->setIsSuccess($message[0]=='info');
					$m->setIsWarning($message[0]=='info');
					$ret[] = $m;
				}
			}
		}
		return($ret);
	}
}
?>