<?
	$local_Login				= $_SESSION["Login"];
	$local_IdPessoaLogin		= $_SESSION["IdPessoa"];
	
	if($localOrdem == ''){							$localOrdem = "Nome";		}
	if($localOrdemDirecao == ''){					$localOrdemDirecao = getCodigoInterno(7,6);	}
	
	LimitVisualizacao("filtro");
	
	if($localTipoDado == ''){						$localTipoDado = 'text';	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_pessoa_com_contrato.php' target='conteudo'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<input type='hidden' name='acesso'					value='<?=$acesso?>' />
			<table>
				<tr>
					<td><?=dicionario(148)?></td>
					<td><?=dicionario(112)?></td>
					<td><?=dicionario(149)?></td>
					<td><?=dicionario(150)?></td>
					<td><?=dicionario(151)?></td>
					<td><?=dicionario(152)?></td>
					<td />
					<td />
				</tr>
				<tr>
					<td><input type='text' value="<?=$localNome?>" name='filtro_nome' style='width:200px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)' onkeyup='busca_pessoa_aproximada(this,event);' /></td>
					<td>
						<select name='filtro_tipo_pessoa' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown="listar(event)">
							<option value=''><?=dicionario(153)?></option>
							<?
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=1 order by ValorParametroSistema";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
									echo "<option value='$lin[IdParametroSistema]' ".compara($localTipoPessoa,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
							?>
						</select>
					</td>
					<td>
						<select name='filtro_grupo_pessoa' style='width:125px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown="listar(event)">
							<option value=''><?=dicionario(153)?></option>
							<?
								
								$where = "";
								
								if($_SESSION["RestringirAgenteAutorizado"] == true){
									$sqlAgente	=	"select 
														AgenteAutorizado.IdGrupoPessoa 
													from 
														AgenteAutorizado
													where 
														AgenteAutorizado.IdLoja = $local_IdLoja  and 
														AgenteAutorizado.IdAgenteAutorizado = '$local_IdPessoaLogin' and 
														AgenteAutorizado.Restringir = 1 and 
														AgenteAutorizado.IdStatus = 1 and
														AgenteAutorizado.IdGrupoPessoa is not null";
									$resAgente	=	@mysql_query($sqlAgente,$con);
									while($linAgente	=	@mysql_fetch_array($resAgente)){
										$where    .=	" and IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
									}
								}
								if($_SESSION["RestringirAgenteCarteira"] == true){
									$sqlAgente	=	"select 
														AgenteAutorizado.IdGrupoPessoa 
													from 
														AgenteAutorizado,
														Carteira
													where 
														AgenteAutorizado.IdLoja = $local_IdLoja  and 
														AgenteAutorizado.IdLoja = Carteira.IdLoja and
														AgenteAutorizado.IdAgenteAutorizado = Carteira.IdAgenteAutorizado and
														Carteira.IdCarteira = '$local_IdPessoaLogin' and 
														AgenteAutorizado.Restringir = 1 and 
														AgenteAutorizado.IdStatus = 1 and 
														AgenteAutorizado.IdGrupoPessoa is not null";
									$resAgente	=	@mysql_query($sqlAgente,$con);
									while($linAgente	=	@mysql_fetch_array($resAgente)){
										$where    .=	" and IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
									}
								}
								
								$sql = "select IdGrupoPessoa, DescricaoGrupoPessoa from GrupoPessoa where 1 $where order by DescricaoGrupoPessoa ASC";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									$lin[DescricaoGrupoPessoa] = url_string_xsl($lin[DescricaoGrupoPessoa],'convert');
									echo "<option value='$lin[IdGrupoPessoa]' ".compara($localGrupoPessoa,$lin[IdGrupoPessoa],"selected='selected'","").">$lin[DescricaoGrupoPessoa]</option>";
								}
							?>
						</select>
					</td>
					<td>
						<select name='filtro_campo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:130px'  onKeyDown="listar(event)">
							<option value=''><?=dicionario(153)?></option>
							<option value='CPF_CNPJ' <?=compara($localCampo,"CPF_CNPJ","selected='selected'","")?>><?=dicionario(154)?></option>
							<option value='Endereco' <?=compara($localCampo,"Endereco","selected='selected'","")?>><?=dicionario(155)?></option>
							<option value='CEP' <?=compara($localCampo,"CEP","selected='selected'","")?>><?=dicionario(156)?></option>
							<option value='Email' <?=compara($localCampo,"Email","selected='selected'","")?>><?=dicionario(104)?></option>
							<option value='Estado' <?=compara($localCampo,"Estado","selected='selected'","")?>><?=dicionario(157)?></option>
							<option value='Cidade' <?=compara($localCampo,"Cidade","selected='selected'","")?>><?=dicionario(186)?></option>
							<option value='Bairro' <?=compara($localCampo,"Bairro","selected='selected'","")?>><?=dicionario(160)?></option>
							<option value='Fone' <?=compara($localCampo,"Fone","selected='selected'","")?>><?=dicionario(158)?></option>
							<option value='NomeRepresentante' <?=compara($localCampo,"NomeRepresentante","selected='selected'","")?>><?=dicionario(94)?></option>
							<option value='Obs' <?=compara($localCampo,"Obs","selected='selected'","")?>><?=dicionario(159)?></option>
						</select>
					</td>
					<td><input type='text' name='filtro_valor' value='<?=$localValor?>' style='width:100px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" /></td>
					<td><input type='text' 	name='filtro_limit' value='<?=$localLimit?>' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)"/></td>
					<td><input class='botao' type='submit' value='<?=dicionario(166)?>' /></td>
					<td  style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_pessoa_opcoes.php'><?=dicionario(120)?><BR /><?=dicionario(162)?></a></td>
				</tr>
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?
			echo menu_acesso_rapido(1);
		?>
	</div>
	<script>
		enterAsTab(document.forms.filtro);
	</script>
