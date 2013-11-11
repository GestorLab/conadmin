<?
	if($localOrdem == ''){					$localOrdem = "Campo1";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}
	
	LimitVisualizacao("filtro");
	
	if($localTipoDado == ''){				$localTipoDado = 'text';	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_importar_registros.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table>
				<tr>
					<?
						if(getParametroSistema(111,3) != ""){
							echo"<td>".getParametroSistema(111,3)."</td>";
						}
						if(getParametroSistema(111,4) != ""){
							echo"<td>".getParametroSistema(111,4)."</td>";
						}
						if(getParametroSistema(111,5) != ""){
							echo"<td>".getParametroSistema(111,5)."</td>";
						}
						if(getParametroSistema(111,6) != ""){
							echo"<td>".getParametroSistema(111,6)."</td>";
						}
						if(getParametroSistema(111,7) != ""){
							echo"<td>".getParametroSistema(111,7)."</td>";
						}
					
					?>
					<td>Qtd.</td>
					<td />
				</tr>
				<tr>
					<?
						if(getParametroSistema(111,3) != ""){
							echo"<td><input type='text' name='filtro_campo1' value='$localCampo1' style='width:135px' maxlength='100' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" onKeyDown=\"listar(event);\" /></td>";
						}
						if(getParametroSistema(111,4) != ""){
							echo"<td><input type='text' name='filtro_campo2' value='$localCampo2' style='width:135px' maxlength='100' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" onKeyDown=\"listar(event);\" /></td>";
						}
						if(getParametroSistema(111,5) != ""){
							echo"<td><input type='text' name='filtro_campo3' value='$localCampo3' style='width:135px' maxlength='100' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" onKeyDown=\"listar(event);\" /></td>";
						}
						if(getParametroSistema(111,6) != ""){
							echo"<td><input type='text' name='filtro_campo4' value='$localCampo4' style='width:135px' maxlength='100' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" onKeyDown=\"listar(event);\" /></td>";
						}
						if(getParametroSistema(111,7) != ""){
							echo"<td><input type='text' name='filtro_campo5' value='$localCampo5' style='width:135px' maxlength='100' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" onKeyDown=\"listar(event);\" /></td>";
						}
					?>
					<td><input type='text' value='<?=$Limit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='Buscar'  class='botao' /></td>
				</tr>	
			</table>
		</form>
	</div>
	<div id='menu_ar'></div>
	<script>
		enterAsTab(document.forms.filtro);
	</script>
