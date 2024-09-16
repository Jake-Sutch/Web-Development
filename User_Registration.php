<!-- Jake -->

<html>
<head>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
    }

    .header {
      background-color: #333;
      color: white;
      padding: 20px;
    }

    .header h1 {
      margin: 0;
    }

    .nav {
      list-style: none;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px;
    }

    .nav li {
      margin: 10px;
    }

    .nav a {
      color: black;
      text-decoration: none;
    }

    .nav a:hover {
      color: black;
    }

    .main {
      display: flex;
      justify-content: space-between;
      padding: 20px;
    }

    .sidebar {
      width: 25%;
      background-color: #f0f0f0;
      padding: 10px;
    }

    .sidebar h3 {
      margin-top: 0;
    }

    .sidebar ul {
      list-style: none;
      padding-left: 0;
    }

    .sidebar li {
      margin-bottom: 10px;
    }

    .sidebar a {
      color: black;
      text-decoration: none;
    }

    .sidebar a:hover {
      color: blue;
    }

    .content {
      width: 70%;
      background-color: white;
      padding: 10px;
    }

    .content h2 {
      margin-top: 0;
    }

    .content p {
      line-height: 1.5;
    }

    .footer {
      background-color: #333;
      color: white;
      padding: 20px;
      text-align: center;
    }

    .login-container {
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
      text-align: center;
      padding: 20px;
    }

    .login-form {
      max-width: 300px;
      margin: 0 auto;
    }

    .login-form h2 {
      color: #333;
    }

    .input-container {
      text-align: left;
      margin: 10px 0;
    }

    .input-container label {
      display: block;
      margin-bottom: 5px;
      color: #666;
    }

    .input-container input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      outline: none;
      font-size: 16px;
    }

    button {
      background-color: #007BFF;
      color: #fff;
      border: none;
      border-radius: 5px;
      padding: 10px 20px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    button:hover {
      background-color: #0056b3;
    }

  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>UMBC Club Management System</h1>
    </div>
    <ul class="nav">
      <li><a href="https://swe.umbc.edu/~ll41010/IS448/Project/Home.php">Home</a></li>
      <li><a href="https://swe.umbc.edu/~ll41010/IS448/Project/User%20Registration.php">User Login</a></li>
    </ul>
    <div class="main">
      <div class="content">
        <h2>Welcome to UMBC's Club Management User Registration</h2>
        <p>To use this website, you need to login with your club credentials. If you don't have an account, you can register below.</p>
        <!-- login -->
        <div class="login-container">
          <form class="login-form" action="Login.php" method="post">
            <h2>Login</h2>
            <div class="input-container">
              <label for="username">Username</label>
              <input type="text" id="username" name="username" required>
            </div>
            <div class="input-container">
              <label for="password">Password</label>
              <input type="password" id="password" name="password" required>
            </div>
            <div class="input-container">
              <label for="role">Role</label>
              <select id="role" name="role" required>
                <option value="" disabled selected>Select your role</option>
                <option value="club_member">Club Member</option>
                <option value="club_officer">Club Officer</option>
              </select>
            </div>
            <div class="input-container">
              <label for="club">Club</label>
              <select id="club" name="club" required>
                <option value="" disabled selected>Select your club</option>
                <?php
                // Fetch clubs from the database
                $servername = "studentdb-maria.gl.umbc.edu";
                $username = "ll41010";
                $password = "ll41010";
                $dbname = "ll41010";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                  die("Connection failed: " . $conn->connect_error);
                }

                $clubs_query = "SELECT club_id, club_name FROM Clubs";
                $clubs_result = $conn->query($clubs_query);

                if ($clubs_result->num_rows > 0) {
                  while ($club_row = $clubs_result->fetch_assoc()) {
                    echo "<option value='" . $club_row["club_name"] . "'>" . $club_row["club_name"] . " (ID: " . $club_row["club_id"] . ")</option>";
                  }
                }

                $conn->close();
                ?>
              </select>
            </div>
            <input type="hidden" id="accountCreated" name="accountCreated" value="0">
            <input type="hidden" id="loginSuccessful" name="loginSuccessful" value="0">
            


            <button type="submit">Log In</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  
<script>
document.addEventListener("DOMContentLoaded", function () {
  const contentDiv = document.querySelector('.content');

  // Update styles dynamically
  contentDiv.style.backgroundColor = '#f8f8f8';
  contentDiv.style.padding = '30px';

  // Check login successful
  const loginSuccessful = document.querySelector('form.login-form').elements['loginSuccessful'].value;
  if (loginSuccessful === '1') {
    // show alert 
    alert('Login successful!');
    redirectToPage(); // Redirect 
  }

  // Check account created successfully
  const accountCreated = document.querySelector('form.login-form').elements['accountCreated'].value;
  if (accountCreated === '1') {
    // alert 
    alert('Account created successfully!');
    redirectToPage(); // redirects
  }

  function redirectToPage() {
    const role = document.getElementById('role').value;
    if (role === 'club_member') {
      window.location.href = 'Home.php'; // https://swe.umbc.edu/~ll41010/IS448/Project/Home.php
    } else if (role === 'club_officer') {
      window.location.href = 'Club%20Management.php'; // https://swe.umbc.edu/~ll41010/IS448/Project/Club%20Management.php
    }
  }
});

</script>


</body>
</html>
