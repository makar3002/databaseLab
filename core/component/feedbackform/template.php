<?
use core\component\nonauthorized\NonAuthorizedComponent;
?>

<? /** @var array $arResult*/?>

<?if ($arResult['IS_USER_AUTHORIZED']) :?>
    <?$feedback = $arResult['FEEDBACK'];?>
    <div>
        <form class="form-signin article" method="post" id="feedbackForm" action="?action=send">
            <div class="text-center mb-4">
                <h2>Форма обратной связи</h2>
            </div>

            <? if (isset($arResult['RESULT_MESSAGE']) && !empty($arResult['RESULT_MESSAGE'])):?>
                <div class="text-center mb-4">
                    <p class="alert-danger py-1"><?=$arResult['RESULT_MESSAGE']?></p>
                </div>
            <? endif;?>
            <div class="form-label-group">
                <input id="name" type="text" name="name" class="form-control sign-in-form-control" placeholder="ФИО" value="<?=$feedback['NAME']?>" required>
                <label for="name">ФИО</label>
            </div>

            <div class="form-label-group">
                <input id="email" type="email" name="email" class="form-control sign-in-form-control" placeholder="Email" value="<?=$feedback['EMAIL']?>" required>
                <label for="email">Почта</label>
            </div>

                <div class="input-block mx-auto">
                    <p>Пол</p>
                    <div class="checkbox-block">
                        <div>
                            <input id="sex0" type="radio" name="sex" class="custom-radio sign-in-form-control" placeholder="Пол" value="0" <?if ($feedback['SEX'] === 0) echo 'checked';?>>
                            <label for="sex0">Не знаю</label>
                        </div>

                        <div>
                            <input id="sex1" type="radio" name="sex" class="custom-radio sign-in-form-control" placeholder="Пол" value="1" <?if ($feedback['SEX'] === 1) echo 'checked';?>>
                            <label for="sex1">Мужской</label>
                        </div>

                        <div>
                            <input id="sex2" type="radio" name="sex" class="custom-radio sign-in-form-control" placeholder="Пол" value="2" <?if ($feedback['SEX'] === 2) echo 'checked';?>>
                            <label for="sex2">Женский</label>
                        </div>
                    </div>
                </div>

                <div class="input-block">
                    <label for="needAnswer">Требуется ли ответ</label>
                    <input id="needAnswer" type="checkbox" name="needAnswer" class="sign-in-form-control" placeholder="Требуется ли ответ" value="Y" <?if (isset($feedback['SEX']) && $feedback['SEX'] == 'Y') echo 'checked';?>>
                </div>

                <div class="form-label-group">
                    <label for="message">Сообщение</label>
                </div>

                <div class="form-label-group">
                    <textarea id="message" name="message" class="form-control" placeholder="Сообщение" required><?=$feedback['MESSAGE']?></textarea>
                </div>

            <input type="submit" class="btn btn-primary sign-in-btn" value="Отправить">
        </form>
    </div>
<?else :?>
    <?
    $component = new NonAuthorizedComponent(array());
    $component->processComponent()
    ?>
<?endif;?>