<!--{fin}-->
	<div class="col_12">
	<h4>Listados personalizados</h4>
	<form id="listas" name="listados" method="post" action="./index.php?ac=af_listados" target='_self'>

		<div class="col_3">
		<label for="select1">Tipo de filtrado</label>
		<select id="filtro" name='filtro'>
			<option value="" selected="selected">--Seleccione Filtro--</option>
			<option value="0" >Afiliados por Cumplea&ntilde;os</option>
			<option value="7" >Afiliados 1ra Categoria</option>
			<option value="9" >Afiliados 2da Categoria</option>
			<option value="5" >Afiliados Farmaceutico</option>
			<option value="2" >Afiliados Administrativos</option>
			<option value="4" >Afiliados Cajeros</option>
			<option value="8" >Afiliados Repartidor</option>
			<option value="6" >Afiliados Perfumeria</option>
			<option value="11" >Afiliados Servicios</option>
			<option value="3" >Afiliados Cadetes</option>
			<option value="10" >Afiliados Traspaso</option>
			<option value="1" >Afiliados Adherentes</option>
			<option value="20" >Familiares</option>
			<option value="21" >Afiliados(o no) al sind.</option>
			<option value="22" >Afiliados(o no) a OSocial</option> 
			
		</select>

		</div>

		<div class="col_3 bloques" id="pormes">
			<label for="select1">Mes de filtrado</label>
			<select id="mes" name='mes'>

				<!-- BEGIN meslista -->
					<option value="{mesval}" {ss}>{mesnombre}</option>
				<!-- END meslista -->		
			</select>
		</div>

		<div class="col_3 bloques" id="portipo">
			<label for="select1">Tipo de afiliado</label>
			<select id="tipo" name='tipo'>
					<option value="1">Solo los activos</option>
					<option value="0">Todos</option>
			</select>
		</div>
		<div class="col_3 bloques" id="porafiliacion">
			<fieldset class="tooltip-bottom" title="Filtrar por el tipo de afiliacion para listar segun su condicion sindical.">
				<legend>Estado Sindical</legend>
					<input type="radio" name="afiliacion" id="radio1" value='1' />	<label for="radio1" class="inline">Afiliados</label><br/>
					<input type="radio" name="afiliacion" id="radio2" value='2' />	<label for="radio2" class="inline">NO Afiliados</label><br/>
					<input type="radio" name="afiliacion" id="radio3" value='0' checked='checked' />	<label for="radio3" class="inline">Ambos</label>
					
			</fieldset>
		</div>

		<div class="col_3 bloques" id="porafiliacionos">
			<fieldset class="tooltip-bottom" title="Filtrar por el tipo de afiliacion para listar segun su condicion antes la Obra Social.">
				<legend>Estado Obra Social</legend>
					<input type="radio" name="afiliacionos" id="radio1" value='1' />	<label for="radio1" class="inline">Afiliados</label><br/>
					<input type="radio" name="afiliacionos" id="radio2" value='2' />	<label for="radio2" class="inline">NO Afiliados</label><br/>
					<input type="radio" name="afiliacionos" id="radio3" value='0' checked='checked' />	<label for="radio3" class="inline">Ambos</label>
					
			</fieldset>
		</div>

		<div class="col_3 bloques" id="porsexo">
			<fieldset class="tooltip-bottom" title="Filtrar agrupando por sexo.">
				<legend>Filtro por Sexo</legend>
					<input type="radio" name="sexo" id="sexo1" value='1' />	<label for="sexo1" class="inline">Masculino</label><br/>
					<input type="radio" name="sexo" id="sexo2" value='2' />	<label for="sexo2" class="inline">Femenino</label><br/>
					<input type="radio" name="sexo" id="sexo3" value='0' checked='checked' />	<label for="radio3" class="inline">Sin filtrar</label>
					
			</fieldset>
		</div>

		<div class="col_3 bloques" id="poredad">
			<fieldset class="tooltip-bottom" title="Filtrar agrupando familiares por rango de edad.">
				<legend>Filtro Edad</legend>
					<input type="text" name="edad1" id="edad1" value='0' />	<label for="edad1" class="inline">Edad Inicio</label><br/>
					<input type="text" name="edad2" id="edad2" value='100' />	<label for="edad2" class="inline">Edad Final</label><br/>					
			</fieldset>
		</div>		
		
		<div class="col_3 bloques" id="votar">
			<fieldset class="tooltip-bottom" title="Tildar, si desea generar listado de afiliados en condicion de votar.">
				<legend>Elecciones</legend>
				<input type="checkbox" id="vota" name="vota"/> <label for="vota" class="inline">ver afiliados con antiguedad de:</label>
				<input type="text" name="antiguedad" id="antiguedad" value='6' />	<label for="antiguedad" class="inline"> (en) meses</label><br/>
			</fieldset>
		</div>

		<div class="col_3 bloques" id="generapdf">
			<fieldset class="tooltip-bottom" title="Tildar, si desea generar un listado en formato PDF.">
				<legend>Generar</legend>
				<input type="checkbox" id="pdf" name="pdf"/> <label for="check1" class="inline">Volcar a PDF</label>
			</fieldset>
		</div>


		<div class="col_2 right">
			<br/>
			<button type="submit" class="small" >Generar</button>
		</div>
	</form>

	</div>
	<div class="col_12" id="listado">
	<h5>Listados personalizados {titulo}</h5>
	<button id='imprimir' class="small"><span class="icon">P</span>Imprimir</button>
	<!-- Table combined Styles -->
	<table class="striped tight sortable" cellspacing="0" cellpadding="0">
	<thead>
		<tr>
			{encabezado-tabla}
		</tr>
	</thead>
	<tbody>
	<!-- BEGIN listado -->
	<tr>
	<td>
		<a href='#' class='veraf' id='{nro_documento}'>{nro_documento}</a>
	</td>	
	<td class="tooltip-bottom" title="Tildar, si desea generar un listado en formato PDF.">
		{nombre}
	</td>
	<td>
		{razon_social}
	</td>
	<td>
		{localidad}
	</td>
	<td>
		{telefono}
	</td>
	<td>
		{telfarmacia}
	</td>
	</tr>
	<!-- END listado -->
	<!-- BEGIN listado_fam -->
	<tr>
	<td>
		{col1}
	<td class="tooltip-bottom" title="Ver Mas Datos: si desea acceder a la ficha del afiliado vinculado.">
		{col2}
	</td>
	<td>
		{col3}
	</td>
	<td>
		{col4}
	</td>
	<td>
		{col5}
	</td>
	<td>
		{col6}
	</td>
	<td>
		{col7}
	</td>

	</tr>
	<!-- END listado_fam -->
	</table>
	<h6>Total de {totallista}</h6>
	</div>
	<div class="col_3">
	<!--
	<h6>Ultimos Cambios</h6>
	<ul class="checks">
	<li>tation ullamcorper suscipit lobortis</li>
	<li>Nam liber tempor cum soluta nobis</li>
	<li>imperdiet doming id quod mazim</li>
	<li>suscipit lobortis nisl ut aliquip ex</li>
	</ul>
	-->
	<h6>Nuestro Soporte</h6>
	
	<span class="icon social x-large darkgray" data-icon="G"></span>
	<span class="icon social x-large" style="color:orange;" data-icon="5"></span>
	<span class="icon social x-large green" data-icon="3"></span>	
	<span class="icon social x-large blue" data-icon="2"></span>
	<span class="icon social x-large gray" data-icon="S"></span>
	<span class="icon social x-large blue" data-icon="E"></span>
	
	
	<h6>Acceda a nuestro RSS</h6>
	<a class="button orange small" href="#"><span class="icon social" data-icon="r"></span> RSS</a>
	</div>
		<form id='verafiliado' action='./index.php?ac=afiliados' target='_blank' method='post' >
			<input type='hidden' id='campo' name='campo' value='nro_documento' /><input id='criteriodni' type='hidden' name='criterio' value='' />
		</form>
	<hr />
