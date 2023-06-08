<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div style="display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  height: 100vh;
}">
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
        echo "<div style='color:white;font-size:24px;'>Sisestatud! Eeldusel, et hääletusaeg kestab ja sa pole 12. hääletaja.";
    } else {
        echo "Error: " . $sql . " " . mysqli_error($conn);
    }
}
?>
<br><iframe style="margin:10%;margin-left:15%" src="https://giphy.com/embed/l6Td5sKDNmDGU" width="480" height="270" frameBorder="0" class="giphy-embed" allowFullScreen></iframe><p><a href="https://giphy.com/gifs/cat-swag-l6Td5sKDNmDGU"></a></p></div>
    <button style="background-color: #71E8BB;
    color: #fff;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;" onclick="window.location.href='https://steinbergkarina.ikt.khk.ee/veebiarendus/haal/'">Tagasi pealehele</button>
</div>
</body>
</html>
