<?
	if($localOrdem == ''){						$localOrdem = "DescricaoFormaPagamento";	}
	if($localOrdemDirecao == ''){				$localOrdemDirecao = getCodigoInterno(7,6);	}

	LimitVisualizacao("filtro");

	if($localTipoDado == ''){					$localTipoDado = 'text';	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_forma_pagamento.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table>
				<tr>
					<td>Nome Forma de Pagamento</td>
					<td>Qtd.</td>
					<td />
				</tr>
				<tr>
					<td><input type='text' value='<?=$localDescricaoFormaPagamento?>' name='filtro_descricao' size='50' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'/></td>
					<td><input type='text' value='<?=$Limit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='Buscar' class='botao'/></td>
				</tr>
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?
			echo menu_acesso_rapido(56);
		?>
	</div>
	<script>
		enterAsTab(document.forms.filtro);
	</script>
