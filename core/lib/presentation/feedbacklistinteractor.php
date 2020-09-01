<?php
namespace core\lib\presentation;


use core\lib\facade\Feedback;


class FeedbackListInteractor implements TableInteractorCompatible {
    private Feedback $feedbackFacadeInstance;

    public function __construct() {
        $this->feedbackFacadeInstance = new Feedback();
    }

    public function addElement($fieldValueList, $multipleFieldValueList = array()): void {
        $this->feedbackFacadeInstance->sendFeedback($fieldValueList);
    }

    public function getElementInfo($primary): array {
        $feedbackList = $this->feedbackFacadeInstance->findFeedbacks(array(
            'filter' => array('ID' => $primary)
        ));
        return empty($feedbackList) ? array() : $feedbackList[0];
    }

    public function updateElement($primary, $fieldValueList): void {
        $this->feedbackFacadeInstance->saveFeedback($primary, $fieldValueList);
    }

    public function deleteElement($primary): bool {
        return $this->feedbackFacadeInstance->deleteFeedback($primary);
    }

    public function getElementList($sort, $search): array {
        $result = $this->feedbackFacadeInstance->findFeedbacks(array(
            'order' => $sort,
            'search' => $search
        ));
        return $result;
    }
}
