/**
 * Propiedades de la plantilla
 */
function initTemplate() {
    const body = $('body'), html = $('html');

    body.attr("data-primary", "color_2"); // color_1 ... color_15
    body.attr("data-sibebarbg", "color_1"); // color_1 .. color_15, image_1 ... image_3
    body.attr("data-headerbg", "color_4"); // color_1 .. color_15, image_1 ... image_3
    body.attr("data-nav-headerbg", "color_4"); // color_1 .. color_15, image_1 ... image_3
    body.attr("data-theme-version", "light"); // light, dark, transparent
    body.attr("data-typography", "poppins"); // poppins, roboto, opensans, helveticaneue
    body.attr("data-layout", "vertical"); // vertical, horizontal
    body.attr("data-sidebar-style", "full"); // full, mini, compact, modern, icon-hover, overlay
    body.attr("data-header-position", "static"); // static, fixed
    body.attr("data-sidebar-position", "fixed"); // static, fixed
    body.attr("data-container", "wide"); // wide, boxed, wide-boxed

    html.attr("dir", "ltr");
    body.attr("direction", "ltr");

    manageResponsiveSidebar();

    // Si es 'data-sidebar-style' es 'icon-hover'
    if (false) {
        $('.dlabnav').hover(
            () => $('#main-wrapper').addClass('iconhover-toggle'),
            () => $('#main-wrapper').removeClass('iconhover-toggle')
        );
    }

    function manageResponsiveSidebar() {
        const innerWidth = $(window).innerWidth();
        if (innerWidth < 1024) {
            body.attr("data-layout", "vertical");
            body.attr("data-container", "wide");
        }

        if (innerWidth > 767 && innerWidth < 1024) {
            body.attr("data-sidebar-style", "mini");
        }

        if (innerWidth < 768) {
            body.attr("data-sidebar-style", "overlay");
        }
    }
}

/**
 * Controlador general de eventos de la plantilla
 */
