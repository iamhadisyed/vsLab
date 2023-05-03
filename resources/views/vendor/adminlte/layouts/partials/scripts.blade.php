<!-- REQUIRED JS SCRIPTS -->

<!-- JQuery and bootstrap are required by Laravel 5.3 in resources/assets/js/bootstrap.js-->
<!-- Laravel App -->

<script src="{{ mix('/js/app.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('packages/bootstrap-session-timeout/dist/bootstrap-session-timeout.js') }}"></script>
{{--<script src="{{ URL::asset('packages/moment/moment.js') }}"></script>--}}
{{--<script src="{{ URL::asset('packages/moment/moment-timezone-with-data.js') }}"></script>--}}
<script type="text/javascript">
    $(document).ready(function() {
        $.sessionTimeout({
            message: 'Your session will expire soon!',
            //keepAliveUrl: '/workspace/keepAlive',
            keepAlive: false,
            logoutUrl: '/logout',
            redirUrl: '/session-timeout',
            warnAfter:  "{{ env('BROWSER_TIMEOUT_WARNING') }}", // 1620000  27 mins, 60000,
            redirAfter: "{{ env('BROWSER_TIMEOUT') }}", // 1800000  30 mins, 120000,
            countdownMessage: 'Redirecting in {timer} seconds.',
            countdownBar: true,
            onWarn: function (opt) {
                var seconds = 300; //180; //60;
                var lastTick = +new Date();
                var ONE_SECOND = 1000;

                Swal({
                    title: '<strong>Session Timeout!</strong>',
                    type: 'warning',
                    html: 'Your session will expire in <b id="countdown">' + seconds + '</b> seconds !!' +
                    '<br \><br \>Please take action now.'
                });

                function tick() {
                    var now = +new Date(),
                        nextTick = 2 * ONE_SECOND - (now - lastTick);

                    lastTick = now;
                    if (--seconds) {
                        setTimeout(tick, nextTick > ONE_SECOND ? ONE_SECOND : nextTick);
                    }
                    $("b[id=countdown]").html(seconds);
                    if (seconds === 0) {
                        window.location = opt.logoutUrl;
                    }
                }

                setTimeout(tick, ONE_SECOND);
            },
            onRedir: function (opt) {
                window.location = opt.redirUrl;
            }
        });

        $('#main_header_timezone').html(moment.tz.guess(true));
        $('#main_header_localtime').html(moment().format('MMMM Do YYYY, h:mm:ss a'));

        $(window).bind('mouseover', (function () { // detecting DOM elements
            window.onbeforeunload = null;
        }));

        $(window).bind('mouseout', (function () { //Detecting event out of DOM
            window.onbeforeunload = ConfirmLeave;
        }));

        function ConfirmLeave() {
            if (performance.navigation.type === 1 || performance.navigation.type === 2) { //detecting refresh page(doesnt work on every browser)
            }
            else {
                logOutUser();
            }
        }

        $(document).bind('keydown', function (e) { //detecting alt+F4 closing
            //shortcuts for ALT+TAB and Window key , //shortcuts for F5 and CTRL+F5 and CTRL+R
            if (e.keyCode === 91 || e.keyCode === 18 || e.keyCode === 116 || (e.ctrlKey && e.keyCode === 82)) {}
            else if (e.altKey && e.keyCode === 115) {
                logOutUser();
            }
        });

        function logOutUser() {
            $.post("client-closed", function () {
                },
                'json'
            );
        }

    });
</script>

@stack('dataTable-scripts')

<!-- Optionally, you can add Slimscroll and FastClick plugins.
   Both of these plugins are recommended to enhance the
   user experience. Slimscroll is required when using the
   fixed layout. -->
