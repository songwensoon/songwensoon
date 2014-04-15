<?php
namespace Admin\Message;
use Zend\Session\Container;
use Admin\Message\Error;
use Admin\Message\Warning;
use Admin\Message\Success;
use Admin\Message\Notice;
class Message
{
    const ERROR     = 'error';
    const WARNING   = 'warning';
    const NOTICE    = 'notice';
    const SUCCESS   = 'success';

    protected $_messages;
    protected $_messagesFirstLevelTagName = 'ul';
    protected $_messagesSecondLevelTagName = 'li';
    protected $_messagesContentWrapperTagName = 'span';
    protected $_escapeMessageFlag = false;
    
    protected function _factory($code, $type, $class='', $method='')
    {
        switch (strtolower($type)) {
            case self::ERROR :
                $message = new Error($code);
                break;
            case self::WARNING :
                $message = new Warning($code);
                break;
            case self::SUCCESS :
                $message = new Success($code);
                break;
            default:
                $message = new Notice($code);
                break;
        }
        $message->setClass($class);
        $message->setMethod($method);
        $this->addMessage($message);
		$msg = $this->getGroupedHtml();
		$this->setMessage($msg,$class, $method);
        return $message;
    }
    
	public function showMessage($class='', $method='')
	{
		$session = new Container('message');
		$key = 'message'.$class.$method;
		$message = $session->offsetGet($key);
		$session->offsetUnset($key);
		echo $message;
	}
	public function setMessage($message,$class='', $method='')
	{
		$session = new Container('message');
		$key = 'message'.$class.$method;
		$session->offsetSet($key,$message);
	}
    public function addError($code, $class='', $method='')
    {
        return $this->_factory($code, self::ERROR, $class, $method);
    }

    public function addWarning($code, $class='', $method='')
    {
        return $this->_factory($code, self::WARNING, $class, $method);
    }

    public function addNotice($code, $class='', $method='')
    {
        return $this->_factory($code, self::NOTICE, $class, $method);
    }

    public function addSuccess($code, $class='', $method='')
    {
        return $this->_factory($code, self::SUCCESS, $class, $method);
    }

	/**
     * Adding new message to collection
     *
     * @param   Mage_Core_Model_Message_Abstract $message
     * @return  Mage_Core_Model_Message_Collection
     */
    public function addMessage(\Admin\Message\MessageAbstract $message)
    {
        if (!isset($this->_messages[$message->getType()])) {
            $this->_messages[$message->getType()] = array();
        }
        $this->_messages[$message->getType()][] = $message;
        $this->_lastAddedMessage = $message;
        return $this;
    }

	/**
     * Retrieve messages in HTML format
     *
     * @param   string $type
     * @return  string
     */
    public function getHtml($type=null)
    {
        $html = '<' . $this->_messagesFirstLevelTagName . ' id="admin_messages">';
        foreach ($this->getMessages($type) as $message) {
            $html.= '<' . $this->_messagesSecondLevelTagName . ' class="'.$message->getType().'-msg">'
                . ($this->_escapeMessageFlag) ? $this->escapeHtml($message->getText()) : $message->getText()
                . '</' . $this->_messagesSecondLevelTagName . '>';
        }
        $html .= '</' . $this->_messagesFirstLevelTagName . '>';
        return $html;
    }
	 /**
     * Escape html entities
     *
     * @param   mixed $data
     * @param   array $allowedTags
     * @return  mixed
     */
    public function escapeHtml($data, $allowedTags = null)
    {
        if (is_array($data)) {
            $result = array();
            foreach ($data as $item) {
                $result[] = $this->escapeHtml($item);
            }
        } else {
            // process single item
            if (strlen($data)) {
                if (is_array($allowedTags) and !empty($allowedTags)) {
                    $allowed = implode('|', $allowedTags);
                    $result = preg_replace('/<([\/\s\r\n]*)(' . $allowed . ')([\/\s\r\n]*)>/si', '##$1$2$3##', $data);
                    $result = htmlspecialchars($result, ENT_COMPAT, 'UTF-8', false);
                    $result = preg_replace('/##([\/\s\r\n]*)(' . $allowed . ')([\/\s\r\n]*)##/si', '<$1$2$3>', $result);
                } else {
                    $result = htmlspecialchars($data, ENT_COMPAT, 'UTF-8', false);
                }
            } else {
                $result = $data;
            }
        }
        return $result;
    }
    /**
     * Retrieve messages in HTML format grouped by type
     *
     * @param   string $type
     * @return  string
     */
    public function getGroupedHtml()
    {
        $types = array(
            self::ERROR,
            self::WARNING,
            self::NOTICE,
            self::SUCCESS
        );
        $html = '';
        foreach ($types as $type) {
            if ( $messages = $this->getMessages($type) ) {
                if ( !$html ) {
                    $html .= '<' . $this->_messagesFirstLevelTagName . ' class="messages" style="list-style-type:none">';
                }
                $html .= '<' . $this->_messagesSecondLevelTagName . ' style="list-style-type:none" class="alert alert-' . $type . '">';
                $html .= '<' . $this->_messagesFirstLevelTagName . ' style="list-style-type:none">';

                foreach ( $messages as $message ) {
                    $html.= '<' . $this->_messagesSecondLevelTagName . ' style="list-style-type:none">';
                    $html.= '<' . $this->_messagesContentWrapperTagName . '>';
                    $html.= ($this->_escapeMessageFlag) ? $this->escapeHtml($message->getText()) : $message->getText();
                    $html.= '</' . $this->_messagesContentWrapperTagName . '>';
                    $html.= '</' . $this->_messagesSecondLevelTagName . '>';
                }
                $html .= '</' . $this->_messagesFirstLevelTagName . '>';
                $html .= '</' . $this->_messagesSecondLevelTagName . '>';
            }
        }
        if ( $html) {
            $html .= '</' . $this->_messagesFirstLevelTagName . '>';
        }
		$html .= "<script>
			setTimeout(function() { $('.messages').fadeOut('slow'); }, 5000);
		</script>";
        return $html;
    }

	/**
     * Retrieve messages collection items
     *
     * @param   string $type
     * @return  array
     */
    public function getMessages($type=null)
    {
        if ($type) {
            return isset($this->_messages[$type]) ? $this->_messages[$type] : array();
        }

        $arrRes = array();
        foreach ($this->_messages as $messageType => $messages) {
            $arrRes = array_merge($arrRes, $messages);
        }
        return $arrRes;
    }
}
