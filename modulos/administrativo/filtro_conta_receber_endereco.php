<?
	if($localOrdem == ''){							$localOrdem = "DataVencimento";		}
	if($localOrdemDirecao == ''){					$localOrdemDirecao = getCodigoInterno(7,6);	}

	LimitVisualizacao("filtro");

	if($localTipoDado == ''){						$localTipoDado = 'number';	}
	
	$localCancelado					=	$_SESSION["filtro_cancelado"];
	$localJuros						=	$_SESSION["filtro_juros"];
	$localSoma						=	$_SESSION["filtro_soma"];
	$localNotaFiscal				=	$_SESSION["filtro_nota_fiscal"];
	$localCPFCNPJ					=	$_SESSION["filtro_cpf_cnpj"];
	$localBoleto					=	$_SESSION["filtro_impressao"];
	$localContaReceberNotaFiscal	=	$_SESSION["filtro_conta_receber_nota_fiscal"];	
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_conta_receber_endereco.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<input type='hidden' name='filtro_carne'			value='<?=$local_IdCarne?>' />
			<input type='hidden' name='IdServico'				value='<?=$local_IdServico?>' />
			<input type='hidden' name='IdCidadeTemp'			value='<?=$local_IdCidade?>' />
			<table>
				<tr>
					<td><?=dicionario(148)?></td>
					<td><?=dicionario(285)?>.</td>
					<td><?=dicionario(150)?></td>
					<td><?=dicionario(151)?></td>
					<td><?=dicionario(140)?></td>
					<td><?=dicionario(152)?></td>
					<td />
					<td />
					<td />
				</tr>
				<tr>
					<td><input type='text' value="<?=$localNome?>" name='filtro_nome' style='width:160px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)' onkeyup='busca_pessoa_aproximada(this,event);' /></td>
					<td>
						<select name='filtro_local_cobranca' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'>
							<option value=''><?=dicionario(153)?></option>
							<?
								$sql = "select IdLocalCobranca, AbreviacaoNomeLocalCobranca from LocalCobranca where IdLoja = $local_IdLoja order by DescricaoLocalCobranca";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									
									$lin[AbreviacaoNomeLocalCobranca] = url_string_xsl($lin[AbreviacaoNomeLocalCobranca],'convert');
									
									echo "<option value='$lin[IdLocalCobranca]' ".compara($localIdLocalCobranca,$lin[IdLocalCobranca],"selected='selected'","").">$lin[AbreviacaoNomeLocalCobranca]</option>";
								}
							?>
						</select>
					</td>
					<td>
						<select name='filtro_campo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:110px'  onKeyDown="listar(event)">
							<option value=''><?=dicionario(153)?></option>
							<option value='IdArquivoRetorno' <?=compara($localCampo,"IdArquivoRetorno","selected='selected'","")?>><?=dicionario(42)?></option>
							<option value='DataLancamento' <?=compara($localCampo,"DataLancamento","selected='selected'","")?>><?=dicionario(409)?>.</option>
							<option value='DataVencimentoOriginal' <?=compara($localCampo,"DataVencimentoOriginal","selected='selected'","")?>><?=dicionario(709)?></option>
							<option value='DataVencimentoAtual' <?=compara($localCampo,"DataVencimentoAtual","selected='selected'","")?>><?=dicionario(710)?></option>
							<option value='IdLancamentoFinanceiro' <?=compara($localCampo,"IdLancamentoFinanceiro","selected='selected'","")?>><?=dicionario(650)?>.</option>
							<option value='MesLancamento' <?=compara($localCampo,"MesLancamento","selected='selected'","")?>><?=dicionario(412)?>.</option>
							<option value='MesVencimentoAtual' <?=compara($localCampo,"MesVencimentoAtual","selected='selected'","")?>><?=dicionario(711)?></option>
							<option value='MesVencimentoOriginal' <?=compara($localCampo,"MesVencimentoOriginal","selected='selected'","")?>><?=dicionario(712)?>.</option>
							<option value='DescricaoServico' <?=compara($localCampo,"DescricaoServico","selected='selected'","")?>><?=dicionario(223)?></option>
							<option value='NumeroDocumento' <?=compara($localCampo,"NumeroDocumento","selected='selected'","")?>>Nº <?=dicionario(713)?></option>
							<option value='IdContaReceber' <?=compara($localCampo,"IdContaReceber","selected='selected'","")?>>Nº <?=dicionario(387)?>.</option>
							<option value='NumeroNF' <?=compara($localCampo,"NumeroNF","selected='selected'","")?>>Nº <?=dicionario(53)?></option>
							<option value='IdReceibo' <?=compara($localCampo,"IdReceibo","selected='selected'","")?>>N° <?=dicionario(708)?></option>
							<option value='IdProcessoFinanceiro' <?=compara($localCampo,"IdProcessoFinanceiro","selected='selected'","")?>><?=dicionario(417)?>.</option>
						</select>
					</td>
					<td><input type='text' name='filtro_valor' value='<?=$localValor?>' style='width:100px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="chama_mascara(this,event)" onKeyDown="listar(event);" /></td>
					<td>
						<select name='filtro_status' style='width:170px'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'>
							<option value=''><?=dicionario(153)?></option>
							<?
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=35 order by ValorParametroSistema";
								$res = @mysql_query($sql,$con);
								while($lin = @mysql_fetch_array($res)){
									
									$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
									
									echo "<option value='$lin[IdParametroSistema]' ".compara($localIdStatus,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
								
								echo "<option value='200' ".compara($localIdStatus,200,"selected='selected'","").">Vencido</option>";
							?>
						</select>
					</td>
					<td>
						<input type='text' value='<?=$localLimit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/>
					</td>
					<td><input type='submit' value='<?=dicionario(166)?>' class='botao'/></td>
					<td><img onmousemove="quadro_alt(event, this, '<?=dicionario(198)?>');" src='../../img/estrutura_sistema/seta_baixo.gif' style='cursor:pointer' onClick="consulta_rapida('bottom'); atualiza_campo();" alt='<?=dicionario(167)?>' /></td>
					<td  style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_conta_receber_opcoes.php'><?=dicionario(120)?><BR /><?=dicionario(162)?></a></td>
				</tr>
				<tr>
					<td colspan='8'>
						<div id='filtroRapido' style='width:100%; display:none; background-color: #0065D5'>
							<table style='width:100%;'>
								<tr>
									<td style='width:120px'>Cep</td>
									<td><input type='text' name='filtro_cep' value='<?=$localCep?>' style='width:90px' maxlength='9' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" onkeypress="mascara(this,event,'cep')" /></td>
								</tr>
								<tr>
									<td style='width:120px'><?=dicionario(157)?></td>
									<td>
										<select name='filtro_estado' style='width:250px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onChange="busca_filtro_cidade_estado(this.value)">
											<option value=''></option>
											<?
												$sql = "select IdEstado, NomeEstado from Estado where 1 order by NomeEstado asc";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													echo "<option value='$lin[IdEstado]' ".compara($local_IdEstado,$lin[IdEstado],"selected='selected'","").">$lin[NomeEstado]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td><?=dicionario(186)?></td>
									<td>
										<select name='filtro_cidade' style='width:250px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown="listar(event)">
											<option value=''></option>
										</select>
									</td>
								</tr>
								<tr>
									<td style='width:120px'><?=dicionario(160)?></td>
									<td><input type='text' name='filtro_bairro' value='<?=$localBairro?>' style='width:245px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" /></td>
								</tr>
								<tr>
									<td style='width:120px'><?=dicionario(155)?></td>
									<td><input type='text' name='filtro_endereco' value='<?=$localEndereco?>' style='width:245px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" /></td>
								</tr>
							</table>
							<div style='width:100%; text-align:right; margin:0;'>
								<img onmousemove="quadro_alt(event, this, '<?=dicionario(291)?>');" src='../../img/estrutura_sistema/seta_cima.gif' style='cursor:pointer' onClick="consulta_rapida('top')" alt='Parâmetros de Consulta Rápida [Fechar]' />
							</div>
						</div>
					</td>
					<td />
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
		function atualiza_campo(){
			if(document.filtro.filtro_estado.value != ''){
				if(document.filtro.IdCidadeTemp.value == ''){
					busca_filtro_cidade_estado(document.filtro.filtro_estado.value,"");
				} else{
					busca_filtro_cidade_estado(document.filtro.filtro_estado.value,document.filtro.IdCidadeTemp.value);
				}
			}
		}
		
		atualiza_campo();
		enterAsTab(document.forms.filtro);
	</script>