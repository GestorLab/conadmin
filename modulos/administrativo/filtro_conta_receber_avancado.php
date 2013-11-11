<?
	if($localOrdem == ''){							$localOrdem = "DataLancamento";		}
	if($localOrdemDirecao == ''){					$localOrdemDirecao = getCodigoInterno(7,6);	}
	if($localLimit == '' && $localFiltro == ''){	$localLimit = getCodigoInterno(7,5);	}
	if($localTipoDado == ''){						$localTipoDado = 'number';	}
?>
	<div id='filtroBuscar' style='width:<?=$localtam?>'>
		<form name='filtro' method='post' action='excel_conta_receber.php' onSubmit='return validar_filtro()'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table>
				<tr>
					<td>Nome Pessoa/Razão Social</td>
					<td class='separador' />
					<td>Tipo de Pessoa</td>
					<td class='separador' />
					<td>Tipo Lançamento</td>
					<td class='separador' />
					<td>Local de Cobrança</td>
					<td class='separador' />
					<td>Valor (<?=getParametroSistema(5,1)?>)</td>
				</tr>
				<tr>
					<td><input type='text' value='<?=$localNome?>' name='filtro_nome' style='width:250px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar_filtro(event)' onkeyup='busca_pessoa_aproximada(this,event);'/></td>
					<td class='separador' />
					<td>
						<select name='filtro_tipo_pessoa' style='width:90px'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar_filtro(event)'>
							<option value=''></option>
							<?
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=1 order by ValorParametroSistema";
								$res = @mysql_query($sql,$con);
								while($lin = @mysql_fetch_array($res)){
									
									$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
									
									echo "<option value='$lin[IdParametroSistema]' ".compara($localIdStatus,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
							?>
						</select>
					</td>
					<td class='separador' />
					<td>
						<select name='filtro_tipo' style='width:180px'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar_filtro(event)'>
							<option value=''>Todos</option>
							<option value='CO'>Contrato</option>
							<option value='EV'>Conta Eventual</option>
							<option value='OS'>Ordem Servico</option>
						</select>
					</td>
					<td class='separador' />
					<td>
						<select name='filtro_local_cobranca' style='width:116px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar_filtro(event)'>
							<option value=''>Todos</option>
							<?
								$sql = "select IdLocalCobranca, AbreviacaoNomeLocalCobranca from LocalCobranca order by DescricaoLocalCobranca";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									
									$lin[AbreviacaoNomeLocalCobranca] = url_string_xsl($lin[AbreviacaoNomeLocalCobranca],'convert');
									
									echo "<option value='$lin[IdLocalCobranca]' ".compara($localIdLocalCobranca,$lin[IdLocalCobranca],"selected='selected'","").">$lin[AbreviacaoNomeLocalCobranca]</option>";
								}
							?>
						</select>
					</td>
					<td class='separador' />
					<td><input type='text' value='<?=$localValor?>' name='filtro_valor' maxlength='12' style='width:154px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'float')" onKeyDown='listar_filtro(event)'/>
					</td>
				</tr>
			</table>
			<table>
				<tr>
					<td>Data Lançamento (De)</td>
					<td>(Até)</td>
					<td class='separador' />
					<td>Data Vencimento (De)</td>
					<td>(Até)</td>
					<td class='separador' />
					<td>Cod. Serviço</td>
					<td class='separador' />
					<td>Nome Serviço</td>
				</tr>
				<tr>
					<td><input type='text' value='<?=$localDataLancamentoIni?>' name='filtro_data_lanc_ini' maxlength='10' style='width:130px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onKeyDown='listar_filtro(event)'/></td>
					<td><input type='text' value='<?=$localDataLancamentoFim?>' name='filtro_data_lanc_fim' maxlength='10' style='width:130px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onKeyDown='listar_filtro(event)'/></td>
					<td class='separador' />
					<td><input type='text' value='<?=$localDataVencimentoIni?>' name='filtro_data_venc_ini' maxlength='10' style='width:130px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onKeyDown='listar_filtro(event)'/></td>
					<td><input type='text' value='<?=$localDataVencimentoFim?>' name='filtro_data_venc_fim' maxlength='10' style='width:130px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onKeyDown='listar_filtro(event)'/></td>
					<td class='separador' />
					<td>
						<select name='filtro_id_servico' style='width: 76px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar_filtro(event)'>
							<option value=''></option>
							<?
									$sql = "select IdServico from Servico where IdStatus = 1";
									$res = mysql_query($sql,$con);
									while($lin = mysql_fetch_array($res)){
										echo "<option value='$lin[IdServico]' ".compara($localIdServico,$lin[IdServico],"selected='selected'","").">$lin[IdServico]</option>";
									}
							?>
						</select>	
					</td>
					<td class='separador' />
					<td><input type='text' value='<?=$localDescricaoServico?>' name='filtro_servico' maxlength='100' style='width:183px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar_filtro(event)'/></td>
				</tr>
			</table>
			<table>
				<tr>
					<td>Data Pagamento (De)</td>
					<td>(Até)</td>
					<td class='separador' />
					<td>Número Documento</td>
					<td class='separador' />
					<td>Processo Financeiro</td>
					<td class='separador' /> 
					<td>Número NF</td>
					<td class='separador' />
					<td>País</td>
				</tr>
				<tr>
					<td><input type='text' value='<?=$localDataPagamentoIni?>' name='filtro_data_paga_ini' maxlength='10' style='width:140px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onKeyDown='listar_filtro(event)'/></td>
					<td><input type='text' value='<?=$localDataPagamentoFim?>' name='filtro_data_paga_fim' maxlength='10' style='width:140px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onKeyDown='listar_filtro(event)'/></td>
					<td class='separador' />
					<td><input type='text' value='<?=$localNumeroDocumento?>' name='filtro_numero_doc' maxlength='11' style='width:120px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'int')" onKeyDown='listar_filtro(event)'/></td>
					<td class='separador' />
					<td><input type='text' value='<?=$localIdProcessoFinanceiro?>' name='filtro_processo' maxlength='11' style='width:120px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'int')" onKeyDown='listar_filtro(event)'/></td>
					<td class='separador' />
					<td><input type='text' value='<?=$localNumeroNF?>' name='filtro_numero_nf' maxlength='20' style='width:104px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar_filtro(event)'/></td>
					<td class='separador' />
					<td>
						<select name='filtro_idpais' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:146px' onChange="verifica_estado(this.value)" onKeyDown='listar_filtro(event)'>
							<option value=''>Todos</option>
							<?
								$sql = "select IdPais, NomePais from Pais order by NomePais";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									echo "<option value='$lin[IdPais]' ".compara($localIdPais,$lin[IdPais],"selected='selected'","").">$lin[NomePais]</option>";
								}
							?>
						</select>
					</td>
				</tr>
			</table>
			<table>
				<tr>
					<td>Estado</td>
					<td class='separador' />
					<td>Cidade</td>
					<td class='separador' />
					<td>Status</td>
				</tr>
				<tr>
					<td>
						<select name='filtro_estado' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:250px'  onKeyDown='listar_filtro(event)'>
							<option value='0'>Todos</option>
							<?
								$sql	=	"select IdEstado, NomeEstado from Pais,Estado where Estado.IdPais = Pais.IdPais and Estado.IdPais=$localIdPais order by NomeEstado";
								$res	=	mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									echo "<option value='$lin[IdEstado]' ".compara($localIdEstado,$lin[IdEstado],"selected='selected'","").">$lin[NomeEstado]</option>\n";
								}
							?>
						</select>
					</td>
					<td class='separador' />
					<td><input type='text' value='<?=$localNomeCidade?>' name='filtro_cidade' maxlength='100' style='width:348px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar_filtro(event)'/></td>
					<td class='separador' />
					<td>
						<select name='filtro_status' style='width:220px'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar_filtro(event)'>
							<option value=''>Todos</option>
							<?
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=35 order by ValorParametroSistema";
								$res = @mysql_query($sql,$con);
								while($lin = @mysql_fetch_array($res)){
									
									$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
									
									echo "<option value='$lin[IdParametroSistema]' ".compara($localIdStatus,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
							?>
						</select>
					</td>
				</tr>
			</table>
			<div id='cp_tit' style='background-color:#E2E7ED; color:#000'>Campos Exibidos</div>
			<table  cellspacing='0' cellpadding='0'>
				<tr>
					<td><input style='border:0' type='checkbox' name='chTodos' onClick="selecionaTodos(this)"/></td>
					<td>Selecionar todos</td>
				</tr>
			</table>
			<table cellspacing='0' id='tableAvancada'>
				<tr>
					<td><input style='border:0' type='checkbox' name='chNome' /></td>
					<td class='espDireita2'>Nome Pessoa</td>
					
					<td class='espEsquerda'><input style='border:0' type='checkbox' name='chCel' /></td>
					<td class='espDireita'>Celular</td>
					
					<td class='espEsquerda'><input style='border:0' type='checkbox' name='chNumNF' /></td>
					<td class='espDireita'>Número Nota Fiscal</td>
					
					<td class='espEsquerda'><input style='border:0' type='checkbox' name='chDataP'/></td>
					<td class='espDireita'>Data Pagamento</td>
					
					<td class='espEsquerda'><input style='border:0' type='checkbox' name='chLRec' /></td>
					<td class='espDireita'>Local Recebimento</td>
				</tr>	
				<tr>
					<td><input style='border:0' type='checkbox' name='chRazao' /></td>
					<td class='espDireita2'>Razão Social</td>
					
					<td class='espEsquerda'><input style='border:0' type='checkbox' name='chFax' /></td>
					<td class='espDireita'>Fax</td>
					
					<td class='espEsquerda'><input style='border:0' type='checkbox' name='chDataF' /></td>
					<td class='espDireita'>Data Nota Fiscal</td>
					
					<td class='espEsquerda'><input style='border:0' type='checkbox' name='chValor' /></td>
					<td class='espDireita'>Valor</td>
					
					<td class='espEsquerda'><input style='border:0' type='checkbox' name='chValF' /></td>
					<td class='espDireita' colspan='3'>Valor Final</td>
				</tr>
				<tr>
					<td><input style='border:0' type='checkbox' name='chFone1' /></td>
					<td class='espDireita2'>Fone Residencial</td>
					
					<td class='espEsquerda'><input style='border:0' type='checkbox' name='chCompF' /></td>
					<td class='espDireita'>Complemento Fone</td>
					
					<td class='espEsquerda'><input style='border:0' type='checkbox' name='chLCob'/></td>
					<td class='espDireita'>Local de Cobranca</td>
					
					<td class='espEsquerda'><input style='border:0' type='checkbox' name='chValDp' /></td>
					<td class='espDireita'>Valor Despesas</td>
					
					<td class='espEsquerda'><input style='border:0' type='checkbox' name='chStat' /></td>
					<td class='espDireita'>Status</td>
				</tr>
				<tr>
					<td><input style='border:0' type='checkbox' name='chFone2' /></td>
					<td class='espDireita2'>Fone Comercial (1)</td>
					
					<td class='espEsquerda'><input style='border:0' type='checkbox' name='chEmail' /></td>
					<td class='espDireita'>E-mail</td>
					
					<td class='espEsquerda'><input style='border:0' type='checkbox' name='chDataL'/></td>
					<td class='espDireita'>Data Lançamento</td>
					
					<td class='espEsquerda'><input style='border:0' type='checkbox' name='chValDe' /></td>
					<td class='espDireita'>Valor Desconto<BR>Concebido</td>
					
					<td class='espEsquerda'><input style='border:0' type='checkbox' name='chObs' /></td>
					<td class='espDireita'>Observações</td>					
				</tr>
				<tr>
					<td><input style='border:0' type='checkbox' name='chFone3' /></td>
					<td class='espDireita2'>Fone Comercial (2)</td>
					
					<td class='espEsquerda'><input style='border:0' type='checkbox' name='chNumD'/></td>
					<td class='espDireita'>Número Documento</td>
					
					<td class='espEsquerda'><input style='border:0' type='checkbox' name='chDataV'/></td>
					<td class='espDireita'>Data Vencimento</td>
					
					<td class='espEsquerda'><input style='border:0' type='checkbox' name='chValDeC' /></td>
					<td class='espDireita'>Valor Desconto<BR>A Conceber</td>
					
					<td class='espEsquerda'>&nbsp;</td>
					<td class='espDireita'>&nbsp;</td>
				</tr>
			</table>
			<table style='width:840px;'>
				<tr>
					<td style='text-align:right; padding-right:14px'><input type='submit' value='Gerar Arquivo'  class='botao' /></td>
					<td style='width:50px; font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_conta_receber_opcoes.php'>Outros<BR />Relatórios</a></td>
				</tr>
			</table>
		</form>
	</div>
	<div id='menu_ar'></div>
	<div id='carregando'>carregando</div>
	<script language='JavaScript' type='text/javascript'> 
		function checarInicial(){
			if(document.filtro.chRazao.checked == false){
				if(document.filtro.chNome.checked == false){
					if('<?=getCodigoInterno(3,24)?>' == 1){
						document.filtro.chRazao.checked		=	true;
						document.filtro.chNome.checked		=	false;
					}else{
						document.filtro.chRazao.checked		=	false;
						document.filtro.chNome.checked		=	true;
					}
				}
			}			
		}
	//	checarInicial();
		ativaNome('Contas a Receber');
		enterAsTab(document.forms.filtro);
	</script>

