<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
    <link rel="image_src" href="https://s3-eu-west-1.amazonaws.com/static.arthurguy.co.uk/images/meta_logo.png" />
    <link rel="Shortcut Icon" href="https://s3-eu-west-1.amazonaws.com/static.arthurguy.co.uk/images/ArthurGuy.ico" />
    <link rel="icon" type="image/vnd.microsoft.icon" href="https://s3-eu-west-1.amazonaws.com/static.arthurguy.co.uk/images/ArthurGuy.ico" />
    <title>@yield('title', 'data.arthurguy.co.uk')</title>

    <!-- Bootstrap -->
    <link href="{{ elixir('css/dashboard.css') }}" rel="stylesheet">

</head>
<body>

<div class="container-fluid">

    @if($errors->any())
    <div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    @if(Session::has('success'))
    <div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>{{ Session::get('success') }}</div>
    @endif

    @yield('content')

</div>


<script src="{{ elixir('js/dashboard.js') }}"></script>
<script src="//js.pusher.com/2.2/pusher.min.js" type="text/javascript"></script>

</body>
</html>