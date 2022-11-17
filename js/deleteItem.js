$(document).ready (function() {
  $(".product-item__delete").on("click", function() {
   
   var id = $(this).val();
  
        $.ajax ({
        url: "/scripts/deleteItem.php",
        type: "POST",
        data: {id: id},
        success:function(response){ 
         console.log(response);
         $('#del_msg').text(response);
        }
      }); 
   });
});