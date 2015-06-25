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
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/glyphicon.css" rel="stylesheet">

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

    <script type="text/javascript" src="http://www.google.com/jsapi"></script>

    <script src="/js/dygraph-combined.js"></script>
    <script src="/js/ICanHaz.min.js"></script>
    <script src="/js/skycons.js"></script>

    <script src="//js.pusher.com/2.2/pusher.min.js" type="text/javascript"></script>
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

    {{ $content or null }}
    @yield('content')

</div>


<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="/js/bootstrap.min.js"></script>

<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>

</body>
</html>