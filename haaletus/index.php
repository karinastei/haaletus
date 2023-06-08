<?php
include_once 'database.php';
$haaletusresult = mysqli_query($conn,"SELECT * FROM HAALETUS");
$tulemusedresult = mysqli_query($conn,"SELECT * FROM TULEMUSED");

if (isset($_POST['clear_data'])) {
    $conn->query("TRUNCATE TABLE HAALETUS");
    $conn->query("TRUNCATE TABLE LOGI");
    $conn->query("TRUNCATE TABLE TULEMUSED");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}


//andmebaasist lõpuaeg
$query = "SELECT MIN(H_lopu_aeg) FROM LOGI";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_row($result);
$endTime = strtotime($row[0]);

$jsEndTime = date('Y/m/d H:i:s', $endTime);

echo "<script>
var endTime = new Date('$jsEndTime');
var timer = setInterval(function() {
    var now = new Date().getTime();
    var distance = endTime - now;
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    document.getElementById('countdown').innerHTML = minutes + 'm ' + seconds + 's ';
    if (distance < 0) {
        clearInterval(timer);
        document.getElementById('countdown').innerHTML = ' &#128526; &#9973; &#127774;';
    }
}, 1000);
</script>";

?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
  <body>
	<form method="post" action="process.php" class="instertform">
	     <p class="countdown">Aeg hääletuse lõpuni: <span style='font-size:25px;'id="countdown"></span></p>
		Eesnimi:<br>
		<input type="text" name="Haaletaja_Eesnimi">
		<br>
		Perenimi:<br>
		<input type="text" name="Haaletaja_Perenimi">
		<br>
		Otsus:<br>
		<select name="Otsus">
			<option value="Poolt">Poolt</option>
			<option value="Vastu">Vastu</option>
		</select><br><br>
		<input type="submit" name="save" value="Hääleta">
   
	</form>

	<table>
  <tr>
  <td class="paks">Eesnimi</td>
  <td class="paks">Perenimi</td>
  <td class="paks">Hääletuse aeg</td>
  <td class="paks">Otsus</td>
  <td class="paks">Hääletaja id</td>
  </tr>
<?php
$i=0;
while($row = mysqli_fetch_array($haaletusresult)) {
?>
<tr>
  <td><?php echo $row["Haaletaja_Eesnimi"]; ?></td>
  <td><?php echo $row["Haaletaja_Perenimi"]; ?></td>
  <td><?php echo $row["Haaletuse_aeg"]; ?></td>
  <td><?php echo $row["Otsus"]; ?></td>
  <td><?php echo $row["Haaletaja_id"]; ?></td>
</tr>
<?php
$i++;
}
?>
</table>

<table>
  <tr>
  <td class="paks">Haaletanute arv</td>
  <td class="paks">Hääletuse alguse aeg</td>
  <td class="paks">Hääletuse lõpu aeg</td>
  <td class="paks">Poolt</td>
  <td class="paks">Vastu</td>
  </tr>
<?php
$i=0;
while($row = mysqli_fetch_array($tulemusedresult)) {
?>
<tr>
  <td><?php echo $row["Haaletanute_arv"]; ?></td>
  <td><?php echo $row["H_alguse_aeg"]; ?></td>
  <td><?php echo $row["H_lopu_aeg"]; ?></td>
  <td><?php echo $row["Poolt"]; ?></td>
  <td><?php echo $row["Vastu"]; ?></td>
</tr>
<?php
$i++;
}
?>
</table>
<form method="post">
    <input type="submit" name="clear_data" value="Tühjenda tabelid">
</form>

  </body>
</html>
