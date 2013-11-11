<?
	if($localOrdem == ''){						$localOrdem = "Login";		}
	if($localOrdemDirecao == ''){				$localOrdemDirecao = getCodigoInterno(7,6);	}
	if($Limit == '' && $localFiltro == ''){		$Limit = getCodigoInterno(7,5);	}
	if($localTipoDado == ''){					$localTipoDado = 'text';	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_usuario_permissao.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table>
				<tr>
					<td>Login</td>
					<td>Nome</td>
					<td>E-mail</td>
					<td>Status</td>
					<td>Qtd.</td>
					<td />
				</tr>
				<tr>
					<td><input type='text' value='<?=$localLogin?>' name='filtro_login' size='20' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"/></td>
					<td><input type='text' value="<?=$localNome?>" name='filtro_nome' size='30' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyUp="busca_pessoa_aproximada(this,event)" /></td>
					<td><input type='text' value='<?=$localEmail?>' name='filtro_email' size='28' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"/></td>
					<td>
						<select name='filtro_status' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:120px'>
							<option value=''>Todos</option>
							<?
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=30 order by ValorParametroSistema";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									echo "<option value='$lin[IdParametroSistema]' ".compara($localStatus,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
							?>
						</select>
					</td>
					<td><input type='text' value='<?=$Limit?>' name='filtro_limit' size='1' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out'); listar()"/></td>
					<td><input type='submit' value='Buscar' class='botao' /></td>
				</tr>
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?
			echo menu_acesso_rapido(6);
		?>
	</div>
