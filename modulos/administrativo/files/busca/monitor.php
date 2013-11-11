			<div id='quadroBuscaMonitor' style='width:506px;' class='quadroFlutuante'>
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Grupo Pessoa</td>
						<td class='fecha' onClick="vi_id('quadroBuscaMonitor', false);">X</div></td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='formularioMonitor' method='post'>
						<table>
							<tr>
								<td class='descCampo'>Descrição do Monitor</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='DescricaoMonitor' autocomplete="off" style='width:371px' maxlength='100' onkeyup="busca_monitor_lista()" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='2'>
								</td>
								<td class='campo'>
									<select name='IdStatus' style='width:100px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onChange="busca_monitor_lista()">
										<option value=''>Todos</option>
										<?
											$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 232 order by ValorParametroSistema";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
											}
										?>
									</select>
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 180px;'>
						<div id='listaDadosQuadroMonitor'>&nbsp;</div>
					</div>
					<form name='BuscaMonitor' method='post' onSubmit='return validar_monitor()'>
						<input type='hidden' name='IdMonitor' value=''>
						<table class='listaDados'>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaMonitor', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>
					<script>
						// Para que os quadros flutuem
						new Draggable('quadroBuscaMonitor');	
						
						var valorCampoMonitor = '';
						
						function validar_monitor(){
							if(valorCampoMonitor != ''){
								busca_monitor(valorCampoMonitor);
							}
							
							return false;
						}
						function busca_monitor_lista(){
							var DescricaoMonitor = document.formularioMonitor.DescricaoMonitor.value;
							var IdStatus = document.formularioMonitor.IdStatus.value;
							var Limit = <?=getCodigoInterno(7,4)?>;
							var url = "./xml/monitor.php";
						    
						    if(DescricaoMonitor == '' && IdStatus == ''){
						    	url += "?Limit="+Limit;
							} else{
								url += "?DescricaoMonitor="+DescricaoMonitor+"&IdStatus="+IdStatus;
							}
							
							call_ajax(url,function (xmlhttp){
								alert(xmlhttp.responseText);
								if(xmlhttp.responseText == 'false'){
									document.getElementById('listaDadosQuadroMonitor').innerHTML = "";
								} else{
									var dados = "<table id='listaDados' style='width:483px'>\n<tr>\n<td class='listaDados_titulo' style='width:60px'>Monitor</td>\n<td class='listaDados_titulo'>Descrição do Monitor</td>\n<td class='listaDados_titulo' style='width:77px;'>Status</td>\n</tr>";
									
									for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdMonitor").length; i++){
										var nameNode = xmlhttp.responseXML.getElementsByTagName("IdMonitor")[i]; 
										var nameTextNode = nameNode.childNodes[0];
										var IdMonitor = nameTextNode.nodeValue;
										
										nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoMonitor")[i]; 
										nameTextNode = nameNode.childNodes[0];
										var DescricaoMonitor = nameTextNode.nodeValue;
										
										nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[i]; 
										nameTextNode = nameNode.childNodes[0];
										var Status = nameTextNode.nodeValue;
										
										dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdMonitor+"' onClick='aciona_monitor(this,"+IdMonitor+")'>";
										dados += 	"\n<td>"+IdMonitor+"</td><td>"+DescricaoMonitor+"</td><td>"+Status+"</td>";
										dados += "\n</tr>";
									}
									
									dados += "\n</table>";
									document.getElementById('listaDadosQuadroMonitor').innerHTML = dados;
								}
							});
						}
						function aciona_monitor(campo,valor){
							if(valorCampoMonitor != ''){
								document.getElementById('listaDados_td_'+valorCampoMonitor).style.backgroundColor = "#FFFFFF";
							}
							
							if(valorCampoMonitor == valor){
								busca_monitor(valor);
							}
							
							valorCampoMonitor = valor;
							document.BuscaMonitor.IdMonitor.value = valorCampoMonitor;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}
						function limpa_form_monitor(){
							document.formularioMonitor.DescricaoMonitor.value = "";
							document.formularioMonitor.IdStatus.value = "";
							
							valorCampoMonitor= "";
						}
						
						enterAsTab(document.forms.formularioMonitor);	
					</script>
				</div>
			</div>