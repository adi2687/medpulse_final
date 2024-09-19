<?php

require "../dbh.inc.php";

// Link the external CSS file

// Correct query to select distinct clinics and their addresses
$query = "SELECT DISTINCT clinic, address FROM doctors";
$stmt = $pdo->prepare($query);
$stmt->execute();

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Loop through distinct clinics
foreach ($result as $row) {
    $clinic = $row['clinic'];
    $address = $row['address'];

    // Query to get doctors for each clinic
    $query1 = "SELECT * FROM doctors WHERE clinic = :clinic";
    $stmt1 = $pdo->prepare($query1);
    $stmt1->bindParam(":clinic", $clinic);
    $stmt1->execute();

    $result1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    // Display clinic and doctor details with enhanced styling and animations
    echo "<div class='clinic-container'>";
    echo "<div class='clinic-title'>Clinic: " . htmlspecialchars($clinic) . "</div>";
    echo "<div class='clinic-address'>Address: " . htmlspecialchars($address) . "</div>";

    foreach ($result1 as $doctor) {
        // echo $row['clinic'];
        $name= $doctor['fname']." ".$doctor['lname'];
        $query1 = "SELECT COUNT(*) AS rating_count FROM rating WHERE doctor=:name";
        $stmt1 = $pdo->prepare($query1);
        $stmt1->bindParam(":name", $name);
        $stmt1->execute();
        $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);
// ''
        $count=$result1['rating_count'];
        $specializations = $doctor['specialization1'] . " " . $doctor['specialization2'] . " " . $doctor['specialization3'] . " " . $doctor['specialization4'];
        echo "<div class='doctor-info'>";
        echo "<div class='doctor-name'>" . htmlspecialchars($doctor['fname'] . " " . $doctor['lname']) . "</div>";
        echo "<div class='doctor-details'>Contact: " . htmlspecialchars($doctor['number']) . "</div>";
        echo "<div class='doctor-details'>Email: " . htmlspecialchars($doctor['email']) . "</div>";
        echo "<div class='specializations'>Specializations: " . htmlspecialchars($specializations) . "</div><br>";
        echo "<div class='rating'>Rating: ".htmlSpecialchars($doctor['rating'])." stars . Rated by $count patients</div>";
        echo "</div><br>";
    }

    echo "</div><br>";
}
?>