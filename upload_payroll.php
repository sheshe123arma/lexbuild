<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll File Upload</title>
</head>
<body>
    <h2>Upload Payroll File (ODS Format)</h2>
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <label for="file">Select an ODS file:</label>
        <input type="file" name="file" id="file" accept=".ods" required>
        <button type="submit">Upload</button>
    </form>
</body>
</html>