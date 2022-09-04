var $scroller = $('#scroller');
var current = 0;
var imagenes = new Array();
 
$("#start").click(function () {
    var element = document.getElementById("mainDiv");
    element.scrollIntoView();

    console.log("click");
    
});

$('.buttonaction').on('click', function() {
    $("#micvcarousel").carousel("next");
});
$('.buttonTertiary').on('click', function() {
    $("#micvcarousel").carousel("next");
});  

