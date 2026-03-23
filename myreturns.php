<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

function e(?string $s): string { return htmlspecialchars($s ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

$user_id = (int)($_SESSION['user_id'] ?? 0);
if ($user_id <= 0) { header("Location: signin.php"); exit(); }

$err = null;
$returns = [];
$eligible_orders = [];

function status_badge_class(string $status): string {
    $s = strtolower(trim($status));
    return match ($s) {
        'approved' => 'process',
        'refunded' => 'completed',
        default    => 'pending',
    };
}
function money_gbp($n): string { return '£' . number_format((float)$n, 2); }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_return'])) {
    if (!isset($conn) || !($conn instanceof mysqli)) {
        echo "<script>alert('Database connection not found');</script>";
    } else {
        $order_id = (int)($_POST['order_id'] ?? 0);
        $reason   = trim($_POST['reason'] ?? '');
        if ($order_id <= 0) {
            echo "<script>alert('Please select an order');</script>";
        } elseif ($reason === '' || mb_strlen($reason) < 5) {
            echo "<script>alert('Please provide a brief reason (at least 5 characters)');</script>";
        } else {
            $hdr = $conn->prepare("SELECT order_id, payment_status, total, created_at FROM orders WHERE order_id = ? AND user_id = ?");
            if ($hdr) {
                $hdr->bind_param("ii", $order_id, $user_id);
                $hdr->execute();
                $order = $hdr->get_result()->fetch_assoc();
                $hdr->close();

                if (!$order) {
                    echo "<script>alert('Order not found');</script>";
                } else {
                    $ps = strtolower($order['payment_status'] ?? 'pending');
                    if (!in_array($ps, ['paid','shipped'], true)) {
                        echo "<script>alert('Only paid or shipped orders can be requested for return');</script>";
                    } else {
                        $dup = $conn->prepare("SELECT return_id FROM returns WHERE order_id = ? AND user_id = ? AND status = 'pending' LIMIT 1");
                        if ($dup) {
                            $dup->bind_param("ii", $order_id, $user_id);
                            $dup->execute();
                            $hasPending = $dup->get_result()->num_rows > 0;
                            $dup->close();

                            if ($hasPending) {
                                echo "<script>alert('A pending request already exists for this order'); window.location.href='myreturns.php';</script>";
                                exit();
                            }
                        }

                        $ins = $conn->prepare("INSERT INTO returns (order_id, user_id, reason, status, created_at) VALUES (?, ?, ?, 'pending', NOW())");
                        if ($ins) {
                            $ins->bind_param("iis", $order_id, $user_id, $reason);
                            if ($ins->execute()) {
                                $ins->close();
                                echo "<script>alert('Return request submitted'); window.location.href='myreturns.php';</script>";
                                exit();
                            } else {
                                $ins->close();
                                echo "<script>alert('Could not submit request');</script>";
                            }
                        } else {
                            echo "<script>alert('Unexpected error (prepare insert)');</script>";
                        }
                    }
                }
            } else {
                echo "<script>alert('Unexpected error (prepare select)');</script>";
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['withdraw_return'])) {
    if (!isset($conn) || !($conn instanceof mysqli)) {
        echo "<script>alert('Database connection not found');</script>";
    } else {
        $return_id = (int)($_POST['return_id'] ?? 0);
        if ($return_id <= 0) {
            echo "<script>alert('Invalid request');</script>";
        } else {
            $chk = $conn->prepare("SELECT return_id FROM returns WHERE return_id = ? AND user_id = ? AND status = 'pending'");
            if ($chk) {
                $chk->bind_param("ii", $return_id, $user_id);
                $chk->execute();
                $ok = $chk->get_result()->num_rows > 0;
                $chk->close();

                if (!$ok) {
                    echo "<script>alert('Only pending requests can be withdrawn');</script>";
                } else {
                    $del = $conn->prepare("DELETE FROM returns WHERE return_id = ? AND user_id = ? AND status = 'pending'");
                    if ($del) {
                        $del->bind_param("ii", $return_id, $user_id);
                        if ($del->execute()) {
                            $del->close();
                            echo "<script>alert('Request withdrawn'); window.location.href='myreturns.php';</script>";
                            exit();
                        } else {
                            $del->close();
                            echo "<script>alert('Unable to withdraw request');</script>";
                        }
                    } else {
                        echo "<script>alert('Unexpected error (prepare delete)');</script>";
                    }
                }
            } else {
                echo "<script>alert('Unexpected error (prepare check)');</script>";
            }
        }
    }
}

if (!isset($conn) || !($conn instanceof mysqli)) {
    $err = "Database connection not found.";
} else {
    $sqlEligible = "
        SELECT o.order_id, o.total, o.created_at, o.payment_status
        FROM orders o
        LEFT JOIN returns r
          ON r.order_id = o.order_id AND r.user_id = o.user_id AND r.status = 'pending'
        WHERE o.user_id = ?
          AND o.payment_status IN ('paid','shipped')
          AND r.return_id IS NULL
        ORDER BY o.created_at DESC
    ";
    if ($st = $conn->prepare($sqlEligible)) {
        $st->bind_param("i", $user_id);
        $st->execute();
        $res = $st->get_result();
        while ($row = $res->fetch_assoc()) $eligible_orders[] = $row;
        $st->close();
    }

    $sqlReturns = "
        SELECT rt.return_id, rt.order_id, rt.reason, rt.status, rt.created_at,
               o.total, o.payment_status, o.created_at AS order_created_at
        FROM returns rt
        INNER JOIN orders o ON o.order_id = rt.order_id
        WHERE rt.user_id = ?
        ORDER BY rt.created_at DESC
    ";
    if ($sr = $conn->prepare($sqlReturns)) {
        $sr->bind_param("i", $user_id);
        $sr->execute();
        $rr = $sr->get_result();
        while ($row = $rr->fetch_assoc()) $returns[] = $row;
        $sr->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="accounts.css">
    <title>My Returns</title>
    <style>
        .content-card { background: var(--light); border-radius: 16px; padding: 24px; box-shadow: 0 2px 10px rgba(0,0,0,0.06); }
        .details-table { width: 100%; border-collapse: collapse; }
        .details-table th, .details-table td { padding: 10px 12px; border-bottom: 1px solid var(--grey); text-align: left; vertical-align: top; }
        .muted { color: var(--dark-grey); }
        .form-inline { display:flex; gap:14px; flex-wrap:wrap; }
        .form-inline .form-input, .form-inline select, .form-inline textarea { max-width: 420px; }
        textarea.form-input { height: 90px; resize: vertical; }
        .actions .btn { margin-right: 8px; }
    </style>
</head>
<body>
<div id="account-wrapper">

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
            <li class="active">
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
                    <h1>My Returns</h1>
                    <ul class="breadcrumb">
                        <li><a href="index.php">Home</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a class="active" href="myreturns.php">Returns</a></li>
                    </ul>
                </div>
            </div>

            <?php if ($err): ?>
                <div class="order-details" style="border-color:#f44336; margin-top:20px;">
                    <h3 style="color:#f44336; margin-bottom:10px;"><i class="bx bx-error-circle"></i> Error</h3>
                    <p><?= e($err) ?></p>
                </div>
            <?php else: ?>

                <section class="content-card" style="margin-top: 12px;">
                    <h2 style="margin:0 0 10px 0;">Request a Return</h2>
                    <?php if (empty($eligible_orders)): ?>
                        <p class="muted">No eligible orders to return. (Only <strong>paid</strong> or <strong>shipped</strong> orders without a pending request are listed here.)</p>
                    <?php else: ?>
                        <form method="post" class="form-inline" action="myreturns.php">
                            <div>
                                <label class="form-label" for="order_id">Order</label>
                                <select id="order_id" name="order_id" class="form-input" required>
                                    <option value="" disabled selected>Select an order</option>
                                    <?php foreach ($eligible_orders as $o): ?>
                                        <option value="<?= (int)$o['order_id'] ?>">
                                            #<?= (int)$o['order_id'] ?> — <?= e(date('j M Y, H:i', strtotime($o['created_at']))) ?>
                                            — <?= e(ucfirst($o['payment_status'])) ?> — <?= e(money_gbp($o['total'] ?? 0)) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div style="flex:1 1 420px;">
                                <label class="form-label" for="reason">Reason</label>
                                <textarea id="reason" name="reason" class="form-input" placeholder="Briefly explain the reason…" required></textarea>
                            </div>
                            <div style="align-self:flex-end;">
                                <button type="submit" name="add_return" class="btn">Submit Request</button>
                            </div>
                        </form>
                    <?php endif; ?>
                </section>

                <section class="content-card" style="margin-top: 24px;">
                    <div class="head" style="display:flex; align-items:center; gap:12px; margin-bottom:10px;">
                        <h2 style="margin:0;">Your Return Requests</h2>
                        <span class="muted" style="margin-left:auto;"><?= count($returns) ?> total</span>
                    </div>

                    <?php if (empty($returns)): ?>
                        <p class="muted">You haven’t submitted any returns yet.</p>
                    <?php else: ?>
                        <table class="details-table" aria-label="My returns">
                            <thead>
                                <tr>
                                    <th>Return #</th>
                                    <th>Order #</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Requested</th>
                                    <th style="width:200px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($returns as $rt): ?>
                                    <?php $cls = status_badge_class($rt['status'] ?? 'pending'); ?>
                                    <tr>
                                        <td>#<?= e((string)$rt['return_id']) ?></td>
                                        <td>
                                            <a href="myorders.php?order_id=<?= (int)$rt['order_id'] ?>" class="link">#<?= e((string)$rt['order_id']) ?></a>
                                            <div class="muted">Order total: <?= e(money_gbp($rt['total'] ?? 0)) ?></div>
                                        </td>
                                        <td><?= nl2br(e($rt['reason'])) ?></td>
                                        <td><span class="status <?= e($cls) ?>"><?= e(ucfirst($rt['status'])) ?></span></td>
                                        <td class="muted"><?= e(date('j M Y, H:i', strtotime($rt['created_at']))) ?></td>
                                        <td class="actions">
                                            <?php if (strtolower($rt['status']) === 'pending'): ?>
                                                <form method="post" action="myreturns.php" style="display:inline;">
                                                    <input type="hidden" name="return_id" value="<?= (int)$rt['return_id'] ?>">
                                                    <button type="submit" name="withdraw_return" class="btn"
                                                            onclick="return confirm('Withdraw this return request?');">
                                                        <i class='bx bx-x-circle'></i> Withdraw
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <span class="muted">—</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </section>

            <?php endif; ?>
        </main>
    </section>

</div>
</body>
</html>