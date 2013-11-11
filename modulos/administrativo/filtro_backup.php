<?
	if($localOrdem == ''){			$localOrdem = "DataHoraInicio";				}
	if($localOrdemDirecao == ''){	$localOrdemDirecao = getCodigoInterno(7,6);	}
	if($localTipoDado == ''){		$localTipoDado = "number";					}
	
	LimitVisualizacao("filtro");
	
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_backup.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<input type='hidden' name='filtro_carne'			value='<?=$local_IdCarne?>' />
			<table>
				<tr>
					<td>Log</td>
					<td>Data Iníc. Backup</td>
					<td>Data Térm. Backup</td>
					<td>Qtd.</td>
					<td />
					<td />
					<td />
				</tr>
				<tr>
					<td><input type='text' value='<?=$localLog?>' name='filtro_log' style='width:260px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'/></td>
				
					<td><input type='text' value='<?=$localDataInicio?>' name='filtro_data_inicio' style='width:115px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')"  maxlength='10' onKeyDown='listar(event)'/></td>
					<td><input type='text' value='<?=$localDataTermino?>' name='filtro_data_termino' style='width:115px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')"  maxlength='10' onKeyDown='listar(event)'/></td>			
					<td><input type='text' value='<?=$localLimit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='Buscar' class='botao'/></td>										
				</tr>		
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?
			echo menu_acesso_rapido(45);
		?>
	</div>
	<script>
		enterAsTab(document.forms.filtro);
	</script>
