<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>College Management System</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="header.css">
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
    <style>
        /* Existing styles */
        .username-container {
            position: relative;
            cursor: pointer;
        }

        .logout-dropdown {
            display: none;
            position: absolute;
            top: 25px;
            right: 0;
            background: white;
            border: 1px solid #ccc;
            padding: 5px 10px;
            border-radius: 5px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
        }

        .logout-dropdown a {
            text-decoration: none;
            color: black;
            display: block;
        }

        /* Navigation Bar Styles to match the image */
        .nav-bar {
            background-color: #020341ef;
            color: white;
            height: 50px;
            width: 100%;
            position: fixed;
            top: 50px; /* Below the media section */
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-evenly;
            padding: 0 20px;
            box-sizing: border-box;
        }

        .nav-bar .nav-links {
            display: flex;
            gap: 20px;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-bar .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .nav-bar .nav-links a:hover {
            color: #00ff40;
        }
    </style>
</head>

<body>
    <!-- Media Section (Top Bar) -->
    <section class="media" >
        <div class="info-links">
            <a href="tel:+914426533140"><i class="fas fa-phone icon"></i> 044 - 26533140</a>
            <a href="tel:+914426530978"><i class="fas fa-phone icon"></i> 044 - 26530978</a>
            <a href="mailto:mail@joychennai.ac.in"><i class="fas fa-envelope icon"></i> mail@joychennai.ac.in</a>
        </div>
        <div class="social-icons">
            <a href="https://www.facebook.com/yourpage" target="_blank"><i class="fab fa-facebook icon"></i> Facebook</a>
            <a href="https://www.instagram.com/yourprofile" target="_blank"><i class="fab fa-instagram icon"></i> Instagram</a>
            <a href="https://twitter.com/yourprofile" target="_blank"><i class="fab fa-twitter icon"></i> Twitter</a>
        </div>
    </section>

    <!-- Logo Section -->
    <section class="box">
        <nav>
            <div class="logo-container">
                <a href="index.html"><img src="images/lion_processed.png" id="logo"></a>
                <h3 class="name">JOY COLLEGE OF ARTS & SCIENCE</h3>
            </div>
            <div>
               <h4>welcome</h4>
            </div>
        </nav>
    </section>

    <!-- Navigation Bar (Matching the Image) -->
    <section class="nav-bar" style="margin-top: 70px;">
        <ul class="nav-links">
            <li><a href="dashboard.php">Students</a></li>
            <li><a href="attendance.php">Attendance</a></li>
            <li><a href="exam_marks.php">Exam Marks</a></li>
            <li><a href="view_attendance.php">View Attendance</a></li>
            
        </ul>
    </section>

   
    <script>
        function toggleLogout() {
            var dropdown = document.getElementById("logoutDropdown");
            dropdown.style.display = (dropdown.style.display === "block") ? "none" : "block";
        }

        // Hide dropdown when clicking outside
        document.addEventListener("click", function(event) {
            var dropdown = document.getElementById("logoutDropdown");
            var usernameContainer = document.querySelector(".username-container");
            if (!usernameContainer.contains(event.target)) {
                dropdown.style.display = "none";
            }
        });
    </script>
</body>
</html>