let Jobick = function () {
    var screenWidth = $(window).width();

    var handlePreloader = function () {
        setTimeout(function () {
            $('#preloader').remove();
            $('#main-wrapper').addClass('show');
        }, 800);
    };

    var handleMetisMenu = function () {
        if ($('#menu').length > 0) {
            $("#menu").metisMenu();
        }
        $('.metismenu > .mm-active ').each(function () {
            if (!$(this).children('ul').length > 0) {
                $(this).addClass('active-no-child');
            }
        });
    };

    var handleAllChecked = function () {
        $("#checkAll").on('change', function () {
            $("td input, .email-list .custom-checkbox input").prop('checked', $(this).prop("checked"));
        });
    };

    var handleNavigation = function () {
        $(".nav-control").on('click', function () {
            $('#main-wrapper').toggleClass("menu-toggle");
            $(".hamburger").toggleClass("is-active");
        });
    };

    var handleCurrentActive = function () {
        for (var nk = window.location,
            o = $("ul#menu a").filter(function () {
                return this.href == nk;
            })
                .addClass("mm-active")
                .parent()
                .addClass("mm-active"); ;) {

            if (!o.is("li")) break;

            o = o.parent()
                .addClass("mm-show")
                .parent()
                .addClass("mm-active");
        }
    };

    var handleMiniSidebar = function () {
        $("ul#menu>li").on('click', function () {
            const sidebarStyle = $('body').attr('data-sidebar-style');
            if (sidebarStyle === 'mini') {
                $(this).find('ul').stop()
            }
        })
    };

    var handleMinHeight = function () {
        var win_h = window.outerHeight;
        var win_h = window.outerHeight;
        if (win_h > 0 ? win_h : screen.height) {
            $(".content-body").css("min-height", (win_h + 60) + "px");
        };
    };

    var handleDataAction = function () {
        $('a[data-action="collapse"]').on("click", function (i) {
            i.preventDefault(),
                $(this).closest(".card").find('[data-action="collapse"] i').toggleClass("mdi-arrow-down mdi-arrow-up"),
                $(this).closest(".card").children(".card-body").collapse("toggle");
        });

        $('a[data-action="expand"]').on("click", function (i) {
            i.preventDefault(),
                $(this).closest(".card").find('[data-action="expand"] i').toggleClass("icon-size-actual icon-size-fullscreen"),
                $(this).closest(".card").toggleClass("card-fullscreen");
        });

        $('[data-action="close"]').on("click", function () {
            $(this).closest(".card").removeClass().slideUp("fast");
        });

        $('[data-action="reload"]').on("click", function () {
            var e = $(this);
            e.parents(".card").addClass("card-load"),
                e.parents(".card").append('<div class="card-loader"><i class=" ti-reload rotate-refresh"></div>'),
                setTimeout(function () {
                    e.parents(".card").children(".card-loader").remove(),
                        e.parents(".card").removeClass("card-load")
                }, 2000)
        });
    };

    var handleHeaderHight = function () {
        const headerHight = $('.header').innerHeight();
        $(window).scroll(function () {
            if ($('body').attr('data-layout') === "horizontal" && $('body').attr('data-header-position') === "static" && $('body').attr('data-sidebar-position') === "fixed")
                $(this.window).scrollTop() >= headerHight ? $('.dlabnav').addClass('fixed') : $('.dlabnav').removeClass('fixed')
        });
    };

    var handleDzScroll = function () {
        $('.dlab-scroll').each(function () {
            var scroolWidgetId = $(this).attr('id');
            const ps = new PerfectScrollbar('#' + scroolWidgetId, {
                wheelSpeed: 2,
                wheelPropagation: true,
                minScrollbarLength: 20
            });
            ps.isRtl = false;
        })
    };

    var handleMenuTabs = function () {
        if (screenWidth <= 991) {
            $('.menu-tabs .nav-link').on('click', function () {
                if ($(this).hasClass('open')) {
                    $(this).removeClass('open');
                    $('.fixed-content-box').removeClass('active');
                    $('.hamburger').show();
                }
                else {
                    $('.menu-tabs .nav-link').removeClass('open');
                    $(this).addClass('open');
                    $('.fixed-content-box').addClass('active');
                    $('.hamburger').hide();
                }
            });
            $('.close-fixed-content').on('click', function () {
                $('.fixed-content-box').removeClass('active');
                $('.hamburger').removeClass('is-active');
                $('#main-wrapper').removeClass('menu-toggle');
                $('.hamburger').show();
            });
        }
    };

    var handlePerfectScrollbar = function () {
        if ($('.dlabnav-scroll').length > 0) {
            const qs = new PerfectScrollbar('.dlabnav-scroll');
            qs.isRtl = false;
        }
    };

    var handleBtnNumber = function () {
        $('.btn-number').on('click', function (e) {
            e.preventDefault();

            fieldName = $(this).attr('data-field');
            type = $(this).attr('data-type');
            var input = $("input[name='" + fieldName + "']");
            var currentVal = parseInt(input.val());
            if (!isNaN(currentVal)) {
                if (type == 'minus')
                    input.val(currentVal - 1);
                else if (type == 'plus')
                    input.val(currentVal + 1);
            }
            else {
                input.val(0);
            }
        });
    };

    var handleDzFullScreen = function () {
        $('.dlab-fullscreen').on('click', function (e) {
            if (document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement) {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen(); /* IE/Edge */
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen(); /* Firefox */
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen(); /* Chrome, Safari & Opera */
                }
            }
            else { /* exit fullscreen */
                if (document.documentElement.requestFullscreen) {
                    document.documentElement.requestFullscreen();
                } else if (document.documentElement.webkitRequestFullscreen) {
                    document.documentElement.webkitRequestFullscreen();
                } else if (document.documentElement.mozRequestFullScreen) {
                    document.documentElement.mozRequestFullScreen();
                } else if (document.documentElement.msRequestFullscreen) {
                    document.documentElement.msRequestFullscreen();
                }
            }
        });
    };

    var handleshowPass = function () {
        $('.show-pass').on('click', function () {
            $(this).toggleClass('active');
            if ($('#dlab-password').attr('type') == 'password') {
                $('#dlab-password').attr('type', 'text');
            } else if ($('#dlab-password').attr('type') == 'text') {
                $('#dlab-password').attr('type', 'password');
            }
        });
    };

    var heartBlast = function () {
        $(".heart").on("click", function () {
            $(this).toggleClass("heart-blast");
        });
    };

    var handleDzLoadMore = function () {
        $(".dlab-load-more").on('click', function (e) {
            e.preventDefault();	//STOP default action
            $(this).append(' <i class="fas fa-sync"></i>');

            var dlabLoadMoreUrl = $(this).attr('rel');
            var dlabLoadMoreId = $(this).attr('id');

            $.ajax({
                method: "POST",
                url: dlabLoadMoreUrl,
                dataType: 'html',
                success: function (data) {
                    $("#" + dlabLoadMoreId + "Content").append(data);
                    $('.dlab-load-more i').remove();
                }
            })
        });
    };

    var handleLightgallery = function () {
        if ($('#lightgallery').length > 0) {
            $('#lightgallery').lightGallery({
                loop: true,
                thumbnail: true,
                exThumbImage: 'data-exthumbimage'
            });
        }
    };

    var handleCustomFileInput = function () {
        $(".custom-file-input").on("change", function () {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    };

    var vHeight = function () {
        var ch = $(window).height() - 206;
        $(".chatbox .msg_card_body").css('height', ch);
    };

    var handleDatetimepicker = function () {
        if ($("#datetimepicker1").length > 0) {
            $('#datetimepicker1').datetimepicker({
                inline: true,
            });
        }
    };

    var handleCkEditor = function () {
        if ($("#ckeditor").length > 0) {
            ClassicEditor
                .create(document.querySelector('#ckeditor'), {
                })
                .then(editor => {
                    window.editor = editor;
                })
                .catch(err => {
                    console.error(err.stack);
                });
        }
    };

    var handleMenuPosition = function () {

        if (screenWidth > 1024) {
            $(".metismenu  li").unbind().each(function (e) {
                if ($('ul', this).length > 0) {
                    var elm = $('ul:first', this).css('display', 'block');
                    var off = elm.offset();
                    var l = off.left;
                    var w = elm.width();
                    var elm = $('ul:first', this).removeAttr('style');
                    var docH = $("body").height();
                    var docW = $("body").width();

                    if ($('html').hasClass('rtl')) {
                        var isEntirelyVisible = (l + w <= docW);
                    } else {
                        var isEntirelyVisible = (l > 0) ? true : false;
                    }

                    if (!isEntirelyVisible) {
                        $(this).find('ul:first').addClass('left');
                    } else {
                        $(this).find('ul:first').removeClass('left');
                    }
                }
            });
        };
    }

    return {
        init: function () {
            handleMetisMenu();
            handleAllChecked();
            handleNavigation();
            handleCurrentActive();
            handleMiniSidebar();
            handleMinHeight();
            handleDataAction();
            handleHeaderHight();
            handleDzScroll();
            handleMenuTabs();
            handlePerfectScrollbar();
            handleBtnNumber();
            handleDzFullScreen();
            handleshowPass();
            heartBlast();
            handleDzLoadMore();
            handleLightgallery();
            handleCustomFileInput();
            vHeight();
            handleDatetimepicker();
            handleCkEditor();
        },

        load: function () {
            handlePreloader();
        },

        resize: function () {
            vHeight();
        },

        handleMenuPosition: function () {
            handleMenuPosition();
        },
    }
} ();

/**
 * Llamar inicialización de plantilla
 */
$(function () {
    initTemplate();
    $(window).on('resize', initTemplate);
    $('[data-bs-toggle="popover"]').popover();
    Jobick.init();
});

$(window).on('load', function() {
    Jobick.load();
    setTimeout(Jobick.handleMenuPosition, 1000);
});

$(window).on('resize', function() {
    Jobick.resize();
    setTimeout(Jobick.handleMenuPosition, 1000);
});

/**
 * Inicialización de TempusDominus
 */
$(function() {
    const datePickerConf = {
        display: {
            calendarWeeks: true,
            keepOpen: false,
            buttons: {
                today: true,
                clear: true,
                close: true
            },
            components: {
                useTwentyfourHour: true,
                decades: false,
                year: true,
                month: true,
                date: true,
                hours: false,
                minutes: false,
                seconds: false
            },
            theme: 'light'
        },
        localization: {
            locale: 'es',
            startOfTheWeek: 0,
            today: 'Hoy',
            clear: 'Limpiar',
            close: 'Cerrar',
            selectMonth: 'Selecciona un mes',
            previousMonth: 'Mes anterior',
            nextMonth: 'Mes siguiente',
            selectYear: 'Seleccione un año',
            previousYear: 'Año anterior',
            nextYear: 'Año siguiente',
            selectDecade: 'Selecciona una década',
            previousDecade: 'Década anterior',
            nextDecade: 'Década siguiente',
            pickHour: 'Selecciona la hora',
            incrementHour: 'Aumentar hora',
            decrementHour: 'Disminur hora',
            pickMinute: 'Seleccione los minutos',
            incrementMinute: 'Aumentar minutos',
            decrementMinute: 'Disminuir minutos',
            toggleMeridiem: 'Cambiar AM/PM',
            selectTime: 'Selecciona la hora',
            selectDate: 'Selecciona la fecha',
            dayViewHeaderFormat: { month: 'long', year: 'numeric' },
        }
    };
    
    document.querySelectorAll(".datepicker input").forEach(field => {
        new tempusDominus.TempusDominus(field, datePickerConf);
    });
    
    document.querySelectorAll(".timepicker input").forEach(field => {
        let timepicker = new tempusDominus.TempusDominus(field, datePickerConf);
        timepicker.updateOptions({
            display: {
                viewMode: 'clock',
                components: {
                    date: false,
                    year: false,
                    month: false,
                    hours: true,
                    minutes: true,
                    seconds: true
                }
            }
        });
    });
    
    document.querySelectorAll(".datetimepicker input").forEach(field => {
        let datetimepicker = new tempusDominus.TempusDominus(field, datePickerConf);
        datetimepicker.updateOptions({
            display: {
                components: {
                    hours: true,
                    minutes: true,
                    seconds: true
                }
            }
        });
    });
});

/**
 * Focus correcto en Select2
 */
setSelect2FocusBehaviour();

function setSelect2FocusBehaviour() {
    $(document).on('focus', '.select2-selection.select2-selection--single', function (e) {
        let select2 = this;
        $(select2).closest(".select2-container").siblings('select:enabled').select2('open');
        let searchBox = document.querySelector(".select2-search__field");

        searchBox.focus();

        $(searchBox).on('keydown', function (e) {
            if (e.keyCode === 9) {
                let candidates = $(select2)
                    .parent().parent()
                    .parent().parent()
                    .next()
                    .find("select[type!=hidden], input[type!=hidden]");

                if (candidates[0]) {
                    candidates[0].focus();
                    $("#select2-drop-mask").closest(".select2-container").siblings('select:enabled').select2("close");
                }
            }
        });
    });

    $('select.select2').on('select2:closing', function (e) {
        $(e.target)
            .data("select2")
            .$selection
            .one('focus focusin', e => e.stopPropagation());
    });
}

/**
 * Enfoque en primer campo
 */
$(document).ready(() => $('form:first *:input[type!=hidden][type!=button]:not([disabled]):first').focus());

/**
 * Datepickers de solo lectura
 */
$.fn.readonlyDatepicker = function (makeReadonly) {
    $(this).each(function () {

        //find corresponding hidden field
        let name = $(this).attr('name');
        let $hidden = $('input[name="' + name + '"][type="hidden"]');

        //if it doesn't exist, create it
        if ($hidden.length === 0) {
            $hidden = $('<input type="hidden" name="' + name + '"/>');
            $hidden.insertAfter($(this));
        }

        if (makeReadonly) {
            $hidden.val($(this).val());
            $(this).unbind('change.readonly');
            $(this).attr('disabled', true);
        }
        else {
            $(this).bind('change.readonly', function () {
                $hidden.val($(this).val());
            });
            $(this).attr('disabled', false);
        }
    });
};

$(function () {
    $(".krajee-datepicker[readonly]").each((ix, el) => $(el).readonlyDatepicker(true));
});

/**
 * Soporte de TAB para select2
 * https://stackoverflow.com/a/39605870/7045452
 */
$(document).ready(function ($) {
    let docBody = $(document.body);
    let shiftPressed = false;
    let clickedOutside = false;
    //let keyPressed = 0;

    docBody.on('keydown', function (e) {
        let keyCaptured = (e.keyCode ? e.keyCode : e.which);
        //shiftPressed = keyCaptured == 16 ? true : false;
        if (keyCaptured == 16) { shiftPressed = true; }
    });

    docBody.on('keyup', function (e) {
        let keyCaptured = (e.keyCode ? e.keyCode : e.which);
        //shiftPressed = keyCaptured == 16 ? true : false;
        if (keyCaptured == 16) { shiftPressed = false; }
    });

    docBody.on('mousedown', function (e) {
        clickedOutside = false;
        if ($(e.target).is('[class*="select2"]') != true) {
            clickedOutside = true;
        }
    });

    docBody.on('select2:opening', function (e) {
        clickedOutside = false;
        $(e.target).attr('data-s2open', 1);
    });
    docBody.on('select2:closing', function (e) {
        $(e.target).removeAttr('data-s2open');
    });

    docBody.on('select2:close', function (e) {
        let elSelect = $(e.target);
        elSelect.removeAttr('data-s2open');
        let currentForm = elSelect.closest('form');
        let othersOpen = currentForm.has('[data-s2open]').length;
        if (othersOpen == 0 && clickedOutside == false) {
            let inputs = currentForm.find(':input:enabled:not([readonly], input:hidden, button:hidden, textarea:hidden)')
                .not(function () {
                    return $(this).parent().is(':hidden');
                });
            let elFocus = null;
            $.each(inputs, function (index) {
                let elInput = $(this);
                if (elInput.attr('id') == elSelect.attr('id')) {
                    if (shiftPressed) { // Shift+Tab
                        elFocus = inputs.eq(index - 1);
                    }
                    else {
                        elFocus = inputs.eq(index + 1);
                    }
                    return false;
                }
            });
            if (elFocus !== null) {
                let isSelect2 = elFocus.siblings('.select2').length > 0;

                if (isSelect2) {
                    elFocus.select2('open');
                    let searchBox = document.querySelector(".select2-search__field");
                    searchBox.focus();
                }
                else {
                    elFocus.focus();
                }
            }
        }
    });

    docBody.on('focus', '.select2', function (e) {
        let elSelect = $(this).siblings('select');
        if (elSelect.is('[disabled]') == false && elSelect.is('[data-s2open]') == false
            && $(this).has('.select2-selection--single').length > 0) {
            elSelect.attr('data-s2open', 1);
            elSelect.select2('open');
        }
    });
});

/**
 * Correccion para el soporte de Select2 en DynamicForms
 */
window.initSelect2Loading = (id, optVar) => initS2Loading(id, optVar);
window.initSelect2DropStyle = (id, kvClose, ev) => initS2Change($('#' + id));

/**
 * Soporte de creación de nueva fila al terminar una en DynamicForms
 */
$(function () {
    document.querySelectorAll('div[data-dynamicform]').forEach(addTabListenerToLastCell);

    function addTabListenerToLastCell(dynamicForm) {
        const lastCell = dynamicForm.querySelector('table tr:last-child td:last-child');

        if (lastCell) {
            lastCell.addEventListener('keyup', handleTab);
        }

        function handleTab(ev) {
            if (ev.keyCode == 9) {
                const addButton = dynamicForm.querySelector('.add-os');

                if (addButton) {
                    addButton.click();
                    setSelect2FocusBehaviour();
                    lastCell.removeEventListener('keyup', handleTab);
                    addTabListenerToLastCell(dynamicForm);
                }
            }
        }
    }
});
