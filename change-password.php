<?php
session_start();
require_once 'db_connect.php';


$DEBUG_ONCE = false;

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    
    $current_password = $_POST['current_password'] ?? '';
    $new_password     = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $user_id          = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

   
    if ($user_id <= 0) {
        echo "<script>alert('User not found');</script>";
        exit();
    }

    if (!isset($conn) || !($conn instanceof mysqli)) {
        echo "<script>alert('Database connection not found');</script>";
        exit();
    }

  
    $stmt = $conn->prepare("SELECT password_hash FROM users WHERE user_id = ?");
    if (!$stmt) {
        $msg = $DEBUG_ONCE ? "Query prep failed: ". addslashes($conn->error) : "Unexpected error";
        echo "<script>alert('{$msg}');</script>";
        exit();
    }

    $stmt->bind_param("i", $user_id);
    if (!$stmt->execute()) {
        $msg = $DEBUG_ONCE ? "Query exec failed: ". addslashes($stmt->error) : "Unexpected error";
        $stmt->close();
        echo "<script>alert('{$msg}');</script>";
        exit();
    }
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $stmt->close();
        echo "<script>alert('User not found');</script>";
        exit();
    }

    $user = $result->fetch_assoc();
    $stmt->close();
    $old_password_hash = $user['password_hash'];

    
    if (!password_verify($current_password, $old_password_hash)) {
        echo "<script>alert('Current password is incorrect');</script>";
    }

    elseif ($new_password !== $confirm_password) {
        echo "<script>alert('Passwords do not match');</script>";
    }

    elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W]).{8,}$/', $new_password)) {
        echo "<script>alert('Password must be 8+ chars, include uppercase, lowercase, number, special char');</script>";
    }

    elseif (password_verify($new_password, $old_password_hash)) {
        echo "<script>alert('New password cannot be the same as old password');</script>";
    }

    else {
        
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);

        $update = $conn->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
        if (!$update) {
            $msg = $DEBUG_ONCE ? "Update prep failed: ". addslashes($conn->error) : "Error updating password";
            echo "<script>alert('{$msg}');</script>";
            exit();
        }

        $update->bind_param("si", $hashed, $user_id);
        if ($update->execute()) {
            $update->close();
            
            @session_regenerate_id(true);
            
            echo "<script>alert('Password updated successfully'); window.location.href='accounts.php';</script>";
        } else {
            $msg = $DEBUG_ONCE ? "Update exec failed: ". addslashes($update->error) : "Error updating password";
            $update->close();
            echo "<script>alert('{$msg}');</script>";
        }
    }
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />

   
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.7/css/dataTables.dataTables.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.7/js/dataTables.min.js"></script>

    
    <link rel="stylesheet" href="accounts.css" />

    <title>Change Password</title>

    <style>
      
        .content-card {
            background: var(--light);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        }
        .content-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 24px;
        }
        .page-title {
            font-size: 28px;
            font-weight: 600;
            color: var(--dark);
            margin: 0 0 8px 0;
        }
        .page-subtitle {
            color: var(--dark-grey);
            margin-top: -4px;
        }
        main form .form-input {
            max-width: 480px; 
        }
        @media (max-width: 576px) {
            main form .form-input {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
   
    <section id="sidebar">
    <a href="index.php" class="brand" aria-label="Lunare Clothing Home" style="display:flex; align-items:center;">
        <img src="../assets/lunare_logo.png" alt="Lunare Clothing logo"
             style="width:150px; height:60px; margin-left:20px; margin-top:20px;">
    </a>

    <ul class="side-menu top">
        <li>
            <a href="accounts.php">
                <i class='bx bxs-dashboard bx-sm'></i>
                <span class="text">My Account</span>
            </a>
        </li>
        <li>
            <a href="mydetails.php">
                <i class='bx bxs-user-detail bx-sm'></i>
                <span class="text">My Details</span>
            </a>
        </li>
        <li>
            <a href="myorders.php">
                <i class='bx bxs-doughnut-chart bx-sm'></i>
                <span class="text">My Orders</span>
            </a>
        </li>
        <li class="active">
            <a href="change-password.php">
                <i class='bx bxs-key bx-sm'></i>
                <span class="text">Change Password</span>
            </a>
        </li>
        <li>
            <a href="myreviews.php">
                <i class='bx bxs-message-dots bx-sm'></i>
                <span class="text">My Reviews</span>
            </a>
        </li>
        <li>
            <a href="myreturns.php">
                <i class='bx bxs-package bx-sm'></i>
                <span class="text">Returns</span>
            </a>
        </li>
    </ul>

    <ul class="side-menu bottom">
        <li>
            <a href="logout.php" class="logout">
                <i class='bx bx-power-off bx-sm bx-burst-hover'></i>
                <span class="text">Logout</span>
            </a>
        </li>
    </ul>
</section>
   

   
    <section id="content">
        <main>
            <div class="head-title">
                <div class="left">
                    <h1 class="page-title">Change Password</h1>
                    <p class="page-subtitle">Keep your account secure by choosing a strong, unique password.</p>
                    <ul class="breadcrumb" style="margin-top:8px;">
                        <li><a href="index.php">Home</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a href="change-password.php" class="active">Change Password</a></li>
                    </ul>
                </div>
            </div>

            <div class="content-grid" style="margin-top:20px;">
                <section class="content-card">
                    <form action="change-password.php" method="post" autocomplete="off">
                        <div style="margin-bottom:16px;">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" id="current_password" name="current_password" required class="form-input" />
                        </div>
                        <div style="margin-bottom:16px;">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" id="new_password" name="new_password" required class="form-input" />
                        </div>
                        <div style="margin-bottom:16px;">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" required class="form-input" />
                        </div>
                        <button type="submit" name="change_password" class="btn">Change Password</button>
                    </form>
                </section>
            </div>
        </main>
    </section>
    
</body>
</html>