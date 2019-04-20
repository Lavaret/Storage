<!doctype html>

<html lang="pl">
<head>
  <meta charset="utf-8">

  <title>Zadanie prorgamistyczne unity-t</title>
  <meta name="description" content="Zadanie prorgamistyczne unity-t">
  <meta name="author" content="Justyna Sieja">
  <link rel="stylesheet" type="text/css" href="styles/style.css">
</head>
<body>
	<?php
		 require_once('Services/DatabaseService.php');
		 require_once('Services/MessageService.php');
		 require_once('StorageController.php');
		 session_start();
	?>
	<form method="post" action="redirectPage.php">
		Iość: <br/>
		<input type="number" name="amount">
		<br/>
		Cena: <br/>
		<input type="number" step="0.01" name="price">
		<input type="submit" value="Save">
	</form>

	<br/>
	<br/>

	<?php
		$database = new DatabaseService();
		$messageService = new MessageService();
		$values = $database->getAll();
		$lp = 0;
	?>

	<table>
	<?php if($values) : ?>
		<tr>
			<td>Lp.</td>
			<td>Ilość</td>
			<td>Cena</td>
		</tr>
	<?php endif; ?>
	<?php foreach ($values as $value) {?>
		<tr>
			<td><?php echo ++$lp; ?></td>
			<td><?php echo $value->amount; ?></td>
			<td><?php echo $value->price; ?></td>
		</tr>
	<?php } ?>

	</table>

	<br/>
	<br/>

	<form method="post" action="redirectPage.php">
		Cegły do wydania: <br/>
		<input type="number" name="pull-amount">

		<input type="submit" value="Save">
	</form>

	<?php
		$message = $messageService->getMessage('sell-info');
		if ($message) {
			echo $message;
		}

		echo "<br/>";

		$errorMessage = $messageService->getErrorMessage();
		if ($errorMessage) {
			echo $errorMessage;
		}
	?>

</body>
</html>
