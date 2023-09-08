<?php
// Establish a database connection
$servername = "localhost";
$username = "webadmin@gmail.com";
$password = "admin1234";
$dbname = "chat";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to retrieve chat messages from the database
function getChatMessages($sender) {
  global $conn;
  $sql = "SELECT *, (sender = '$sender') AS removable FROM messages ORDER BY timestamp ASC";
  $result = $conn->query($sql);

  $messages = array();
  if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          if (!empty($row['message'])) {
              $messages[] = $row;
          }
      }
  }
  return $messages;
}
// Function to insert a new message into the database
function insertChatMessage($sender, $receiver, $message) {
    global $conn;
    $sql = "INSERT INTO messages (sender, receiver, message, timestamp)
            VALUES ('$sender', '$receiver', '$message', NOW())";
    $result = $conn->query($sql);
    return $result;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $message = $_POST["message-text"];
  $sender = ($_POST["sender"] === "admin") ? "admin" : "user";
  $receiver = ($_POST["sender"] === "admin") ? "user" : "admin";

  // Insert the message into the database
  insertChatMessage($sender, $receiver, $message);
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message-id'])) {
  $messageId = $_POST['message-id'];

  // Remove the message from the database
  $sql = "DELETE FROM messages WHERE id = '$messageId'";
  $result = $conn->query($sql);

  // Check if the deletion was successful
  if ($result) {
      // Redirect to the same page using the GET method
      header("Location: ".$_SERVER['PHP_SELF']);
      exit();
  } else {
      // Handle the deletion error
      echo "Error deleting the message: " . $conn->error;
  }
}

// Retrieve chat messages from the database
$messages = getChatMessages("admin");

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Messages</title>
  <link rel="stylesheet" href="../messages.css">
  <style>
    .back-button {
      display: inline-block;
      padding: 10px 20px;
      background-color: #D291BC;
      color: white;
      text-decoration: none;
      font-size: 30px;
      border-radius: 5px;
      transition: background-color 0.3s;
    }
    
    .back-button:hover {
      background-color: #957DAD;
    }
  </style>
</head>
<body>
<a href="AdminPanel.php" class="back-button" style="font-size:20px; ">Home</a>
  <div class="phone">
    <div class="screen">
      <header>
        <h1>YAYHotels</h1>
      </header>
      <main>
        <section class="chat-section">
          <h2>Chat</h2>
          <div class="chat-container">
          <?php foreach ($messages as $message) : ?>
  <div class="message">
    <div class="message-bubble <?php echo ($message['sender'] === 'admin') ? 'admin' : 'user'; ?>">
      <?php echo $message['message']; ?>
    </div>
    <?php if ($message['removable']): ?>
      <form class="remove-message-form" method="post" style="display: inline;">
        <input type="hidden" name="message-id" value="<?php echo $message['id']; ?>">
        <button type="submit">Delete</button>
      </form>
    <?php endif; ?>
  </div>
<?php endforeach; ?>

          </div>
          <form class="send-message-form" method="post">
            <textarea id="message-text" name="message-text" required></textarea>
            <input type="hidden" name="sender" value="admin">
            <button type="submit">Send</button>
          </form>
        </section>
      </main>
    </div>
    <div class="button-row">
      <div class="button"></div>
      <div class="button"></div>
      <div class="button"></div>
    </div>
  </div>
  <script src="script.js"></script>
</body>
</html>
