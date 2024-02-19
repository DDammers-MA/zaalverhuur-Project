<?php
require 'function.php';
$connection = dbConnect();

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Process the form data and perform booking-related actions
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];

    // Insert data into the booking_records table
    $query = "INSERT INTO booking_record (Voornaam, Achternaam, Telefoon, Email, start_datum, eind_datum) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(1, $firstName);
    $stmt->bindParam(2, $lastName);
    $stmt->bindParam(3, $phone);
    $stmt->bindParam(4, $email);
    $stmt->bindParam(5, $startDate);
    $stmt->bindParam(6, $endDate);

    $stmt->execute();

    // Redirect to a confirmation page or show a success message
    header('Location: index.php');
    exit();
}

$stmt = $connection->prepare('SELECT * FROM `zaalen` WHERE `id` = :id');
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$zaal = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!-- Your HTML content here -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Document</title>
</head>
<body>
    
<header>

</header>

<main>


<li class="main__zalenListItem">
                        <img class="main__zaalImg" src="img/<?php echo $zaal['image']; ?>" alt="">
                            <p class="main__zaalNaam"><?php echo $zaal['naam'];?></p>
                        </li>

<form action="" method="post">
    <!-- Your form fields here -->
  
    <input type="text" name="first_name" placeholder="First Name" required>
    <input type="text" name="last_name" placeholder="Last Name" required>
    <input type="text" name="phone" placeholder="Phone" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="date" name="start_date" required>
    <input type="date" name="end_date" required>

    <input type="submit" name="submit" value="Book Now">
</form>
</main>
</body>
</html>
