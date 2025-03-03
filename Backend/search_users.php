<?php
include 'db/connect.php';

$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';
$sex = $_GET['sex'] ?? '';

$sql = "SELECT id, name, sex, email, phone, user, role FROM tbl_user WHERE 1=1"; 

if (!empty($search)) {
    $sql .= " AND (name LIKE ? OR email LIKE ? OR user LIKE ?)";
}

if (!empty($status)) {
    $sql .= " AND status = ?";
}

if (!empty($sex)) {
    $sql .= " AND sex = ?";
}

$stmt = $mysqli->prepare($sql);

if ($stmt === false) {
    echo "Lỗi prepare: " . $mysqli->error;
    exit;
}

$types = "";
$params = [];

if (!empty($search)) {
    $searchParam = "%" . $search . "%";
    $types .= "sss";
    $params = array_merge($params, [$searchParam, $searchParam, $searchParam]);
}

if (!empty($status)) {
    $types .= "s";
    $params[] = $status;
}

if (!empty($sex)) {
    $types .= "s";
    $params[] = $sex;
}

if (!empty($types)) {
    $stmt->bind_param($types, ...$params);
}

if (!$stmt->execute()) {
    echo "Lỗi execute: " . $stmt->error;
    exit;
}

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["name"] ?? '') . "</td>";  //Xử lý NULL
        echo "<td>" . htmlspecialchars($row["sex"] ?? '') . "</td>"; //Xử lý NULL
        echo "<td>" . htmlspecialchars($row["email"] ?? '') . "</td>"; //Xử lý NULL
        echo "<td>" . htmlspecialchars($row["phone"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["user"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["role"]) . "</td>";
        echo "<td>
                 <button onclick=\"viewUser('" . htmlspecialchars($row["id"]) . "')\">Xem</button>
                 <button onclick=\"editUser('" . htmlspecialchars($row["id"]) . "')\">Sửa</button>
                 <button onclick=\"toggleBlockUser('" . htmlspecialchars($row["id"]) . "')\">Khóa/Mở</button>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='8'>Không tìm thấy người dùng nào.</td></tr>";
}

$stmt->close();
$mysqli->close();
?>