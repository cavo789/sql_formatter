<?php

declare(strict_types=1);

/**
 * AUTHOR : AVONTURE Christophe
 *
 * Written date : 9 october 2018
 *
 * Interface allowing to copy a non formatted SQL statement (in one line) and
 * get a SQL statement that is formatted (on multiple lines) and using color
 * syntaxing. *
 *
 * @see SQL-Formatter on https://github.com/jdorn/sql-formatter
 */

$task = filter_input(INPUT_POST, 'task', FILTER_SANITIZE_STRING);

if ($task == 'format') {
    // Retrieve the SQL statement
    $SQL = base64_decode(filter_input(INPUT_POST, 'sql', FILTER_SANITIZE_STRING));

    // Include the library
    require_once __DIR__ . '/lib/SqlFormatter.php';

    // Return the formatted SQL
    header('Content-Type: text/html');
    echo SqlFormatter::format($SQL);

    die();
}

// Sample values
$SQL = 'SELECT LAT_N, CITY, TEMP_F FROM STATS, STATION ' .
    'WHERE MONTH = 7 AND STATS.ID = STATION.ID ORDER BY TEMP_F';

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8"/>
		<meta name="author" content="Christophe Avonture" />
		<meta name="robots" content="noindex, nofollow" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8;" />
		<title>SQL Formatter</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
	</head>
	<body>
		<div class="container">
			<div class="page-header"><h1>SQL Formatter</h1></div>
			<div class="container">
				<div class="form-group">
					<label for="SQL">Copy/Paste your SQL statement in the textbox below then click on the Format button:</label>
					<textarea class="form-control" rows="5" id="SQL" name="SQL"><?php echo $SQL; ?></textarea>
				</div>
				<button type="button" id="btnFormat" class="btn btn-primary">Format</button>
				<hr/>
				<pre id="Result"></pre>
				<i style="display:block;font-size:0.6em;"><a href="https://github.com/jdorn/sql-formatter">SQL Formatter written by Jeremy Dorn</a></i>
			</div>
		</div>
		<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
		<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
		<script type="text/javascript">
			$('#btnFormat').click(function(e)  {

				e.stopImmediatePropagation();

				var $data = new Object;
				$data.task = "format";
				$data.sql = window.btoa($('#SQL').val());

				$.ajax({
					beforeSend: function() {
						$('#Result').html('<div><span class="ajax_loading">&nbsp;</span><span style="font-style:italic;font-size:1.5em;">Formatting...</span></div>');
						$('#btnFormat').prop("disabled", true);
					},
					async: true,
					type: "POST",
					url: "<?php echo basename(__FILE__); ?>",
					data: $data,
					datatype: "html",
					success: function (data) {
						$('#btnFormat').prop("disabled", false);
						$('#Result').html(data);
					}
				}); // $.ajax()
			});
		</script>
	</body>
</html>
