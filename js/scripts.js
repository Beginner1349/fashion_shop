'use strict';

const toggleHidden = (...fields) => {

  fields.forEach((field) => {

    if (field.hidden === true) {

      field.hidden = false;

    } else {

      field.hidden = true;

    }
  });
};

const labelHidden = (form) => {

  form.addEventListener('focusout', (evt) => {

    const field = evt.target;
    const label = field.nextElementSibling;

    if (field.tagName === 'INPUT' && field.value && label) {

      label.hidden = true;

    } else if (label) {

      label.hidden = false;

    }
  });
};

const toggleDelivery = (elem) => {

  const delivery = elem.querySelector('.js-radio');
  const deliveryYes = elem.querySelector('.shop-page__delivery--yes');
  const deliveryNo = elem.querySelector('.shop-page__delivery--no');
  const fields = deliveryYes.querySelectorAll('.custom-form__input');

  delivery.addEventListener('change', (evt) => {

    if (evt.target.id === 'dev-no') {

      fields.forEach(inp => {
        if (inp.required === true) {
          inp.required = false;
        }
      });


      toggleHidden(deliveryYes, deliveryNo);

      deliveryNo.classList.add('fade');
      setTimeout(() => {
        deliveryNo.classList.remove('fade');
      }, 1000);

    } else {

      fields.forEach(inp => {
        if (inp.required === false) {
          inp.required = true;
        }
      });

      toggleHidden(deliveryYes, deliveryNo);

      deliveryYes.classList.add('fade');
      setTimeout(() => {
        deliveryYes.classList.remove('fade');
      }, 1000);
    }
  });
};

const filterWrapper = document.querySelector('.filter__list');
if (filterWrapper) {

  filterWrapper.addEventListener('click', evt => {

    const filterList = filterWrapper.querySelectorAll('.filter__list-item');

    filterList.forEach(filter => {

      if (filter.classList.contains('active')) {

        filter.classList.remove('active');

      }

    });

    const filter = evt.target;

    filter.classList.add('active');

  });

}

const shopList = document.querySelector('.shop__list');
if (shopList) {

  shopList.addEventListener('click', (evt) => {

    const prod = evt.path || (evt.composedPath && evt.composedPath());;
   
   //Ловим событие клика на карточке товара и передаем id товара в форму заказа
    const id_prod = $(event.target).data('id');
    $('#id_products').val(id_prod);
//Ловим событие клика на карточке товара и передаем price товара в форму заказа
    var price = $(event.target).data('price');
    $('#prod_price').val(price);

    if (prod.some(pathItem => pathItem.classList && pathItem.classList.contains('shop__item'))) {

      const shopOrder = document.querySelector('.shop-page__order');

      toggleHidden(document.querySelector('.intro'), document.querySelector('.shop'), shopOrder);

      window.scroll(0, 0);

      shopOrder.classList.add('fade');
      setTimeout(() => shopOrder.classList.remove('fade'), 1000);

      const form = shopOrder.querySelector('.custom-form');
      labelHidden(form);

      toggleDelivery(shopOrder);

      const buttonOrder = shopOrder.querySelector('#order_form');
      const popupEnd = document.querySelector('.shop-page__popup-end');

      buttonOrder.addEventListener('submit', (evt) => {

        form.noValidate = true;

        const inputs = Array.from(shopOrder.querySelectorAll('[required]'));

        inputs.forEach(inp => {

          if (!!inp.value) {

            if (inp.classList.contains('custom-form__input--error')) {
              inp.classList.remove('custom-form__input--error');
            }

          } else {

            inp.classList.add('custom-form__input--error');

          }
        });

        if (inputs.every(inp => !!inp.value)) {

          evt.preventDefault();
          //Сериализуем объект и отправляем на сервер 
          var data = $('#order_form').serialize();
          
                    $.ajax({
                        url:    "/scripts/order.php", //url страницы сервера
                        method: "post", //метод отправки
                        data: data,  // Передаем данные из формы
                      success: function(response) {
                           console.log(response);
                           $('#order_info').text(response.code);
                           $(window).scrollTop(0);
                           $('#order_form').trigger('reset');
                           
                           if (response.code == "success") {
                            $('#order_info').text('Успех! Заказ добавлен!');
                          
                           }
                           if (response.code == "error") {
                            $('#order_info').html('Error!' + response);
                           }
                      },
                         error: function(response) {
                            console.log(response);
                            $('#order_info').html('Ошибка. Что-то пошло не так!');
                        },
                    });

          // toggleHidden(shopOrder, popupEnd);

          // popupEnd.classList.add('fade');
          // setTimeout(() => popupEnd.classList.remove('fade'), 1000);

          // window.scroll(0, 0);

          const buttonEnd = popupEnd.querySelector('.button');

          buttonEnd.addEventListener('click', () => {


            popupEnd.classList.add('fade-reverse');

            setTimeout(() => {

              popupEnd.classList.remove('fade-reverse');

              toggleHidden(popupEnd, document.querySelector('.intro'), document.querySelector('.shop'));

            }, 1000);

          });

        } else {
          window.scroll(0, 0);
          evt.preventDefault();
        }
      });
    }
  });
}

