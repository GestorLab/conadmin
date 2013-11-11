<?
	if($localOrdem == ''){					$localOrdem = "Nome";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}
	
	LimitVisualizacao("filtro");
	
	$localQTDCaracterColunaPessoa	=	$_SESSION["filtro_QTDCaracterColunaPessoa"];
	
	if($localTipoDado == ''){				$localTipoDado = 'text';	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_contrato_tipo_vigencia.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<input type='hidden' name='IdServico'				value='<?=$localIdServico?>' />
			<input type='hidden' name='IdContrato'				value='' />
			<input type='hidden' name='Local'					value='Contrato' />
			<table>
				<tr>
					<td>Nome Pessoa/Razão Social</td>
					<td>Nome Serviço</td>
					<td>Tipo Vigência</td>
					<td>Data Início</td>
					<td>Data Término</td>
					<td>Qtd.</td>
					<td />
					<td />
				</tr>
				<tr>
					<td><input type='text' value="<?=$localPessoa?>" name='filtro_pessoa' style='width:190px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)" onkeyup="busca_pessoa_aproximada(this,event);" /></td>
					<td><input type='text' value='<?=$localDescricaoServico?>' name='filtro_descricao_servico' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td>
						<select name='filtro_tipo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)' style='width:110px'>
							<option value=''>Todos</option>
							<?
								$sql = "select IdContratoTipoVigencia, DescricaoContratoTipoVigencia from ContratoTipoVigencia where IdLoja=$local_IdLoja order by DescricaoContratoTipoVigencia ASC";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									$lin[DescricaoContratoTipoVigencia] = url_string_xsl($lin[DescricaoContratoTipoVigencia],'convert');
									echo "<option value='$lin[IdContratoTipoVigencia]' ".compara($localIdContratoTipoVigencia,$lin[IdContratoTipoVigencia],"selected='selected'","").">$lin[DescricaoContratoTipoVigencia]</option>";
								}
							?>
						</select>
					</td>
					<td><input type='text' value='<?=$localDataInicio?>' name='filtro_data_inicio' style='width:80px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')"  maxlength='10' onKeyDown="listar(event)"/></td>
					<td><input type='text' value='<?=$localDataTermino?>' name='filtro_data_termino' style='width:80px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" maxlength='10' onKeyDown="listar(event)"/></td>
					<td><input type='text' value='<?=$Limit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)" /></td>
					<td><input type='submit' value='Buscar'  class='botao' /></td>
					<td><img onmousemove="quadro_alt(event, this, 'Menu Avançado')" src='../../img/estrutura_sistema/seta_baixo.gif' style='cursor:pointer' onClick="consulta_rapida('bottom');atualizar_campo();" alt='Parâmetros de Consulta Rápida' /></td>
					<td  style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_contrato_opcoes.php'>Outros<BR />Relatórios</a></td>
				</tr>	
				<tr>
					<td colspan='8'>
						<div id='filtroRapido' style='width:100%; display:none; background-color: #0065D5'>
							<table style='width:100%;'>
								<tr>
									<td><?=dicionario(222)?></td>
									<td>
										<input type='text' name='filtro_id_servico' value='<?=$localIdServico?>' style='width:70px' maxlength='11' onChange='filtro_buscar_servico(this.value,document.filtro.Local.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"/><input type='text' class='agrupador' name='filtro_descricao_id_servico' value='' style='width:369px' readOnly="readOnly" />
									</td>
								</tr>
								<tr>
									<td style='width:300px'>Qtd carácter na coluna Nome Pessoa/Razão Social</td>
									<td><input type='text' value='<?=$localQTDCaracterColunaPessoa?>' name='filtro_QTDCaracterColunaPessoa' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)" onkeypress="mascara(this,event,'int')" onChange="atualizaSessao(this.name, (this.value == '' ? 0 : this.value));" /></td>
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
			echo menu_acesso_rapido(2);
		?>
	</div>
	<script>
		function atualizar_campo(){
			if(document.filtro.IdServico.value != ''){
				filtro_buscar_servico(document.filtro.IdServico.value,document.filtro.Local.value);
			}
		}
		enterAsTab(document.forms.filtro);
	</script>
