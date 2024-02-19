<?php
require 'function.php';
$connection = dbConnect();

$id = $_GET['id'];

$zaalNaam = isset($_POST['zaal_naam']) ? $_POST['zaal_naam'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Process the form data and perform booking-related actions
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $startDate = $_POST['start_date'];
    $tijd = isset($_POST['time']) ? $_POST['time'] : '';
    $hours = $_POST['hours'];
    $maand = $_POST['maand'];
    $timezone = $_POST['timezone'];

    // Check if the time slot is available
    $nextAvailableTime = isTimeSlotAvailable($zaalNaam, $startDate, $tijd, $hours);

    if ($nextAvailableTime !== null) {
        // Time slot is not available, handle accordingly (e.g., show an error message)
        echo "The selected time slot is not available. The next available time slot is: " . $nextAvailableTime;
    } else {
        // Insert data into the booking_records table
        $query = "INSERT INTO booking_record (zaal_naam, Voornaam, Achternaam, Telefoon, Email, dag, tijd, uuren, maand) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(1, $zaalNaam);
        $stmt->bindParam(2, $firstName);
        $stmt->bindParam(3, $lastName);
        $stmt->bindParam(4, $phone);
        $stmt->bindParam(5, $email);
        $stmt->bindParam(6, $startDate);

        $europeanTime = date('H:i', strtotime($tijd));
        $stmt->bindParam(7, $europeanTime);

        $stmt->bindParam(7, $tijd);
        $stmt->bindParam(8, $hours);
        $stmt->bindParam(9, $maand);
        

        $stmt->execute();

        // Redirect to a confirmation page or show a success message
        header('Location: index.php');
        exit();
    }
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
        <input type="tel" name="phone" placeholder="Phone" required>
        <input type="email" name="email" placeholder="Email" required>

        <!-- Updated date fields with day and month -->
        <label for="start_date">maand:</label>
        <input type="month" name="maand" required>

        <label for="dag">dag:</label>
        <input type="number" min="1" max="31" name="start_date" required>

        <!-- Updated field name from 'tijd' to 'time' -->
        <input type="hidden" name="timezone" value="Europe/Amsterdam">

        <label for="time">time:</label>
        <input type="time" name="time" step="00:00:00" required>

        <label for="end_date">Uuren:</label>
        <input type="number" name="hours" required>

        <!-- Hidden field to pass zaal_naam -->
        <input type="hidden" name="zaal_naam" value="<?php echo $zaal['naam']; ?>">

        <input type="submit" name="submit" value="Book Now">
    </form>
</main>
</body>
</html>

<?php
function isTimeSlotAvailable($zaalNaam, $dag, $tijd, $uuren) {
    global $connection;

    // Calculate end time based on start time and duration
    $endTime = date('H:i', strtotime($tijd) + $uuren * 3600);

    $query = "SELECT MIN(ADDTIME(tijd, SEC_TO_TIME(uuren * 3600))) AS next_available
              FROM booking_record 
              WHERE zaal_naam = ? 
              AND dag = ? 
              AND ((tijd <= ? AND ADDTIME(tijd, SEC_TO_TIME(uuren * 3600)) >= ?)
                  OR (tijd <= ? AND ADDTIME(tijd, SEC_TO_TIME(uuren * 3600)) >= ?)
                  OR (tijd >= ? AND ADDTIME(tijd, SEC_TO_TIME(uuren * 3600)) <= ?))";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(1, $zaalNaam);
    $stmt->bindParam(2, $dag);
    $stmt->bindParam(3, $tijd);
    $stmt->bindParam(4, $endTime);
    $stmt->bindParam(5, $tijd);
    $stmt->bindParam(6, $endTime);
    $stmt->bindParam(7, $tijd);
    $stmt->bindParam(8, $endTime);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $nextAvailableTime = $result['next_available'];

    return $nextAvailableTime; // Return the next available time (even if null)
}

?>

