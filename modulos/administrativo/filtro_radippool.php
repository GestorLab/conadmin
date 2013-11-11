<?
	if($localOrdem == ''){					$localOrdem = "Descricao";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}
	
	LimitVisualizacao("filtro");
	
	if($localTipoDado == ''){				$localTipoDado = 'number';	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_radippool.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table>
				<tr>
					<td>Pool Name</td>
					<td>Framed Ip Address</td>
					<td>Nas Ip Address</td>
					<td>Qtd.</td>
					<td />
				</tr>
				<tr>
					<td>
						<input type='text' value='<?=$local_filtro_PoolName?>' name='filtro_PoolName' style='width:180px'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/>
					</td>
					<td>
						<input type='text' value='<?=$local_filtro_FrameIpAddress?>' name='filtro_FrameIpAddress' style='width:190px'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/>
					</td>					
					<td>
						<input type='text' value='<?=$local_filtro_NasIpAddress?>' name='filtro_NasIpAddress' style='width:190px'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/>
					</td>					
					<td><input type='text' value='<?=$Limit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='Buscar'  class='botao' /></td>
				</tr>	
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?
			echo menu_acesso_rapido(10000);
		?>
	</div>
	<script>	
		inicia();
		enterAsTab(document.forms.filtro);
	</script>
	
