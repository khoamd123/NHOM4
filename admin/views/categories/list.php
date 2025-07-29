<!DOCTYPE html>
<html>
<head>
    <title>Quản lý danh mục</title>
    <link rel="stylesheet" href="/NHOM4_DU_AN_1/assets/css/Administrator.css">
    <link rel="stylesheet" href="/NHOM4_DU_AN_1/vendor/bootstrap/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4 text-primary">Quản lý danh mục</h2>
        <form method="post" action="" class="row g-3 mb-4">
            <div class="col-md-5">
                <input type="text" name="name" class="form-control" placeholder="Tên danh mục" required>
            </div>
            <div class="col-md-5">
                <input type="text" name="slug" class="form-control" placeholder="Slug" required>
            </div>
            <div class="col-md-2">
                <button type="submit" name="add" class="btn btn-success w-100">Thêm danh mục</button>
            </div>
        </form>
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Tên danh mục</th>
                    <th>Slug</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($categories as $cat): ?>
                <tr>
                    <td><?= $cat['id'] ?></td>
                    <td><?= $cat['name'] ?></td>
                    <td><?= $cat['slug'] ?></td>
                    <td>
                        <a href="?delete=<?= $cat['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="../home.php" class="btn btn-secondary mt-3">Quay lại trang chủ admin</a>
    </div>
</body>
</html>