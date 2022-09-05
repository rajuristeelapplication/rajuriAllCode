$(function () {
    "use strict";
    $(function () {
        $(".preloader").fadeOut();
    });
    jQuery(document).on('click', '.mega-dropdown', function (e) {
        e.stopPropagation()
    });
    // ==============================================================
    // This is for the top header part and sidebar part
    // ==============================================================
    var set = function () {
        var width = (window.innerWidth > 0) ? window.innerWidth : this.screen.width;
        var topOffset = 190;
        if (width < 1170) {
            $("body").addClass("mini-sidebar");
            $('.navbar-brand span').hide();
            $(".sidebartoggler i").addClass("ti-menu");
        }
        else {
            $("body").removeClass("mini-sidebar");
            $('.navbar-brand span').show();
       }
        var height = ((window.innerHeight > 0) ? window.innerHeight : this.screen.height) - 1;
        height = height - topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) {
            $(".page-wrapper").css("min-height", (height) + "px");
        }
    };
    $(window).ready(set);
    $(window).on("resize", set);
    // ==============================================================
    // Theme options
    // ==============================================================
    $(".sidebartoggler").on('click', function () {
        if ($("body").hasClass("mini-sidebar")) {
            $("body").trigger("resize");
            $("body").removeClass("mini-sidebar");
            $('.navbar-brand span').show();
        }
        else {
            $("body").trigger("resize");
            $("body").addClass("mini-sidebar");
            $('.navbar-brand span').hide();
        }
    });
    // this is for close icon when navigation open in mobile view
    $(".nav-toggler").click(function () {
        $("body").toggleClass("show-sidebar");
        $(".nav-toggler i").toggleClass("ti-menu");
        $(".nav-toggler i").addClass("ti-close");
    });
    $(".search-box a, .search-box .app-search .srh-btn").on('click', function () {
        $(".app-search").toggle(200);
    });
    // ==============================================================
    // Right sidebar options
    // ==============================================================
    $(".right-side-toggle").click(function () {
        $(".right-sidebar").slideDown(50);
        $(".right-sidebar").toggleClass("shw-rside");
    });
    // ==============================================================
    // This is for the floating labels
    // ==============================================================
    $('.floating-labels .form-control').on('focus blur', function (e) {
        $(this).parents('.form-group').toggleClass('focused', (e.type === 'focus' || this.value.length > 0));
    }).trigger('blur');

    // ==============================================================
    //tooltip
    // ==============================================================
    $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    // ==============================================================
    //Popover
    // ==============================================================
    $(function () {
         $('[data-toggle="popover"]').popover()
    })

    // ==============================================================
    // Perfact scrollbar
    // ==============================================================
    $('.right-side-panel, .message-center, .right-sidebar').perfectScrollbar();
	$('#chat, #msg, #comment, #todo').perfectScrollbar(); 

    // ==============================================================
    // Resize all elements
    // ============================================================== 
    $("body").trigger("resize");
    // ==============================================================
    // To do list
    // ==============================================================
    $(".list-task li label").click(function () {
        $(this).toggleClass("task-done");
    });
    // ==============================================================
    // Collapsable cards
    // ==============================================================
    $('a[data-action="collapse"]').on('click', function (e) {
        e.preventDefault();
        $(this).closest('.card').find('[data-action="collapse"] i').toggleClass('ti-minus ti-plus');
        $(this).closest('.card').children('.card-body').collapse('toggle');
    });
    // Toggle fullscreen
    $('a[data-action="expand"]').on('click', function (e) {
        e.preventDefault();
        $(this).closest('.card').find('[data-action="expand"] i').toggleClass('mdi-arrow-expand mdi-arrow-compress');
        $(this).closest('.card').toggleClass('card-fullscreen');
    });
    // Close Card
    $('a[data-action="close"]').on('click', function () {
        $(this).closest('.card').removeClass().slideUp('fast');
    });
    // ==============================================================
    // fixed navigattion while scrolll
    // ==============================================================
    // function collapseNavbar() {
    //     if ($(window).scrollTop() > 80) {
    //         $("body").addClass("fixed-sidebar");
    //         $(".left-sidebar").addClass("animated slideInDown");

    //     } else {
    //         $("body").removeClass("fixed-sidebar");
    //         $(".left-sidebar").removeClass("animated slideInDown");
    //     }
    // }
    // $(window).scroll(collapseNavbar);
    // collapseNavbar()
    // ==============================================================
    // Color variation
    // ==============================================================


    

    $(window).scroll(function () {
        var header_height = $('header').outerHeight();
        if ($(window).scrollTop() > header_height) {
            $('body').addClass('fixed');
        } else {
            $('body').removeClass('fixed');
        }
    });

    var mySkins = [
        "skin-default",
        "skin-green",
        "skin-red",
        "skin-blue",
        "skin-purple",
        "skin-megna",
        "skin-default-dark",
        "skin-green-dark",
        "skin-red-dark",
        "skin-blue-dark",
        "skin-purple-dark",
        "skin-megna-dark"
    ]
        /**
         * Get a prestored setting
         *
         * @param String name Name of of the setting
         * @returns String The value of the setting | null
         */
    function get(name) {
        if (typeof (Storage) !== 'undefined') {
            return localStorage.getItem(name)
        }
        else {
            window.alert('Please use a modern browser to properly view this template!')
        }
    }
    /**
     * Store a new settings in the browser
     *
     * @param String name Name of the setting
     * @param String val Value of the setting
     * @returns void
     */
    function store(name, val) {
        if (typeof (Storage) !== 'undefined') {
            localStorage.setItem(name, val)
        }
        else {
            window.alert('Please use a modern browser to properly view this template!')
        }
    }

    /**
     * Replaces the old skin with the new skin
     * @param String cls the new skin class
     * @returns Boolean false to prevent link's default action
     */
    function changeSkin(cls) {
        $.each(mySkins, function (i) {
            $('body').removeClass(mySkins[i])
        })
        $('body').addClass(cls)
        store('skin', cls)
        return false
    }

    function setup() {
        var tmp = get('skin')
        if (tmp && $.inArray(tmp, mySkins)) changeSkin(tmp)
            // Add the change skin listener
        $('[data-skin]').on('click', function (e) {
            if ($(this).hasClass('knob')) return
            e.preventDefault()
            changeSkin($(this).data('skin'))
        })
    }
    setup()
    $("#themecolors").on("click", "a", function () {
        $("#themecolors li a").removeClass("working"),
        $(this).addClass("working")
    })
    // For Custom File Input
    $('.custom-file-input').on('change',function(){
        //get the file name
        var fileName = $(this).val();
        //replace the "Choose a file" label
        $(this).next('.custom-file-label').html(fileName);
    })
});


