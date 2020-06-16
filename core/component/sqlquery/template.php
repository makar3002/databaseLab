<?
use Core\Component\nonauthorized\NonAuthorizedComponent;
use Core\Component\profile\ProfileComponent;
use Core\Component\UserList\UserListComponent;
?>

<?if (!isset($arParams['QUERY']) :?>
    <div>
        <form class="form-signin" method="post" id="profileForm">
            <div class="text-center mb-4">
                <p>SQL-запросы</p>
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
    <script>
        $(document).ready(function () {
            let profile = new Profile("<?=str_replace('\\', '\\\\', ProfileComponent::class);?>");
            profile.initialize();
        });
    </script>
<?endif;?>