<?if ($arResult['IS_USER_AUTHORIZED']) :?>
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
<?endif;?>