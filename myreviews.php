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

$user_id = (int)($_SESSION['user_id'] ?? 0);
if ($user_id <= 0) {
    header("Location: signin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($conn) || !($conn instanceof mysqli)) {
        echo "<script>alert('Database connection not found.');</script>";
    } else {
        if (isset($_POST['add_review'])) {
            $product_id = (int)($_POST['product_id'] ?? 0);
            $rating     = (int)($_POST['rating'] ?? 0);
            $comment    = trim($_POST['comment'] ?? '');
            if ($product_id <= 0) {
                echo "<script>alert('Please select a product.');</script>";
            } elseif ($rating < 1 || $rating > 5) {
                echo "<script>alert('Rating must be between 1 and 5.');</script>";
            } elseif ($comment === '') {
                echo "<script>alert('Please write a short comment.');</script>";
            } else {
                if ($chk = $conn->prepare("SELECT product_id FROM products WHERE product_id = ?")) {
                    $chk->bind_param("i", $product_id);
                    $chk->execute();
                    $exists = $chk->get_result()->num_rows > 0;
                    $chk->close();
                    if (!$exists) {
                        echo "<script>alert('Selected product does not exist.');</script>";
                    } else {
                        if ($ins = $conn->prepare("INSERT INTO reviews (user_id, product_id, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())")) {
                            $ins->bind_param("iiis", $user_id, $product_id, $rating, $comment);
                            if ($ins->execute()) {
                                $ins->close();
                                echo "<script>alert('Review added successfully'); window.location.href='myreviews.php';</script>";
                                exit();
                            } else {
                                $ins->close();
                                echo "<script>alert('Error adding review');</script>";
                            }
                        } else {
                            echo "<script>alert('Unexpected error (prepare insert).');</script>";
                        }
                    }
                } else {
                    echo "<script>alert('Unexpected error (prepare product check).');</script>";
                }
            }
        }

        if (isset($_POST['update_review'])) {
            $review_id = (int)($_POST['review_id'] ?? 0);
            $rating    = (int)($_POST['rating'] ?? 0);
            $comment   = trim($_POST['comment'] ?? '');
            if ($review_id <= 0) {
                echo "<script>alert('Invalid review.');</script>";
            } elseif ($rating < 1 || $rating > 5) {
                echo "<script>alert('Rating must be between 1 and 5.');</script>";
            } elseif ($comment === '') {
                echo "<script>alert('Please enter a comment.');</script>";
            } else {
                if ($own = $conn->prepare("SELECT review_id FROM reviews WHERE review_id = ? AND user_id = ?")) {
                    $own->bind_param("ii", $review_id, $user_id);
                    $own->execute();
                    $valid = $own->get_result()->num_rows > 0;
                    $own->close();
                    if (!$valid) {
                        echo "<script>alert('You can only edit your own reviews.');</script>";
                    } else {
                        if ($upd = $conn->prepare("UPDATE reviews SET rating = ?, comment = ? WHERE review_id = ?")) {
                            $upd->bind_param("isi", $rating, $comment, $review_id);
                            if ($upd->execute()) {
                                $upd->close();
                                echo "<script>alert('Review updated successfully'); window.location.href='myreviews.php';</script>";
                                exit();
                            } else {
                                $upd->close();
                                echo "<script>alert('Error updating review');</script>";
                            }
                        } else {
                            echo "<script>alert('Unexpected error (prepare update).');</script>";
                        }
                    }
                } else {
                    echo "<script>alert('Unexpected error (prepare ownership check).');</script>";
                }
            }
        }

        if (isset($_POST['delete_review'])) {
            $review_id = (int)($_POST['review_id'] ?? 0);
            if ($review_id <= 0) {
                echo "<script>alert('Invalid review.');</script>";
            } else {
                if ($own = $conn->prepare("SELECT review_id FROM reviews WHERE review_id = ? AND user_id = ?")) {
                    $own->bind_param("ii", $review_id, $user_id);
                    $own->execute();
                    $valid = $own->get_result()->num_rows > 0;
                    $own->close();
                    if (!$valid) {
                        echo "<script>alert('You can only delete your own reviews.');</script>";
                    } else {
                        if ($del = $conn->prepare("DELETE FROM reviews WHERE review_id = ?")) {
                            $del->bind_param("i", $review_id);
                            if ($del->execute()) {
                                $del->close();
                                echo "<script>alert('Review deleted successfully'); window.location.href='myreviews.php';</script>";
                                exit();
                            } else {
                                $del->close();
                                echo "<script>alert('Error deleting review');</script>";
                            }
                        } else {
                            echo "<script>alert('Unexpected error (prepare delete).');</script>";
                        }
                    }
                } else {
                    echo "<script>alert('Unexpected error (prepare ownership check).');</script>";
                }
            }
        }
    }
}

