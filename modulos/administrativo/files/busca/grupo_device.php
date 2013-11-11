<!-- Janela Modal -->
			<div id="dialog" class="quadroFlutuante" style='width:365px; height: 295px;'>
				<!--<a href="#" class="close">Fechar [X]</a>-->
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Grupo Device</td>
						<td class='fecha close'>X</div></td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='formularioGrupoDevice' method='post'>
						<table>
							<tr>
								<td class='descCampo'>Nome Grupo Device</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' id="descricao" name='DescricaoGrupoDevice' autocomplete="off" value='' style='width:345px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<!--<div id="dados" style="position: static;"></div>-->
					<div class='listaDados' style='height: 180px;'>
						<div id='listaDadosQuadroGrupoDevice'>&nbsp;</div>
					</div>
					<form id="formDialog" name='BuscaGrupoDevice' method='post'>
						<input type='hidden' name='IdGrupoDevice' value=''>
						<table class='listaDados'>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<!--<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaGrupoDevice', false);" class='botao'>-->
									<input type='button' id="cancelarQuadroFlutuante" value='Cancelar' class='botao'>
								</td>
							</tr>
						</table>
					</form>	
					</div>
			</div>
			<!-- Máscara para cobrir a tela -->
			<div id="mask"></div>