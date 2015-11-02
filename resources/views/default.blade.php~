<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Blockchain Visualizer</title>

    <!-- UI Custom CSS-->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.dark.min.css') }}" rel="stylesheet">
    <link rel="alternate stylesheet" type="text/css" href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.dark.min.css') }}" rel="stylesheet" title="dark">
    <link rel="alternate stylesheet" type="text/css" href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.light.min.css') }}" rel="stylesheet" title="light">

    <!-- Custom Fonts -->
    <link href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>

    <!-- ./navbar start -->
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Design <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#" onclick="setActiveStyleSheet('dark'); return false;">Dark Theme</a></li>
                            <li><a href="#" onclick="setActiveStyleSheet('light'); return false;">Light Theme</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                </ul>
                <form class="navbar-form navbar-right" role="search" abineguid="DCF5FB0DE82B4B138AF5612DF2A85AAE">
                    <div class="form-group" style="padding-right: 20px;">
                        <input type="checkbox" id="alert_on_new_block" checked> Alert on new block
                    </div>
                    <div class="form-group">
                        <input id="alert_tx_id" type="text" style="width:400px" class="form-control" placeholder="Enter TX ID for Alert on First Confirmation">
                    </div>
                </form>
            </div>
        </div>
    </nav>
    <div id="wrapper">
        @yield('content')
    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>

    <!-- Flot Charts JavaScript -->
    <script src="{{ asset('bower_components/flot/excanvas.min.js') }}"></script>
    <script src="{{ asset('bower_components/flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('bower_components/flot/jquery.flot.pie.js') }}"></script>
    <script src="{{ asset('bower_components/flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ asset('bower_components/flot/jquery.flot.time.js') }}"></script>
    <script src="{{ asset('bower_components/flot.tooltip/js/jquery.flot.tooltip.min.js') }}"></script>
    <script src="{{ asset('js/flot-data.js') }}"></script>
    <script src="{{ asset('js/moment.js') }}"></script>
    <script src="{{ asset('js/design.js') }}"></script>
    @yield('scripts')

</body>
</html>
