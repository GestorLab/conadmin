	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='menu_local_recebimento.php'>
			<table>
				<tr>
					<td>Mês/Ano</td>
					<td />
					<td />
				</tr>
				<tr>
					<td><input type='text' name='MesReferencia' value='<?=$localMesReferencia?>' style='width:100px' maxlength='8' onkeypress="mascara(this,event,'mes')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" /></td>
					<td><input type='submit' value='Buscar' class='botao' /></td>
					<td style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_conta_receber_opcoes.php'>Outros<BR />Relatórios</a></td>
				</tr>
			</table>
		</form>
	</div>
	<div id='menu_ar'>&nbsp;</div>
	<div class='graficos' style='text-align:center'>
		<BR><BR><img src='graficos/conta_receber_local_recebimento.php?mesReferencia=<?=$localMesReferencia?>' />
	</div>
	<script>
		enterAsTab(document.forms.filtro);
	</script>
