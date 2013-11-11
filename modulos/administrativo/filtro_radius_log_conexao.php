	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_radius_log_conecao.php'>
			<table>
				<tr>
					<td>Mês/Ano</td>
					<td />
				</tr>
				<tr>
					<td><input type='text' name='MesReferencia' value='<?=$localMesReferencia?>' style='width:100px' maxlength='8' onkeypress="mascara(this,event,'mes')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" /></td>
					<td><input type='submit' value='Buscar' class='botao' /></td>
				</tr>
			</table>
		</form>
	</div>
	<div id='menu_ar'>&nbsp;</div>
	
