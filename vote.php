<?php
// Настройки подключения к базе данных
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "voting_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка подключения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Получение данных из формы
$name = $_POST['name'];
$wcme_vote = $_POST['wcme_vote'];
$reason = $_POST['reason'];

// Проверка, голосовал ли пользователь ранее
$sql_check = "SELECT * FROM votes WHERE name = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $name);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    // Если пользователь уже голосовал
    echo "<div style='font-size: 20px; color: red; text-align: center;'>Вы уже голосовали ранее!</div>";
} else {
    // Подготовка SQL запроса для вставки
    $sql = "INSERT INTO votes (name, wcme_vote, reason) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $wcme_vote, $reason);

    // Выполнение SQL запроса
    if ($stmt->execute()) {
        echo "<div style='font-size: 24px; color: green; text-align: center;'>Спасибо за ваш голос!</div>";
    } else {
        echo "Ошибка: " . $stmt->error;
    }

    // Закрытие соединения
    $stmt->close();
}

$conn->close();
?>
