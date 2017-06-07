$(document).ready(function () {

    $(window).on('popstate', function () {
        wk.loadPageAjax(window.history.state, true);
    });
    /* wczytanie strony do contentu za pomoca ajaxa */
    $(document).on('click', '.loadPageAjax', function (event) {
        event.preventDefault();
        wk.loadPageAjax($(this).attr('href'));
    });

    $(document).on('click', '#registerFormSubmit', function (event) {
        event.preventDefault();
        var input = getFormData('#registerForm');
        wk.ajax('/register/register-user', input);
    });

});