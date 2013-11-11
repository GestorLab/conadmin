	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='menu_ordem_servico_qtd_aberto_mes.php'>
			<input type='hidden' name='Local' value='OrdemServicoQTDAbertoMes' />
			<table>
				<tr>
					<td><?=dicionario(196)?></td>
					<td><?=dicionario(30)?></td>
					<td />
					<td />
				</tr>
				<tr>
					<td>
						<input type='text' name='filtro_mes_referencia' value='<?=$localMesReferencia?>' style='width:100px' maxlength='7' onkeypress="mascara(this,event,'mes')" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown="listar(event);" />
					</td>	
					<td>
						<input type='text' name='filtro_id_servico' value='<?=$localIdServico?>' style='width:70px' maxlength='11' onChange='filtro_buscar_servico(this.value,document.filtro.Local.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')"/><input type='text' class='agrupador' name='filtro_descricao_id_servico' value='' style='width:200px' readOnly="readOnly" />
					</td>
					<td>
						<input type='submit' value='<?=dicionario(166)?>' class='botao' />
					</td>
					<td style='font-size:9px; font-weight:normal'>
						<a style='color:#FFF'  href='listar_ordem_servico_opcoes.php'><?=dicionario(120)?><br /><?=dicionario(162)?></a>
					</td>
				</tr>
			</table>
		</form>
	</div>
	<div id='menu_ar'>&nbsp;</div>
	<div class='graficos' style='text-align:center'>
		<br /><br /><img src='graficos/ordem_servico_qtd_aberto_mes.php?MesReferencia=<?=$localMesReferencia?>&IdServico=<?=$localIdServico?>'/>	
	</div>
	<script type='text/javascript'>
		filtro_buscar_servico(document.filtro.filtro_id_servico.value);
		enterAsTab(document.forms.filtro);
	</script>