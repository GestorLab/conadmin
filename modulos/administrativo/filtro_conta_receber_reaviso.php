<?
	if($localOrdem == ''){							$localOrdem = "Nome";		}
	if($localOrdemDirecao == ''){					$localOrdemDirecao = getCodigoInterno(7,6);	}
	
	LimitVisualizacao("filtro");
	
	if($localTipoDado == ''){						$localTipoDado = 'text';	}
	
	$localCancelado		=	$_SESSION["filtro_cancelado"];
	$localJuros			=	$_SESSION["filtro_juros"];
	$localSoma			=	$_SESSION["filtro_soma"];
	$localNotaFiscal	=	$_SESSION["filtro_nota_fiscal"];
	$localBoleto		=	$_SESSION["filtro_impressao"];
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_conta_receber_reaviso.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table>
				<tr>
					<td>Nome Pessoa/Razão Social</td>
					<td>Forma Cob.</td>
					<td>Local Cob.</td>
					<td>Data Venc. Iníc.</td>
					<td>Data Venc. Fim</td>
					<td>Qtd.</td>
					<td />
					<td />
					<td />
				</tr>
				<tr>
					<td><input type='text' value="<?=$localNome?>" name='filtro_nome' style='width:160px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)' onkeyup='busca_pessoa_aproximada(this,event);' /></td>
					<td>
						<select name='filtro_forma_cobranca' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:120px' onKeyDown='listar(event)'>
							<option value=''>Todos</option>
							<option value='C' <?=compara($localFormaCobranca,"C","selected='selected'","")?>>Correios</option>
							<option value='E' <?=compara($localFormaCobranca,"E","selected='selected'","")?>>E-mail</option>
							<option value='O' <?=compara($localFormaCobranca,"O","selected='selected'","")?>>Outros</option>
						</select>
					</td>
					<td>
						<select name='filtro_local_cobranca' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'>
							<option value=''>Todos</option>
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
					<td><input type='text' name='filtro_data_inicio' value='<?=$localDataInicio?>' style='width:100px' maxlength='10' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onKeyDown="listar(event);" /></td>
					<td><input type='text' name='filtro_data_fim' value='<?=$localDataTermino?>' style='width:100px' maxlength='10' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onKeyDown="listar(event);" /></td>
					<td><input type='text' value='<?=$localLimit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='Buscar' class='botao'/></td>
					<td><img onmousemove="quadro_alt(event, this, 'Menu Avançado');" src='../../img/estrutura_sistema/seta_baixo.gif' style='cursor:pointer' onClick="consulta_rapida('bottom')" alt='Parâmetros de Consulta Rápida' /></td>
					<td  style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_conta_receber_opcoes.php'>Outros<BR />Relatórios</a></td>
				</tr>
				<tr>
					<td colspan='8'>
						<div id='filtroRapido' style='width:100%; display:none; background-color: #0065D5'>
							<table style='width:100%;' cellpadding='0'>
								<tr>
									<td style='width:240px'>Listar Contas à Receber/Reaviso Estado </td>
									<td>
										<select name='filtro_estado' style='width:250px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value); busca_filtro_cidade(this.value);">
											<option value=''></option>
											<?
												$sql = "select IdEstado, SiglaEstado from Estado order by SiglaEstado";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													
													$lin[SiglaEstado] = url_string_xsl($lin[SiglaEstado],'convert');
													
													echo "<option value='$lin[IdEstado]' ".compara($localIdEstado,$lin[IdEstado],"selected='selected'","").">$lin[SiglaEstado]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td style='width:240px'>Listar Contas à Receber/Reaviso Cidade</td>
									<td>
										<select name='filtro_cidade' style='width:250px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">											
										</select>
									</td>
								</tr>
								<tr>
									<td style='width:240px'>Listar Contas à Receber/Reaviso Bairro</td>
									<td>
										<input type='text' name='filtro_bairro' value='<?=$localBairro?>' style='width:245px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="atualizaSessao(this.name,this.value)" onKeyDown="listar(event);" />
									</td>
								</tr>
								<tr>
									<td style='width:240px'>Listar Contas à Receber/Reaviso Status do Contrato</td>
									<td>
										<select name='filtro_contrato_status' style='width:250px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">
											<option value=''></option>
											<?												
												$i	=	0;
								
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=69 order by ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													$vetor[$i]	=	substituir_string($lin[ValorParametroSistema])."#".$lin[IdParametroSistema];
													$i++;
												}
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=113 order by ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													$vetor[$i]	=	substituir_string($lin[ValorParametroSistema])." (Todos)#G_".$lin[IdParametroSistema];
													$i++;
												}
												
												sort($vetor);
												
												foreach ($vetor as $key => $val) {
													$vet	=	explode("#",$val);
													$id		=	trim($vet[1]);	
													$value	=	trim($vet[0]);	
													
													echo "<option value='$id' ".compara($localIdContratoStatus,$id,"selected='selected'","").">$value</option>";
												}

											?>
										</select>
									</td>
								</tr>								
							</table>
							<div style='width:100%; text-align:right; margin:0;'>
								<img onmousemove="quadro_alt(event, this, 'Os filtros do \'Menu Avançado\' ao serem alterados, o valor prevalece selecionado durante a sessão');" src='../../img/estrutura_sistema/seta_cima.gif' style='cursor:pointer' onClick="consulta_rapida('top')" alt='Parâmetros de Consulta Rápida [Fechar]' />
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
		function inicia(){
			if(document.filtro.filtro_estado.value != ""){
				busca_filtro_cidade(document.filtro.filtro_estado.value,'<?=$localIdCidade?>');
			}			
		}
		inicia();
		enterAsTab(document.forms.filtro);
	</script>
