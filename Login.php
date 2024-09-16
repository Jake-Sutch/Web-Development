<!--Jake-->
<?php
session_start();

// Logout mechanism
if (isset($_POST['logout'])) {
    // Unset all session variables
    session_unset();

    // Destroy the session
    session_destroy();

    // Redirect to the home page
    header("Location: Home.php");
    exit();
}

// Database connect
$servername = "studentdb-maria.gl.umbc.edu";
$username = "ll41010";
$password = "ll41010";
$dbname = "ll41010";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process/injection protection
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string(htmlspecialchars($_POST["username"]));
    $password = $conn->real_escape_string(htmlspecialchars($_POST["password"]));
    $role = $conn->real_escape_string(htmlspecialchars($_POST["role"]));
    $club = $conn->real_escape_string(htmlspecialchars($_POST["club"]));

    // Check if the user already exists
    $check_user_query = "SELECT user_id, password, club_id FROM user_accounts WHERE username='$username' AND user_role='$role'";
    $check_user_result = $conn->query($check_user_query);

    if ($check_user_result->num_rows > 0) {
        // User exists, proceed with login
        $row = $check_user_result->fetch_assoc();
        $storedPassword = $row["password"];

        // Verify password
        if ($password === $storedPassword) {
            // Fetch additional user information
            $fetch_user_info_query = "SELECT ua.user_id, ua.user_role, ua.club_id, c.club_name, c.webpage_link FROM user_accounts ua
                                      JOIN Clubs c ON ua.club_id = c.club_id
                                      WHERE ua.username = '$username' AND ua.user_role = '$role'";
            $user_info_result = $conn->query($fetch_user_info_query);

            if ($user_info_result && $user_info = $user_info_result->fetch_assoc()) {
                // User is authenticated, set session variables
                $_SESSION["user_id"] = $user_info["user_id"];
                $_SESSION["user_role"] = $user_info["user_role"];
                $_SESSION["club_id"] = $user_info["club_id"];
                $_SESSION["club_name"] = $user_info["club_name"];
                $_SESSION["username"] = $username;

                // Regenerate the session ID to prevent session fixation
                session_regenerate_id();

                // Set hidden input field values and show alert messages
                echo '<input type="hidden" id="loginSuccessful" name="loginSuccessful" value="1">';
                echo '<script>alert("Login successful!");</script>';

                // Redirect based on role
                if ($_SESSION["user_role"] == "club_officer") {
                    header("Location: https://swe.umbc.edu/~ll41010/IS448/Project/Club%20Management.php");
                    exit();
                } elseif ($_SESSION["user_role"] == "club_member") {
                    // Redirect to the club page based on webpage_link
                    header("Location: " . $user_info["webpage_link"]);
                    exit();
                }
            } else {
                echo "Error fetching user information";
            }
        } else {
            echo "Invalid login credentials";
        }
    } else {
        // User doesn't exist, proceed with signup
        $insert_user_query = "INSERT INTO user_accounts (username, password, user_role, club_id) VALUES ('$username', '$password', '$role', (SELECT club_id FROM Clubs WHERE club_name = '$club'))";

        if ($conn->query($insert_user_query) === TRUE) {
            // User created successfully
            echo '<input type="hidden" id="accountCreated" name="accountCreated" value="1">';
            echo '<script>alert("Account created successfully!");</script>';
            header("Location: https://swe.umbc.edu/~ll41010/IS448/Project/Home.php");
            exit();
        } else {
            echo "Error: " . $insert_user_query . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>
