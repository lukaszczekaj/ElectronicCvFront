var windowWidth = $(window).width();
var errorCounter = 0;
var AjaxResponse_CODE = {
    CODE_DATA: 100,
    CODE_ERROR: 101,
    CODE_OK: 102,
    CODE_WARN: 103,
    CODE_DATA_SUCCESS: 104,
    CODE_SESSION_EXPIRED: 105
};
var templateLanguage = {
    ERROR_TITLE: 'Bład',
    ERROR_TEXT: 'Nierozpoznany błąd w ajax'
};
var dialog = {
    info: function (title, msg, time) {
        if (!title) {
            title = 'Informacja';
        }
        showAlert(msg, 'alert-info', title, time);
    },
    error: function (title, msg, time) {
        if (!title) {
            title = 'Błąd';
        }
        showAlert(msg, 'alert-danger', title, time);
    },
    succes: function (title, msg, time) {
        if (!title) {
            title = 'Sukces';
        }
        showAlert(msg, 'alert-success', title, time);
    },
    warn: function (title, msg, time) {
        if (!title) {
            title = 'Uwaga';
        }
        showAlert(msg, 'alert-warning', title, time);
    }
};
var wk = {
    ajaxAnimate: true,
    progressBar: true,
    /**
     * Załaduje przez Ajax stronę do contentu strony
     * @param {a} href  element odnośnika
     * @returns {undefined}
     */
    loadPageAjax: function (href, backFowardClicked, disableScroll) {
        wk.clearIntervals();
        // TOTO - przerobic na nasze potrzeby

//        if ($(el).hasClass("current") == true) {
//            return;
//        }
//        var href = $(el).attr('href');
//
//        $('ul.m a').removeClass('current');
//        $(el).addClass('current');
//        $('#ajax_komunikat').show();
//
        this.ajax(href, backFowardClicked, '.l-main', disableScroll);
    },
    /**
     *  Wysyłka zapytania przez ajax
     * @param {type} 1 href link
     * @param {type} 2 inputData dane
     * @param {type} 3 responseDIVname nazwa (id) diva gdzie mają trafić dane gdy kod CODE_DATA_SUCCESS
     * @param {type} 4 dataFunction co ma się wykonać gdy przyjdą dane , kod DATA
     * @param {type} 5 errorFunction 
     * @param {type} 6 okFunction co ma się wykonać gdy przyjdzie ok, Kod OK
     * @param {type} 7 warnFunction co ma się wykonać gdy przyjdzie warningu, Kod WARN
     * @param {type} 8 allRunFunction uruchamiać się będzie po każdej poprawnej odpowiedzi
     * @param {type} 9 sync
     * @param {type} 10 ajaxContentType
     * @param {type} 11 ajaxProcessData
     * @returns {undefined}
     */
    ajax: function (href, inputData, responseDIVname, dataFunction, errorFunction, okFunction, warnFunction, allRunFunction, sync, ajaxContentType, ajaxProcessData, disableScroll) {

        if (wk.ajaxAnimate) {
            //  $('#ajax_komunikat').show();
        }

        if ((typeof (ajaxContentType) === "undefined"))
            ajaxContentType = 'application/x-www-form-urlencoded; charset=UTF-8'; // domyślna wartość contentType w JQuery: 'application/x-www-form-urlencoded; charset=UTF-8'
        if ((typeof (ajaxProcessData) === "undefined"))
            ajaxProcessData = true; // domyślna wartość w jQuery dla processData: true

        //zakomentowane z powodu uzywania $this->getUrl i {getUrl} dajacych juz pageId
//        if(typeof pageId !== "undefined") {
//            href += '&pageId=' + pageId;
//        }
        var async = true;
        if (sync) {
            async = false;
        }
        $.ajax({
            async: async,
            type: "POST",
            url: href,
            data: {ajaxAction: '1', data: inputData},
            dataType: "json",
            contentType: ajaxContentType,
            processData: ajaxProcessData,
            success: function (data) {
                var code = AjaxResponse_CODE;
                switch (data.code) {
                    case code.CODE_DATA :
                        if ($.isFunction(dataFunction)) {
                            dataFunction(data.data, data); //w data.data content
                        }
                        break;
                    case code.CODE_ERROR :
                        if ($.isFunction(errorFunction)) {
                            errorFunction(data.data, data); //w data.data content
                        } else {
                            if (data.data && data.data.msg) {
                                dialog.error(null, data.data.msg);
                            }
                        }
                        break;
                    case code.CODE_OK :
                        if ($.isFunction(okFunction)) {
                            okFunction(data.data, data); //w data.data content
                        } else {
                            if (data.data && data.data.msg) {
                                dialog.succes(null, data.data.msg);
                            }
                        }
                        break;
                    case code.CODE_WARN :
                        if ($.isFunction(warnFunction)) {
                            warnFunction(data.data, data); //w data.data content
                        } else {
                            if (data.data && data.data.msg) {
                                dialog.warn(null, data.data.msg);
                            }
                        }
                        break;
                    case code.CODE_SESSION_EXPIRED :
                        if (data.data && data.data.msg) {
                            dialog.warn(null, data.data.msg);
                        }
                        setTimeout(function () {
                            window.location.reload('/');
                        }, 1500);
                        break;
                    case code.CODE_DATA_SUCCESS :
                        $(responseDIVname).animate({opacity: 0}, 150);
                        setTimeout(function () {
                            $(responseDIVname).html(data.data);
                            $(responseDIVname).animate({opacity: 1}, 200);
                        }, 150);
                        if (!inputData) {
                            window.history.pushState(href, "", href);
                        }
                        if (!dataFunction) {
                            scrollToElement('.main-header');
                        }
                        break;
                    default:
                        console.error(templateLanguage.ERROR_TITLE, templateLanguage.ERROR_TEXT);
                }
                scrollToElement('.main-header');
                if ($.isFunction(allRunFunction)) {
                    allRunFunction(data); //w data.data content
                }
                //       $('#ajax_komunikat').hide();
                wk.ajaxAnimate = true;
            },
            error: function (jqXHR, textStatus, errorThrown) {
                scrollToElement('.main-header');
                console.log('Funkcja error');
                console.log(jqXHR);
                //     dialog.error(templateLanguage.ERROR_TITLE, templateLanguage.ERROR_TEXT);

                // TODO Napisac obsługe błędu 404 i innych
                if (jqXHR.status === 400) {
                    dialog.warn(null, jqXHR.statusText);
                }
                if (jqXHR.status === 404) {
                    dialog.warn(null, 'Taka stronie nie istnieje');
                }
                // TODO Napisac obsługe błędu 404 i innych
                if (jqXHR.status === 403) {
                    dialog.warn(null, 'Sesja wygasła');
                    setTimeout(function () {
                        window.location = '/';
                    }, 1500);
                }
                if (jqXHR.status === 300) {
                    dialog.warn(null, 'Błąd wewnętrzny');
                }
                if (jqXHR.status === 200) {
                    dialog.warn(null, 'Problem w komunikacji. Spróbuj ponownie.');
                }
                progressBarHide();
                //  $('.l-main').html(jqXHR.responseText);
                console.log(jqXHR.responseText);
                console.log(textStatus);
                console.log(errorThrown);

                //   $('#ajax_komunikat').hide();
                if ($.isFunction(errorFunction)) {
                    errorFunction(); //w data.data content
                }
                wk.ajaxAnimate = true;
            },
            beforeSend: function () {
//                if (wk.progressBar) {
//                    progressBarShow();
//                }
            }
        }).done(function () {
           // progressBarHide();
        });
    },
    otherFunctionRunner: null,
    extensionLogonSession: function () {
        /*
         * Funkcja przedluzajaca czas trwania sesji w php
         * start interval wywolujacy znajduje się w widoku menu/index.phtml
         */
        wk.ajax('/auth/extension-logon-session', null, null, null, null,
                function () {
                    console.log('session ext');
                });
    },
    clearIntervals: function () {
    }
};

