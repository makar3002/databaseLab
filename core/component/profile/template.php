<?

use core\component\feedbacklist\FeedbackListComponent;
use core\component\nonauthorized\NonAuthorizedComponent;
use core\component\profile\ProfileComponent;
use core\component\sqlquery\SqlQueryComponent;
use core\component\userlist\UserListComponent;
?>

<? /** @var array $arResult*/?>

<?if ($arResult['IS_AJAX_REQUEST']) :?>
    <?if ($arResult['IS_USER_AUTHORIZED']) :?>
        <div>
            <form class="form-signin" method="post" id="profileForm">
                <div class="text-center mb-4">
                    <p>Профиль пользователя</p>
                </div>

                <div class="form-label-group">
                    <input id="name" type="text" name="name" class="form-control sign-in-form-control" placeholder="Имя" value="<?=$arResult['NAME']?>">
                    <label for="name">Имя</label>
                </div>

                <div class="form-label-group">
                    <input id="last_name" type="text" name="last_name" class="form-control sign-in-form-control" placeholder="Фамилия" value="<?=$arResult['LAST_NAME']?>">
                    <label for="last_name">Фамилия</label>
                </div>

                <div class="form-label-group">
                    <input id="second_name" type="text" name="second_name" class="form-control sign-in-form-control" placeholder="Отчество" value="<?=$arResult['SECOND_NAME']?>">
                    <label for="second_name">Отчество</label>
                </div>

                <div class="form-label-group">
                    <input id="email" type="email" name="email" class="form-control sign-in-form-control" placeholder="Email" value="<?=$arResult['EMAIL']?>" required>
                    <label for="email">Почта</label>
                </div>

                <div class="form-label-group">
                    <input id="password" type="password" name="password" class="form-control sign-in-form-control" placeholder="Пароль">
                    <label for="password">Пароль</label>
                </div>

                <button class="btn btn-primary sign-in-btn" id="profile-save-btn" type="button">Сохранить</button>
            </form>
        </div>
        <hr>
        <?
        $component = new FeedbackListComponent(array());
        $component->processComponent();
        ?>
        <?if ($arResult['CAN_MANIPULATE_USERS']) :?>
            <hr>
            <?
            $component = new UserListComponent(array());
            $component->processComponent();
            ?>
        <?endif;?>
        <?if ($arResult['CAN_USER_EXECUTE_SQL_QUERY']) :?>
            <hr>
            <?
            $component = new SqlQueryComponent(array());
            $component->processComponent()
            ?>
        <?endif;?>
    <?else :?>
        <?
            $component = new NonAuthorizedComponent(array());
            $component->processComponent()
        ?>
    <?endif;?>

<?else :?>
    <script>
        $(document).ready(function () {
            let profile = new Profile("<?=str_replace('\\', '\\\\', ProfileComponent::class);?>");
            profile.initialize();
        });
    </script>
<?endif;?>