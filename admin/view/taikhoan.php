<?php
// Thực hiện kết nối cơ sở dữ liệu sử dụng PDO


// Hàm lấy danh sách tất cả người dùng
function getAllUsers($conn)
{
    $conn = connectdb();
    $stmt = $conn->prepare("SELECT id, user, role FROM tbl_users");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Hàm cập nhật vai trò của người dùng
function updateUserRole($conn, $userId, $role)
{
    $conn = connectdb();
    $stmt = $conn->prepare("UPDATE tbl_users SET role = :role WHERE id = :userId");
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':userId', $userId);
    return $stmt->execute();
}

// Lấy danh sách người dùng
$conn = connectdb();
$users = getAllUsers($conn);
?>

    <div class="container mt-5">
        <h2 class="mb-4">User List</h2>
        <input type="text" id="searchInput" placeholder="Search..." class="form-control mb-3">
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Edit Role</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($user['user'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo ($user['role'] == 1) ? 'Admin' : 'User'; ?></td>
                        <td class="editButton" onclick="openEditModal(<?php echo $user['id']; ?>)">Edit</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit User Role</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="user_id">
                    <label for="roleSelect">Select Role:</label>
                    <select id="roleSelect" class="form-control">
                        <option value="1">Admin</option>
                        <option value="2">User</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="updateUserRole()">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    function openEditModal(userId) {
        document.getElementById('user_id').value = userId;
        $('#editModal').modal('show');
    }

    function updateUserRole() {
        var userId = document.getElementById('user_id').value;
        var roleSelect = document.getElementById('roleSelect');
        var selectedRole = roleSelect.options[roleSelect.selectedIndex].value;

        // Gửi yêu cầu AJAX để cập nhật vai trò của người dùng
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Nếu cập nhật thành công, hiển thị thông báo hoặc làm mới trang
                    alert("User role updated successfully.");
                    location.reload(); // Làm mới trang sau khi cập nhật thành công
                } else {
                    // Nếu cập nhật thất bại, hiển thị thông báo lỗi hoặc xử lý tương ứng
                    alert("Failed to update user role.");
                }
            }
        };
        xhr.open("POST", "../view/update_user_role.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send("userId=" + userId + "&role=" + selectedRole);
    }

    function searchUser() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        table = document.getElementsByTagName("table")[0];
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[1]; // Username column
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }

    // Gọi hàm tìm kiếm mỗi khi có sự thay đổi trong ô nhập
    document.getElementById("searchInput").addEventListener("input", searchUser);
</script>


