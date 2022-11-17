/* Ловим событие submit 
При нажатии кнопки вызывается метод sendAjaxForm */
$(document).ready(function() {
    $('#order_form').on('submit', function (e) {
            e.preventDefault();
			sendAjaxForm();
		}
	);
});
 
function sendAjaxForm() {
    
    var data = $('#order_form').serialize();
    $.ajax({
        url:    "/scripts/order.php", //url страницы
        method: "post", //метод отправки
        data: data,  // Передаем данные из формы
    	success: function(response) {
           console.log(response);
           $('#order_info').text(response);
           $('#order_form').trigger('reset');
    	},
         error: function(response) {
            console.log(response);
            $('#order_info').html('Ошибка. Что-то пошло не так!');
        },
 	});
}