<script>
$(document).ready(function() {

 $(".veraf").click(function(){
 	
 	$("#criteriodni").val(this.id);
    $("#verafiliado").submit();


 });

 $(".veraf_sindni").click(function(){
 	$("#campo").val('id_afiliado');
 	$("#criteriodni").val(this.id);
    $("#verafiliado").submit();


 });
 $("#imprimir").click(function(){
 	window.print();
 	return false;
 });


	$('.bloques').hide();
	{listado}

	$('#pdf').change(function(){
		if ($('#pdf').is(':checked'))  {
		  	$('#listas').attr('target', '_blank');
		  } else {
		  	$('#listas').attr('target', '_self');
		  }
	}) 

    $("#filtro").change(function() {
        var n= ($(this).val());
        switch(n)
		{
		case '0':
		  $('.bloques').hide();
		  $('#pormes').show();
		  $('#listas').attr('target', '_blank');
		  break;
		case '1':
		  $('.bloques').hide();
		  $('#portipo').show();
		  $('#porafiliacion').show();
		  $('#generapdf').show();
		  $('#listas').attr('target', '_self');
		  break;
		case '20':
		  $('.bloques').hide();
		  $('#portipo').show();
		  $('#porsexo').show();
		  $('#poredad').show();
		  $('#porafiliacion').show();
		  $('#generapdf').show();
		  $('#listas').attr('target', '_self');
		  break;
		case '21':
		  $('.bloques').hide();
		  $('#portipo').show();
		  $('#porsexo').show();
		  $('#poredad').show();
		  $('#porafiliacion').show();
		  //$('#generapdf').show();
		  //$('#votar').show();
		  $('#listas').attr('target', '_self');
		  break;		
		case '22':
		  $('.bloques').hide();
		  $('#portipo').show();
		  $('#porsexo').show();
		  $('#poredad').show();
		  $('#porafiliacionos').show();
		  //$('#generapdf').show();
		  $('#listas').attr('target', '_self');
		  break;		    
		default:
		  $('.bloques').hide();
		  $('#portipo').show();
		  $('#porafiliacion').show();
		  $('#generapdf').show();
		  $('#listas').attr('target', '_self');
		}
    });
});
$('#portipo').show();
</script>