<?php
session_start();
if ($_SESSION['auth'] !== true || $_SESSION['user']['role'] !== 'admin') {
    header('Location: /');
    exit();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/basic.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
getHeader();
?>
<main class="page-add">
  <h1 class="h h--1">Добавление товара</h1>
  <div class="msg" id="result_form"></div>
  <form class="custom-form" method="post" enctype="multipart/form-data" id="ajax_form">
    <fieldset class="page-add__group custom-form__group">
      <legend class="page-add__small-title custom-form__title">Данные о товаре</legend>
      <label for="product-name" class="custom-form__input-wrapper page-add__first-wrapper">
        <input type="text" class="custom-form__input" name="product-name" id="product-name" required>
        <p class="custom-form__input-label">
          Название товара
        </p>
      </label>
      <label for="price" class="custom-form__input-wrapper">
        <input type="text" class="custom-form__input" name="price" id="price" required>
        <p class="custom-form__input-label">
          Цена товара
        </p>
      </label>
    </fieldset>
    <fieldset class="page-add__group custom-form__group">
      <legend class="page-add__small-title custom-form__title">Фотография товара</legend>
      <ul class="add-list">
        <li class="add-list__item add-list__item--add">
          <input type="file" name="image[]" multiple id="image" hidden="">
          <label for="image">Добавить фотографию</label>
        </li>
      </ul>
    </fieldset>
    <fieldset class="page-add__group custom-form__group">
      <legend class="page-add__small-title custom-form__title">Категория товара</legend>
      <div class="page-add__select">
        <select name="categories" id="categories" class="custom-form__select">
         <?php $group_names = get_products_category ();
         foreach ($group_names as $value) {?>
        <option value="<?=$value?>"><?=$value?></option>
      <?php } ?>
        </select>
      </div>
      <input type="radio" name="status" id="new" class="custom-form__checkbox" value="new">
      <label for="new" class="custom-form__checkbox-label">Новинка</label>
      <input type="radio" name="status" id="sale" class="custom-form__checkbox" value="sale">
      <label for="sale" class="custom-form__checkbox-label">Распродажа</label>
    </fieldset>
    <button class="button" type="submit" name="add_products">Добавить товар</button>
  </form>
  <section class="shop-page__popup-end page-add__popup-end" id="popup_end" hidden="">
    <div class="shop-page__wrapper shop-page__wrapper--popup-end">
      <h2 class="h h--1 h--icon shop-page__end-title">Товар успешно добавлен</h2>
    </div>
  </section>
</main>

<?php
getFooter();
?>