$products = [];
if (isset($conn) && ($conn instanceof mysqli)) {
    if ($ps = $conn->prepare("SELECT product_id, name FROM products ORDER BY name ASC")) {
        $ps->execute();
        $r = $ps->get_result();
        while ($row = $r->fetch_assoc()) $products[] = $row;
        $ps->close();
    }
}

$reviews = [];
if (isset($conn) && ($conn instanceof mysqli)) {
    $sql = "SELECT r.review_id, r.product_id, r.rating, r.comment, r.created_at,
                   p.name AS product_name,
                   pi.image_url
            FROM reviews r
            INNER JOIN products p ON p.product_id = r.product_id
            LEFT JOIN product_images pi ON pi.product_id = r.product_id AND pi.is_main = 1
            WHERE r.user_id = ?
            ORDER BY r.created_at DESC";
    if ($st = $conn->prepare($sql)) {
        $st->bind_param("i", $user_id);
        $st->execute();
        $res = $st->get_result();
        while ($row = $res->fetch_assoc()) $reviews[] = $row;
        $st->close();
    }
}

function stars(int $n): string {
    $n = max(0, min(5, $n));
    $out = '';
    for ($i = 0; $i < 5; $i++) {
        $out .= ($i < $n) ? "<i class='bx bxs-star' style='color:#f5b50a;'></i>" : "<i class='bx bx-star' style='color:#f5b50a;'></i>";
    }
    return $out;
}

$current = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="accounts.css" />
    <title>My Reviews</title>
    <style>
        .content-card { background: var(--light); border-radius: 16px; padding: 24px; box-shadow: 0 2px 10px rgba(0,0,0,0.06); }
        .details-table { width: 100%; border-collapse: collapse; }
        .details-table th, .details-table td { padding: 10px 12px; border-bottom: 1px solid var(--grey); text-align: left; vertical-align: middle; }
        .muted { color: var(--dark-grey); }
        .product-cell { display:flex; align-items:center; gap:12px; }
        .product-cell img { width:36px; height:36px; border-radius:6px; object-fit:cover; }
        .btn-inline { display:inline-block; margin-right:8px; }
        .form-inline { display:flex; gap:10px; flex-wrap:wrap; margin-top:10px; }
        .form-inline .form-input, .form-inline select, .form-inline textarea { max-width: 320px; }
        .form-input, select, textarea { font-family: var(--poppins); }
        textarea.form-input { height: 90px; resize: vertical; }
    </style>
