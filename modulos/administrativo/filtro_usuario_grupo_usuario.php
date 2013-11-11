<?
	if($localOrdem == ''){						$localOrdem = "Login";		}
	if($localOrdemDirecao == ''){				$localOrdemDirecao = getCodigoInterno(7,6);	}
	if($Limit == '' && $localFiltro == ''){		$Limit = getCodigoInterno(7,5);	}
	if($localTipoDado == ''){					$localTipoDado = 'text';	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_usuario_grupo_usuario.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table>
				<tr>
					<td>Login</td>
					<td>Nome Usuário</td>
					<td>E-mail</td>
					<td>Nome Grupo Usuário</td>
					<td>Qtd.</td>
					<td />
				</tr>
				<tr>
					<td><input type='text' value='<?=$localLogin?>' name='filtro_login' size='15' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'/></td>
					<td><input type='text' value="<?=$localNome?>" name='filtro_nome' size='23' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)' onKeyUp="busca_pessoa_aproximada(this,event)" /></td>
					<td><input type='text' value='<?=$localEmail?>' name='filtro_email' size='23' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'/></td>
					<td><input type='text' value='<?=$localGrupoUsuario?>' name='filtro_grupo_usuario' size='23' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'/></td>
					<td><input type='text' value='<?=$Limit?>' name='filtro_limit' style='width:34px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyPress="mascara(this,event,'int')" onKeyDown="listar(event)"/></td>
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
	
	<script>
		enterAsTab(document.forms.filtro);
	</script>
