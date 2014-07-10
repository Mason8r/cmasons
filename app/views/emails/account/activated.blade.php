<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Welcome {{$first_name}}</h2>
		<p>You are now activated. Login to the website here:</p>
		<p>{{link_to('login','Login')}}</p>
		<p>Use the email address and password you signed up with.</p>
	</body>
</html>
