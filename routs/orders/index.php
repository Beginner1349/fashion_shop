<?php
session_start();
if ($_SESSION['auth'] !== true) {
    header('Location: /');
    exit();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/basic.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
getHeader();
 $query = "select * from orders";
 $data = $pdo->prepare($query);
 $data->execute();
 $rows = $data->fetchAll(PDO::FETCH_ASSOC);
?>
<main class="page-order">
  <h1 class="h h--1">Список заказов</h1>
  <ul class="page-order__list">
    <?php foreach ($rows as $value) { ?>
    <li class="order-item page-order__item">
      <div class="order-item__wrapper">
        <div class="order-item__group order-item__group--id">
          <span class="order-item__title">Номер заказа</span>
          <span class="order-item__info order-item__info--id"><?=$value['id']?></span>
        </div>
        <div class="order-item__group">
          <span class="order-item__title">Сумма заказа</span>
          <?=$value['summ']?>
        </div>
        <button class="order-item__toggle"></button>
      </div>
      <div class="order-item__wrapper">
        <div class="order-item__group order-item__group--margin">
          <span class="order-item__title">Заказчик</span>
          <span class="order-item__info"><?=$value['surname'] . ' ' .  $value['name'] . ' ' . $value['thirdName']?></span>
        </div>
        <div class="order-item__group">
          <span class="order-item__title">Номер телефона</span>
          <span class="order-item__info"><?=$value['phone']?></span>
        </div>
        <div class="order-item__group">
          <span class="order-item__title">Способ доставки</span>
          <span class="order-item__info"><?=$value['delivery']?></span>
        </div>
        <div class="order-item__group">
          <span class="order-item__title">Способ оплаты</span>
          <span class="order-item__info"><?=$value['pay']?></span>
        </div>
        <div class="order-item__group order-item__group--status">
          <span class="order-item__title">Статус заказа</span>
          <span class="order-item__info order-item__info--no"><?=$value['status']?></span>
          <button class="order-item__btn">Изменить</button>
        </div>
      </div>
      <?php if ($value['delivery'] == 'Курьер') { ?>
        <div class="order-item__wrapper">
        <div class="order-item__group">
          <span class="order-item__title">Адрес доставки</span>
          <span class="order-item__info"><?='г.' . $value['city'] . '  ' . 'ул.' . $value['street'] . '  ' . 'д.' . $value['home'] . '  ' . 'кв.' . $value['aprt']?></span>
        </div>
      </div>
      <?php } ?>
      <div class="order-item__wrapper">
        <div class="order-item__group">
          <span class="order-item__title">Комментарий к заказу</span>
          <span class="order-item__info"><?=$value['comment']?></span>
        </div>
      </div>
    </li>
  <?php } ?>
  </ul>
</main>

<?php
getFooter();
?>