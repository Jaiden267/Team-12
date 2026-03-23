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
$orders = [];
$selected_order = null;
$order_items = [];

function status_badge_class(string $status): string {
    $s = strtolower(trim($status));
    if ($s === 'shipped') return 'completed';
    if ($s === 'paid')    return 'process';
    return 'pending';
}
function money_gbp($n): string { return '£' . number_format((float)$n, 2); }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_order'])) {
    if (!isset($conn) || !($conn instanceof mysqli)) {
        echo "<script>alert('Database connection not found');</script>";
    } else {
        $oid = (int)($_POST['order_id'] ?? 0);
        if ($oid <= 0) {
            echo "<script>alert('Invalid order');</script>";
        } else {
            $hdr = $conn->prepare("SELECT order_id, payment_status FROM orders WHERE order_id = ? AND user_id = ?");
            if ($hdr) {
                $hdr->bind_param("ii", $oid, $user_id);
                $hdr->execute();
                $row = $hdr->get_result()->fetch_assoc();
                $hdr->close();

                if (!$row) {
                    echo "<script>alert('Order not found');</script>";
                } else {
                    $status = strtolower($row['payment_status'] ?? 'pending');

                    if ($status === 'pending') {
                        $upd = $conn->prepare("UPDATE orders SET payment_status = 'failed' WHERE order_id = ? AND user_id = ?");
                        if ($upd) {
                            $upd->bind_param("ii", $oid, $user_id);
                            if ($upd->execute()) {
                                $upd->close();
                                echo "<script>alert('Order cancelled'); window.location.href='myorders.php';</script>";
                                exit();
                            } else {
                                $upd->close();
                                echo "<script>alert('Unable to cancel this order');</script>";
                            }
                        } else {
                            echo "<script>alert('Unexpected error (prepare update)');</script>";
                        }
                    } elseif ($status === 'paid') {
                        $exists = false;
                        if ($chk = $conn->prepare("SELECT return_id FROM returns WHERE order_id = ? AND user_id = ? AND status = 'pending' LIMIT 1")) {
                            $chk->bind_param("ii", $oid, $user_id);
                            $chk->execute();
                            $exists = $chk->get_result()->num_rows > 0;
                            $chk->close();
                        }
                        if ($exists) {
                            echo "<script>alert('A cancellation request is already pending for this order.'); window.location.href='myorders.php?order_id=".$oid."';</script>";
                            exit();
                        }
                        $reason = "Cancellation request by customer";
                        $ins = $conn->prepare("INSERT INTO returns (order_id, user_id, reason, status, created_at) VALUES (?, ?, ?, 'pending', NOW())");
                        if ($ins) {
                            $ins->bind_param("iis", $oid, $user_id, $reason);
                            if ($ins->execute()) {
                                $ins->close();
                                echo "<script>alert('Cancellation request sent'); window.location.href='myorders.php?order_id=".$oid."';</script>";
                                exit();
                            } else {
                                $ins->close();
                                echo "<script>alert('Unable to send cancellation request');</script>";
                            }
                        } else {
                            echo "<script>alert('Unexpected error (prepare request)');</script>";
                        }
                    } else {
                        echo "<script>alert('This order cannot be cancelled at its current stage');</script>";
                    }
                }
            } else {
                echo "<script>alert('Unexpected error (prepare select)');</script>";
            }
        }
    }
}

