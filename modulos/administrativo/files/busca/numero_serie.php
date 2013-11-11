<div id='quadroBuscaNumeroSerie' style='width:347px; margin-bottom:0' class='quadroFlutuante'>
	<table class='titMenu' cellspacing='0' cellpading='0'>
		<tr>
			<td class='tit'>Número Série</td>
			<td class='fecha' onClick="validarNumeroSerie();">X</div></td>
		</tr>
	</table>
	<div class='filtro_busca'>
		<table style='margin-left:5px' id='tabelaNumeroSerie'>
			<tr>
				<td class='descCampo'>Número de Série</td>
			</tr>
		</table>
		<table style='width:100%; text-align:right;'>
			<tr>
				<td style='padding-right:4px'>
					<input type='button' value='Ok' class='botao' onClick='validarNumeroSerie()'>
					<input type='button' value='Cancelar' class='botao' onClick='validarNumeroSerie()'>
				</td>
			</tr>
		</table>
	</div>
</div>
<script language='JavaScript' type='text/javascript'>
	// Para que os quadros flutuem
	new Draggable('quadroBuscaNumeroSerie');						
</script>
