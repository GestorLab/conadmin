<?
	if($localOrdem == ''){							$localOrdem = "DataLancamento";		}
	if($localOrdemDirecao == ''){					$localOrdemDirecao = getCodigoInterno(7,6);	}

	LimitVisualizacao("filtro");

	if($localTipoDado == ''){						$localTipoDado = 'number';	}
	
	$localCancelado		=	$_SESSION["filtro_cancelado"];
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_contrato_periodo.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<input type='hidden' name='filtro_carne'			value='<?=$local_IdCarne?>' />
			<table>
				<tr>
					<td><?=dicionario(376)?></td>
					<td><?=dicionario(377)?></td>
				</tr>
				<tr>
					<td>
						<input type='text' name='filtro_data_inicio' style='width: 120px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)' onkeypress="mascara(this,event,'date')" maxlength='10' value='<?=$localDataInicio?>'/>
					</td>
					<td>
						<input type='text' name='filtro_data_termino' style='width: 120px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)' onkeypress="mascara(this,event,'date')" maxlength='10' value='<?=$localDataFim?>'/>
					</td>
					<td><input type='submit' value='<?=dicionario(166)?>' class='botao'/></td>
				</tr>
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?
			echo menu_acesso_rapido(19);
		?>
	</div>
	<script>
		enterAsTab(document.forms.filtro);
	</script>