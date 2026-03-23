<?php
session_start();
require_once 'db_connect.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

function e(?string $s): string {
    return htmlspecialchars($s ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$user = null;
$addresses = [];
$stats = ['orders' => 0, 'favourites' => 0, 'reviews' => 0, 'returns' => 0];
$err = null;

$user_id = (int)($_SESSION['user_id'] ?? 0);
if ($user_id <= 0) {
    header("Location: signin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_profile'])) {
    if (!isset($conn) || !($conn instanceof mysqli)) {
        echo "<script>alert('Database connection not found.');</script>";
    } else {
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name  = trim($_POST['last_name'] ?? '');
        $email      = trim($_POST['email'] ?? '');
        $phone      = trim($_POST['phone'] ?? '');

        if ($first_name === '' || $last_name === '') {
            echo "<script>alert('First name and last name are required.');</script>";
        }
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Please enter a valid email address.');</script>";
        }
        elseif ($phone !== '' && !preg_match('/^[0-9+()\-.\s]{6,20}$/', $phone)) {
            echo "<script>alert('Please enter a valid phone number.');</script>";
        }
        else {
            if ($chk = $conn->prepare("SELECT user_id FROM users WHERE email = ? AND user_id <> ?")) {
                $chk->bind_param("si", $email, $user_id);
                $chk->execute();
                $exists = $chk->get_result()->num_rows > 0;
                $chk->close();
                if ($exists) {
                    echo "<script>alert('That email address is already in use.');</script>";
                } else {
                    if ($upd = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ? WHERE user_id = ?")) {
                        $upd->bind_param("ssssi", $first_name, $last_name, $email, $phone, $user_id);
                        if ($upd->execute()) {
                            $upd->close();
                            $_SESSION['first_name'] = $first_name;
                            echo "<script>alert('Details updated successfully'); window.location.href='mydetails.php';</script>";
                            exit();
                        } else {
                            if ($conn->errno === 1062) {
                                $upd->close();
                                echo "<script>alert('That email address is already in use.');</script>";
                            } else {
                                $upd->close();
                                echo "<script>alert('Error updating details');</script>";
                            }
                        }
                    } else {
                        echo "<script>alert('Unexpected error (prepare update).');</script>";
                    }
                }
            } else {
                echo "<script>alert('Unexpected error (prepare check).');</script>";
            }
        }
    }
}

if (!isset($conn) || !($conn instanceof mysqli)) {
    $err = "Database connection not found.";
} else {
    $q = $conn->prepare("SELECT user_id, first_name, last_name, email, phone, role, created_at FROM users WHERE user_id=?");
    if ($q) {
        $q->bind_param("i", $user_id);
        if ($q->execute()) {
            $res = $q->get_result();
            $user = $res->fetch_assoc();
            if (!$user) $err = "User not found.";
        } else {
            $err = "Failed to load user details.";
        }
        $q->close();
    } else {
        $err = "Failed to prepare user query.";
    }

    if (!$err) {
        $qa = $conn->prepare("SELECT address_id, address_line1, address_line2, city, postcode, country, is_default
                              FROM addresses
                              WHERE user_id = ?
                              ORDER BY is_default DESC, address_id ASC");
        if ($qa) {
            $qa->bind_param("i", $user_id);
            if ($qa->execute()) {
                $ra = $qa->get_result();
                while ($row = $ra->fetch_assoc()) {
                    $addresses[] = $row;
                }
            }
            $qa->close();
        }
    }

    if (!$err) {
        if ($s = $conn->prepare("SELECT COUNT(*) AS c FROM orders WHERE user_id = ?")) {
            $s->bind_param("i", $user_id);
            $s->execute(); $r = $s->get_result()->fetch_assoc(); $stats['orders'] = (int)($r['c'] ?? 0); $s->close();
        }
        if ($s = $conn->prepare("SELECT COUNT(*) AS c FROM favourites WHERE user_id = ?")) {
            $s->bind_param("i", $user_id);
            $s->execute(); $r = $s->get_result()->fetch_assoc(); $stats['favourites'] = (int)($r['c'] ?? 0); $s->close();
        }
        if ($s = $conn->prepare("SELECT COUNT(*) AS c FROM reviews WHERE user_id = ?")) {
            $s->bind_param("i", $user_id);
            $s->execute(); $r = $s->get_result()->fetch_assoc(); $stats['reviews'] = (int)($r['c'] ?? 0); $s->close();
        }
        if ($s = $conn->prepare("SELECT COUNT(*) AS c FROM returns WHERE user_id = ?")) {
            $s->bind_param("i", $user_id);
            $s->execute(); $r = $s->get_result()->fetch_assoc(); $stats['returns'] = (int)($r['c'] ?? 0); $s->close();
        }
    }
}

$fullName = $user ? trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) : '';
$memberSince = $user && !empty($user['created_at']) ? date('j M Y, H:i', strtotime($user['created_at'])) : '—';
$primaryEmail = $user['email'] ?? '—';
$phone = $user['phone'] ?? '—';
$role = $user['role'] ?? 'customer';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="accounts.css" />
    <title>My Details</title>
    <style>
        .inline-edit{border:none;background:transparent;padding:0;margin:0;width:100%;font:inherit;color:inherit;outline:none;}
        .inline-edit::-ms-clear{display:none;}
        .inline-edit::-webkit-contacts-auto-fill-button{visibility:hidden;display:none!important;}
        .content-card{background:var(--light);border-radius:16px;padding:24px;box-shadow:0 2px 10px rgba(0,0,0,0.06);}
        .grid-2{display:grid;grid-template-columns:1fr 1fr;gap:24px;}
        .grid-1{display:grid;grid-template-columns:1fr;gap:24px;}
        @media (max-width: 992px){.grid-2{grid-template-columns:1fr;}}
        .label{color:var(--dark-grey);font-weight:600;width:220px;}
        .value{color:var(--dark);}
        .details-table{width:100%;border-collapse:collapse;}
        .details-table th,.details-table td{padding:10px 12px;border-bottom:1px solid var(--grey);text-align:left;}
        .address-badge{display:inline-block;font-size:11px;padding:4px 8px;border-radius:12px;background:var(--dark-color);color:var(--light);margin-left:8px;}
        .muted{color:var(--dark-grey);}
        .kpi .bx{background:var(--dark-color);color:var(--light);}
        .visually-hidden{position:absolute;left:-9999px;width:1px;height:1px;overflow:hidden;}
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
            <li class="active">
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
            <li>
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
                    <h1>My Details</h1>
                    <ul class="breadcrumb">
                        <li><a href="index.php">Home</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a class="active" href="mydetails.php">My Details</a></li>
                    </ul>
                </div>
            </div>

            <?php if ($err): ?>
                <div class="order-details" style="border-color:#f44336; margin-top:20px;">
                    <h3 style="color:#f44336; margin-bottom:10px;"><i class="bx bx-error-circle"></i> Error</h3>
                    <p><?= e($err) ?></p>
                </div>
            <?php else: ?>

                <ul class="box-info kpi">
                    <li>
                        <i class='bx bxs-store-alt'></i>
                        <span class="text">
                            <h3><?= e((string)$stats['orders']) ?></h3>
                            <p>Orders</p>
                        </span>
                    </li>
                    <li>
                        <i class='bx bxs-heart'></i>
                        <span class="text">
                            <h3><?= e((string)$stats['favourites']) ?></h3>
                            <p>Favourites</p>
                        </span>
                    </li>
                    <li>
                        <i class='bx bxs-star'></i>
                        <span class="text">
                            <h3><?= e((string)$stats['reviews']) ?></h3>
                            <p>Reviews</p>
                        </span>
                    </li>
                </ul>

                <div class="grid-2" style="margin-top:24px;">
                    <section class="content-card">
                        <div style="display:flex; align-items:center; gap:16px; margin-bottom:12px;">
                            <i class='bx bxs-user-circle' style="font-size:48px; color:var(--dark-color);"></i>
                            <div>
                                <h2 style="margin:0;"><?= e($fullName ?: 'Your Name') ?></h2>
                                <p class="muted" style="margin:2px 0 0;">User ID: <?= e((string)$user['user_id']) ?> • Role: <?= e($role) ?></p>
                                <p class="muted" style="margin:2px 0 0;">Member since: <?= e($memberSince) ?></p>
                            </div>
                            <div style="margin-left:auto;">
                                <a href="change-password.php" class="btn"><i class='bx bx-key'></i> Change Password</a>
                            </div>
                        </div>

                        <form method="post" id="profileForm" action="mydetails.php" style="margin:0;">
                            <table class="details-table" aria-label="Personal details">
                                <tr>
                                    <th class="label">First Name</th>
                                    <td class="value">
                                        <input class="inline-edit" type="text" name="first_name"
                                               value="<?= e($user['first_name'] ?? '') ?>" autocomplete="given-name" required>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="label">Last Name</th>
                                    <td class="value">
                                        <input class="inline-edit" type="text" name="last_name"
                                               value="<?= e($user['last_name'] ?? '') ?>" autocomplete="family-name" required>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="label">Email</th>
                                    <td class="value">
                                        <input class="inline-edit" type="email" name="email"
                                               value="<?= e($user['email'] ?? '') ?>" autocomplete="email" required>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="label">Phone</th>
                                    <td class="value">
                                        <input class="inline-edit" type="text" name="phone"
                                               value="<?= e($user['phone'] ?? '') ?>" placeholder="" autocomplete="tel">
                                    </td>
                                </tr>
                            </table>
                            <input type="hidden" name="save_profile" value="1" />
                            <button type="submit" class="visually-hidden" aria-hidden="true">Save</button>
                        </form>
                    </section>

                    <section class="content-card">
                        <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
                            <i class='bx bxs-map' style="font-size:36px; color:var(--dark-color);"></i>
                            <h2 style="margin:0;">Addresses</h2>
                            <span class="muted" style="margin-left:auto;"><?= count($addresses) ?> on file</span>
                        </div>

                        <?php if (empty($addresses)): ?>
                            <p class="muted">No addresses saved yet.</p>
                        <?php else: ?>
                            <table class="details-table" aria-label="Addresses">
                                <thead>
                                    <tr>
                                        <th>Address</th>
                                        <th>City</th>
                                        <th>Postcode</th>
                                        <th>Country</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($addresses as $a): ?>
                                    <tr>
                                        <td>
                                            <?= e($a['address_line1']) ?>
                                            <?php if (!empty($a['address_line2'])): ?>
                                                , <?= e($a['address_line2']) ?>
                                            <?php endif; ?>
                                            <?php if ((int)$a['is_default'] === 1): ?>
                                                <span class="address-badge">Default</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= e($a['city'] ?? '—') ?></td>
                                        <td><?= e($a['postcode'] ?? '—') ?></td>
                                        <td><?= e($a['country'] ?? '—') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </section>
                </div>

                <div class="grid-1" style="margin-top:24px;">
                    <section class="content-card">
                        <div class="table-data">
                            <div class="order" style="width:100%;">
                                <div class="head">
                                    <h3>Account Activity</h3>
                                </div>
                                <table style="width:100%; border-collapse:collapse;">
                                    <tr>
                                        <th style="text-align:left; padding:8px 0; border-bottom:1px solid var(--grey);">Metric</th>
                                        <th style="text-align:left; padding:8px 0; border-bottom:1px solid var(--grey);">Value</th>
                                    </tr>
                                    <tr>
                                        <td style="padding:10px 0;">Orders placed</td>
                                        <td style="padding:10px 0;"><?= e((string)$stats['orders']) ?></td>
                                    </tr>
                                    <tr>
                                        <td style="padding:10px 0;">Favourites saved</td>
                                        <td style="padding:10px 0;"><?= e((string)$stats['favourites']) ?></td>
                                    </tr>
                                    <tr>
                                        <td style="padding:10px 0;">Reviews written</td>
                                        <td style="padding:10px 0;"><?= e((string)$stats['reviews']) ?></td>
                                    </tr>
                                    <tr>
                                        <td style="padding:10px 0;">Returns requested</td>
                                        <td style="padding:10px 0;"><?= e((string)$stats['returns']) ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </section>
                </div>

            <?php endif; ?>
        </main>
    </section>

    <script>
        (function () {
            const form = document.getElementById('profileForm');
            if (!form) return;
            document.addEventListener('keydown', function (e) {
                const sKey = (e.key || '').toLowerCase() === 's';
                if ((e.ctrlKey || e.metaKey) && sKey) {
                    e.preventDefault();
                    form.requestSubmit();
                }
            });
        })();
    </script>
</body>
</html>
