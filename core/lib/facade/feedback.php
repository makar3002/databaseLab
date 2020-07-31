<?php
namespace core\lib\facade;

use core\lib\orm\Entity;
use core\lib\table\FeedbackTable;

class Feedback extends TableInteraction {
    public function sendFeedback(array $fieldValueList): void {
        $fieldValueList['DATE_CREATE'] = null;
        $feedbackEntity = $this->getEntity();
        $feedbackEntity->add($fieldValueList);
    }

    protected function getEntity(): Entity {
        return FeedbackTable::getEntity();
    }
}