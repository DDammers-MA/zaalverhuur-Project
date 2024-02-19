<?php
require 'config.php';
require 'function.php';
$connection = dbConnect();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Check if a file was uploaded
    if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
        // Assuming you have form fields for zaal name and image file upload
        $zaalId = isset($_POST['zaal_id']) ? $_POST['zaal_id'] : '';
        $voornaam = isset($_POST['voornaam']) ? $_POST['voornaam'] : '';
        $achternaam = isset($_POST['achternaam']) ? $_POST['achternaam'] : '';
        $telefoon = isset($_POST['telefoon']) ? $_POST['telefoon'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';

        // Rest of your code...

        // Insert data into the database
        $query = "INSERT INTO booking_records (zaal_id, Voornaam, Achternaam, Telefoon, Email, start_datum, eind_datum, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $connection->prepare($query);

        // Bind parameters
        $stmt->bindParam(1, $zaalId);
        $stmt->bindParam(2, $voornaam);
        $stmt->bindParam(3, $achternaam);
        $stmt->bindParam(4, $telefoon);
        $stmt->bindParam(5, $email);
        $stmt->bindParam(6, $start_datum);
        $stmt->bindParam(7, $eind_datum);
        $stmt->bindParam(8, $imagePath); // Store the file path in the database

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
