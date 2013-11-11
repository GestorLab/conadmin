<?
	if($localOrdem == ""){
		$localOrdem = "NumeroNF";
	}
	
	if($localOrdemDirecao == ""){
		$localOrdemDirecao = getCodigoInterno(7,6);
	}
	
	if($localTipoDado == ""){
		$localTipoDado = "number";
	}
	
	LimitVisualizacao("filtro");
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_sici_lancamento.php' onSubmit='return validar();'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table>
				<tr>
					<td>Período de Apuração</td>
					<td>Descrição do Serviço</td>
					<td>Número NF</td>
					<td>Data NF Inícial</td>
					<td>Data NF Final</td>
					<td>Qtd.</td>
					<td />
					<td />
					<td />
				</tr>
				<tr>
					<td>
						<select name='filtro_periodo_apuracao' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:136px' onKeyDown='listar(event)' onChange="addGrupo(this.value); inserirAtributo(this.value); addAtribute()">
							<option value=''></option>
							<?
								$sql = "SELECT SICI.PeriodoApuracao FROM SICI WHERE SICI.IdStatus > 1 ORDER BY SICI.PeriodoApuracao;";
								$res = mysql_query($sql,$con);
								
								while($lin = mysql_fetch_array($res)){
									$lin[PeriodoApuracao] = dataConv($lin[PeriodoApuracao], "Y-m", "m/Y");
									echo "<option value='$lin[PeriodoApuracao]' ".compara($localPeriodoApuracao,$lin[PeriodoApuracao],"selected='selected'","").">$lin[PeriodoApuracao]</option>";
								}
							?>
						</select>
					</td>
					<td><input type='text' value='<?=$localDescricaoServico?>' name='filtro_descricao_servico' style='width:280px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" maxlength='100' onKeyDown="listar(event)"/></td>
					<td><input type='text' value='<?=$localNumeroNF?>' name='filtro_numero_nota_fiscal' style='width:88px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" maxlength='11' onKeyDown="listar(event)"/></td>
					<td><input type='text' value='<?=$localDataNFInicial?>' name='filtro_data_nota_fiscal_inicial' style='width:84px' maxlength='10' onkeypress="mascara(this,event,'date')" onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" onKeyDown="listar(event)" /></td>
					<td><input type='text' value='<?=$localDataNFFinal?>' name='filtro_data_nota_fiscal_final' style='width:84px' maxlength='10' onkeypress="mascara(this,event,'date')" onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" onKeyDown="listar(event)" /></td>
					<td><input type='text' value='<?=$Limit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown="listar(event)" /></td>
					<td><input type='submit' value='Buscar'  class='botao' /></td>
				</tr>
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?=menu_acesso_rapido(55)?>
	</div>
	<script type='text/javascript'>
		function validar(){
			if(document.filtro.filtro_periodo_apuracao.value != ""){
				document.filtro.submit();
			}else{
				alert("Atencao!\nPreencha os campos do filtro.");
				return false;
			}
		}
		
		enterAsTab(document.forms.filtro);
	</script>