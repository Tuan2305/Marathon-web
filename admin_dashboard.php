<?php
// Gọi navbar.php vào đây
include('connection.php'); // Kết nối cơ sở dữ liệu

// Kiểm tra quyền đăng nhập
session_start();
if (!isset($_SESSION['role'])) {
    header("Location: login.php"); // Nếu chưa đăng nhập, chuyển hướng về trang đăng nhập
    exit();
}

// Xử lý cập nhật entryNo và timeRecord chỉ dành cho admin
if ($_SESSION['role'] === 'admin' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $participationID = $_POST['participationID'];
    $entryNo = mysqli_real_escape_string($conn, $_POST['entryNo']);
    $timeRecord = mysqli_real_escape_string($conn, $_POST['timeRecord']);

    $update_query = "UPDATE participation 
                     SET entryNo = '$entryNo', timeRecord = '$timeRecord' 
                     WHERE participationID = '$participationID'";

    if (mysqli_query($conn, $update_query)) {
        $successMessage = "Updated successfully!";
    } else {
        $errorMessage = "Error updating record: " . mysqli_error($conn);
    }
}

// Lấy danh sách người tham gia và tên giải marathon, đồng thời sắp xếp theo timeRecord
$query = "SELECT p.participationID, p.entryNo, p.timeRecord, u.name, u.email, m.raceName AS marathon_name, p.hotel, p.phone, p.address 
          FROM participation p 
          JOIN user u ON p.userID = u.userID
          JOIN marathon m ON p.marathonID = m.marathonID
          ORDER BY p.timeRecord ASC"; // Sắp xếp theo thời gian chạy (timeRecord) từ nhỏ đến lớn

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
<?php include('navbar.php'); ?>
    <div class="container mt-4">
        <h1 class="text-center">Dashboard - Participant Information</h1>

        <?php if (isset($successMessage)): ?>
            <div class="alert alert-success"><?= $successMessage ?></div>
        <?php endif; ?>
        <?php if (isset($errorMessage)): ?>
            <div class="alert alert-danger"><?= $errorMessage ?></div>
        <?php endif; ?>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Marathon</th>
                    <th>Entry No</th>
                    <th>Time Record</th>
                    <th>Hotel</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Standing</th> <!-- Thêm cột thứ hạng -->
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <th>Actions</th> <!-- Cột hành động chỉ hiển thị cho admin -->
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $rank = 1; // Khởi tạo thứ hạng
                while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <form method="POST" action="">
                        <?php endif; ?>
                            <td><?= htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['marathon_name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <input type="text" name="entryNo" value="<?= htmlspecialchars($row['entryNo'], ENT_QUOTES, 'UTF-8') ?>" class="form-control">
                                <?php else: ?>
                                    <?= htmlspecialchars($row['entryNo'], ENT_QUOTES, 'UTF-8') ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <input type="text" name="timeRecord" value="<?= htmlspecialchars($row['timeRecord'], ENT_QUOTES, 'UTF-8') ?>" class="form-control">
                                <?php else: ?>
                                    <?= htmlspecialchars($row['timeRecord'], ENT_QUOTES, 'UTF-8') ?>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($row['hotel'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['phone'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['address'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= $rank++ ?></td> <!-- Hiển thị thứ hạng -->
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                <td>
                                    <input type="hidden" name="participationID" value="<?= $row['participationID'] ?>">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </td>
                            <?php endif; ?>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            </form>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
