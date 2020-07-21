<?php
namespace core\component\feedbackform;


use core\component\general\BaseComponent;
use core\lib\facade\Feedback;
use core\util\Request;
use core\lib\facade\User;
use core\util\Validator;


class FeedbackFormComponent extends BaseComponent
{
    protected const FORM_FIELD_NAME_ALIAS_MAP = array(
        'NAME' => 'name',
        'EMAIL' => 'email',
        'MESSAGE' => 'message',
        'NEED_ANSWER' => 'needAnswer',
        'SEX' => 'sex'
    );

    protected $feedbackInstance;
    protected $userInstance;

    public function __construct($arParams)
    {
        parent::__construct($arParams);

        $this->feedbackInstance = new Feedback();
        $this->userInstance = User::getInstance();
    }

    public function processComponent()
    {
        $request = Request::getRequest();
        $this->arResult['IS_USER_AUTHORIZED'] = $this->userInstance->isUserAuthorized();
        $feedback = $this->getFeedbackFromRequest();
        if (!isset($request['action']) || empty($request['action'])) {
            $this->arResult['FEEDBACK'] = $this->getEmptyFeedback();
        } elseif ($request['action'] === 'send') {
            try {
                $preparedFeedback = $this->prepareFeedback($feedback);
                $this->validateFeedback($preparedFeedback);
                $this->feedbackInstance->sendFeedback($preparedFeedback);
                $this->arResult['FEEDBACK'] = $this->getEmptyFeedback();
                $this->arResult['RESULT_MESSAGE'] = 'Форма успешно отправлена.';
            } catch (\Exception $e) {
                $this->arResult['FEEDBACK'] = $this->getFeedbackFromRequest();
                $this->arResult['RESULT_MESSAGE'] = $e->getMessage();
            }
        } elseif ($request['action'] === 'clean') {
            $this->arResult['FEEDBACK'] = $this->getEmptyFeedback();
            $this->arResult['RESULT_MESSAGE'] = 'Форма успешно очищена.';
        } else {
            $this->arResult['FEEDBACK'] = $this->getFeedbackFromRequest();
        }
        $this->renderComponent();
    }

    protected function validateFeedback($fieldValueList)
    {
        foreach (self::FORM_FIELD_NAME_ALIAS_MAP as $fieldName => $fieldAlias) {
            if (!isset($fieldValueList[$fieldName])) {
                throw new \Exception('Поле ' . $fieldName . ' не заполнено.');
            }

            if ($fieldName === 'EMAIL' && !Validator::checkEmailFormat($fieldValueList[$fieldName])) {
                throw new \Exception('Неправильный формат почтового ящика.');
            }
        }
    }

    protected function getFeedbackFromRequest()
    {
        $request = Request::getPost();
        $feedback = array();
        foreach (self::FORM_FIELD_NAME_ALIAS_MAP as $formFieldName => $formFieldAlias) {
            if (empty($request[$formFieldAlias])) {
                $feedback[$formFieldName] = '';
                continue;
            }

            $feedback[$formFieldName] = $request[$formFieldAlias];
        }

        return $feedback;
    }

    protected function prepareFeedback($feedback)
    {
        if (empty($feedback['NEED_ANSWER'])) {
            $feedback['NEED_ANSWER'] = 'N';
        }
        $feedback['SEX'] = intval($feedback['SEX']);
        return $feedback;
    }

    protected function getEmptyFeedback()
    {
        $emptyFeedback = array();
        foreach (self::FORM_FIELD_NAME_ALIAS_MAP as $fieldName => $fieldAlias) {
            $emptyFeedback[$fieldName] = '';
        }

        return $emptyFeedback;
    }
}
