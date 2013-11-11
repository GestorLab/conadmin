			<div id='quadroBuscaServicoGrupo' style='width:365px;' class='quadroFlutuante'>	
				<!--div class='tit'>Busca Grupo Serviço<div class='fecha' onClick="vi_id('quadroBuscaServicoGrupo', false);">X</div></div-->
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Grupo Serviço</td>
						<td class='fecha' onClick="vi_id('quadroBuscaServicoGrupo', false);">X</div></td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='formularioServicoGrupo' method='post'>
						<table>
							<tr>
								<td class='descCampo'>Nome Grupo Serviço</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='DescricaoServicoGrupo' value='' autocomplete="off" style='width:334px' maxlength='50' onkeyup="busca_servico_grupo_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 180px;'>
						<div id='listaDadosQuadroServicoGrupo'>&nbsp;</div>
					</div>
					<form name='BuscaServicoGrupo' method='post' onSubmit='return validar_servico_grupo()'>
						<input type='hidden' name='IdServicoGrupo' value=''>
						<table class='listaDados'>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' class='botao' onClick="vi_id('quadroBuscaServicoGrupo', false);">
								</td>
							</tr>
						</table>
					</form>	
					<script>
						// Para que os quadros flutuem
						new Draggable('quadroBuscaServicoGrupo');
						
						var valorCampoServicoGrupo = '';
						function validar_servico_grupo(){
							if(valorCampoServicoGrupo !=''){
								busca_servico_grupo(valorCampoServicoGrupo);
							}
							return false;
						}
						function busca_servico_grupo_lista(){
							var DescricaoServicoGrupo = document.formularioServicoGrupo.DescricaoServicoGrupo.value;
							var Limit	 			  = <?=getCodigoInterno(7,4)?>;
							var nameNode, nameTextNode, url;
							
							var xmlhttp   = false;
							if (window.XMLHttpRequest) { // Mozilla, Safari,...
						    	xmlhttp = new XMLHttpRequest();
						        if(xmlhttp.overrideMimeType){
						        	xmlhttp.overrideMimeType('text/xml');
								}
							}else if (window.ActiveXObject){ // IE
								try{
									xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
								}catch(e){
									try{
										xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
						            } catch (e) {}
						        }
						    }
						    if(DescricaoServicoGrupo == ''){
						    	url = "xml/servico_grupo.php?Limit="+Limit;
							}else{
								url = "xml/servico_grupo.php?DescricaoServicoGrupo="+DescricaoServicoGrupo;
							}
							xmlhttp.open("GET", url,true);
							
							xmlhttp.onreadystatechange = function(){ 
					
								// Carregando...
								carregando(true);
					
								if(xmlhttp.readyState == 4){ 
									if(xmlhttp.status == 200){
										if(xmlhttp.responseText == 'false'){
											document.getElementById('listaDadosQuadroServicoGrupo').innerHTML = "";
											// Fim de Carregando
											carregando(false);
										}else{
											var dados = "<table id='listaDados' style='width: 343px'>\n<tr>\n<td class='listaDados_titulo' style='width: 80px'>Grupo Serv.</td>\n<td class='listaDados_titulo'>Nome Grupo Serviço</td>\n</tr>";
											for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdServicoGrupo").length; i++){
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("IdServicoGrupo")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var IdServicoGrupo = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServicoGrupo")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var DescricaoServicoGrupo = verifica_dado(nameTextNode.nodeValue);
												
												dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdServicoGrupo+"' onClick='aciona(this,"+IdServicoGrupo+")'>";
												dados += 	"\n<td>"+IdServicoGrupo+"</td>\n<td>"+DescricaoServicoGrupo.substr(0,30)+"</td>";
												dados += "\n</tr>";
											}
											dados += "\n</table>";
											document.getElementById('listaDadosQuadroServicoGrupo').innerHTML = dados;
										}
									}
								} 
								// Fim de Carregando
								carregando(false);
							}
							xmlhttp.send(null);
						}
						function aciona(campo,valor){
							if(valorCampoServicoGrupo!=''){
								document.getElementById('listaDados_td_'+valorCampoServicoGrupo).style.backgroundColor = "#FFFFFF";
							}
							if(valorCampoServicoGrupo == valor){
								busca_servico_grupo(valor);
							}
							valorCampoServicoGrupo = valor;
							document.formulario.IdServicoGrupo.value = valorCampoServicoGrupo;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}
						enterAsTab(document.forms.formularioServicoGrupo);
					</script>
				</div>
		    </div>
