<!DOCTYPE html>
<html>
<head>
	<title>Login Area</title>
</head>
<body>
	<form id="email-action" action="login" method="POST">
		<label>Email</label>
		<input type="text" name="email">
		<label>Password</label>
		<input type="password" name="password">
		<input type="submit" name="save">
	</form>

	<form id="cep-action" action="trytofindcep" method="POST">
		<label>CEP</label>
		<input type="text" name="cep">
		<input type="submit" name="fetch">
	</form>
</body>
</html>