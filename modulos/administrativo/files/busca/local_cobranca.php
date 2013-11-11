			<div id='quadroBuscaLocalCobranca' style='width:365px;' class='quadroFlutuante'>
				<!--div id='tit'>Busca Local de Cobrança<div class='fecha' onClick="vi_id('quadroBuscaLocalCobranca', false);">X</div></div-->
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Local de Cobrança</td>
						<td class='fecha' onClick="vi_id('quadroBuscaLocalCobranca', false);">X</div></td>
					</tr>
				</table>
				<div id='filtro_busca'>
					<form name='formularioLocalCobranca' method='post'>
						<table>
							<tr>
								<td class='descCampo'>Nome Local de Cobrança</td>
								<td class='descCampo'>Abreviação</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='Nome' autocomplete="off" value='' style='width:206px' maxlength='100' onkeyup="busca_local_cobranca_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
								</td>
								<td class='campo'>
									<input type='text' name='Abreviacao' autocomplete="off" value='' style='width:120px' maxlength='6' onkeyup="busca_local_cobranca_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 180px;'>
						<div id='listaDadosQuadroLocalCobranca'>&nbsp;</div>
					</div>
					<form name='BuscaLocalCobranca' method='post' onSubmit='return validar_busca_local_cobranca()'>
						<input type='hidden' name='IdLocalCobranca' value=''>
						<table class='listaDados'>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaLocalCobranca', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>
					<script>
						// Para que os quadros flutuem
						new Draggable('quadroBuscaLocalCobranca');
						
						var valorCampoLocalCobranca = '';
						function validar_busca_local_cobranca(){
							if(valorCampoLocalCobranca !=''){
								busca_local_cobranca(valorCampoLocalCobranca);
							}
							return false;
						}
						function busca_local_cobranca_lista(){
							var Nome 		= document.formularioLocalCobranca.Nome.value;
							var Abreviacao	= document.formularioLocalCobranca.Abreviacao.value;
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
						    
						    if(Nome == '' && Abreviacao == ''){
						    	url = "../administrativo/xml/local_cobranca.php?Limit="+Limit;
							}else{
								url = "../administrativo/xml/local_cobranca.php?Nome="+Nome+"&Abreviacao="+Abreviacao;
							}
							
							xmlhttp.open("GET", url,true);
							
							xmlhttp.onreadystatechange = function(){ 
					
								// Carregando...
								carregando(true);
					
								if(xmlhttp.readyState == 4){ 
									if(xmlhttp.status == 200){
										if(xmlhttp.responseText == 'false'){
											document.getElementById('listaDadosQuadroLocalCobranca').innerHTML = "";
											// Fim de Carregando
											carregando(false);
										}else{
											var dados = "<table id='listaDados' style='width: 343px'>\n<tr>\n<td class='listaDados_titulo' style='width: 70px'>Local Cob.</td>\n<td class='listaDados_titulo'>Nome Local Cobrança</td>\n<td class='listaDados_titulo'>Abreviação</td>\n</tr>";
											for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca").length; i++){
													
												nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var IdLocalCobranca = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalCobranca")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var DescricaoLocalCobranca = nameTextNode.nodeValue;
												
												DescricaoLocalCobranca = DescricaoLocalCobranca.substr(0,50);
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("AbreviacaoNomeLocalCobranca")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var AbreviacaoNomeLocalCobranca = nameTextNode.nodeValue;
												
												dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdLocalCobranca+"' onClick='aciona_busca_local_cobranca(this,"+IdLocalCobranca+")'>";
												dados += 	"\n<td>"+IdLocalCobranca+"</td><td>"+DescricaoLocalCobranca+"</td><td>"+AbreviacaoNomeLocalCobranca+"</td>";
												dados += "\n</tr>";
											}
											dados += "\n</table>";
											document.getElementById('listaDadosQuadroLocalCobranca').innerHTML = dados;
										}
									}
								} 
								// Fim de Carregando
								carregando(false);
							}
							xmlhttp.send(null);
						}
						function aciona_busca_local_cobranca(campo,valor){
							if(valorCampoLocalCobranca!=''){
								document.getElementById('listaDados_td_'+valorCampoLocalCobranca).style.backgroundColor = "#FFFFFF";
							}
							if(valorCampoLocalCobranca == valor){
								busca_local_cobranca(valor);
							}
							valorCampoLocalCobranca = valor;
							document.BuscaLocalCobranca.IdLocalCobranca.value = valorCampoLocalCobranca;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}
						function limpa_form_local_cobranca(){
							document.formularioLocalCobranca.Nome.value			=	''; 
							document.formularioLocalCobranca.Abreviacao.value	=	''; 
							
							valorCampoLocalCobranca='';
						}
						enterAsTab(document.forms.formularioLocalCobranca);	
					</script>
				</div>
			</div>
