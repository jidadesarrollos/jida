function setLinkMenuClass($linkToggle){
 	if($linkToggle.hasClass('fa-arrow-right')){
 		$linkToggle.find('span.fa').removeClass('fa-arrow-right').addClass('fa-arrow-left');
 	}else{
 		$linkToggle.find('span.fa').removeClass('fa-arrow-left').addClass('fa-arrow-right');
 	}
}
function addMenuTooltip(){
	console.log("ak");
	$('.menu li a').each(function(k,item){
 			var $item =$( item );
 			var $icon =$item.find('.fa');
 			console.log($item);
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
 			console.log($item);
 			if($icon.size()){
 				var $text = $icon.next();
 				$icon.parent().removeAttr('data-toggle');
 			}  
	});
	$('.tooltip').remove();
}
function toggleMenu(open){
	var $content = $('#content-wrapper');
	console.log($content);
	if(open){
		
		if(!$content.hasClass('short-menu')){
			$content.addClass('short-menu');
			addMenuTooltip();
		}
	}else{
		
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
	
	// var lisId = $('#step1');
	// var $pasos=[];
	// var cadenas ={
		// '/admin/clientes':'<h4>Texto Clientes</h4>',
		// '/admin/recursos/proyecto':'<h4>Texto Proyectos</h4>',
		// '/admin/recursos/director':'<h4>Texto Directores</h4>',
		// '/admin/recursos/fotografo':'<h4>Texto Fotografos</h4>',
		// '/admin/recursos/digital':'<h4>Texto Digitales</h4>',
		// '/admin/recursos/novedad':'<h4>Texto Novedades</h4>',
	// };
	// $pasos.push({
		// intro: "<h4>Bienvenido al Administrador de Alfonsa!</h4>"
	// });
// 	
	// cont=0;
	// lisId.find('li').each(function(key,item){
// 		
		// var $this = $(item);
		// var link = $this.find('a');
		// var texto = cadenas[link.attr('href')];
		// $this.attr('id','li'+cont);
// 		
		// if(typeof texto != 'undefined'){
			// $pasos.push({
				// intro: texto,
		        // element: item
			// });	
		// }
		// ++cont;
	// });
// 	
	// $pasos.push({
		// intro: "<h4>Fin del Demo Alfonsa!</h4>"
	// });
	// var intro = introJs();
  	// intro.setOptions({
	    // steps: $pasos
  	// });
//  
  	// intro.setOption('showProgress', true).start();
	
	var $linkToggle = $('.menu-toggle');
	if($('#content-wrapper').hasClass('short-menu'));
		addMenuTooltip();
	 
	 $("body").tooltip({
	 	selector:'[data-toggle="tooltip"]'
	 });
	 
	 $('.li-parent').on('click',function(){
	 	
	 	var $this = $( this );
	 	
	 	if($this.find('ul').size()>0)
	 	{
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


