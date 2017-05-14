<header class="header">
    <h1>Configuracion de Formularios...</h1>
    <div class="alert alert-info">
        <h3>Ten en cuenta:</h3>
        <ol>
            <li><strong>Si deseas editar el orden de los campos</strong> Haz click en el bot&oacute;n <strong>Editar Orden</strong> que se
                encuentra al final de la lista de campos y luego haciendo click en cada campo colocalo en la posicion que deseas.</li>
            <li><strong>Si deseas editar la conf. del campo</strong> Debes hacer <i>doble click</i> sobre el elemento</li>
        </ol>
    </div>
</header>
<div class="row">
	<div class="col-md-3 col-xs-12 col-sm-3">
	    <div>
	        <button 
               id="btnEditOrden" 
               title="editar orden" 
               class="btn btn-primary" 
               value="1">
                <span class="fa fa-edit fa-lg"/>
                Editar orden
            </button>
	    </div>
    	<div 
    	   id="listCamposFormulario" 
    	   class="list-group list-form-item" 
    	   data-url="/jadmin/formularios/configuracion-campo/<?=$this->idFormulario?>">    
        <?php foreach ($this->campos as $key => $campo): ?>
                        
            <a href="#formCampos" id="campoForm-<?=$campo->id?>" class="list-group-item">
                <?=$campo->name?>
            </a>

        <?php endforeach ?>	    
	    </div>
	</div>
	<div class="col-md-9 col-sm-9">
	    <section id="formCampos">
	        
	    </section>
	</div>
</div>
