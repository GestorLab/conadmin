<div id='filtroBuscar'>
	<form name='filtro' method='post' action='listar_grupo_device.php'>
		<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
		<table class='filtro'>
			<tr>
				<td><?=dicionario(996)?></td>
				<td><?=dicionario(152)?></td>
				<td />
			</tr>
			<tr>
				<td><input type='text' value='<?=$localDescricaoGrupoDevice?>' name='filtro_nome_device' size='50' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'/></td>
				<td><input type='text' value='<?=$Limit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)"/></td>
				<td><input type='submit' value='<?=dicionario(166)?>' class='botao' /></td>
			</tr>
		</table>
	</form>
</div>
<div id='menu_ar'>
	<?
		echo menu_acesso_rapido(10001);
	?>
</div>
<script>
	enterAsTab(document.forms.filtro);
</script>