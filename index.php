<?php
// Kết nối với cơ sở dữ liệu
include('connection.php');

// Lấy thông tin từ cơ sở dữ liệu
$sql = "SELECT * FROM marathon"; // Giả sử bảng chứa thông tin các giải chạy là 'marathon'
$result = $conn->query($sql);

// Kiểm tra nếu có dữ liệu
$marathons = [];
if ($result && $result->num_rows > 0) {
    $marathons = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marathon Home</title>
    <!-- Link to CSS -->
    <link rel="stylesheet" href="style.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include('navbar.php'); ?>
    <div class="container-lg mt-3" style="max-width:1000px; margin: 0 auto;">
        
        <!-- Carousel -->
        <div id="carouselExampleIndicators" class="carousel slide my-3" data-bs-ride="carousel">
            <ol class="carousel-indicators">
                <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"></li>
                <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"></li>
                <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="image/d.png" class="d-block w-100" alt="First slide">
                </div>
                <div class="carousel-item">
                    <img src="image/161022.jpg" class="d-block w-100" alt="Second slide">
                </div>
                <div class="carousel-item">
                    <img src="image/run1.png" class="d-block w-100" alt="Third slide">
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </a>
        </div>

        <main>
            <!-- Marathon Information -->
            <section class="welcome text-center">
                <h2>About the Marathon</h2>
                <p>We organize a marathon to promote public health. Join us to participate in this grand sporting event!</p>
            </section>

            <!-- Marathon Photo Gallery -->
            <section class="photo-gallery text-center">
                <h2>Marathon Events</h2>
                <?php if (empty($marathons)): ?>
                    <p>No marathon events have been updated yet.</p>
                <?php else: ?>
                    <div class="gallery-container d-flex justify-content-center">
                        <div class="gallery d-flex justify-content-center flex-wrap">
                            <?php foreach ($marathons as $index => $marathon): ?>
                                <div class="photo-item m-4">
                                    <img 
                                        src="<?php echo $marathon['path']; ?>" 
                                        alt="Marathon Event <?php echo $marathon['raceName']; ?>" 
                                        class="img-thumbnail " 
                                        style="width: 400px; height: auto;"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#marathonModal<?php echo $index; ?>">
                                </div>

                                <!-- Modal -->
                                <div class="modal fade" id="marathonModal<?php echo $index; ?>" tabindex="-1" aria-labelledby="marathonModalLabel<?php echo $index; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="marathonModalLabel<?php echo $index; ?>">
                                                    <?php echo $marathon['raceName']; ?>
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <img 
                                                    src="<?php echo $marathon['path']; ?>" 
                                                    alt="Marathon Event <?php echo $marathon['raceName']; ?>" 
                                                    class="img-fluid mb-3">
                                                <p><strong>Details:</strong> <?php echo $marathon['description']; ?></p>
                                                <p><strong>Date:</strong> <?php echo $marathon['date']; ?></p>
                                                <p><strong>Location:</strong> <?php echo $marathon['location']; ?></p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <a href="registration_form.php?marathon_id=<?php echo $marathon['marathonID']; ?>" class="btn btn-primary">Register</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </section>
        </main>
    </div>
    <footer>
        <p>&copy; 2024 Marathon. All rights reserved.</p>
    </footer>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
