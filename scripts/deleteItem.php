<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . '/config.php');
if (isset($_POST['id'])) {
	$msg = [];
	$id = intval($_POST['id']);
	$query = $pdo->prepare("DELETE FROM `products` WHERE `id`=:id");
	$query->bindParam(':id', $id);
	$query->execute();
	$info = $query->errorInfo();
	if ($info == ["00000",null,null]) {
		$msg[] = 'Вы успешно удалили товар!';
		echo json_encode($msg, JSON_UNESCAPED_UNICODE);
	} else {
		$msg[] = 'Error' . $info;
		echo json_encode($msg, JSON_UNESCAPED_UNICODE);
	}
}
