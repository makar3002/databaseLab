<?php
namespace core\component\feedbackform;


use core\component\general\BaseComponent;
use core\lib\facade\Feedback;
use core\util\Request;
use core\util\User;


class FeedbackFormComponent extends BaseComponent
{
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
        $feedback = $this->feedbackInstance->getFeedbackFromRequest();
        if (!isset($request['action']) || empty($request['action'])) {
            $this->arResult['FEEDBACK'] = $this->feedbackInstance->getEmptyFeedback();
        } elseif ($request['action'] === 'send') {
            try {
                $this->feedbackInstance->sendFeedback($feedback);
                $this->arResult['FEEDBACK'] = $this->feedbackInstance->getEmptyFeedback();
                $this->arResult['RESULT_MESSAGE'] = 'Форма успешно отправлена.';
            } catch (\Exception $e) {
                $this->arResult['FEEDBACK'] = $this->feedbackInstance->getFeedbackFromRequest();
                $this->arResult['RESULT_MESSAGE'] = $e->getMessage();
            }
        } elseif ($request['action'] === 'clean') {
            $this->arResult['FEEDBACK'] = $this->feedbackInstance->getEmptyFeedback();
            $this->arResult['RESULT_MESSAGE'] = 'Форма успешно очищена.';
        } else {
            $this->arResult['FEEDBACK'] = $this->feedbackInstance->getFeedbackFromRequest();
        }
        $this->renderComponent();
    }

    protected function getFeedbackFromRequest()
    {
        return $this->feedbackInstance->getFeedbackFromRequest();
    }
}
