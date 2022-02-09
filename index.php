<?php 
require 'result.php'; 

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>iMTD</title>

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="style.css">
	
</head>
<body>
	<?php
		if(isset($error))
		{
			?>
			<div class="row">
				<div class="container">
					<div class="alert alert-danger"> <?= $error ?> </div>
				</div>
			</div>
			<?php
		}
	?>
	

	<?php
		if(isset($result))
		{
			?>
			<div class="row">
				<div class="container">
					<h3 style="margin: 10px 0px;"> Analyzed Result </h3>
					<hr>
					<div class="result"> <?= $result ?> </div>
				</div>
			</div>
			<?php
		}
		else
		{
			?>

			<div class="row">
				<div class="container">	
					<h3 style="margin: 10px 0px;"> Analyze String </h3>
					<form method="post" action="">
						<textarea name="text_string" class="form-control"></textarea>
						<button type="submit" name="submit" class="btn btn-primary" style="margin: 10px 0px;">Analyze</button>
					</form>
				</div>
			</div>

			<?php
		}
	?>

</body>
</html>