//По событию submit запускаем отправку формы
$(document).ready(function() {
    $('#signin_form').on('submit', function (e) {
            e.preventDefault();
			 var data = $('#signin_form').serialize();
    $.ajax({
        url:    "/scripts/signin.php", //url страницы
        method: "post", //метод отправки
        dataType: "json", //тип данных
        data: data,  // Передаем данные из формы
        success: function(response) {
           console.log(response);
           $('#signin_info').text(response.error);
           $('#signin_form').trigger('reset');
           if (response.code == "success" && response.role == "admin") {
            document.location.replace("http://inetshop.ru/routs/products/index.php");
           } else {
            document.location.replace("http://inetshop.ru/routs/orders/index.php");
           }
        },
         error: function(response) {
            console.log(response);
            $('#signin_info').html('Ошибка. Что-то пошло не так!');
        },
    });
		}
	);
});