if (!isset($conn) || !($conn instanceof mysqli)) {
    $err = "Database connection not found.";
} else {
    $sql = "SELECT o.order_id, o.total, o.payment_status, o.created_at,
                   COALESCE(SUM(oi.quantity), 0) AS item_count
            FROM orders o
            LEFT JOIN order_items oi ON oi.order_id = o.order_id
            WHERE o.user_id = ?
            GROUP BY o.order_id
            ORDER BY o.created_at DESC";
    if ($st = $conn->prepare($sql)) {
        $st->bind_param("i", $user_id);
        if ($st->execute()) {
            $res = $st->get_result();
            while ($row = $res->fetch_assoc()) { $orders[] = $row; }
        } else {
            $err = "Failed to load orders.";
        }
        $st->close();
    } else {
        $err = "Failed to prepare orders query.";
    }

    if (!$err && isset($_GET['order_id'])) {
        $oid = (int)$_GET['order_id'];
        if ($oid > 0) {
            $hdr = $conn->prepare("SELECT order_id, full_name, email, phone, address1, address2, city, postcode, country, total, payment_status, created_at
                                   FROM orders WHERE order_id = ? AND user_id = ?");
            if ($hdr) {
                $hdr->bind_param("ii", $oid, $user_id);
                if ($hdr->execute()) {
                    $rh = $hdr->get_result();
                    $selected_order = $rh->fetch_assoc();
                }
                $hdr->close();
            }
            if ($selected_order) {
                $it = $conn->prepare("SELECT product_name, sku, color, size, quantity, price, subtotal, product_sku
                                      FROM order_items WHERE order_id = ? ORDER BY item_id ASC");
                if ($it) {
                    $it->bind_param("i", $oid);
                    if ($it->execute()) {
                        $ri = $it->get_result();
                        while ($r = $ri->fetch_assoc()) { $order_items[] = $r; }
                    }
                    $it->close();
                }
            }
        }
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
    <title>My Orders</title>
    <style>
        .content-card { background: var(--light); border-radius: 16px; padding: 24px; box-shadow: 0 2px 10px rgba(0,0,0,0.06); }
        .details-table { width: 100%; border-collapse: collapse; }
        .details-table th, .details-table td { padding: 10px 12px; border-bottom: 1px solid var(--grey); text-align: left; vertical-align: middle; }
        .muted { color: var(--dark-grey); }
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
            <li class="active">
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
                    <h1>My Orders</h1>
                    <ul class="breadcrumb">
                        <li><a href="index.php">Home</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a class="active" href="myorders.php">My Orders</a></li>
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
                    <div class="head" style="display:flex; align-items:center; gap:12px; margin-bottom:10px;">
                        <h2 style="margin:0;">Your Orders</h2>
                        <span class="muted" style="margin-left:auto;"><?= count($orders) ?> total</span>
                    </div>

                    <?php if (empty($orders)): ?>
                        <p class="muted">You don’t have any orders yet.</p>
                    <?php else: ?>
                        <table class="details-table" aria-label="My orders">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th style="width:240px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $o): ?>
                                    <?php $st = strtolower($o['payment_status'] ?? ''); ?>
                                    <tr>
                                        <td>#<?= e((string)$o['order_id']) ?></td>
                                        <td><?= e(date('j M Y, H:i', strtotime($o['created_at']))) ?></td>
                                        <td>
                                            <?php $cls = status_badge_class($o['payment_status'] ?? ''); ?>
                                            <span class="status <?= e($cls) ?>"><?= e(ucfirst($o['payment_status'])) ?></span>
                                        </td>
                                        <td><?= e((string)($o['item_count'] ?? 0)) ?></td>
                                        <td><?= e(money_gbp($o['total'] ?? 0)) ?></td>
                                        <td class="actions">
                                            <a href="myorders.php?order_id=<?= (int)$o['order_id'] ?>" class="btn">
                                                <i class='bx bx-show'></i> View
                                            </a>

                                            <?php if ($st === 'pending'): ?>
                                                <form action="myorders.php" method="post" style="display:inline;">
                                                    <input type="hidden" name="order_id" value="<?= (int)$o['order_id'] ?>">
                                                    <button type="submit" name="cancel_order" class="btn"
                                                            onclick="return confirm('Cancel this order?');">
                                                        <i class='bx bx-x-circle'></i> Cancel
                                                    </button>
                                                </form>
                                            <?php elseif ($st === 'paid'): ?>
                                                <form action="myorders.php" method="post" style="display:inline;">
                                                    <input type="hidden" name="order_id" value="<?= (int)$o['order_id'] ?>">
                                                    <button type="submit" name="cancel_order" class="btn"
                                                            onclick="return confirm('Send a cancellation request for this order?');">
                                                        <i class='bx bx-envelope'></i> Request Cancel
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </section>

                <?php if ($selected_order): ?>
                    <section class="content-card" style="margin-top: 24px;">
                        <div class="head" style="display:flex; align-items:center; gap:12px; margin-bottom:10px;">
                            <h2 style="margin:0;">Order #<?= e((string)$selected_order['order_id']) ?> Details</h2>
                            <span class="muted" style="margin-left:auto;"><?= e(date('j M Y, H:i', strtotime($selected_order['created_at']))) ?></span>
                        </div>

                        <div class="order-details" style="margin-top:0;">
                            <table class="details-table">
                                <tr>
                                    <th style="width:220px;">Name</th>
                                    <td><?= e($selected_order['full_name'] ?? '—') ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?= e($selected_order['email'] ?? '—') ?></td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td><?= e($selected_order['phone'] ?? '—') ?></td>
                                </tr>
                                <tr>
                                    <th>Shipping Address</th>
                                    <td>
                                        <?php
                                            $parts = [];
                                            if (!empty($selected_order['address1'])) $parts[] = $selected_order['address1'];
                                            if (!empty($selected_order['address2'])) $parts[] = $selected_order['address2'];
                                            $cityline = trim(($selected_order['city'] ?? '') . (empty($selected_order['postcode']) ? '' : ', ' . $selected_order['postcode']));
                                            if ($cityline !== '') $parts[] = $cityline;
                                            if (!empty($selected_order['country'])) $parts[] = $selected_order['country'];
                                            echo e(implode(', ', $parts) ?: '—');
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <?php $cls = status_badge_class($selected_order['payment_status'] ?? ''); ?>
                                        <span class="status <?= e($cls) ?>"><?= e(ucfirst($selected_order['payment_status'])) ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Order Total</th>
                                    <td><?= e(money_gbp($selected_order['total'] ?? 0)) ?></td>
                                </tr>
                            </table>
                        </div>

                        <div class="table-data" style="margin-top:16px;">
                            <div class="order" style="width:100%;">
                                <div class="head">
                                    <h3>Items</h3>
                                </div>
                                <table class="details-table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Options</th>
                                            <th>Qty</th>
                                            <th>Price</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($order_items)): ?>
                                            <tr><td colspan="5" class="muted">No items found for this order.</td></tr>
                                        <?php else: ?>
                                            <?php foreach ($order_items as $it): ?>
                                                <tr>
                                                    <td><?= e($it['product_name'] ?? '—') ?></td>
                                                    <td class="muted">
                                                        <?php
                                                            $opts = [];
                                                            if (!empty($it['size']))  $opts[] = 'Size: ' . $it['size'];
                                                            if (!empty($it['color'])) $opts[] = 'Color: ' . $it['color'];
                                                            if (!empty($it['sku']))   $opts[] = 'SKU: ' . $it['sku'];
                                                            echo e(implode(' • ', $opts) ?: '—');
                                                        ?>
                                                    </td>
                                                    <td><?= e((string)($it['quantity'] ?? 0)) ?></td>
                                                    <td><?= e(money_gbp($it['price'] ?? 0)) ?></td>
                                                    <td><?= e(money_gbp(($it['subtotal'] ?? null) !== null ? $it['subtotal'] : (($it['price'] ?? 0) * ($it['quantity'] ?? 0)))) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div style="margin-top:16px; display:flex; gap:10px; align-items:center;">
                            <a href="myorders.php" class="btn"><i class='bx bx-arrow-back'></i> Back to Orders</a>

                            <?php
                              $st = strtolower($selected_order['payment_status'] ?? '');
                              if ($st === 'pending' || $st === 'paid'):
                            ?>
                                <form action="myorders.php" method="post" style="display:inline;">
                                    <input type="hidden" name="order_id" value="<?= (int)$selected_order['order_id'] ?>">
                                    <button type="submit" name="cancel_order" class="btn"
                                            onclick="return confirm('<?= $st==='pending' ? 'Cancel this order?' : 'Send a cancellation request for this order?' ?>');">
                                        <?php if ($st === 'pending'): ?>
                                            <i class='bx bx-x-circle'></i> Cancel
                                        <?php else: ?>
                                            <i class='bx bx-envelope'></i> Request Cancel
                                        <?php endif; ?>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </section>
                <?php endif; ?>

            <?php endif; ?>
        </main>
    </section>

</div>
</body>
</html>