</head>
<body>
    <section id="sidebar">
        <a href="index.php" class="brand" aria-label="Lunare Clothing Home" style="display:flex; align-items:center;">
            <img src="../assets/lunare_logo.png" alt="Lunare Clothing logo" style="width:150px; height:60px; margin-left:20px; margin-top:20px;">
        </a>
        <ul class="side-menu top">
            <li class="<?= $current === 'accounts.php' ? 'active' : '' ?>">
                <a href="accounts.php">
                    <i class='bx bxs-dashboard bx-sm'></i>
                    <span class="text">My Account</span>
                </a>
            </li>
            <li class="<?= $current === 'mydetails.php' ? 'active' : '' ?>">
                <a href="mydetails.php">
                    <i class='bx bxs-user-detail bx-sm'></i>
                    <span class="text">My Details</span>
                </a>
            </li>
            <li class="<?= $current === 'myorders.php' ? 'active' : '' ?>">
                <a href="myorders.php">
                    <i class='bx bxs-doughnut-chart bx-sm'></i>
                    <span class="text">My Orders</span>
                </a>
            </li>
            <li class="<?= $current === 'change-password.php' ? 'active' : '' ?>">
                <a href="change-password.php">
                    <i class='bx bxs-key bx-sm'></i>
                    <span class="text">Change Password</span>
                </a>
            </li>
            <li class="<?= $current === 'myreviews.php' ? 'active' : '' ?>">
                <a href="myreviews.php">
                    <i class='bx bxs-message-dots bx-sm'></i>
                    <span class="text">My Reviews</span>
                </a>
            </li>
            <li class="<?= $current === 'myreturns.php' ? 'active' : '' ?>">
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
                    <h1>My Reviews</h1>
                    <ul class="breadcrumb">
                        <li><a href="index.php">Home</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a class="active" href="myreviews.php">My Reviews</a></li>
                    </ul>
                </div>
            </div>

            <section class="content-card" style="margin-top: 12px;">
                <h2 style="margin:0 0 10px 0;">Add a Review</h2>
                <form method="post" class="form-inline" action="myreviews.php">
                    <div>
                        <label class="form-label" for="product_id">Product</label>
                        <select id="product_id" name="product_id" class="form-input" required>
                            <option value="" disabled selected>Select a product</option>
                            <?php foreach ($products as $p): ?>
                                <option value="<?= (int)$p['product_id'] ?>"><?= e($p['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="form-label" for="rating">Rating</label>
                        <select id="rating" name="rating" class="form-input" required>
                            <?php for ($i=5; $i>=1; $i--): ?>
                                <option value="<?= $i ?>"><?= $i ?> star<?= $i>1?'s':'' ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div style="flex:1 1 320px;">
                        <label class="form-label" for="comment">Comment</label>
                        <textarea id="comment" name="comment" class="form-input" placeholder="Share a quick thought..." required></textarea>
                    </div>
                    <div style="align-self:flex-end;">
                        <button type="submit" name="add_review" class="btn">Submit Review</button>
                    </div>
                </form>
            </section>

            <section class="content-card" style="margin-top: 24px;">
                <div class="head" style="display:flex; align-items:center; gap:12px; margin-bottom:10px;">
                    <h2 style="margin:0;">Your Reviews</h2>
                    <span class="muted" style="margin-left:auto;"><?= count($reviews) ?> total</span>
                </div>

                <?php if (empty($reviews)): ?>
                    <p class="muted">You haven’t written any reviews yet.</p>
                <?php else: ?>
                    <table class="details-table" aria-label="My reviews">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Rating</th>
                                <th>Comment</th>
                                <th>Date</th>
                                <th style="width:210px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($reviews as $rv): ?>
                            <tr>
                                <td>
                                    <div class="product-cell">
                                        <?php if (!empty($rv['image_url'])): ?>
                                            <img src="<?= e($rv['image_url']) ?>" alt="<?= e($rv['product_name']) ?>">
                                        <?php else: ?>
                                            <div style="width:36px;height:36px;border-radius:6px;background:#ddd;"></div>
                                        <?php endif; ?>
                                        <span><?= e($rv['product_name']) ?></span>
                                    </div>
                                </td>
                                <td><?= stars((int)$rv['rating']) ?></td>
                                <td><?= e($rv['comment']) ?></td>
                                <td class="muted"><?= e(date('j M Y, H:i', strtotime($rv['created_at']))) ?></td>
                                <td>
                                    <button type="button" class="btn btn-inline"
                                            onclick="editReview(<?= (int)$rv['review_id'] ?>, <?= (int)$rv['rating'] ?>, <?= json_encode($rv['comment'], JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) ?>)">
                                        <i class='bx bx-edit'></i> Edit
                                    </button>
                                    <form method="post" action="myreviews.php" style="display:inline;">
                                        <input type="hidden" name="review_id" value="<?= (int)$rv['review_id'] ?>">
                                        <button type="submit" name="delete_review" class="btn btn-inline"
                                                onclick="return confirm('Delete this review?');">
                                            <i class='bx bx-trash'></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </section>
        </main>
    </section>

    <form id="editForm" method="post" action="myreviews.php" style="display:none;">
        <input type="hidden" name="update_review" value="1">
        <input type="hidden" name="review_id" id="edit_review_id">
        <input type="hidden" name="rating" id="edit_rating">
        <input type="hidden" name="comment" id="edit_comment">
    </form>

    <script>
        function editReview(id, currentRating, currentComment) {
            var r = prompt("Update rating (1-5):", String(currentRating));
            if (r === null) return;
            r = parseInt(r, 10);
            if (!(r >= 1 && r <= 5)) {
                alert("Rating must be between 1 and 5.");
                return;
            }
            var c = prompt("Update comment:", String(currentComment));
            if (c === null) return;
            c = c.trim();
            if (c.length === 0) {
                alert("Please enter a comment.");
                return;
            }
            document.getElementById('edit_review_id').value = id;
            document.getElementById('edit_rating').value = r;
            document.getElementById('edit_comment').value = c;
            document.getElementById('editForm').submit();
        }
    </script>
</body>
</html>