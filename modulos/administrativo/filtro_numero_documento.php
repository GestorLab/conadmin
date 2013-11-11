<?
	if($localOrdem == ''){							$localOrdem = "NumeroDocumento";		}
	if($localOrdemDirecao == ''){					$localOrdemDirecao = getCodigoInterno(7,6);	}
	
	LimitVisualizacao("filtro");
	
	if($localTipoDado == ''){						$localTipoDado = 'number';	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_numero_documento.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table>	
				<tr>
					<td>Nome Pessoa/Razão Social</td>
					<td>Nº Documento</td>
					<td>Nº Contas a Receber</td>
					<td>Local Cob.</td>
					<td>Usuário Cadastro</td>
					<td>Qtd.</td>
					<td />
				</tr>
				<tr>
					<td><input type='text' value="<?=$localNome?>" name='filtro_nome' style='width:210px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)' onkeyup="busca_pessoa_aproximada(this,event);" /></td>
					<td><input type='text' name='filtro_numero_documento' value='<?=$localNumeroDocumento?>' style='width:120px' maxlength='11' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'int')" onKeyDown="listar(event);" /></td>
					<td><input type='text' name='filtro_conta_receber' value='<?=$localIdContaReceber?>' style='width:120px' maxlength='11' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'int')" onKeyDown="listar(event);" /></td>
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
					<td>
						<select name='filtro_usuario_cadastro' style='width:120px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown='listar(event)'>
							<option value=''>Todos</option>
							<?
								$sql = "select Login from Usuario where Login != 'cda' and Login != 'root' and Login != 'automatico' order by Login;";
								$res = @mysql_query($sql,$con);
								while($lin = @mysql_fetch_array($res)){
									echo "<option value='$lin[Login]' ".compara($localUsuarioCadastro,$lin[Login],"selected='selected'","").">$lin[Login]</option>";
								}
							?>
						</select>
					</td>
					<td><input type='text' value='<?=$localLimit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='Buscar' class='botao'/></td>
					<td><img src='../../img/estrutura_sistema/seta_baixo.gif' style='cursor:pointer' onClick="consulta_rapida('bottom')" alt='Parâmetros de Consulta Rápida' /></td>
				</tr>
				<tr>
					<td colspan='9'>
						<div id='filtroRapido' style='width:100%; display:none; background-color: #0065D5'>
							<table style='width:100%;'>
								<tr>
									<td style='width:160px;'>Data/Hora Cadastro Início</td>
									<td><input type='text' value='<?=$localDataCadastroInicio?>' name='filtro_data_cadastro_inicio' style='width:140px' maxlength='19' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'dateHora')" onKeyDown='listar(event)'/></td>
								</tr>
								<tr>
									<td>Data/Hora Cadastro Fim</td>
									<td><input type='text' value='<?=$localDataCadastroFim?>' name='filtro_data_cadastro_fim' style='width:140px' maxlength='19' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'dateHora')" onKeyDown='listar(event)'/></td>
								</tr>
							</table>
							<div style='width:100%; text-align:right; margin:0;'>
								<img src='../../img/estrutura_sistema/seta_cima.gif' style='cursor:pointer' onClick="consulta_rapida('top')" alt='Parâmetros de Consulta Rápida [Fechar]' />
							</div>
						</div>
					</td>
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
