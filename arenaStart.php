<!DOCTYPE html>
<html>
  <head>
    <title>
      Page counter demonstration via PHP embedded in HTML 
      J. Loftus, May 2023
    </title>

<style>
   
 body {
     background-color: MistyRose;
 }   

</style> 

</head>
<body>
    
 <?php

require_once 'sqlSignon.php';
// Function to increment the page counter
function incrementPageCounter($conn) {
    $sql = "UPDATE page_counter SET counter = counter + 1";
    
    if ($conn->query($sql) === TRUE) {
        //echo "Page counter incremented successfully!";
    } else {
        echo "Error updating page counter: " . $conn->error;
    }
}

// Function to retrieve the page counter value
function getPageCounter($conn) {
    $sql = "SELECT counter FROM page_counter";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row["counter"];
    } else {
        echo "Page counter not found.";
    }
}

// Increment the page counter
incrementPageCounter($conn);

// Retrieve and display the page counter value
$pageCounter = getPageCounter($conn);

// Function to check if a visitor is unique based on IP address
function isUniqueVisitor($conn, $ipAddress) {
    $sql = "SELECT * FROM unique_visitors WHERE ip_address = '$ipAddress'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return false; // Not a unique visitor
    } else {
        return true; // Unique visitor
    }
}

// Function to add a new unique visitor
function addUniqueVisitor($conn, $ipAddress) {
    $sql = "INSERT INTO unique_visitors (ip_address) VALUES ('$ipAddress')";
    
    if ($conn->query($sql) === TRUE) {
        //echo "New unique visitor added successfully!";
    } else {
        echo "Error adding unique visitor: " . $conn->error;
    }
}

// Function to retrieve the count of unique visitors
function getUniqueVisitorCount($conn) {
    $sql = "SELECT COUNT(*) AS count FROM unique_visitors";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row["count"];
    } else {
        return 0;
    }
}

// Get the visitor's IP address
$ipAddress = $_SERVER['REMOTE_ADDR'];


// Check if the visitor is unique
if (isUniqueVisitor($conn, $ipAddress)) {
    // If unique, add the visitor to the database
    addUniqueVisitor($conn, $ipAddress);
}

// Retrieve and display the count of unique visitors
$uniqueVisitorCount = getUniqueVisitorCount($conn);

// Close the database connection
$conn->close();
?>

<center>
<h1> Arena </h1>
<h2 style = "color:HotPink;" id="notices">Arena hobby game launch page</h2>
</center>
<center>
<a href="https://worldclassapps.org/arena.php">Play the game!</a>
</center>
<p>  This is a little hobby game I created.  It's territorial and consists of a competition between three factions, the Elite, 
the Elves, and the Skylords.  Right now it consists of capturing, attacking and charging portals in the map of the world.  
Players have experience and energy.  Later some form of money will be added, some missions, probably bases.   Give it a go and 
if you could be so kind as to send me feedback or suggestions.  The game is web based and should run on any device. 
</p>

<p> To begin you need only register an account, then begin clicking on portals.  A popup will appear with actions one can 
take, capture, attack, etc.  </p>

<p> This game was inspired a little by Ingress, and also by Qonqr.   </p>

  <p>This page has been visited  <?php echo $pageCounter;?> times.</p>  
  <p> Number of unique visitors <?php echo $uniqueVisitorCount; ?>  </p>
<p>
My email: gamedevfool@gmail.com
</p>
  </body>
</html>