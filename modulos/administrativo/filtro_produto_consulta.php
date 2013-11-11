<?
	if($localOrdem == ''){						$localOrdem = "DescricaoReduzidaProduto";	}
	if($localOrdemDirecao == ''){				$localOrdemDirecao = getCodigoInterno(7,6);	}
	if($Limit == '' && $localFiltro == ''){		$Limit = getCodigoInterno(7,5);	}
	if($localTipoDado == ''){					$localTipoDado = 'text';	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_produto_consulta.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table class='filtro'>
				<tr>
					<td>Nome Produto</td>
					<td>Fabricante</td>
					<td>Grupo Produto</td>
					<td>SubGrupo Produto</td>
					<td>Qtd.</td>
					<td />
					<td />
				</tr>
				<tr>
					<td><input type='text' value='<?=$localDescricaoProduto?>' name='filtro_descricao' style='width:180px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'/></td>
					<td><input type='text' value='<?=$localDescricaoFabricante?>' name='filtro_fabricante' style='width:150px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'/></td>
					<td><input type='text' value='<?=$localDescricaoGrupoProduto?>' name='filtro_grupo_produto' style='width:150px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'/></td>
					<td><input type='text' value='<?=$localDescricaoSubGrupoProduto?>' name='filtro_subgrupo_produto' style='width:150px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'/></td>
					<td><input type='text' value='<?=$Limit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='Buscar' class='botao' /></td><td  style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_produto_opcoes.php'>Outros<BR />Relatórios</a></td>
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
