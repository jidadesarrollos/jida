<?php include_once 'header.tpl.php'?>
    <tr>
    	<td>
    		<h2>
    			{{:nombre}}
    			<small style="font-weight: lighter">
    				se ha comunicado con nosotros y ha dejado el siguiente mensaje:
				</small>
			</h2>

            <div style="padding:1px 0px;">
                <p>{{:mensaje}}</p>
            </div>
			
			<h3>{{:correo}}</h3>
    	</td>
    </tr>

    <tr>
        <td class="texto texto-nota" style="padding:15px;font-size:11px;color:#494949;background:#f5f5f5;border-top:1px solid #dcdcdc;border-bottom:1px solid #dcdcdc;">
           <h3>Este mensaje fue enviado a trav&eacute;s del formulario de contacto de la web.</h3>
        </td>
    </tr>
    
<?php include_once 'footer.tpl.php'?>