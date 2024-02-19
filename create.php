<?php
require 'config.php';
require 'function.php';
$connection = dbConnect();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Check if a file was uploaded
    if (isset($_FILES['image'])) {
        // Assuming you have form fields for zaal name and image file upload
        $zaalNaam = isset($_POST['naam']) ? $_POST['naam'] : '';

        // File upload handling
        $imageFileName = $_FILES['image']['name'];

        $imagePath = 'img/' . $imageFileName;
        move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '../' . $imagePath);
        
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            // Handle file upload error
            die('File upload failed with error code: ' . $_FILES['image']['error']);
        }
        
        // Insert data into the database
        $query = "INSERT INTO zaalen (naam, image) VALUES (?, ?)";
        $stmt = $connection->prepare($query);
        
        // Bind parameters
        $stmt->bindParam(1, $zaalNaam);
        $stmt->bindParam(2, $imageFileName); // Store only the filename in the database
        
        // Execute the statement
        $stmt->execute();
    } else {
        // Handle case where form submitted without a file
        die('No file was uploaded.');
    }
}
?>

<form action="create.php" method="post" enctype="multipart/form-data">
    <!-- Your form fields go here -->
    <input type="file" name="image" id="imgToUpload">
    <input type="text" name="naam">
    <input type="submit" value="submit" name="submit">
</form>
