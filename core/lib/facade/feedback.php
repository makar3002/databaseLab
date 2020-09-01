<?php
namespace core\lib\facade;


use core\lib\orm\Entity;
use core\lib\table\FeedbackTable;


class Feedback {
    private Entity $feedbackEntity;

    public function __construct() {
        $this->feedbackEntity = FeedbackTable::getEntity();
    }

    public function sendFeedback(array $fieldValueList): void {
        $fieldValueList['DATE_CREATE'] = null;
        $this->feedbackEntity->add($fieldValueList);
    }

    public function findFeedbacks(array $params) {
        return $this->feedbackEntity->getList($params);
    }

    public function saveFeedback(int $id, array $fieldValueList): void {
        $this->feedbackEntity->update($id, $fieldValueList);
    }

    public function deleteFeedback(int $id): bool {
        return $this->feedbackEntity->deleteByPrimary($id);
    }
}