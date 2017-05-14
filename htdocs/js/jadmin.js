$( document ).ready(function(){

     if($('[data-selectall]').length>0){
    	$seleccionador = $('[data-selectall]');
    	$seleccionador.on('click',function(){
	    	console.log("click");
	    	var $this = $( this );
	    	var seleccion = $this.data('selectall');
			$( seleccion ).each(function(){
				this.checked=$this.prop('checked');
			});

	    });
	    $($seleccionador.data('selectall')).on('click',function(){

	    	if($($seleccionador.data('selectall')+':checked').lenght== $($seleccionador.data('selectall')).length )
	    	$seleccionador.prop('checked',true);
	    	else
	    	$seleccionador.prop('checked',false);
	    });
    }

});
