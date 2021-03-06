			<div id='quadroBuscaArquivoRetornoTipo' style='width:365px;' class='quadroFlutuante'>
				<!--div class='tit'>Busca Arquivo de Retorno Tipo<div class='fecha' onClick="vi_id('quadroBuscaArquivoRetornoTipo', false);">X</div></div-->
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Arquivo de Retorno Tipo</td>
						<td class='fecha' onClick="vi_id('quadroBuscaArquivoRetornoTipo', false);">X</div></td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='formularioArquivoRetornoTipo' method='post'>
						<table>
							<tr>
								<td class='descCampo'>Nome Arquivo de Retorno Tipo</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='Nome' autocomplete="off" value='' style='width:330px' maxlength='100' onkeyup="busca_arquivo_retorno_tipo_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 180px;'>
						<div id='listaDadosQuadroArquivoRetornoTipo'>&nbsp;</div>
					</div>
					<form name='BuscaArquivoRetornoTipo' method='post' onSubmit='return validar_busca_arquivo_retorno_tipo()'>
						<input type='hidden' name='IdArquivoRetornoTipo' value=''>
						<table class='listaDados'>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaArquivoRetornoTipo', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>
					<script>
						var valorCampoArquivoRetornoTipo = '';
						function validar_busca_arquivo_retorno_tipo(){
							if(valorCampoArquivoRetornoTipo !=''){
								busca_arquivo_retorno_tipo('',valorCampoArquivoRetornoTipo);
							}
							return false;
						}
						function busca_arquivo_retorno_tipo_lista(){
							var Nome 		= document.formularioArquivoRetornoTipo.Nome.value;
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
						    	url = "../administrativo/xml/arquivo_retorno_tipo.php?Limit="+Limit;
							}else{
								url = "../administrativo/xml/arquivo_retorno_tipo.php?Nome="+Nome;
							}
							xmlhttp.open("GET", url,true);
							
							xmlhttp.onreadystatechange = function(){ 
					
								// Carregando...
								carregando(true);
					
								if(xmlhttp.readyState == 4){ 
									if(xmlhttp.status == 200){
										if(xmlhttp.responseText == 'false'){
											document.getElementById('listaDadosQuadroArquivoRetornoTipo').innerHTML = "";
											// Fim de Carregando
											carregando(false);
										}else{
											var dados = "<table id='listaDados' style='width: 343px'>\n<tr>\n<td class='listaDados_titulo' style='width: 80px'>Arq. Ret. Tipo</td>\n<td class='listaDados_titulo'>Nome Arquivo Retorno Tipo</td>\n</tr>";
											for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdArquivoRetornoTipo").length; i++){
													
												nameNode = xmlhttp.responseXML.getElementsByTagName("IdArquivoRetornoTipo")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var IdArquivoRetornoTipo = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoArquivoRetornoTipo")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var DescricaoArquivoRetornoTipo = nameTextNode.nodeValue;
												
												dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdArquivoRetornoTipo+"' onClick='aciona(this,"+IdArquivoRetornoTipo+")'>";
												dados += 	"\n<td>"+IdArquivoRetornoTipo+"</td><td>"+DescricaoArquivoRetornoTipo+"</td>";
												dados += "\n</tr>";
											}
											dados += "\n</table>";
											document.getElementById('listaDadosQuadroArquivoRetornoTipo').innerHTML = dados;
										}
									}
								} 
								// Fim de Carregando
								carregando(false);
							}
							xmlhttp.send(null);
						}
						function aciona(campo,valor){
							if(valorCampoArquivoRetornoTipo!=''){
								document.getElementById('listaDados_td_'+valorCampoArquivoRetornoTipo).style.backgroundColor = "#FFFFFF";
							}
							if(valorCampoArquivoRetornoTipo == valor){
								busca_arquivo_retorno_tipo('',valor);
							}
							valorCampoArquivoRetornoTipo = valor;
							document.BuscaArquivoRetornoTipo.IdArquivoRetornoTipo.value = valorCampoArquivoRetornoTipo;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}
						enterAsTab(document.forms.formularioArquivoRetornoTipo);	
					</script>
				</div>
			</div>
