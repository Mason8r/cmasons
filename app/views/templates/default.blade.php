<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="SITE DESCRIPTION">
    <meta name="author" content="Waverley Media">
    <link rel="icon" href="../../favicon.ico">

    <title>@yield('title' , Site::pluck('name'))</title>


    <!-- Bootstrap core CSS -->
    <link href="{{asset('assets/stylesheets/frontend.css')}}" rel="stylesheet">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
 <body>
      <div class="container main">

      <div class="row">
        <div class="col-lg-12">
          @include('templates.menu')
        </div>
      </div>

      <div class="row content">
        <div class="col-lg-12">
          @yield('content')
        </div>
      </div>

      <hr />
      <div class="row">
        <div class="col-lg-12">
          <p class="text-muted small">{{Site::pluck('copyright')}}</p>
        </div>
      </div>

   </div> <!-- /container -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="{{asset('assets/javascript/frontend.js')}}"></script>
    <script>
      //For the script 
      var url = '{{url('/')}}';
    </script>
    @yield('script')
  </body>
</html>
