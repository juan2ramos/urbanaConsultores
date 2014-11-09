/*
 *  Written By: Mike Newell
 *  Mike@iwearshorts.com
 *  http://iwearshorts.com/
 *  
 *  No license - Feel free to copy.
 *  
 */
$(window).load(function () {

    var layers = $('.mover').get();
    var layersCount = layers.length;
    var master = [];
    for(var i = 0; i < layersCount; i++) {
       master[i] = {
           speed : 1 - (i / layersCount),
           pos : $(layers[i]).position()
       }

    }

    var top;
    var left;
    var winWidth;
    var docWidth;
    var winHeight;
    var percentLeft;
    var percentTop;
    var absPercent;

    winHeight = $(window).height();
    winWidth = $(window).width();
    docWidth = $(document).width();
    absPercent = (winWidth / docWidth);

   $(window).scroll(function () {

       top = $(this).scrollTop();
       left = $(this).scrollLeft();

       for(var t = 0; t < layersCount; t++) {
           percentLeft = (left / winWidth) * master[t].speed;
           percentTop = (top / winHeight) * master[t].speed;

           var newPositionLeft = master[t].pos.left - (percentLeft * master[t].pos.left);
           var newPositionTop = master[t].pos.top - (percentTop * master[t].pos.top);


           $(layers[t]).css({"left":newPositionLeft, "top":newPositionTop});

       }
   }); 
});