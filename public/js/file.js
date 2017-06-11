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
        console.log(input);
        wk.ajax('/register/register-user', input, null, null,
                function (data, scope) {
                    // error function
                    alert(data.msg);
                },
                function (data, scope) {
                    // ok
                    alert(data.msg);
                    window.location = '/';

                });
    });

    $(document).on('click', '#loginFormSubmit', function (event) {
        event.preventDefault();
        var input = getFormData('#loginForm');
        console.log(input);
        wk.ajax('/auth/logon', input, null, null,
                function (data, scope) {
                    // error function
                    alert(data.msg);
                },
                function (data, scope) {
                    window.location = '/';
                });
    });

    $(document).on('click', '.close-alert', function (event) {
        event.preventDefault();
        hideAlert();
    });

    $(document).on('click', '.formSubmit', function (event) {
        event.preventDefault();
        var form = $(this).parent().closest('form');
        var action = form.attr('action');
        var input = getFormData('#' + form.attr('id'));
        console.info(input);
        console.info(action);
        wk.ajax(action, input, null, null, null,
                function (data, scope) {
                    dialog.succes(null, data.msg);
                    setTimeout(function () {
                        window.location = window.location.href;
                    }, 3000);
                });
    });

    $(document).on('click', '.ajaxActionRemove', function (event) {
        event.preventDefault();
        var $this = $(this);
        var action = $this.data('action');
        var input = {id: $this.data('id')};
        console.info(input);
        console.info(action);
        wk.ajax(action, input, null, null, null,
                function (data, scope) {
                    dialog.succes(null, data.msg);
                    $this.parent().closest('tr').remove();
                });
    });

});