<?php 
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/basic.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
getHeader();
?>
<main class="page-authorization">
  <h1 class="h h--1">Авторизация</h1>
  <form class="custom-form" id="signin_form" method="POST">
    <input type="email" class="custom-form__input" id="log" name="login" required placeholder="Введите логин/e-mail">
    <input type="password" class="custom-form__input" id="pass" name="password" autocomplete="off" required placeholder="Введите пароль">
    <button class="button" type="submit" id="sub" name="signin">Войти в личный кабинет</button>
    <div class="msg" id="signin_info"></div>
  </form>
</main>
<footer class="page-footer">
  <div class="container">
    <a class="page-footer__logo" href="/">
      <img src="/img/logo--footer.svg" alt="Fashion">
    </a>
    <?php if ($_SERVER['REQUEST_URI'] !== '/routs/auth/index.php') {?>
    <nav class="page-footer__menu">
      <ul class="main-menu main-menu--footer">
         <?php $menu = ($_SERVER['REQUEST_URI'] == '/'|| $_SERVER['REQUEST_URI'] == '/routs/delivery/index.php') ? getMenu() : get_admin_menu ();
      foreach ($menu as $value) { ?>
      <li>
        <a class="main-menu__item" href="<?=$value['puth']?>"><?=$value['name']?></a>
      </li>
     <?php } ?>
      </ul>
    </nav>
  <?php } ?>
    <address class="page-footer__copyright">
      © Все права защищены
    </address>
  </div>
</footer>
<script id="goods-template" type="text/template">
        <% _.each(goods, function(item) { %>
           <article class="shop__item product" data-id="<%=item.id%>" data-price="<%=item.price%>" tabindex="0">
            <div class="product__image" data-id="<%=item.id%>" data-price="<%=item.price%>">
              <img src="<%=item.image%>" alt="product-name">
            </div>
            <p class="product__name"><%=item.name%></p>
            <span class="product__price"><%='Цена: ' + item.price + ' руб.'%></span>
           </article>
        <% }); %>
    </script>
     <script id="pagination-template" type="text/template">
        <% if (page !== 1) { %>
        <li data-page="1"><a class="paginator__item" href>&laquo;</a></li>
        <li data-page="<%= page-1 %>"><a class="paginator__item" href>&lt;</a></li>
        <% } %>

        <% for (var i = 1; i <= countPages; i++) { %>
        <li data-page="<%= i %>" <%= (i === page) ? 'class="active"' : '' %>><a class="paginator__item" href><%= i %></a></li>
        <% } %>

        <% if (page !== countPages) { %>
        <li data-page="<%= page + 1 %>"><a class="paginator__item" href>&gt;</a></li>
        <li data-page="<%= countPages %>"><a class="paginator__item" href>&raquo;</a></li>
        <% } %>
    </script>
    <script type="text/javascript" src="/js/lib/underscore.js"></script>
    <script src="/js/catalog.js"></script>
    <script src="/js/signin.js"></script>
    <script src="/js/main.js" type="text/javascript"></script>
</body>
</html>