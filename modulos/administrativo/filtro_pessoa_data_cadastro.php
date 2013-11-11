<?
	$local_Login				= $_SESSION["Login"];
	$local_IdPessoaLogin		= $_SESSION["IdPessoa"];
	
	if($localOrdem == ''){							$localOrdem = "Nome";		}
	if($localOrdemDirecao == ''){					$localOrdemDirecao = getCodigoInterno(7,6);	}
	if($localTipoDado == ''){						$localTipoDado = 'text';	}
	
	LimitVisualizacao("filtro");
	
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_pessoa_data_cadastro.php' target='conteudo'>
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
					<td><?=dicionario(184)?></td>
					<td><?=dicionario(185)?></td>
					<td><?=dicionario(152)?></td>
					<td />
					<td />
				</tr>
				<tr>
					<td><input type='text' value="<?=$localNome?>" name='filtro_nome' style='width:190px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)' onkeyup='busca_pessoa_aproximada(this,event);' /></td>
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
						<select name='filtro_grupo_pessoa' style='width:110px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown="listar(event)">
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
					<td><input type='text' name='filtro_data_inicio' value='<?=$localDataInicio?>' style='width:130px' maxlength='10' onkeypress="mascara(this,event,'date')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" /></td>
					<td><input type='text' name='filtro_data_termino' value='<?=$localDataTermino?>' style='width:130px' maxlength='10' onkeypress="mascara(this,event,'date')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" /></td>
					<td><input type='text' 	name='filtro_limit' value='<?=$localLimit?>' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)"/></td>
					<td><input class='botao' type='submit' value='Buscar' /></td>
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
