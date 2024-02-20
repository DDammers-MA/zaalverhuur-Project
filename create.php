<?php
require 'config.php';
require 'function.php';
$connection = dbConnect();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
   
        $zaalNaam = isset($_POST['naam']) ? $_POST['naam'] : '';

    
        $imageFileName = $_FILES['image']['name'];
        $imagePath = 'img/' . $imageFileName;

        move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../' . $imagePath);

        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
     
            die('File upload failed with error code: ' . $_FILES['image']['error']);
        }

        $query = "INSERT INTO zaalen (naam, image) VALUES (?, ?)";
        $stmt = $connection->prepare($query);

      
        $stmt->bindParam(1, $zaalNaam);
        $stmt->bindParam(2, $imagePath);

    
        $stmt->execute();
    } else {
  
        die('No file was uploaded.');
    }
}
?>
<form action="create.php" method="post" enctype="multipart/form-data">

    <input type="file" name="image" id="imgToUpload">
    <input type="text" name="naam"> 
    <input type="submit" value="submit" name="submit">
</form>