var url = $('meta[name="baseUrl"]').attr('content');


$('.alphawithspecialchar').keypress(function (e) {
var regex = new RegExp("^[a-zA-Z- _ &]+$");
var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
if (regex.test(str)) {
return true;
}

e.preventDefault();
return false;
});


jQuery.validator.addMethod("alphawithspecialchar", function(value, element) {

    return this.optional(element) || /^[a-zA-Z- _ &]+$/i.test(value);
}, "Letters, dash ,space , underscore and & only ");
   

$.validator.addClassRules("alphawithspecialchar", {

alphawithspecialchar: true

});


$('.alphaOnly').keypress(function (e) {
var regex = new RegExp("^[a-zA-Z ]+$");
var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
if (regex.test(str)) {
return true;
}

e.preventDefault();
return false;
});




jQuery.validator.addMethod("alphaOnly", function(value, element) {

    return this.optional(element) || /^[a-zA-Z ]+$/i.test(value);
}, "Letters only please");
   

$.validator.addClassRules("alphaOnly", {

alphaOnly: true

});



$('.alphanumeric').keypress(function (e) {
var regex = new RegExp("^[a-zA-Z0-9 -]+$");
var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
if (regex.test(str)) {
return true;
}

e.preventDefault();
return false;
});


jQuery.validator.addMethod("alphanumeric", function(value, element) {

    return this.optional(element) || /^[a-zA-Z0-9 -]+$/i.test(value);
}, "Letters, numbers only please");
   

