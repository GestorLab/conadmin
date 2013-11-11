<?
	if($localOrdem == ''){
		$localOrdem = "DataHora";
	}
	
	if($localOrdemDirecao == ''){
		$localOrdemDirecao = getCodigoInterno(7,6);
	}
	
	LimitVisualizacao("filtro");
	
	if($localTipoDado == ''){
		$localTipoDado = 'number';
	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_log_acesso.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table>
				<tr>
					<td>Login</td>
					<td>Nome Usuário</td>
					<td>Data Acesso</td>
					<td>Navegador</td>
					<td>Encerrada</td>
					<td>Qtd.</td>
					<td />
				</tr>
				<tr>
					<td>
						<select name='filtro_login' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)">
							<option value=''>Todos</option>
							<?
								$sql = "select Usuario.Login, Pessoa.Nome, concat('[',Login,']',Nome) Usuario from Usuario,Pessoa where Pessoa.IdPessoa = Usuario.IdPessoa order by Login";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									$lin[Login] = url_string_xsl($lin[Login],'convert');
									echo "<option value='$lin[Login]' ".compara($localLogin,$lin[Login],"selected='selected'","").">$lin[Login]</option>";
								}
							?>
						</select>
					</td>
					<td><input type='text' value="<?=$localNome?>" name='filtro_nome' maxlength='10' style='width:200px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)" onKeyUp="busca_pessoa_aproximada(this,event)" autocomplete='off'/></td>								
					<td><input type='text' value='<?=$localData?>' name='filtro_data' maxlength='10' style='width:90px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onKeyDown="listar(event)"/></td>								
					<td>
						<select name='filtro_navegador' onFocus="Foco(this,'in')"  style='width:180px' onBlur="Foco(this,'out')" onKeyDown="listar(event)">
							<option value=''>Todos</option>
							<?
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 89 order by ValorParametroSistema";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
									echo "<option value='$lin[IdParametroSistema]' ".compara($localNavegador,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
							?>
						</select>
					</td>
					<td>
						<select name='filtro_fechada' onFocus="Foco(this,'in')"  style='width:100px' onBlur="Foco(this,'out')" onKeyDown="listar(event)">
							<option value=''>Todos</option>
							<?
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 109 order by ValorParametroSistema";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
									echo "<option value='$lin[IdParametroSistema]' ".compara($localFechada,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
							?>
						</select>
					</td>
					<td><input type='text' value='<?=$Limit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='Buscar'  class='botao' /></td>
				</tr>	
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?
			echo menu_acesso_rapido(6);
		?>
	</div>
	<script type="text/javascript">
		enterAsTab(document.forms.filtro);
	</script>