const pageOrderList = document.querySelector('.page-order__list');
if (pageOrderList) {

  pageOrderList.addEventListener('click', evt => {


    if (evt.target.classList && evt.target.classList.contains('order-item__toggle')) {
      var path = evt.path || (evt.composedPath && evt.composedPath());
      Array.from(path).forEach(element => {

        if (element.classList && element.classList.contains('page-order__item')) {

          element.classList.toggle('order-item--active');

        }

      });

      evt.target.classList.toggle('order-item__toggle--active');

    }

    if (evt.target.classList && evt.target.classList.contains('order-item__btn')) {

      const status = evt.target.previousElementSibling;

      if (status.classList && status.classList.contains('order-item__info--no')) {
        status.textContent = 'Выполнено';
      } else {
        status.textContent = 'Не выполнено';
      }

      status.classList.toggle('order-item__info--no');
      status.classList.toggle('order-item__info--yes');

    }

  });

}

const checkList = (list, btn) => {

  if (list.children.length === 1) {

    btn.hidden = false;

  } else {
    btn.hidden = true;
  }

};
const addList = document.querySelector('.add-list');
if (addList) {

  const form = document.querySelector('.custom-form');
  labelHidden(form);

  const addButton = addList.querySelector('.add-list__item--add');
  const addInput = addList.querySelector('#image');


  checkList(addList, addButton);

  addInput.addEventListener('change', evt => {
 
    const template = document.createElement('LI');
    const img = document.createElement('IMG');

    template.className = 'add-list__item add-list__item--active';
    template.addEventListener('click', evt => {
      addList.removeChild(evt.target);
      addInput.value = '';
      checkList(addList, addButton);
    });

    const file = evt.target.files[0];
    const reader = new FileReader();

    reader.onload = (evt) => {
      img.src = evt.target.result;
      template.appendChild(img);
      addList.appendChild(template);
      checkList(addList, addButton);
    };

    reader.readAsDataURL(file);

  });

  const popupEnd = document.querySelector('.page-add__popup-end');
  const ajaxForm = document.querySelector('#ajax_form');

  ajaxForm.addEventListener('submit', (evt) => {

    evt.preventDefault();
 
    var formData = new FormData(document.getElementById("ajax_form"));

    $.ajax({
        url: "/scripts/downloadProducts.php", //url страницы
        method: "post", //метод отправки
        contentType: false, // убираем форматирование данных по умолчанию
        processData: false, // убираем преобразование строк по умолчанию
        data: formData,  // Передаем данные из формы
        cache: false,
      success: function(response) {
           console.log(response);
           $('#result_form').text(response);
           $('#ajax_form').trigger('reset');
           $(window).scrollTop(0);
           
           // if (response.code === 'success') {
           //   _catalogSuccess(responce);
           // }
      },
         error: function(response) {
            console.log(response);
            $('#result_form').html('Ошибка. Что-то пошло не так!');
        },
  });
    //return false;



    form.hidden = false;
    popupEnd.hidden = true;

  })

}

const productsList = document.querySelector('.page-products__list');
if (productsList) {

  productsList.addEventListener('click', evt => {

    const target = evt.target;

    if (target.classList && target.classList.contains('product-item__delete')) {

      productsList.removeChild(target.parentElement);

    }

  });

}

// jquery range maxmin
if (document.querySelector('.shop-page')) {

  $('.range__line').slider({
    min: 350,
    max: 120000,
    values: [350, 120000],
    range: true,
    step: 100,
    stop: function(event, ui) {
      $('.min-price').text($('.range__line').slider('values', 0) + ' руб.');
      $('.max-price').text($('.range__line').slider('values', 1) + ' руб.');
    },
    slide: function(event, ui) {
      $('.min-price').text($('.range__line').slider('values', 0) + ' руб.');
      $('.max-price').text($('.range__line').slider('values', 1) + ' руб.');
    }
  });
}