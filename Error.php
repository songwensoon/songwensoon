<?php
namespace Admin\Message;
use Admin\Message\MessageAbstract;
class Error extends MessageAbstract
{
    public function __construct($code)
    {
        parent::__construct(\Admin\Message\Message::ERROR, $code);
    }
}
