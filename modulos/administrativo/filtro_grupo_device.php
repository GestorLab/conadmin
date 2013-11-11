<div id='filtroBuscar'>
	<form name='filtro' method='post' action='listar_grupo_device.php'>
		<input type='hidden' name='corRegRand' value='<?=getParametroSistema(15,1)?>' />
		<table class='filtro'>
			<tr>
				<td>Grupo Device</td>
				<td><?=dicionario(152)?></td>
				<td />
			</tr>
			<tr>
				<td><input type='text' value='<?php echo $_REQUEST['filtro_nome_grupo_device'];?>' name='filtro_nome_grupo_device' style="width: 200px;" size='50' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'/></td>
				<td><input type='text' value='<?php echo $_REQUEST['filtro_limit'];?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)"/></td>
				<td><input type='submit' value='<?=dicionario(166)?>' class='botao' /></td>
			</tr>
		</table>
	</form>
</div>
<div id='menu_ar'>
	<?
		echo menu_acesso_rapido(58);
	?>
</div>
<script>
	enterAsTab(document.forms.filtro);
</script>