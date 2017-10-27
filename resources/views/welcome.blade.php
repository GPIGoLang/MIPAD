<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!--begin::Web font -->
        <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
        <script>
            WebFont.load({
                google: {"families":["Montserrat:300,400,500,600,700","Roboto:300,400,500,600,700"]},
                active: function() {
                    sessionStorage.fonts = true;
                }
            });
        </script>
        <!--begin::Base Styles -->
		<link href="{{asset('metronic/vendors/base/vendors.bundle.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('metronic/demo/demo4/base/style.bundle.css')}}" rel="stylesheet" type="text/css" />
		<!--end::Base Styles -->
		<link rel="shortcut icon" href="{{asset('metronic/demo/demo4/media/img/logo/favicon.ico')}}" />
        <link rel="stylesheet" href="{{mix('css/app.css')}}">

        <script>
            window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
        </script>

        <title>MIPAD</title>

    </head>
    <body style="background-image: url({{ asset('metronic/app/media/img/bg/bg-5.jpg') }})"  class="m-page--boxed m-body--fixed m-header--static m-aside--offcanvas-default">
        <div id="root"></div>
    </body>
    <script type="text/javascript" src="{{mix('js/app.js')}}"></script>
    <script type="text/javascript" src="{{asset('metronic/vendors/base/vendors.bundle.js')}}"></script>
    <script type="text/javascript" src="{{asset('metronic/demo/demo4/base/scripts.bundle.js')}}"></script>
    <script type="text/javascript" src="{{asset('metronic/app/js/dashboard.js')}}"
</html>