$.validator.addClassRules("alphanumeric", {

alphanumeric: true

});



 // small char with dash only     
$('.smallCharWithDash').keypress(function (e) {
var regex = new RegExp("^[a-z- ]+$");
var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
if (regex.test(str)) {
return true;
}

e.preventDefault();
return false;
});


jQuery.validator.addMethod("smallCharWithDash", function(value, element) {

    return this.optional(element) || /^[a-z- ]+$/i.test(value);
}, "Letters and dash only please");
   

$.validator.addClassRules("smallCharWithDash", {

smallCharWithDash: true

});


 // small char with dash only     
$('.smallCapsCharWithDash').keypress(function (e) {
var regex = new RegExp("^[a-zA-Z- ]+$");
var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
if (regex.test(str)) {
return true;
}

e.preventDefault();
return false;
});


jQuery.validator.addMethod("smallCapsCharWithDash", function(value, element) {

    return this.optional(element) || /^[a-zA-Z- ]+$/i.test(value);
}, "Letters and dash only please");
   

$.validator.addClassRules("smallCapsCharWithDash", {

smallCapsCharWithDash: true

});

// number with colan    
$('.numberWithCollan').keypress(function (e) {
    var regex = new RegExp("^[0-9:]+$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (regex.test(str)) {
        
    return true;
    }
    
    e.preventDefault();
    return false;
    });
    
    jQuery.validator.addMethod("numberWithCollan", function(value, element) {
    
        return this.optional(element) || /^[[0-9:]+$/i.test(value);
    }, "Number and collan only");
    
    $.validator.addClassRules("numberWithCollan", {
    
        numberWithCollan: true
    
    });

jQuery.validator.addMethod("numericOnly", function(value, element) {

    return this.optional(element) || /^(0|[1-9][0-9]*)$/i.test(value);
}, "Please enter only number");
   
$.validator.addClassRules("numericOnly", {

numericOnly: true

});


jQuery.validator.addMethod("decimal", function(value, element) {

    return this.optional(element) || /^[1-9]\d*(\.\d+)?$/i.test(value);
}, "Please enter only number");
   
$.validator.addClassRules("decimal", {

decimal: true

});



// -------------------Start Custom Validations ----------------------------//
var emailpattern = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;

$.validator.addMethod(
    "regex",
    function(value, element, regexp) {
        var re = new RegExp(regexp);
        return this.optional(element) || re.test(value);
    },
    "Please check your input."
);

$(document).on("keypress",'.numeric',function(evt) {

    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode != 46 && charCode > 31  && (charCode < 48 || charCode > 57))
        return false;
    return true;
});


$('.number').keypress(function(event) {
        var $this = $(this);
        if ((event.which != 46 || $this.val().indexOf('.') != -1) &&
           ((event.which < 48 || event.which > 57) &&
           (event.which != 0 && event.which != 8))) {
               event.preventDefault();
        }

        var text = $(this).val();
        if ((event.which == 46) && (text.indexOf('.') == -1)) {
            setTimeout(function() {
                if ($this.val().substring($this.val().indexOf('.')).length > 3) {
                    $this.val($this.val().substring(0, $this.val().indexOf('.') + 3));
                }
            }, 1);
        }

        if ((text.indexOf('.') != -1) &&
            (text.substring(text.indexOf('.')).length > 2) &&
            (event.which != 0 && event.which != 8) &&
            ($(this)[0].selectionStart >= text.length - 2)) {
                event.preventDefault();
        }      
    });

    $('.number').bind("paste", function(e) {
    var text = e.originalEvent.clipboardData.getData('Text');
    if ($.isNumeric(text)) {
        if ((text.substring(text.indexOf('.')).length > 3) && (text.indexOf('.') > -1)) {
            e.preventDefault();
            $(this).val(text.substring(0, text.indexOf('.') + 3));
       }
    }
    else {
            e.preventDefault();
         }
    });





// -------------------Over Custom Validations ----------------------------//
