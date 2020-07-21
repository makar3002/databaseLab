<?php
namespace core\lib\facade;

use core\component\feedbackform\FeedbackFormComponent;
use core\lib\table\FeedbackTable;
use core\util\Request;
use core\util\Validator;

class Feedback
{
    protected const FORM_FIELD_NAME_ALIAS_MAP = array(
        'NAME' => 'name',
        'EMAIL' => 'email',
        'MESSAGE' => 'message',
        'NEED_ANSWER' => 'needAnswer',
        'AGE' => 'age',
        'SEX' => 'sex'
    );

    private $feedbackEntity;
    public function __construct()
    {
        $this->feedbackEntity = FeedbackTable::getEntity();
    }

    public function sendFeedback($fieldValueList)
    {
        $this->validateFeedback($fieldValueList);
        $this->feedbackEntity->add($fieldValueList);
    }

    public function getFeedbackFromRequest()
    {
        $request = Request::getPost();
        var_dump($request);
        $feedback = array();
        foreach (Feedback::FORM_FIELD_NAME_ALIAS_MAP as $formFieldName => $formFieldAlias) {
            if (empty($request[$formFieldAlias])) {
                $feedback[$formFieldName] = '';
                continue;
            }

            $feedback[$formFieldName] = $request[$formFieldAlias];
        }

        return $feedback;
    }

    public function validateFeedback($fieldValueList)
    {
        foreach (Feedback::FORM_FIELD_NAME_ALIAS_MAP as $fieldName => $fieldAlias) {
            if (empty($fieldValueList[$fieldName])) {
                throw new \Exception('Поле ' . $fieldName . ' не заполнено.');
            }

            if ($fieldName === 'EMAIL' && !Validator::checkEmailFormat($fieldValueList[$fieldName])) {
                throw new \Exception('Неправильный формат почтового ящика.');
            }
        }
    }

    public function getEmptyFeedback()
    {
        $emptyFeedback = array();
        foreach (Feedback::FORM_FIELD_NAME_ALIAS_MAP as $fieldName => $fieldAlias) {
            $emptyFeedback[$fieldName] = '';
        }

        return $emptyFeedback;
    }
}