<!DOCTYPE html>
<html>
<head>
    <title>Quản lý tài khoản</title>
    <link rel="stylesheet" href="/NHOM4_DU_AN_1/assets/css/Administrator.css">
    <link rel="stylesheet" href="/NHOM4_DU_AN_1/vendor/bootstrap/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4 text-primary">Quản lý tài khoản</h2>
        <form method="post" action="" class="row g-3 mb-4">
            <div class="col-md-2">
                <input type="text" name="name" class="form-control" placeholder="Tên" required>
            </div>
            <div class="col-md-2">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="col-md-2">
                <input type="password" name="password" class="form-control" placeholder="Mật khẩu" required>
            </div>
            <div class="col-md-2">
                <input type="text" name="phone" class="form-control" placeholder="SĐT">
            </div>
            <div class="col-md-2">
                <select name="role" class="form-control" required>
                    <option value="0">User</option>
                    <option value="1">Admin</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" name="add" class="btn btn-success w-100">Thêm tài khoản</button>
            </div>
        </form>
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>SĐT</th>
                    <th>Quyền</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= $user['name'] ?></td>
                    <td><?= $user['email'] ?></td>
                    <td><?= $user['phone'] ?></td>
                    <td><?= $user['role'] == 1 ? 'Admin' : 'User' ?></td>
                    <td><?= $user['created_at'] ?></td>
                    <td>
                        <a href="?delete=<?= $user['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="../home.php" class="btn btn-secondary mt-3">Quay lại trang chủ admin</a>
    </div>
</body>
</html>