<?php
include 'db/connect.php';

$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';
$sex = $_GET['sex'] ?? '';

$sql = "SELECT id, name, sex, email, phone, user, role FROM tbl_user WHERE 1=1";

if (!empty($search)) {
  $search = $mysqli->real_escape_string($search);
  $sql .= " AND (name LIKE '%$search%' OR email LIKE '%$search%' OR user LIKE '%$search%')";
}

if (!empty($status)) {
  $status = $mysqli->real_escape_string($status);
  //  if ($status != "") {    // Assuming the `status` field does not exist in your table.  This is only a sample
  //  $sql .= " AND status = '$status'";  //Add status column in your php code to enable status filtering and fix this
  //}
}

if (!empty($sex)) {
  $sex = $mysqli->real_escape_string($sex);
  $sql .= " AND sex = '$sex'";
}

$result = $mysqli->query($sql);

if ($result === false) {
  echo "Lỗi truy vấn: " . $mysqli->error . "<br>";
  exit;
}

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
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

$mysqli->close();
?>