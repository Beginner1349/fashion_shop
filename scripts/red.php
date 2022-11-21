<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . '/config.php');

if (isset($_POST['product-name']) && !empty($_POST['product-name'])) {
	$productName = trim( $_POST['product-name']);
	$productPrice = intval($_POST['price']);
	$statusGroups = $_POST['status'];
	$productCategories = $_POST['categories'];
	$count = count($_FILES['image']['name']);
	$id = intval($_POST['id']);
	
	for ($i=0; $i < $count; $i++) {
		// Получаем путь к файлу
		$fileTmp = $_FILES['image']['tmp_name'][$i];
		// Получаем размер файла в байтах
		$fileSize = $_FILES['image']['size'][$i];
		// Отделяем расширение от имени файла
		$fileExt = explode(".", $_FILES['image']['name'][$i]);
		// Получим актуальное расширение
		$fileActualExt = strtolower(end($fileExt));
		// Создаем массив расширений
		$exp = ["jpg", "jpeg", "png"];
		//Создаём массив для вывода ошибок
		$messages = "";

		// Проверяем загруженные файлы на соответствие количеству, формату и размеру
		if ($count > 1) {
				$messages = "ERROR. Больше одного не влезет!";
			} else {
				if (!in_array($fileActualExt, $exp)) {
					$messages = "Неверный формат!";
					echo json_encode($messages, JSON_UNESCAPED_UNICODE);
					exit();
					
				} elseif ($fileSize > 3000000) {
					$messages = "ERROR. Файл слишком большой!";
					
				} else {
					if (is_uploaded_file($_FILES['image']['tmp_name'][$i])) {
							$newFileName = uniqid('', true) . "." . $fileActualExt;
			 				$filePuth = '../img/products/' . $newFileName;
						if (move_uploaded_file($fileTmp, $filePuth)) {
							 $messages = "Файл успешно загружен!";
							 echo json_encode($messages, JSON_UNESCAPED_UNICODE);
						} else {
							$messages = "При загрузке файла произошла ошибка!";
							echo json_encode($messages, JSON_UNESCAPED_UNICODE);
							exit();
						}
					
					} else {
						$messages = "Файл не загружен";
						echo json_encode($messages, JSON_UNESCAPED_UNICODE);
						exit();
					}
				}
			}
    }
	//UPDATE `products` SET `price` = '7250' WHERE `products`.`id` = 6;
	//Отправляем запрос на добавление данных в БД
	$query = $pdo->prepare("UPDATE `products` SET `name` = :name, `price` = :price, `image` = :image, `status` = :status WHERE `products`.`id` = :id");
	
	$query->bindParam(':id', $id);
	$query->bindParam(':name', $productName, PDO::PARAM_STR);
	$query->bindParam(':price', $productPrice, PDO::PARAM_INT);
	$query->bindParam(':image', $filePuth, PDO::PARAM_STR);
	$query->bindParam(':status', $statusGroups, PDO::PARAM_STR);

	$query->execute();

	//Вывод ошибок SQL запросов
	$info = $query->errorInfo();
	
	// Проверяем нет ли ошибок при добавлении записи в БД. В случае ошибки выводим текст ошибки или положительный ответ сервера
	$messages = ($info == ["00000",null,null]) ? ['success' => 'true'] : $info;
	echo json_encode($messages, JSON_UNESCAPED_UNICODE);
	
	//Получаем id группы товаров
	$groupsQuery = $pdo->prepare("SELECT `categories_id` FROM `products_has_categories` WHERE `products_id`=:id");
	$groupsQuery->bindParam(':id', $id);
	$groupsQuery->execute();
	$idArr = $groupsQuery->fetch(PDO::FETCH_ASSOC);
	$idGroups = intval($idArr['id']);
	//Вывод ошибок SQL запросов
	// $info = $groupsQuery->errorInfo();

	//Отправлям запрос на добавление id товара и id группы
	$sqlQuery = $pdo->prepare("UPDATE `products_has_categories` SET `categories_id` = $idGroups WHERE `pr_id`=:pr_id");
	$sqlQuery->bindParam(':pr_id', $id);
	$sqlQuery->execute();
	//Вывод ошибок SQL запросов
	//$info = $sqlQuery->errorInfo();

} else {
    	$messages = "Заполните поля пожалуйста!";
		echo json_encode($messages, JSON_UNESCAPED_UNICODE);
		exit();
       }
