<?
	if($localOrdem == ''){					$localOrdem = "DataInicio";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}
	if($Limit == '' && $localFiltro == ''){	$Limit = getCodigoInterno(7,5);	}
	if($localTipoDado == ''){				$localTipoDado = 'number';	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_produto_vigencia.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<input type='hidden' name='IdProduto'				value='<?=$localIdProduto?>' />
			<table>
				<tr>
					<td>Data Início</td>
					<td>Data Término</td>
					<td>Tipo Vigência Produto</td>
					<td>Valor (<?=getParametroSistema(5,1)?>)</td>
					<td>Qtd.</td>
					<td />
				</tr>
				<tr>
					<td><input type='text' value='<?=$localDataInicio?>' name='filtro_data_inicio' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')"  maxlength='10' onKeyDown="listar(event)"/></td>
					<td><input type='text' value='<?=$localDataTermino?>' name='filtro_data_termino' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" maxlength='10' onKeyDown="listar(event)"/></td>
					<td><input type='text' value='<?=$localDescricao?>' name='filtro_tipo_vigencia' style='width:220px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td><input type='text' value='<?=$localValor?>' name='filtro_valor' style='width:120px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'float')" maxlength='12' onKeyDown="listar(event)"/></td>
					<td><input type='text' value='<?=$Limit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)" /></td>
					<td><input type='submit' value='Buscar'  class='botao' /></td>
				</tr>	
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?
			echo menu_acesso_rapido(31);
		?>
	</div>
	<script>
		enterAsTab(document.forms.filtro);
	</script>
