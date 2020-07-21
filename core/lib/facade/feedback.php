<?php
namespace core\lib\facade;

use core\lib\table\FeedbackTable;

class Feedback
{
    private $feedbackEntity;
    public function __construct()
    {
        $this->feedbackEntity = FeedbackTable::getEntity();
    }

    public function sendFeedback($fieldValueList)
    {
        $this->feedbackEntity->add($fieldValueList);
    }
}