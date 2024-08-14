<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<div id="add_service" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <div class="alert alert-info">Add Service</div>
    </div>
    <div class="modal-body">
        <form class="form-horizontal" method="POST" enctype="multipart/form-data">
            <div class="control-group">
                <label class="control-label" for="inputDetails">Details</label>
                <div class="controls">
                    <input type="text" name="details" id="inputDetails" placeholder="Details" required>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputPrice">Price</label>
                <div class="controls">
                    <input type="text" name="price" id="inputPrice" placeholder="Price" required>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputImage">Image</label>
                <div class="controls">
                    <input type="file" name="image" id="inputImage" required>
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <button type="submit" name="ad" class="btn btn-info">Add</button>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    </div>
</div>
</body>
</html>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "china-global-door";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['ad'])) {
    $details = $_POST['details'] ?? ''; // Ensure 'details' is set
    $price = $_POST['price'] ?? '';     // Ensure 'price' is set
    
    // Ensure 'images/' directory exists
    $targetDirectory = "images/";
    if (!is_dir($targetDirectory)) {
        mkdir($targetDirectory, 0755, true); // Create directory if it doesn't exist
    }

    $targetFile = $targetDirectory . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
    
    // Check file size
    if ($_FILES["image"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    
    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
    
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            echo "The file " . htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    // Extracting only the image name
    $image_name = basename($_FILES["image"]["name"]);

    // Insert data into database
    $query = "INSERT INTO products (details, price, image) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $details, $price, $image_name);

    if ($stmt->execute()) {
        ?>
    
        <?php
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
