<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200;600&display=swap" rel="stylesheet">
    <link rel="stylesheet"
          href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <script src="https://www.google.com/recaptcha/api.js?onload=vueRecaptchaApiLoaded&render=explicit" async defer>
    </script>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}"/>
    <link rel="stylesheet" href="/css/global.css"/>

    <title>{{env('APP_NAME')}}</title>
</head>
<body>

@include('flash::message')

@yield('body')

<script type="text/javascript">window.$crisp = [];
    window.CRISP_WEBSITE_ID = "{{ env('CRISP_APP_ID') }}";
    (function () {
        d = document;
        s = d.createElement("script");
        s.src = "https://client.crisp.chat/l.js";
        s.async = 1;
        d.getElementsByTagName("head")[0].appendChild(s);
    })();</script>
<script>
    $crisp.push(["set", "user:email", "{{ session('authMail') }}"]);
</script>
<script src="{{ mix('js/app.js') }}"></script>
<script>
    if (document.getElementById('flashData')) {
        let popup = document.getElementById('flashData');
        let popupDelete = document.getElementById('alert-flash-dissmiss');

        popup.style.left = "42px";

        popupDelete.addEventListener('click', e => {
            popup.style.left = "-100%";
        }, {passive: true});

        setTimeout(() => {
            popup.removeAttribute('style');
        }, 2000);
    }
</script>
</body>
</html>

