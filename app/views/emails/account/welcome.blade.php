<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Welcome {{$first_name}}</h2>

		<p>You are almost done registering. You just need to click on the link below to activate your account and confirm your email address!</p>

		<p>{{link_to('account/activate/'.$activationCode.'/'.$id , "Click Here to Activate Your Account!")}}</p>
	</body>
</html>
