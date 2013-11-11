			<div id='quadroBuscaMalaDiretaEnviada' style='width:665px;' class='quadroFlutuante'>
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Mala Direta</td>
						<td class='fecha' onClick="vi_id('quadroBuscaMalaDiretaEnviada', false);">X</div></td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='formularioMalaDiretaEnviada' method='post'>
						<table>
							<tr>
								<td class='descCampo'>Descrição</td>
								<td class='descCampo'>Tipo Mensagem</td>
								<td class='descCampo'>Status</td>
							</tr>
							<tr>
								<td class='campo'>	
									<input type='text' name='Descricao' autocomplete="off" value='' style='width:407px' maxlength='100' onkeyup="busca_mala_direta_enviada_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'>
								</td>
								<td class='campo'>	
									<input type='text' name='IdTipoMensagem' autocomplete="off" value='' style='width:90px' maxlength='7' onkeyup="busca_mala_direta_enviada_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'int')" tabindex='2'>
								</td>
								<td  class='campo'>
									<select name='IdStatus' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:125px' tabindex='3' disabled>
										<option value=''>Enviado</option>									
									</select>								
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 180px;'>
						<div id='listaDadosQuadroMalaDiretaEnviada'>&nbsp;</div>
					</div>
					<form name='BuscaMalaDiretaEnviada' method='post' onSubmit='return validar_busca_mala_direta_enviada()'>
						<input type='hidden' name='IdMalaDiretaEnviada' value=''>
						<table class='listaDados'>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaMalaDiretaEnviada', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>
					<script>
						new Draggable('quadroBuscaMalaDiretaEnviada');
						
						var valorCampoMalaDiretaEnviada = '';
						
						function validar_busca_mala_direta_enviada() {
							if(valorCampoMalaDiretaEnviada != '') {
								busca_mala_direta_enviada(valorCampoMalaDiretaEnviada);	
							}
							
							return false;
						}
						function busca_mala_direta_enviada_lista() {
							var Descricao = document.formularioMalaDiretaEnviada.Descricao.value;
							var IdTipoMensagem = document.formularioMalaDiretaEnviada.IdTipoMensagem.value;
							var Limit = <?=getCodigoInterno(7,4)?>;
						    var url = "./xml/mala_direta.php?IdStatus=&Descricao=" + Descricao + "&IdTipoMensagem=" + IdTipoMensagem + "&Limit=" + Limit;
							
							call_ajax(url, function (xmlhttp) {
								var nameNode, nameTextNode;
								
								if(xmlhttp.responseText == 'false') {
									document.getElementById('listaDadosQuadroMalaDiretaEnviada').innerHTML = "";
								} else {
									var dados = "<table id='listaDados' style='width: 642px'>\n<tr>\n<td class='listaDados_titulo' style='width: 70px'>Mala Direta</td>\n<td class='listaDados_titulo'>Descrição</td>\n<td class='listaDados_titulo' style='width: 90px'>Tipo Mensagem</td>\n<td class='listaDados_titulo' style='width: 80px;'>Status</td>\n</tr>";
									
									for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdMalaDireta").length; i++) {
										nameNode = xmlhttp.responseXML.getElementsByTagName("IdMalaDireta")[i]; 
										nameTextNode = nameNode.childNodes[0];
										var IdMalaDireta = nameTextNode.nodeValue;
										
										nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoMalaDireta")[i]; 
										nameTextNode = nameNode.childNodes[0];
										var DescricaoMalaDireta = verifica_dado(nameTextNode.nodeValue);
										
										nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoMensagem")[i]; 
										nameTextNode = nameNode.childNodes[0];
										var IdTipoMensagem = verifica_dado(nameTextNode.nodeValue);
										
										nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[i]; 
										nameTextNode = nameNode.childNodes[0];
										var Status = nameTextNode.nodeValue;
										
										var Anexa = true;
										
										if(document.formulario.Local.value == "MalaDireta"){
											if(document.formulario.IdMalaDireta.value == IdMalaDireta){
												Anexa = false;
											}
										}
										
										if(Anexa){
											dados += "\n<tr style='background-color:#FFF' id='listaDados_td_" + IdMalaDireta + "' onClick='aciona_busca_mala_direta(this," + IdMalaDireta + ")'>";
											dados += 	"\n<td>" + IdMalaDireta + "</td><td>" + DescricaoMalaDireta + "</td><td>" + IdTipoMensagem + "</td><td>" + Status + "</td>";
											dados += "\n</tr>";
										}
									}
									
									dados += "\n</table>";
									document.getElementById('listaDadosQuadroMalaDiretaEnviada').innerHTML = dados;
								}
							});
						}
						function aciona_busca_mala_direta(campo, valor) {
							if(valorCampoMalaDiretaEnviada != '') {
								document.getElementById('listaDados_td_' + valorCampoMalaDiretaEnviada).style.backgroundColor = "#FFFFFF";
							}
							
							if(valorCampoMalaDiretaEnviada == valor) {
								busca_mala_direta_enviada(valor, true);
							}
							
							valorCampoMalaDiretaEnviada = valor;
							document.BuscaProcessoFinanceiro.IdProcessoFinanceiro.value = valorCampoMalaDiretaEnviada;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}
						function limpa_form_mala_direta_enviada() {
							document.formularioMalaDiretaEnviada.Descricao.value = "";
							document.formularioMalaDiretaEnviada.IdTipoMensagem.value = "";
							valorCampoMalaDiretaEnviada = '';
						}
						
						enterAsTab(document.forms.formularioMalaDiretaEnviada);
					</script>
				</div>
			</div>