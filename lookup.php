<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "krabutech";

$conn = new mysqli($servername, $username, $password, $dbname);

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fileId = $_POST["meetingID"];

    $stmt = $conn->prepare("SELECT file_location FROM filelocations WHERE meetingid = ?");
    $stmt->bind_param("i", $fileId);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $fileLocation = $row["file_location"];
        
        //If no .xml, adds .xml to ending
        if (pathinfo($fileLocation, PATHINFO_EXTENSION) !== 'xml') {
            $fileLocation .= '.xml';
        }

        $fileHandle = fopen($fileLocation, 'r');

        if ($fileHandle !== false) {
            $fileContent = fread($fileHandle, filesize($fileLocation));

            fclose($fileHandle);

            if ($fileContent !== false) {
                $response['success'] = true;
                $response['fileContent'] = $fileContent;
            } else {
                $response['success'] = false;
                $response['message'] = "Unable to read file content.";
            }
        } else {
            $response['success'] = false;
            $response['message'] = "Error opening file.";
        }
    } else {
        $response['success'] = false;
        $response['message'] = "No file found for ID: $fileId";
    }
}

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>
