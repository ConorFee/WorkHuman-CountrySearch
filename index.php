<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Country Search</title>
    <link rel="stylesheet" href="style.css"> 

</head>
<body>
<?php

    // Variable declaration for database connection
    //
    $servername = "localhost";
    $username = "root";
    // no password required
    //
    $password = "";
    // Database name is countriesDB
    //
    $dbname = "countriesDB";

    // Create connection
    //
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    //
    if ($conn->connect_error)
    {
        die("Connection failed: " . $conn->connect_error);
    }

    // Define an empty search query variable
    $searchQuery = "";

    // Check if the form has been submitted
    if (isset($_POST['search'])) 
    {
        $searchQuery = $_POST['search_query'];  // Get the user input from the form
    }

    // Prepare the SQL query to search for countries using a wildcard (LIKE operator)
    $sql = "SELECT * FROM countries WHERE countryName LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $searchQuery . "%";  // Add wildcard to search term
    $stmt->bind_param("s", $searchTerm);  // Bind the search term
    $stmt->execute();  // Execute the query
    $result = $stmt->get_result();  // Get the result of the query

?>

<h1>Search for a Country</h1>

<!-- Create the search form -->
<form method="POST" action="">
    <input type="text" name="search_query" placeholder="Enter country name" value="<?php echo htmlspecialchars($searchQuery); ?>">
    <button type="submit" name="search">Search</button>
</form>

<?php
// Display the search results
if ($result->num_rows > 0) {
    echo "<h2>Results:</h2>";
    echo "<ul>";
    
    // Loop through the result and display each country
    //
    while($row = $result->fetch_assoc()) {
        echo "<li>" . $row["countryName"] . "</li>"; 
    }
    
    echo "</ul>";
} else {
    echo "<p>No countries found matching your search criteria.</p>";
}
// Close the prepared statement and the database connection
//
$stmt->close();
$conn->close();  
?>

</body>
</html>

