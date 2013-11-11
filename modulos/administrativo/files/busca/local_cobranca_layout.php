			<div id='quadroBuscaLocalCobrancaLayout' style='width:365px;' class='quadroFlutuante'>
				<!--div class='tit'>Busca Local de Cobrança Layout<div class='fecha' onClick="vi_id('quadroBuscaLocalCobrancaLayout', false);">X</div></div-->
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Local de Cobrança Layout</td>
						<td class='fecha' onClick="vi_id('quadroBuscaLocalCobrancaLayout', false);">X</div></td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='formularioLocalCobrancaLayout' method='post'>
						<table>
							<tr>
								<td class='descCampo'>Nome Local Cobrança Layout</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='Nome' autocomplete="off" value='' style='width:330px' maxlength='100' onkeyup="busca_local_cobranca_layout_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 180px;'>
						<div id='listaDadosQuadroLocalCobrancaLayout'>&nbsp;</div>
					</div>
					<form name='BuscaLocalCobrancaLayout' method='post' onSubmit='return validar_busca_local_cobranca_layout()'>
						<input type='hidden' name='IdLocalCobrancaLayout' value=''>
						<table>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaLocalCobrancaLayout', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>
					<script>
						// Para que os quadros flutuem
						new Draggable('quadroBuscaLocalCobrancaLayout');
						
						var valorCampoLocalCobrancaLayout = '';
						function validar_busca_local_cobranca_layout(){
							if(valorCampoLocalCobrancaLayout !=''){
								busca_local_cobranca_layout(valorCampoLocalCobrancaLayout);
							}
							return false;
						}
						function busca_local_cobranca_layout_lista(){
							var Nome 		= document.formularioLocalCobrancaLayout.Nome.value;
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
						    	url = "../administrativo/xml/local_cobranca_layout.php?Limit="+Limit;
							}else{
								url = "../administrativo/xml/local_cobranca_layout.php?Nome="+Nome;
							}
							
							if(document.formulario.Local.value == "LocalCobranca"){
								url += "&IdStatus=1";
							}
							
							xmlhttp.open("GET", url,true);
							xmlhttp.onreadystatechange = function(){
								// Carregando...
								carregando(true);
					
								if(xmlhttp.readyState == 4){ 
									if(xmlhttp.status == 200){
										if(xmlhttp.responseText == 'false'){
											document.getElementById('listaDadosQuadroLocalCobrancaLayout').innerHTML = "";
											// Fim de Carregando
											carregando(false);
										}else{
											var dados = "<table id='listaDados' style='width: 340px'>\n<tr>\n<td class='listaDados_titulo' style='width: 100px'>Local Cob. Layout</td>\n<td class='listaDados_titulo'>Nome Local Cobrança Layout</td>\n</tr>";
											for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdLocalCobrancaLayout").length; i++){
													
												nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobrancaLayout")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var IdLocalCobrancaLayout = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalCobrancaLayout")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var DescricaoLocalCobrancaLayout = nameTextNode.nodeValue;
												
												dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdLocalCobrancaLayout+"' onClick='aciona_busca_local_cobranca_layout(this,"+IdLocalCobrancaLayout+")'>";
												dados += 	"\n<td>"+IdLocalCobrancaLayout+"</td><td>"+DescricaoLocalCobrancaLayout+"</td>";
												dados += "\n</tr>";
											}
											dados += "\n</table>";
											document.getElementById('listaDadosQuadroLocalCobrancaLayout').innerHTML = dados;
										}
									}
								} 
								// Fim de Carregando
								carregando(false);
							}
							xmlhttp.send(null);
						}
						function aciona_busca_local_cobranca_layout(campo,valor){
							if(valorCampoLocalCobrancaLayout!=''){
								document.getElementById('listaDados_td_'+valorCampoLocalCobrancaLayout).style.backgroundColor = "#FFFFFF";
							}
							if(valorCampoLocalCobrancaLayout == valor){
								busca_local_cobranca_layout(valor);
							}
							valorCampoLocalCobrancaLayout = valor;
							document.BuscaLocalCobrancaLayout.IdLocalCobrancaLayout.value = valorCampoLocalCobrancaLayout;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}
						enterAsTab(document.forms.formularioLocalCobrancaLayout);	
					</script>
				</div>
			</div>
