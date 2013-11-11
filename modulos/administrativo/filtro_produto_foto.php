<?
	if($IdProdutoTemp == ""){
		$campo = 1;
	}
	
	if($campo == 1){
		if($localOrdem == ''){					$localOrdem = "IdProduto";		}
		if($localOrdemDirecao == ''){			$localOrdemDirecao = 'ascending';	}	
	}else{
		if($localOrdem == ''){					$localOrdem = "IdProdutoFoto";		}
		if($localOrdemDirecao == ''){			$localOrdemDirecao = 'descending';	}	
	}
	
	LimitVisualizacao("filtro");
	
	if($localTipoDado == ''){				$localTipoDado = 'text';	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_produto_foto.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<input type='hidden' name='IdProduto'				value='<?=$localIdProduto?>' />
			<table>
				<tr>
					<td>Descrição Foto</td>
					<td>Qtd.</td>
					<td />
				</tr>
				<tr>
					<td><input type='text' value='<?=$localDescricaoFoto?>' name='filtro_descricao' style='width:220px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td><input type='text' value='<?=$Limit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
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
