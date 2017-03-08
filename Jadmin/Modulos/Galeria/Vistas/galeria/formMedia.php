<div class="panel panel-default">
  
  <div class="panel-body">
    <?=$this->data->form?>
  </div>
</div>


<article class="row">
	<div class="col-md-12">
	  
	</div>
</article>

<script>
    
    function sendData(){
        $form = $("#formGestionObjetoMedia");
        data= $form.serializeObject();
        data.btnGestionObjetoMedia=true;
        ajax =  new jd.ajax({
            parametros:data,
            url:"/admin/galeria/set-media/",
            respuesta:'json',
            funcionCarga:function(){
                if(this.respuesta.result){
                    $form.before('<div class="alert alert-success">Se ha registrado la informacion correctamente</div>');
                }else{
                    $form.before('<div class="alert alert-warning">No se ha podido guardar la informaci&oacute;n</div>');
                }   
            }
            
        });
        
   
    }
    $("#addImgPrincipal").on('click',function(){
        var $form = $("#formGestionObjetoMedia");
        var $this = $( this );
        
        // new jd.ajax({
            // url:'/adm/posts/add-portada',
            // respuesta:'json',
            // parametros:{imgPortada:$this.data('id')},
            // funcionCarga:function(){
                // if(this.respuesta.ejecutado)
                    // $form.before('<div class="alert alert-success">La imagen ha sido asignada</div>');
            // }
        // })
    });
    $(function(){
        $("#btnGestionObjetoMedia").jValidador({post:sendData});
       
    });
    
    $.fn.serializeObject = function()
    {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function() {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };
</script>