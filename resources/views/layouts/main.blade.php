<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>data.ArthurGuy.co.uk</title>

    <link href="{{ elixir('css/all.css') }}" rel="stylesheet">

    <script type="text/javascript" src="http://www.google.com/jsapi"></script>

    <script src="{{ elixir('js/app.js') }}"></script>

    <script src="//js.pusher.com/2.2/pusher.min.js" type="text/javascript"></script>
</head>
<body>

<nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ route('home') }}">data.ArthurGuy.co.uk</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class=""><a href="{{ route('stream.index') }}">Streams</a></li>
                <li class=""><a href="{{ route('graph.index') }}">Graphs</a></li>
                <li class=""><a href="{{ route('trigger.index') }}">Triggers</a></li>
                <!--<li class=""><a href="{{ route('variable.index') }}">Variables</a></li>
                <li class=""><a href="{{ route('apiresponse.index') }}">Responses</a></li>-->
                <li class=""><a href="{{ route('locations.index') }}">Locations</a></li>
                <li class=""><a href="{{ route('dashboard') }}">Dashboard</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                @if (Auth::guest())
                <li><a href="/login">Login</a></li>
                @else
                <li><a href="/logout">Logout</a></li>
                @endif
            </ul>
        </div>
    </div>
</nav>

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


<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>

</body>
</html>