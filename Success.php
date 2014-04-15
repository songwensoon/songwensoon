<?php
namespace Admin\Message;
use Admin\Message\MessageAbstract;
class Success extends MessageAbstract
{
    public function __construct($code)
    {
        parent::__construct(\Admin\Message\Message::SUCCESS, $code);
    }
}
