
<?php
// Kết nối cơ sở dữ liệu

require 'connection.php';
session_start();

// Kiểm tra người dùng đã đăng nhập chưa
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

// Lấy userID từ session
$userID = $_SESSION['userID'];

try {
    // Lấy thông tin người dùng từ bảng user
    $user_query = $conn->prepare("SELECT name, nationality, gender, age, email FROM user WHERE userID = ?");
    $user_query->bind_param("s", $userID);
    $user_query->execute();
    $user_result = $user_query->get_result();
    $user = $user_result->fetch_assoc();
    $userName = $user['name'];  // Lấy tên người dùng
    $userNationality = $user['nationality'];  // Lấy quốc tịch người dùng
    $userGender = $user['gender'];  // Lấy giới tính người dùng
    $userAge = $user['age'];  // Lấy độ tuổi người dùng
    $userEmail = $user['email'];  // Lấy email người dùng
    $user_query->close();

    // Lấy thông tin các giải chạy mà người dùng đã tham gia (không yêu cầu entryNo phải có giá trị)
    $stmt = $conn->prepare("
        SELECT 
            u.name AS participant_name,
            m.raceName AS marathon_name,
            p.entryNo AS bib_number,
            p.timeRecord AS finish_time
        FROM 
            user u
        JOIN 
            participation p ON u.userID = p.userID
        JOIN 
            marathon m ON p.marathonID = m.marathonID
        WHERE 
            u.userID = ?
    ");
    $stmt->bind_param("s", $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Kiểm tra nếu có kết quả trả về
    if ($result->num_rows > 0) {
        // Chuyển kết quả thành mảng để sử dụng trong HTML
        $registrations = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $registrations = [];  // Không có dữ liệu
    }

    $stmt->close();
} catch (Exception $e) {
    die("Error: " . htmlspecialchars($e->getMessage()));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        body {
            background-color: #f7f7f7;
        }
        .profile-container {
            margin-top: 50px;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-info {
            margin-bottom: 20px;
        }
        .table-container {
            margin-top: 30px;
        }
    </style>
</head>
<body>
<?php include('navbar.php');?>
<div class="container">
    <div class="row">
        <!-- Hiển thị thông tin cá nhân -->
        <div class="col-md-6 col-md-offset-3 profile-container">
            <h1 class="form-title">User Profile</h1>
            <div class="profile-info">
                <strong>Name:</strong> <?php echo htmlspecialchars($userName, ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <div class="profile-info">
                <strong>Nationality:</strong> <?php echo htmlspecialchars($userNationality, ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <div class="profile-info">
                <strong>Gender:</strong> <?php echo htmlspecialchars($userGender, ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <div class="profile-info">
                <strong>Age:</strong> <?php echo htmlspecialchars($userAge, ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <div class="profile-info">
                <strong>Email:</strong> <?php echo htmlspecialchars($userEmail, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        </div>
    </div>

    <!-- Hiển thị bảng thông tin giải chạy -->
    <div class="row table-container">
        <div class="col-md-10 col-md-offset-1">
            <h2 class="form-title">Registered Marathons</h2>
            <?php if (!empty($registrations)): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Marathon</th>
                            <th>Entry Number</th>
                            <th>Finish Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registrations as $registration): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($registration['participant_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($registration['marathon_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($registration['bib_number'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($registration['finish_time'], ENT_QUOTES, 'UTF-8'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center">You have no registered marathons yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>

<?php
// Đóng kết nối cơ sở dữ liệu
$conn->close();
?>
