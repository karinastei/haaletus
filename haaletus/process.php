<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php
include_once 'database.php';
if(isset($_POST['save']))
{
    $Haaletaja_Eesnimi = $_POST['Haaletaja_Eesnimi'];
    $Haaletaja_Perenimi = $_POST['Haaletaja_Perenimi'];
    $Otsus = $_POST['Otsus'];

    $sql = "SET @H_lopu_aeg = (SELECT MIN(H_lopu_aeg) FROM LOGI);
    SET @Nimi_exists = EXISTS(SELECT * FROM HAALETUS WHERE '$Haaletaja_Eesnimi' = Haaletaja_Eesnimi AND '$Haaletaja_Perenimi' = Haaletaja_Perenimi);
    
	 IF (NOW() < @H_lopu_aeg) AND (!@Nimi_exists) THEN
	 	INSERT INTO HAALETUS(Haaletaja_Eesnimi, Haaletaja_Perenimi, Haaletuse_aeg, Otsus) VALUES ('$Haaletaja_Eesnimi','$Haaletaja_Perenimi',NOW(),'$Otsus');
	 ELSEIF (NOW() < @H_lopu_aeg) AND (@Nimi_exists)THEN
	 	UPDATE HAALETUS SET Haaletaja_Eesnimi='$Haaletaja_Eesnimi', Haaletaja_Perenimi='$Haaletaja_Perenimi', Haaletuse_aeg=NOW(), Otsus='$Otsus' WHERE '$Haaletaja_Eesnimi' = Haaletaja_Eesnimi AND '$Haaletaja_Perenimi' = Haaletaja_Perenimi;
	 ELSEIF (@H_lopu_aeg IS NULL) THEN
		 INSERT INTO HAALETUS(Haaletaja_Eesnimi, Haaletaja_Perenimi, Haaletuse_aeg, Otsus) VALUES ('$Haaletaja_Eesnimi','$Haaletaja_Perenimi',NOW(),'$Otsus');
	 END IF;";

    if (mysqli_multi_query($conn, $sql)) {
        echo "New record created successfully!";
    } else {
        echo "Error: " . $sql . " " . mysqli_error($conn);
    }
}
?>
<button onclick="window.location.href='https://steinbergkarina.ikt.khk.ee/veebiarendus/haaletus/'">Tagasi pealehele</button>
</body>
</html>