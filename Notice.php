<?php
namespace Admin\Message;

class Notice extends MessageAbstract
{
    public function __construct($code)
    {
        parent::__construct(Admin\Message\Message::NOTICE, $code);
    }
}
