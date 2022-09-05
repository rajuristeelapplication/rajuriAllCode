    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    {{-- <script src="{{ url('js/all.min.js') }}"></script> --}}
    <script src="{{ url('js/all.js') }}"></script>
    <!-- Popup message jquery -->
    <script src="{{ url('admin-assets/node_modules/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ url('admin-assets/node_modules/toast-master/js/jquery.toast.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.pack.js"></script>
    <script type="text/javascript" src="{{ url('admin-assets/js/moments.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('admin-assets/js/daterangepicker.min.js') }}"></script>
    <script>

        $(document).on("keypress",'.numeric',function(evt) {

            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31  && (charCode < 48 || charCode > 57))
                return false;

            return true;
        });

        $(document).on("keypress",'.decimalOnly',function(evt) {

            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode != 46 && charCode > 31  && (charCode < 48 || charCode > 57))
                return false;

            return true;
        });

        var baseUrl = $('meta[name="baseUrl"]').attr('content');

        function deleteData(url) {
            $('#deleteData').attr('data-delete-link', url);
            $("#delete-confirmation-modal").modal('show');
        }

        $('#deleteData').click(function() {
            $('.loader').show();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: $(this).attr('data-delete-link'),
                cache: false,
                processData: false,
                contentType: false,
                type: 'DELETE',
                success: function(result) {
                    $('.loader').hide();
                    $("#delete-confirmation-modal").modal('hide');
                    if (result.status == 1) {
                        $.toast({
                            heading: 'Success',
                            text: result.message,
                            showHideTransition: 'fade',
                            icon: 'success',
                            position: 'top-right',
                            loader: false,
                        });
                        table.ajax.reload();
                        return true;
                    }
                    $.toast({
                        heading: 'Error',
                        text: result.message,
                        showHideTransition: 'fade',
                        icon: 'error',
                        position: 'top-right',
                        loader: false,
                    });
                },
                error: function(error) {
                    console.log(error);
                    $('.loader').hide();
                    $("#delete-confirmation-modal").modal('hide');
                }
            });
        });


        function deleteDataImage(url) {
            $('#deleteImageData').attr('data-delete-link', url);
            $("#delete-image-confirmation-modal").modal('show');
        }


        $('#deleteImageData').click(function() {
            $('.loader').show();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: $(this).attr('data-delete-link'),
                cache: false,
                processData: false,
                contentType: false,
                type: 'DELETE',
                success: function(result) {

                    console.log(result.result);
                    $('.loader').hide();
                    $("#delete-image-confirmation-modal").modal('hide');
                    if (result.status == 1) {
                        $.toast({
                            heading: 'Success',
                            text: result.message,
                            showHideTransition: 'fade',
                            icon: 'success',
                            position: 'top-right',
                            loader: false,
                        });

                         $(`.${result.result.classHide}`).attr( "style", "display: none !important;");

                        return true;
                    }
                    $.toast({
                        heading: 'Error',
                        text: result.message,
                        showHideTransition: 'fade',
                        icon: 'error',
                        position: 'top-right',
                        loader: false,
                    });
                },
                error: function(error) {
                    console.log(error);
                    $('.loader').hide();
                    $("#delete-image-confirmation-modal").modal('hide');
                }
            });
        });


        $('#statusData').click(function() {
            $('.loader').show();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: $(this).attr('data-status-link'),
                cache: false,
                processData: false,
                contentType: false,
                type: 'POST',
                success: function(result) {
                    $('.loader').hide();
                    $("#status-confirmation-modal").modal('hide');

                    if (result.status == 1) {
                        $.toast({
                            heading: 'Success',
                            text: result.message,
                            showHideTransition: 'fade',
                            icon: 'success',
                            position: 'top-right',
                            loader: false,
                        });
                        table.ajax.reload();
                        return true;
                    }
                    $.toast({
                        heading: 'Error',
                        text: result.message,
                        showHideTransition: 'fade',
                        icon: 'error',
                        position: 'top-right',
                        loader: false,
                    });
                },
                error: function(error) {
                    console.log(error);
                    $('.loader').hide();
                    $("#status-confirmation-modal").modal('hide');

                }
            });
        });

        $('#statusCancel,#reloadData').click(function() {
            table.ajax.reload();
        });

        // for alert message disppper after 3 sec
        // for alert message disppper after 3 sec
        $(document).ready(function() {
            setTimeout(function() {
                $('.alertdisapper').fadeOut();
            }, 4000);
            const isOpenPanel = $("body").hasClass("open-panel");
            if (isOpenPanel) {
                $("img[id='logoImg1']").css('display', 'none');
                $("img[id='logoImg2']").css('display', 'block');
            } else {
                $("img[id='logoImg1']").css('display', 'block');
                $("img[id='logoImg2']").css('display', 'none');
            }

            $(".navbar-toggle").on("click", function() {
                $(this).toggleClass("active");
                $('body').toggleClass("open-panel");

                const isOpenPanel = $("body").hasClass("open-panel");
                if (isOpenPanel) {
                    $("img[id='logoImg1']").css('display', 'none');
                    $("img[id='logoImg2']").css('display', 'block');

                } else {
                    $("img[id='logoImg1']").css('display', 'block');
                    $("img[id='logoImg2']").css('display', 'none');
                }

            });

            $(window).on('resize', function() {
                $('.open-panel').toggleClass('open-panel', $(window).width() < 1024);
                if ($(window).width() < 1024) {
                    $('body').removeClass('open-panel');
                } else {
                    // $('body').addClass('open-panel');
                }
            }).trigger('resize');

            notificationCountCall()

        function notificationCountCall()
        {
            $.ajax({
                url: "{{ route('notificationCount') }}",
                type: "post",
                data: { "_token": "{{ csrf_token() }}" },
                success: function(data){


                if(data.result.notificationCount != 0)
                {
                        $('.notificationCounterClass').addClass('counter notification-counter');
                        $('.notificationCounterClass').text(data.result.notificationCount);
                        $('.notificationCounterClass').removeClass('notification-hide-class');
                }
                }
            });
        }

        const myTimeout = setInterval(
        notificationCountCall
        , 3000);


            let isHideMenu = '';
            $('.hide-menu').on('click', function () {
                const isOpenPanel = $("body").hasClass("open-panel");
                if (isOpenPanel) {
                    // console.log('Yes, is hide');
                    setCookie('isHideMenu', isHideMenu = 1, 1);
                    isHideMenu = getCookie('isHideMenu');

                } else {
                    // console.log('No, is not hide');
                    setCookie('isHideMenu', isHideMenu = 0, 1);

                }
            });
             isHideMenu = getCookie('isHideMenu');
            // console.log(isHideMenu);
            if (isHideMenu == 1) {
                $('body').addClass('open-panel');

                $("img[id='logoImg1']").css('display', 'none');
                $("img[id='logoImg2']").css('display', 'block');
            } else {
                $('body').removeClass('open-panel');
                $("img[id='logoImg1']").css('display', 'block');
                $("img[id='logoImg2']").css('display', 'none');
            }
        });


        // Get a Cookie
        function getCookie(cName) {
            const name = cName + "=";
            const cDecoded = decodeURIComponent(document.cookie); //to be careful
            const cArr = cDecoded.split('; ');
            let res;
            cArr.forEach(val => {
                if (val.indexOf(name) === 0) res = val.substring(name.length);
            })
            return res;
        }

        // Set a Cookie
        function setCookie(cName, cValue, expDays) {
            let date = new Date();
            date.setTime(date.getTime() + (expDays * 24 * 60 * 60 * 1000));
            const expires = "expires=" + date.toUTCString();
            document.cookie = cName + "=" + cValue + "; " + expires + "; path=/";
        }

    </script>

    {{--
    *Created By LS
    * 24-02-2022
    * Tab Active
    --}}
    <script>
    $(document).ready(function(){
        $('button[data-bs-toggle="tab"]').on('show.bs.tab', function(e) {
            targetId = $(this).attr('data-bs-target');
            localStorage.setItem('activeTab', targetId);
        });

        var activeTab = localStorage.getItem('activeTab');

        if(activeTab){
            $($('[data-bs-target="' + activeTab + '"]')).tab("show");
        }
    });


    //Remove Active Tab
    $(document).ready(function(){
    $('.remove-active-class-tab').click(function(e){

        localStorage.removeItem("activeTab");
    });
    });


    //Image Zoom Effect
    $(".fancybox").fancybox({

        openEffect : 'none',

        closeEffect : 'none',

        arrows : false,

        type:"image",

        afterShow :function() {

        }

    });
    </script>
    @yield('js')
