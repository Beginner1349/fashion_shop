<?php 
require_once ($_SERVER['DOCUMENT_ROOT'] . '/config.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/functions/validation.php');

if (isset($_POST['product-name']) && !empty($_POST['product-name'])) {

	//Создаём переменную для вывода ошибок
	$messages = [];
	//Достаем данные и валидируем их
	$productName = (filterString( $_POST['product-name']) !== FALSE) ? $productName = filterString( $_POST['product-name']) : $messages = "Ошибка ввода!";
	$productPrice = intval($_POST['price']);
	$statusGroups = $_POST['status'];
	$productCategories = $_POST['categories'];
	$count = count($_FILES['image']['name']);

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
		

		// Проверяем загруженные файлы на соответствие количеству, формату и размеру
		if ($count > 1) {
				$messages = "ERROR.Слишком много файлов!";
			} else {
				if (!in_array($fileActualExt, $exp)) {
					$messages = "Неверный формат!";
					echo json_encode($messages, JSON_UNESCAPED_UNICODE);
					exit();
					
				} elseif ($fileSize > 3000000) {
					$messages = "ERROR. Файл слишком большой!";
					exit();
				} else {
					if (is_uploaded_file($_FILES['image']['tmp_name'][$i])) {
							$newFileName = uniqid('', true) . "." . $fileActualExt;
			 				$filePuth = '../img/products/' . $newFileName;
						if (move_uploaded_file($fileTmp, $filePuth)) {
							$messages = "Файл загружен!";
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
	
	//Отправляем запрос на добавление данных в БД
	$query = $pdo->prepare("INSERT INTO `products` (id, name, price, image, status) VALUES (:id, :name, :price,:image, :status)");
	
	$query->bindParam(':id', $id);
	$query->bindParam(':name', $productName, PDO::PARAM_STR);
	$query->bindParam(':price', $productPrice, PDO::PARAM_INT);
	$query->bindParam(':image', $filePuth, PDO::PARAM_STR);
	$query->bindParam(':status', $statusGroups, PDO::PARAM_STR);
	//$messages = var_dump($productPrice);
	$id = NULL;
	$query->execute();

	//Вывод ошибок SQL запросов
	$info = $query->errorInfo();
    
	
	// Получаем id вставленной записи
	$product_id = $pdo->lastInsertId();
	$messages = (!empty($product_id)) ? ['code' => 'success'] : [];
	echo json_encode($messages, JSON_UNESCAPED_UNICODE);
	
	//Получаем id группы товаров
	$groupsQuery = $pdo->prepare("SELECT `id` FROM `categories` WHERE `name`=:name");
	$groupsQuery->bindParam(':name', $productCategories);
	$groupsQuery->execute();
	$idArr = $groupsQuery->fetch(PDO::FETCH_ASSOC);
	$idGroups = intval($idArr['id']);
	//Вывод ошибок SQL запросов
	$info = $groupsQuery->errorInfo();
   
	//Отправлям запрос на добавление id товара и id группы
	$sqlQuery = $pdo->prepare("INSERT INTO `products_has_categories`(products_id, categories_id) VALUES (:pr_id, :cat_id)");
	$sqlQuery->bindParam(':cat_id', $idGroups);
	$sqlQuery->bindParam(':pr_id', $product_id);
	$sqlQuery->execute();
	//Вывод ошибок SQL запросов
	$info = $sqlQuery->errorInfo();

} else {
    	$messages = "Заполните поля пожалуйста!";
		echo json_encode($messages, JSON_UNESCAPED_UNICODE);
		exit();
       }
