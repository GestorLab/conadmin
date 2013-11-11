<?
	if($localOrdem == ''){					$localOrdem = "Nome";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}
	
	LimitVisualizacao("filtro");
	
	if($localTipoDado == ''){				$localTipoDado = 'text';	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_conta_receber_mudar_status.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table>
				<tr>
					<td>Nome Pessoa/Razão Social</td>
					<td>Nome Serviço</td>
					<td>Parâmetro</td>
					<td>Valor Parâm.</td>
					<td>Status</td>
					<td>Qtd.</td>
					<td />
					<td />
				</tr>
				<tr>
					<td><input type='text' value="<?=$localPessoa?>" name='filtro_pessoa' style='width:160px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)" onkeyup="busca_pessoa_aproximada(this,event);" /></td>
					<td><input type='text' value='<?=$localDescricaoServico?>' name='filtro_descricao_servico' style='width:90px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td>
						<select name='filtro_parametro' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" style='width:150px' onKeyDown='listar(event)'>
							<option value=''>Todos</option>
							<?
								$sql = "select distinct DescricaoParametroServico from ServicoParametro where IdLoja = $local_IdLoja order by DescricaoParametroServico";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									$lin[DescricaoParametroServico] = substituir_string($lin[DescricaoParametroServico]);
									echo "<option value=\"$lin[DescricaoParametroServico]\" ".compara($localDescricaoParametroServico,$lin[DescricaoParametroServico],"selected='selected'","").">$lin[DescricaoParametroServico]</option>";
								}
							?>
						</select>
					</td>
					<td><input type='text' value='<?=$localValorParametroServico?>' name='filtro_valor_parametro' style='width:80px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)" /></td>
					<td>
						<select name='filtro_status' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" style='width:170px' onKeyDown='listar(event)'>
							<option value=''>Todos</option>
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
									
									echo "<option value='$id' ".compara($localIdStatus,$id,"selected='selected'","").">$value</option>";
								}

							?>
						</select>
					</td><td><input type='text' value='<?=$Limit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='Buscar'  class='botao' /></td>
					<td  style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_contrato_opcoes.php'>Outros<BR />Relatórios</a></td>
				</tr>	
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?
			echo menu_acesso_rapido(2);
		?>
	</div>
	<script type='text/javascript'>
		enterAsTab(document.forms.filtro);
	</script>
