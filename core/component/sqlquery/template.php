<?
use Core\Component\nonauthorized\NonAuthorizedComponent;
use Core\Component\profile\ProfileComponent;
use Core\Component\UserList\UserListComponent;
?>

<?if (!isset($arParams['QUERY'])) :?>
    <div>
        <form class="form-signin" method="post" id="queryForm">
            <div class="text-center mb-4">
                <p>SQL-запросы</p>
            </div>

            <div class="form-label-group">
                <input id="query" type="text" name="QUERY" class="form-control" placeholder="Запрос">
                <label for="query">Запрос</label>
            </div>

            <button class="btn btn-primary sign-in-btn" id="execute-query-btn" type="button">Обработать</button>
        </form>
    </div>
    <div id="query-result">

    </div>
    <script>
        $(document).ready(function () {
            let profile = new SqlQuery();
            profile.initialize();
        });
    </script>
<?else :?>
    <pre class="text-left ml-5">
        <?=$arResult['QUERY_RESULT'];?>
    </pre>
<?endif;?>
