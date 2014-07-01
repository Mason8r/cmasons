<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="SITE DESCRIPTION">
    <meta name="author" content="Waverley Media">
    <link rel="icon" href="../../favicon.ico">

    <title>Installation!</title>


    <!-- Bootstrap core CSS -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
 <body>

    <div class="container">

      <div class="row">
        <div class="col-lg-12">

        	<h1>Installation Time!</h1>
			    {{ Form::open(array('url' => 'install' , 'role' => 'form')) }}
			    <div class="form-group">
				    {{ Form::label('sitename', 'Site Name *') }}
				    {{ Form::text('sitename', '', array('class' => 'form-control') ) }}
				    <p class="text-danger">{{ $errors->first('sitename') }}</p>
				</div>
				<div class="form-group">
				    {{ Form::label('creator', 'First name *') }}
				    {{ Form::text('creator', '', array('class' => 'form-control') ) }}
				    <p class="text-danger">{{ $errors->first('creator') }}</p>
				</div>
			    <div class="form-group">
				    {{ Form::label('email', 'Admin E-Mail Address (use this for login) *') }}
				    {{ Form::email('email', '', array('class' => 'form-control') ) }}
				    <p class="text-danger">{{ $errors->first('email') }}</p>
				</div>
				<hr />
				<div class="form-group">
				    {{ Form::label('password', 'Password *') }}
				    {{ Form::password('password', array('class' => 'form-control') ) }}
				    <p class="text-danger">{{ $errors->first('password') }}</p>
				</div>
				<div class="form-group">
				    {{ Form::label('password_confirmation', 'Confirm') }}
				    {{ Form::password('password_confirmation', array('class' => 'form-control') ) }}
					<p class="text-danger">{{ $errors->first('password_confirmation') }}</p>
				</div>
				<hr />
				<div class="form-group">
				    {{ Form::label('copyright', 'Copyright info *') }}
				    {{ Form::text('copyright', '&copy; '.strtotime('now',date('Y')) , array('class' => 'form-control') ) }}
					<p class="text-danger">{{ $errors->first('copyright') }}</p>
				</div>
				<div class="form-group">
				    {{ Form::label('description', 'Description *') }}
				    {{ Form::textarea('description', 'Site Made in CMASONS' , array('class' => 'form-control') ) }}
					<p class="text-danger">{{ $errors->first('description') }}</p>
				</div>
				<div class="form-group text-center">
			    {{ Form::submit('Go!', array( 'class' => 'btn btn-primary btn-lg' )) }}
			   	</div>
				{{ Form::close() }}
      </div>

   </div> <!-- /container -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  </body>
</html>