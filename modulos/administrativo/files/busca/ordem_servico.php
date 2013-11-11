			<div id='quadroBuscaOrdemServico' style='width:778px;' class='quadroFlutuante'>
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Ordem Serviço</td>
						<td class='fecha' onClick="vi_id('quadroBuscaOrdemServico', false);">X</div></td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='formularioOrdemServico' method='post'>
						<table>
							<tr>
								<td class='descCampo'>Descrição</td>
								<td class='descCampo'>Tipo</td>
								<td class='descCampo'>Status</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='DescricaoOrdemServico' autocomplete="off" value='' style='width:441px' maxlength='100' onkeyup="busca_ordem_servico_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
								</td>
								<td class='campo'>
									<select name='TipoOrdemServico' style='width:120px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="busca_ordem_servico_lista()">
										<option value=''>Todos</option>
										<?
											$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=50 order by ValorParametroSistema";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
											}
										?>
									</select>
								</td>
								<td class='campo'>
									<select name='IdStatusOrdemServico' style='width:180px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="busca_ordem_servico_lista()">
										<option value=''>Todos</option>
										<?
											$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=40 order by ValorParametroSistema";
											$res = mysql_query($sql,$con);
											while($lin = mysql_fetch_array($res)){
												$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
												echo "<option value='$lin[IdParametroSistema]' ".compara($localIdStatus,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
											}
										?>
									</select>
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 245px;'>
						<div id='listaDadosQuadroOrdemServico'>&nbsp;</div>
					</div>
					<form name='BuscaOrdemServico' method='post' onSubmit='return validar_busca_ordem_servico()'>
						<input type='hidden' name='IdOrdemServico' value=''>
						<table class='listaDados'>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaOrdemServico',false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>
					<script type='text/javascript'>
						// Para que os quadros flutuem
						new Draggable('quadroBuscaOrdemServico');
						
						var valorCampoOrdemServico = '';
						
						function validar_busca_ordem_servico() {
							if(valorCampoOrdemServico != '') {
								busca_ordem_servico(valorCampoOrdemServico,'false',document.formulario.Local.value);
							}
							
							return false;
						}
						
						function busca_ordem_servico_lista() {
							var DescricaoOrdemServico = document.formularioOrdemServico.DescricaoOrdemServico.value;
							var TipoOrdemServico = document.formularioOrdemServico.TipoOrdemServico.value;
							var IdStatusOrdemServico = document.formularioOrdemServico.IdStatusOrdemServico.value;
							var Local = document.formulario.Local.value;
							var Limit = <?=getCodigoInterno(7,4)?>;
							var url = "xml/ordem_servico.php";
							
						    if(DescricaoOrdemServico == '' && TipoOrdemServico == '' && IdStatusOrdemServico=='') {
						    	url += "?Limit="+Limit;
							} else {
								url += "?DescricaoOS="+DescricaoOrdemServico+"&IdTipoOrdemServico="+TipoOrdemServico+"&IdStatus="+IdStatusOrdemServico;
							}
							
							if(Local == 'Protocolo') {
								var IdPessoa = document.formulario.IdPessoa.value;
								
								if(IdPessoa == "") {
									IdPessoa = document.formulario.IdPessoaF.value;
								}
								
								url	+= "&IdPessoa="+IdPessoa+"&IdContrato="+document.formulario.IdContrato.value+"&IdContaReceber="+document.formulario.IdContaReceber.value;
							}
							
							call_ajax(url, function (xmlhttp) {
								if(xmlhttp.responseText == 'false') {
									document.getElementById('listaDadosQuadroOrdemServico').innerHTML = "";
								} else {
									var nameNode, nameTextNode, dados = "<table id='listaDados' style='width:755px;'>\n<tr>\n<td class='listaDados_titulo' style='width: 70px'>OS</td>\n<td class='listaDados_titulo'>Descrição</td>\n<td class='listaDados_titulo' style='width:90px;'>Tipo</td>\n<td class='listaDados_titulo' style='width:80px;'>Valor (<?=getParametroSistema(5,1)?>)</td>\n<td class='listaDados_titulo' style='width:160px;'>Status</td>\n</tr>";
									
									for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdOrdemServico").length; i++) {
										nameNode = xmlhttp.responseXML.getElementsByTagName("IdOrdemServico")[i]; 
										nameTextNode = nameNode.childNodes[0];
										var IdOrdemServico = nameTextNode.nodeValue;
										
										nameNode = xmlhttp.responseXML.getElementsByTagName("DescTipoOrdemServico")[i]; 
										nameTextNode = nameNode.childNodes[0];
										var DescTipoOrdemServico = nameTextNode.nodeValue;
										
										nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoOS")[i]; 
										nameTextNode = nameNode.childNodes[0];
										var DescricaoOS = nameTextNode.nodeValue;
										
										nameNode = xmlhttp.responseXML.getElementsByTagName("ValorOutros")[i]; 
										nameTextNode = nameNode.childNodes[0];
										var ValorOutros = nameTextNode.nodeValue;
										
										nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
										nameTextNode = nameNode.childNodes[0];
										var Valor = Number(nameTextNode.nodeValue);
										
										nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[i]; 
										nameTextNode = nameNode.childNodes[0];
										var Status = nameTextNode.nodeValue;
										
										DescricaoOS = DescricaoOS.substring(0,50);
										
										Valor += Number(ValorOutros);
										
										dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdOrdemServico+"' onClick='aciona_busca_ordem_servico(this,"+IdOrdemServico+")'>";
										dados += 	"\n<td>"+IdOrdemServico+"</td><td>"+DescricaoOS+"</td><td>"+DescTipoOrdemServico+"</td><td>"+formata_float(Arredonda(Valor,2),2).replace(/\./i,",")+"</td><td>"+Status+"</td>";
										dados += "\n</tr>";
									}
									
									dados += "\n</table>";
									document.getElementById('listaDadosQuadroOrdemServico').innerHTML = dados;
								}
							});
						}
						
						function aciona_busca_ordem_servico(campo,valor) {
							if(valorCampoOrdemServico != '') {
								document.getElementById('listaDados_td_'+valorCampoOrdemServico).style.backgroundColor = "#FFFFFF";
							}
							
							if(valorCampoOrdemServico == valor) {
								busca_ordem_servico(valor,false,document.formulario.Local.value);
							}
							
							valorCampoOrdemServico = valor;
							document.BuscaOrdemServico.IdOrdemServico.value = valorCampoOrdemServico;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}
						
						function limpa_form_ordem_servico() {
							document.formularioOrdemServico.DescricaoOrdemServico.value = "";
							document.formularioOrdemServico.TipoOrdemServico.value = "";
							document.formularioOrdemServico.IdStatusOrdemServico.value = "";
							valorCampoOrdemServico = "";
						}
						
						enterAsTab(document.forms.formularioOrdemServico);
					</script>
				</div>
			</div>