function showAlert(msg, type, title, time) {
    $('.alert .msg-type').text(title);
    $('.alert .msg-text').text(msg);
    $('.alert').attr('data-alert-type', type);
    $('.alert').fadeIn().addClass(type);
    //hideAlert(type, time);
}


function hideAlert(time) {
    if (!time) {
        time = 0;
    }
    setTimeout(function () {
        $('.alert').fadeOut();
        setTimeout(function () {
            $('.alert').removeClass($('.alert').attr('data-alert-type'));
        }, 400);
        $('.alert.msg-type').text('');
        $('.alert.msg-text').text('');
    }, time);
}

function scrollToElement(element) {
    //  event.preventDefault();
//    $('html,body').stop();
//    if ($('.menuSmall').css('display') !== 'none') {
//        $('.menu').fadeOut(); //JEZELI JEST WERSJA MNIEJSZA, TO CHOWAC MENU PO KLIKNIECIU
//    }
    $('html,body').animate({
        scrollTop: $(element).offset().top - 40
    }, 700);
}

function showElement(what, duration) {
    $(what).each(function (i) {
        var top = $(this).offset().top;
        var height = ($(window).height() * 0.8) + $(window).scrollTop();
        if (top < height) {
            $(this).animate({
                opacity: 1
            }, duration);
        }
    });
}

function showHideElement(idElement, animation) {
    if ($('#' + idElement).is(':visible')) {
        $('#' + idElement).hide(animation);
    } else {
        $('#' + idElement).show(animation);
    }
}


function toogleElement(self, idElement, msgVisible, msgInvisible) {
    $('#' + idElement).toggle('slow', function () {
        if ($('#' + idElement).is(':visible')) {
            self.html(msgInvisible);
        } else {
            self.html(msgVisible);
        }
    });
}

function progressBarShow() {
    if (wk.progressBar) {
        $('html').bind('click', handler);
        $('.progress').fadeIn();
    }
}

function progressBarHide() {
    if (wk.progressBar) {
        $('.progress').fadeOut();
        $('html').unbind('click', handler);
    }
}

function blackScreen(onOff) {
    if (onOff) {
        $('body').addClass('blocked');
    } else {
        $('body').removeClass('blocked');
    }
}

function isMobile() {
    try {
        document.createEvent("TouchEvent");
        return true;
    }
    catch (e) {
        return false;
    }
}

function handler(e) {
    e.stopPropagation();
    e.preventDefault();
}

function format(num) {
    return num < 10 ? "0" + num : num;
}

function closeDialog() {
    $('body').removeClass('blocked');
    $('.dialog').fadeOut();
    clearDialog();
}

function openDialog() {
    $('body').addClass('blocked');
    $('.dialog').fadeIn();
}

function getFormData(form) {
    var input = {},
            inputs = $(form + ' input, ' + form + ' select, ' + form + ' textarea'),
            inputName = "",
            inputValue,
            inputType,
            JSONinputs;

    $.each(inputs, function () {
        var $this = $(this);
        inputName = $this.attr('name');
        inputType = $this.attr('type');
        if (inputType === 'checkbox') {
            if ($this.is(':checked')) {
                inputValue = '1';
            } else {
                inputValue = '0';
            }
        } else if (inputType === 'radio') {
            if ($this.is(':checked')) {
                inputValue = $(this).val();
            }
        } else {
            inputValue = $(this).val();
        }
        input[inputName] = inputValue;
        JSONinputs = JSON.stringify(input);
    });
    return input;
}