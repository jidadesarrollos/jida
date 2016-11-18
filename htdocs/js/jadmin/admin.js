function setLinkMenuClass($linkToggle){
 	if($linkToggle.hasClass('fa-arrow-right')){
 		$linkToggle.find('span.fa').removeClass('fa-arrow-right').addClass('fa-arrow-left');
 	}else{
 		$linkToggle.find('span.fa').removeClass('fa-arrow-left').addClass('fa-arrow-right');
 	}
}
function addMenuTooltip(){
	$('.menu li a').each(function(k,item){
 			var $item =$( item );
 			var $icon =$item.find('.fa');

 			if($icon.size()){
 				var $text = $icon.next();
 		
 				$icon.parent().attr({
 					'data-toggle':'tooltip',
 					'data-placement':'right',
 					'title':$.trim($text.html())
 				});
 			}  
 		});
}
function removeMenuTooltip(){
	$('.menu li a').each(function(k,item){
 			var $item =$( item );
 			var $icon =$item.find('.fa');
 			if($icon.size()){
 				var $text = $icon.next();
 				$icon.parent().removeAttr('data-toggle');
 			}  
	});
	$('.tooltip').remove();
}
function toggleMenu(open){
	var $content = $('#content-wrapper');
	
	if(open){
		if(!$content.hasClass('short-menu')){
			$content.addClass('short-menu');
			addMenuTooltip();
		}
	}else{
		$('.li-parent').removeClass('selected').find('ul').removeClass('show');
		if($content.hasClass('short-menu')){
			$content.removeClass('short-menu');
			removeMenuTooltip();
		}else
			$content.addClass('short-menu'); 
	}
		
	
 	if($content.hasClass('short-menu')){
 		addMenuTooltip();
 	}else{
 		$('aside li a').each(function(item,key){
 			var $item = $(item);
 			$item.removeAttr('data-toggle');
 		});
 		
 	}
}
(function($){
	console.log('Jida Administrador');

	var $linkToggle = $('.menu-toggle');
	console.log($linkToggle);
	if($('#content-wrapper').hasClass('short-menu'));
		addMenuTooltip();
	 
	 $('.li-parent').on('click',function(e){
	 		
	 	var $this = $( this );
	 	if($this.find('ul').length>1){
	 		
	 		setLinkMenuClass($linkToggle);
		 	toggleMenu(true);	
	 	}
		 	
	 	
	 });
	 $linkToggle.on('click',function(){
	 	console.log("hola");
		setLinkMenuClass($linkToggle);
		toggleMenu();
		
	 });
	 
	 
	 
})(jQuery);


