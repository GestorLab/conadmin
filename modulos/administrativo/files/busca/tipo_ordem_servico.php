			<div id='quadroBuscaTipoOrdemServico' style='width:365px;' class='quadroFlutuante'>	
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Tipo Ordem Serviço</td>
						<td class='fecha' onClick="vi_id('quadroBuscaTipoOrdemServico', false);">X</div></td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='formularioTipoOrdemServico' method='post'>
						<table>
							<tr>
								<td class='descCampo'>Nome Tipo Ordem Serviço</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='Nome' autocomplete="off" value='' style='width:334px' maxlength='100' onkeyup="busca_tipo_ordem_servico_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 180px;'>
						<div id='listaDadosQuadroTipoOrdemServico'>&nbsp;</div>
					</div>
					<form name='BuscaTipoOrdemServico' method='post' onSubmit='return validar_busca_tipo_ordem_servico()'>
						<input type='hidden' name='IdTipoOrdemServico' value=''>
						<table class='listaDados'>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaTipoOrdemServico', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>
					<script>
						// Para que os quadros flutuem
						new Draggable('quadroBuscaTipoOrdemServico');
						
						var valorCampoTipoOrdemServico = '';
						function validar_busca_tipo_ordem_servico(){
							if(valorCampoTipoOrdemServico !=''){
								busca_tipo_ordem_servico(valorCampoTipoOrdemServico);
							}
							return false;
						}
						function busca_tipo_ordem_servico_lista(){
							var Nome 		= document.formularioTipoOrdemServico.Nome.value;
							var Limit	  	= <?=getCodigoInterno(7,4)?>;
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
						    
						    if(Nome == ''){
						    	url = "../administrativo/xml/tipo_ordem_servico.php?Limit="+Limit;
							}else{
								url = "../administrativo/xml/tipo_ordem_servico.php?DescricaoTipoOrdemServico="+Nome;
							}
							xmlhttp.open("GET", url,true);
							
							xmlhttp.onreadystatechange = function(){ 
					
								// Carregando...
								carregando(true);
					
								if(xmlhttp.readyState == 4){ 
									if(xmlhttp.status == 200){
										if(xmlhttp.responseText == 'false'){
											document.getElementById('listaDadosQuadroTipoOrdemServico').innerHTML = "";
											// Fim de Carregando
											carregando(false);
										}else{
											var dados = "<table id='listaDados' style='width: 343px'>\n<tr>\n<td class='listaDados_titulo' style='width: 70px'>Tipo OS</td>\n<td class='listaDados_titulo'>Nome Tipo Ordem Serviço</td>\n</tr>";
											for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdTipoOrdemServico").length; i++){
													
												nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoOrdemServico")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var IdTipoOrdemServico = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoTipoOrdemServico")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var DescricaoTipoOrdemServico = nameTextNode.nodeValue;
												
												dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdTipoOrdemServico+"' onClick='aciona_busca_tipo_ordem_servico(this,"+IdTipoOrdemServico+")'>";
												dados += 	"\n<td>"+IdTipoOrdemServico+"</td><td>"+DescricaoTipoOrdemServico+"</td>";
												dados += "\n</tr>";
											}
											dados += "\n</table>";
											document.getElementById('listaDadosQuadroTipoOrdemServico').innerHTML = dados;
										}
									}
								} 
								// Fim de Carregando
								carregando(false);
							}
							xmlhttp.send(null);
						}
						function aciona_busca_tipo_ordem_servico(campo,valor){
							if(valorCampoTipoOrdemServico!=''){
								document.getElementById('listaDados_td_'+valorCampoTipoOrdemServico).style.backgroundColor = "#FFFFFF";
							}
							if(valorCampoTipoOrdemServico == valor){
								busca_tipo_ordem_servico(valor);
							}
							valorCampoTipoOrdemServico = valor;
							document.BuscaTipoOrdemServico.IdTipoOrdemServico.value = valorCampoTipoOrdemServico;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}
						enterAsTab(document.forms.formularioTipoOrdemServico);
					</script>
				</div>
			</div>
