<?php
session_start();
if ($_SESSION['auth'] !== true || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/basic.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

 $query = "select p.id, p.name, p.price, p.status, cat.name as 'cat' from products as p
inner join products_has_categories as pc on p.id=pc.products_id
inner join categories as cat on cat.id=pc.categories_id;";
 $data = $pdo->prepare($query);
 $data->execute();
 $rows = $data->fetchAll(PDO::FETCH_ASSOC);

getHeader();
?>
<main class="page-products">
  <h1 class="h h--1">Товары</h1>
  <a class="page-products__button button" href="/routs/add/index.php">Добавить товар</a>
  <div class="msg" id="del_msg"></div>
  <div class="page-products__header">
    <span class="page-products__header-field">Название товара</span>
    <span class="page-products__header-field">ID</span>
    <span class="page-products__header-field">Цена</span>
    <span class="page-products__header-field">Категория</span>
    <span class="page-products__header-field">Новинка</span>
  </div>
  <?php foreach ($rows as $value) { ?>
  <ul class="page-products__list">
    <li class="product-item page-products__item">
      <b class="product-item__name"><?=$value['name']?></b>
      <span class="product-item__field"><?=$value['id']?></span>
      <span class="product-item__field"><?=$value['price'] . " руб."?></span>
      <span class="product-item__field"><?=$value['cat']?></span>
      <span class="product-item__field"><?=($value['status'] == 'new') ? 'Да' : 'Нет'?></span>
      <a href="/routs/red.php?id=<?=$value['id']?>&name_pr=<?=$value['name']?>&pr_price=<?=$value['price']?>" class="product-item__edit" aria-label="Редактировать"></a>
      <button class="product-item__delete" type="submit" name="del" value="<?=$value['id']?>"></button>
    </li>
  </ul>
<?php } ?>
</main>

<?php
getFooter();
?>