$(document).ready(function () {
   $('#sidebar-toggle').on('click',function(){
      $('#sidebar').toggleClass('active');
      if($(window).width() > 450){
          $('.body-main').toggleClass('active');
      }else{
         $('.body-main').css("margin","10px");
      }
   });
});