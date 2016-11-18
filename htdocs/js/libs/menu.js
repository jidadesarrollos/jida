+function($){
    var contenedor= '[data-liparent]';
    var menu = function(ele){
        $(contenedor).on('click',this.checksubnivel);
    };
    menu.prototype.checksubnivel=function(){
        var ele = $( this );
        console.log('ak?');
        if(ele.children('ul').size()>0){
            if(ele.children('ul').hasClass('show')){
                $("ul.show").removeClass('show');
                ele.removeClass('selected');
            }else{

                $("ul.show").removeClass('show');
                $("li").removeClass('selected');
                ele.addClass('selected');
                ele.children('ul').addClass('show');
            }
        }
    };


 



}(jQuery);