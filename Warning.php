<?php
namespace Admin\Message;
class Warning extends MessageAbstract
{
    public function __construct($code)
    {
        parent::__construct(Admin\Message\Message::WARNING, $code);
    }
}
