<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phoneNo = $_POST["phoneNo"];
    $country = $_POST["countries"];
    $selectedSessions = $_POST["sessions"];

    // Validate and process the data
    if (!empty($name) && !empty($email) && !empty($selectedSessions)) {
        // Sanitize the data to prevent SQL injection
        $name = htmlspecialchars($name);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $phoneNo = filter_var($phoneNo, FILTER_SANITIZE_STRING);
        $country = filter_var($country, FILTER_SANITIZE_STRING);
        $selectedSessions = array_map('htmlspecialchars', $selectedSessions);

        // Connect to the database
        $conn = new mysqli("localhost", "root", "", "ICAIR_db");
        if ($conn->connect_error){
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and execute the INSERT statement using prepared statements
        $stmt = $conn->prepare("INSERT INTO registrations (name, email, phoneNo, country, sessions) VALUES (?, ?, ?, ?, ?)");
        $choice = implode(", ", $selectedSessions);
        $stmt->bind_param("sssss", $name, $email, $phoneNo, $country, $choice);
        $stmt->execute();

        // Close the database connection
        $stmt->close();
        $conn->close();

        // Display a thank you message
        echo "<h1>Thank You for Registering, $name</h1>";
        echo "<p>Name: $name</p>";
        echo "<p>Email: $email</p>";
        echo "<p>Phone Number: $phoneNo</p>";
        echo "<p>Country: $country</p>";
        echo "<p>Selected Sessions:</p>";
        echo "<ul>";
        foreach ($selectedSessions as $session) {
            echo "<li>$session</li>";
        }
        echo "</ul>";
    } else {
        echo "<h1>Error: All fields are required.</h1>";
    }
}
?>
