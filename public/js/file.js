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
                    }, 2000);
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
    
    $(document).on('click', '.ajaxAction', function (event) {
        event.preventDefault();
        var $this = $(this);
        var action = $this.data('action');
        var input = {id: $this.data('id')};
        console.info(input);
        console.info(action);
        wk.ajax(action, input, null, null, null,
                function (data, scope) {
                    dialog.succes(null, data.msg);
                    setTimeout(function () {
                        window.location = window.location.href;
                    }, 2000);
                });
    });

    $(document).on('click', '#profilePictureFormSubmit', function (event) {
        event.preventDefault();
        $.ajax({
            url: "/profile/profile-picture-upload", // Url to which the request is send
            type: "POST", // Type of request to be send, called as method
            data: new FormData($('#profilePictureForm')[0]), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
            contentType: false, // The content type used when sending data to the server.
            cache: false, // To unable request pages to be cached
            processData: false, // To send DOMDocument or non processed data file it is set to false
            success: function (data)   // A function to be called if request succeeds
            {
                var r = JSON.parse(data);
                console.log(r);
                if (r.code === AjaxResponse_CODE.CODE_OK) {
                    dialog.succes(null, r.data.msg);
                    setTimeout(function () {
                        window.location = window.location.href;
                    }, 2000);
                } else {
                    dialog.error(null, r.data.msg);
                }


            }
        });
    });
});