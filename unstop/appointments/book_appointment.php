<?php

require "../dbh.inc.php";

$diseaseInput = trim($_POST['disease']);
if ($diseaseInput) {
    $jsonFile = 'diseases.json';
    $jsonData = file_get_contents($jsonFile);
    $diseaseToSpecialization = json_decode($jsonData, true);

    $specializations = [];

    foreach ($diseaseToSpecialization as $disease => $specialization) {
        if (stripos($disease, $diseaseInput) !== false) {
            $specializations = array_merge($specializations, $specialization);
        }
    }

    if (empty($specializations)) {
        echo "No matching specializations found for this disease.";
        exit;
    }

    $specializations = array_unique($specializations);

    $placeholders = str_repeat('?,', count($specializations) - 1) . '?';
    $query = "SELECT * FROM doctors 
          WHERE specialization1 IN ($placeholders)
          OR specialization2 IN ($placeholders)
          OR specialization3 IN ($placeholders)
          OR specialization4 IN ($placeholders)";

    $stmt = $pdo->prepare($query);

    $paramIndex = 1;
    foreach ($specializations as $specialization) {
        $stmt->bindValue($paramIndex++, $specialization, PDO::PARAM_STR);
    }
    foreach ($specializations as $specialization) {
        $stmt->bindValue($paramIndex++, $specialization, PDO::PARAM_STR);
    }
    foreach ($specializations as $specialization) {
        $stmt->bindValue($paramIndex++, $specialization, PDO::PARAM_STR);
    }
    foreach ($specializations as $specialization) {
        $stmt->bindValue($paramIndex++, $specialization, PDO::PARAM_STR);
    }

    try {
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $detail = "";

    foreach ($result as $row) {
        $detail .= '<input type="checkbox" class="doctor-checkbox" value="' . htmlspecialchars($row['fname'] . ' ' . $row['lname']) . '">' .
            htmlspecialchars($row['fname'] . ' ' . $row['lname']) . '<br>' .
            "Clinic Name: " . htmlspecialchars($row['clinic']) . "<br>" .
            "Address: " . htmlspecialchars($row['address']) . "<br>" .
            "Specializations: " . htmlspecialchars($row['specialization1']) . " " .
            htmlspecialchars($row['specialization2']) . " " .
            htmlspecialchars($row['specialization3']) . " " .
            htmlspecialchars($row['specialization4']) . "<br><br>";
    }
    if (!empty($detail)) {
        echo $detail;
    } else {
        echo "No matches found bye";
    }
} else {


    $name = $_POST['name'];
    if (empty($name)) {
        echo "it is empty";
        exit();
    }
    $query2 = "SELECT * FROM doctors WHERE fname LIKE :name OR lname LIKE :name OR CONCAT(fname, lname) LIKE :name";
    $stmt = $pdo->prepare($query2);
    $stmt->execute(['name' => '%' . $name . '%']);

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $detail = "";

    foreach ($result as $row) {
        $detail .= '<input type="checkbox" class="doctor-checkbox" value="' . htmlspecialchars($row['fname'] . ' ' . $row['lname']) . '">' .
            htmlspecialchars($row['fname'] . ' ' . $row['lname']) . '<br>' .
            "Clinic Name: " . htmlspecialchars($row['clinic']) . "<br>" .
            "Address: " . htmlspecialchars($row['address']) . "<br>" .
            "Specializations: " . htmlspecialchars($row['specialization1']) . " " .
            htmlspecialchars($row['specialization2']) . " " .
            htmlspecialchars($row['specialization3']) . " " .
            htmlspecialchars($row['specialization4']) . "<br><br>";
    }

    echo $detail;

    // $date = $_POST['date'] ?? '';
    // $time = $_POST['time'] ?? '';
    // $message = $_POST['message'] ?? '';
    // $preferredDoctors = $_POST['preferred_doctors'] ?? [];

    // $preferredDoctorsJson = json_encode($preferredDoctors);
    // session_start();
    // $unqiue_id = $_SESION['unique_id'];
    // $querymain = "SELECT * FROM users WHERE unique_id=:unique_id";
    // $stmtmain = $pdo->prepare($querymain);
    // $resultmain=$stmtmain->fetch(PDO::FETCH_ASSOC);
    // $name=$resultmain['fname']." ".$resultmain['lname'];
    // $sql = "INSERT INTO appointments (date,name, time, message, preferred_doctors) VALUES (:date, :time,:name, :message, :preferred_doctors)";
    // $stmt = $pdo->prepare($sql);
    // $stmt->bindParam(":name",$name);
    // $stmt->bindParam(':date', $date);
    // $stmt->bindParam(':time', $time);
    // $stmt->bindParam(':message', $message);
    // $stmt->bindParam(':preferred_doctors', $preferredDoctorsJson, PDO::PARAM_STR);

    // try {
    //     $stmt->execute();
    //     echo "Appointment booked successfully.";
    // } catch (PDOException $e) {
    //     echo "Error: " . $e->getMessage();
    //     exit;
    // }

}
?>