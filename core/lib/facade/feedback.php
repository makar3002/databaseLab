<?php
namespace core\lib\facade;

use core\lib\table\FeedbackTable;

class Feedback extends TableInteraction
{
    private $feedbackEntity;
    public function __construct() {
        $this->feedbackEntity = FeedbackTable::getEntity();
        parent::__construct($this->feedbackEntity);
    }

    public function sendFeedback(array $fieldValueList): void {
        $this->feedbackEntity->add($fieldValueList);
    }
}