/* Ловим событие submit 
При нажатии кнопки вызывается метод sendAjaxForm */
$(document).ready(function() {
    $('#update_form').on('submit', function (e) {
            e.preventDefault();
			sendAjaxForm();
		}
	);
});
 
function sendAjaxForm() {
    var formData = new FormData(document.getElementById("update_form"));
    //var data = $('#update_form').serialize();
    $.ajax({
        url:    "/scripts/red.php", //url страницы
        method: "post", //метод отправки
        contentType: false, // убираем форматирование данных по умолчанию
        processData: false, // убираем преобразование строк по умолчанию
        data: formData,  // Передаем данные из формы
        cache: false,
    	success: function(response) {
           console.log(response);
           $('#update_info').text(response);
           $('#update_form').trigger('reset');
           // if (response.success == true) {
           //   document.location.replace("http://inetshop.ru/routs/products/index.php");
           // }
    	},
         error: function(response) {
            console.log(response);
            $('#update_info').html('Ошибка. Что-то пошло не так!');
        },
 	});
}