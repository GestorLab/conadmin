<?
	if($localOrdem == ''){					$localOrdem = "NasTemp";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}
	if($localTipoDado == ''){				$localTipoDado = 'text';	}
	
	LimitVisualizacao("filtro");
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_check_nas.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<input type='hidden' name='IdServico'				value='<?=$localIdServico?>' />
			<input type='hidden' name='IdPessoa'				value='<?=$localIdPessoa?>' />
			<input type='hidden' name='IdContrato'				value='' />
			<table>
				<tr>
					<td>Nas</td>
					<td>Qtd.</td>
					<td />
				</tr>
				<tr>
					<td><input type='text' value='<?=$localNas?>' name='filtro_nas' style='width:160px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" onKeyDown="listar(event)" /></td>
					<td><input type='text' value='<?=$Limit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='Buscar'  class='botao' /></td>
				</tr>	
			</table>
			
		</form>
	</div>
	<div id='menu_ar'>
		<?
			echo menu_acesso_rapido(53);
		?>
	</div>
	<script type='text/javascript'>
		enterAsTab(document.forms.filtro);
	</script>