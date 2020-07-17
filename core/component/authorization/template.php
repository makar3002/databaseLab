<?
use core\component\Authorization\AuthorizationComponent;
?>

<? /** @var array $arResult*/?>

<?if ($arResult['IS_AJAX_REQUEST']) :?>
    <?if ($arResult['IS_USER_AUTHORIZED']) :?>
        <a href="/profile" class="btn btn-sm btn-outline-secondary">
            Профиль
        </a>
        <button id="sign-out-btn" class="btn btn-sm btn-outline-secondary" type="button">
            Выйти
        </button>
    <?else :?>
        <button id="sign-in-btn" class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#modalAuthorization" type="button">
            Войти
        </button>
        <button id="sign-up-btn" class="btn btn-sm btn-outline-secondary ml-2" data-toggle="modal" data-target="#modalRegistration" type="button">
            Регистрация
        </button>
        <!-- Authorization Modal -->
        <div class="modal fade" id="modalAuthorization" tabindex="-1" role="dialog" aria-labelledby="modalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title p-0" id="modalLongTitle">Авторизация</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeAuthorizationModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="form-signin" method="post" id="authorizationForm">
                            <div class="text-center mb-4">
                                <p>Авторизуйтесь на сайте, чтобы пользоваться всеми преимуществами сервиса</p>
                            </div>

                            <div class="form-label-group">
                                <input type="email" name="email" id="auth-email" class="form-control sign-in-form-control" placeholder="email" required autofocus>
                                <label for="inputEmail">Почта</label>
                            </div>

                            <div class="form-label-group">
                                <input type="password" name="password" id="auth-password" class="form-control sign-in-form-control " placeholder="password" required>
                                <label for="inputPassword">Пароль</label>
                            </div>

                            <div class="checkbox mb-3">
                                <label>
                                    <input type="checkbox" value="remember-me"> Запомнить меня
                                </label>
                            </div>

                            <button id="sign-in" class="btn btn-lg btn-primary btn-block sign-in-btn" type="submit">Войти</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Registration Modal -->
        <div class="modal fade" id="modalRegistration" tabindex="-1" role="dialog" aria-labelledby="modalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title p-0" id="modalLongTitle">Регистрация</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeRegistrationModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="form-signin" method="post" id="registrationForm">
                            <div class="text-center mb-4">
                                <p>Зарегистрируйтесь на сайте, чтобы пользоваться всеми преимуществами сервиса</p>
                            </div>

                            <div class="form-label-group">
                                <input type="email" name="email" id="reg-email" class="form-control sign-in-form-control" placeholder="email" required autofocus>
                                <label for="inputEmail">Почта</label>
                            </div>

                            <div class="form-label-group">
                                <input type="password" name="password" id="reg-password" class="form-control sign-in-form-control" placeholder="password" required>
                                <label for="inputPassword">Пароль</label>
                            </div>

                            <div class="form-label-group">
                                <input type="password" name="anotherPassword" id="another-reg-password" class="form-control sign-in-form-control" placeholder="password" required>
                                <label for="inputPassword">Повторите пароль</label>
                            </div>

                            <button id="sign-up" class="btn btn-lg btn-primary btn-block sign-in-btn" type="submit">Регистрация</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?endif;?>
<?else :?>
<div id="sign-inupout-buttons">
</div>
<script>
    $(document).ready(function () {
        let authorization = new Authorization("<?=str_replace('\\', '\\\\', AuthorizationComponent::class);?>");
        authorization.initialize();
    });
</script>
<?endif;?>