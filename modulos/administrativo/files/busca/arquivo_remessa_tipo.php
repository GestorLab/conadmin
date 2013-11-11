			<div id='quadroBuscaArquivoRemessaTipo' style='width:365px;' class='quadroFlutuante'>
				<!--div class='tit'>Busca Arquivo de Remessa Tipo<div class='fecha' onClick="vi_id('quadroBuscaArquivoRemessaTipo', false);">X</div></div-->
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Arquivo de Remessa Tipo</td>
						<td class='fecha' onClick="vi_id('quadroBuscaArquivoRemessaTipo', false);">X</div></td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='formularioArquivoRemessaTipo' method='post'>
						<table>
							<tr>
								<td class='descCampo'>Nome Arquivo de Remessa Tipo</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='Nome' autocomplete="off" value='' style='width:334px' maxlength='100' onkeyup="busca_arquivo_remessa_tipo_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 180px;'>
						<div id='listaDadosQuadroArquivoRemessaTipo'>&nbsp;</div>
					</div>
					<form name='BuscaArquivoRemessaTipo' method='post' onSubmit='return validar_busca_arquivo_remessa_tipo()'>
						<input type='hidden' name='IdArquivoRemessaTipo' value=''>
						<table class='listaDados'>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaArquivoRemessaTipo', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>
					<script>
						// Para que os quadros flutuem
						new Draggable('quadroBuscaArquivoRemessaTipo');
						
						var valorCampoArquivoRemessaTipo = '';
						function validar_busca_arquivo_remessa_tipo(){
							if(valorCampoArquivoRemessaTipo !=''){
								busca_arquivo_remessa_tipo(valorCampoArquivoRemessaTipo);
							}
							return false;
						}
						function busca_arquivo_remessa_tipo_lista(){
							var Nome 		= document.formularioArquivoRemessaTipo.Nome.value;
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
						    	url = "../administrativo/xml/arquivo_remessa_tipo.php?Limit="+Limit;
							}else{
								url = "../administrativo/xml/arquivo_remessa_tipo.php?Nome="+Nome;
							}
							xmlhttp.open("GET", url,true);
							
							xmlhttp.onreadystatechange = function(){ 
					
								// Carregando...
								carregando(true);
					
								if(xmlhttp.readyState == 4){ 
									if(xmlhttp.status == 200){
										if(xmlhttp.responseText == 'false'){
											document.getElementById('listaDadosQuadroArquivoRemessaTipo').innerHTML = "";
											// Fim de Carregando
											carregando(false);
										}else{
											var dados = "<table id='listaDados' style='width: 343px'>\n<tr>\n<td class='listaDados_titulo' style='width: 70px'>Arq. Rem.</td>\n<td class='listaDados_titulo'>Nome Arquivo Remessa Tipo</td>\n</tr>";
											for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdArquivoRemessaTipo").length; i++){
													
												nameNode = xmlhttp.responseXML.getElementsByTagName("IdArquivoRemessaTipo")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var IdArquivoRemessaTipo = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoArquivoRemessaTipo")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var DescricaoArquivoRemessaTipo = nameTextNode.nodeValue;
												
												dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdArquivoRemessaTipo+"' onClick='aciona_busca_arquivo_remessa_tipo(this,"+IdArquivoRemessaTipo+")'>";
												dados += 	"\n<td>"+IdArquivoRemessaTipo+"</td><td>"+DescricaoArquivoRemessaTipo+"</td>";
												dados += "\n</tr>";
											}
											dados += "\n</table>";
											document.getElementById('listaDadosQuadroArquivoRemessaTipo').innerHTML = dados;
										}
									}
								} 
								// Fim de Carregando
								carregando(false);
							}
							xmlhttp.send(null);
						}
						function aciona_busca_arquivo_remessa_tipo(campo,valor){
							if(valorCampoArquivoRemessaTipo!=''){
								document.getElementById('listaDados_td_'+valorCampoArquivoRemessaTipo).style.backgroundColor = "#FFFFFF";
							}
							if(valorCampoArquivoRemessaTipo == valor){
								busca_arquivo_remessa_tipo(valorCampoArquivoRemessaTipo);
							}
							valorCampoArquivoRemessaTipo = valor;
							document.BuscaArquivoRemessaTipo.IdArquivoRemessaTipo.value = valorCampoArquivoRemessaTipo;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}
						enterAsTab(document.forms.formularioArquivoRemessaTipo);	
					</script>
				</div>
			</div>
