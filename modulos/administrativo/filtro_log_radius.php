<?
	if($localOrdem == ''){						$localOrdem = "retorno";	}
	if($localOrdemDirecao == ''){				$localOrdemDirecao = getCodigoInterno(7,6);	}
	if($Limit == '' && $localFiltro == ''){		
		$Limit = getCodigoInterno(7,5);	
		$Limit = 20;
	}
	if($localTipoDado == ''){					$localTipoDado = 'text';	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='radius_log.php' onSubmit='validar()'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ano'				value='<?=$localAno?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table class='filtro'>
				<tr>
					<td>Login</td>
					<td>Tempo de Atualização (s)</td>
					<td>Qtd.</td>
					<td />
				</tr>
				<tr>
					<td><input type='text' value='<?=$localUsuario?>' name='filtro_usuario' size='30' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar()'/></td>
					<td><input type='text' value='<?=$localTempo?>' name='filtro_tempo' size='20' maxlength='11' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'int')" onKeyDown='listar()'/></td>
					<td><input type='text' value='<?=$Limit?>' name='filtro_limit' size='1' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar()"/></td>
					<td><input type='submit' value='Buscar' class='botao' /></td>
				</tr>
			</table>
		</form>
	</div>
	<div id='menu_ar'></div>
	<script>
		function validar(){
			if(document.filtro.filtro_tempo.value != ''){
				temp =	parseInt(document.filtro.filtro_tempo.value);
				temp = temp*1000;
				setInterval("atualiza()",temp);
			}
		}
		function atualiza(){
			document.filtro.submit();
		}
		validar();
	</script>
