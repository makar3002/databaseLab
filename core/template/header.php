<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Автомобильный гараж</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/core/template/css/style.css">
    <link rel="stylesheet" href="/core/template/css/bootstrap/bootstrap.css">
    <script src="/core/template/js/jquery/jquery-3.4.1.min.js"></script>
    <script src="/core/template/js/util/popper.min.js"></script>
    <script src="/core/template/js/bootstrap/bootstrap.min.js"></script>
    <script src="/core/template/js/util/validator.js"></script>
    <script src="/core/template/js/authorization/authorization.js"></script>
    <script src="/core/template/js/util/ajax.js"></script>
</head>
<body>
<div id="header">
    <header class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 border-bottom page-header">
        <a class="main-label my-0 mr-md-auto font-weight-normal text-light" href = "index.php">ГаражQ</a>
        <nav class="my-2 my-md-0 mx-md-auto">
            <a class="p-2 text-light" href="marks.php">Марки</a>
            <a class="p-2 text-light" href="owners.php">Владельцы</a>
            <a class="p-2 text-light" href="list_of_security.php">Список сторожей</a>
            <a class="p-2 text-light" href="journal.php">Журнал</a>
        </nav>
        <div class="my-md-0 ml-md-auto d-flex align-items-center">
            <div id="sign-inupout-buttons">

            </div>
        </div>
    </header>

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
</div>
<main class="pt-5">