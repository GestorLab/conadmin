			<div id='quadroBuscaCFOP' style='width:390px;' class='quadroFlutuante'>	
				<!--div id='tit'>Busca CFOP<div class='fecha' onClick="vi_id('quadroBuscaCFOP', false);">X</div></div-->
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca CFOP</td>
						<td class='fecha' onClick="vi_id('quadroBuscaCFOP', false);">X</div></td>
					</tr>
				</table>
				<div id='filtro_busca'>
					<form name='formularioCFOP' method='post'>
						<table>
							<tr>
								<td class='descCampo'>CFOP</td>
								<td class='descCampo'>Natureza Operação</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='CFOP' autocomplete="off" value='' style='width:70px' maxlength='16' onkeyup="busca_cfop_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
								</td>
								<td class='campo'>
									<input type='text' name='Nome' autocomplete="off" value='' style='width:280px' maxlength='255' onkeyup="busca_cfop_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 250px;'>
						<div id='listaDadosQuadroCFOP'>&nbsp;</div>
					</div>
					<form name='BuscaCFOP' method='post' onSubmit='return validar_busca_cfop()'>
						<input type='hidden' name='CFOP' value=''>
						<table class='listaDados'>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaCFOP', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>
					<script>
						// Para que os quadros flutuem
						new Draggable('quadroBuscaCFOP');
						
						var valorCampoCFOP = '';
						function validar_busca_cfop(){
							if(valorCampoCFOP !=''){
								busca_cfop(valorCampoCFOP);	
							}
							return false;
						}
						function busca_cfop_lista(){
							var Nome		= document.formularioCFOP.Nome.value;
							var CFOP		= document.formularioCFOP.CFOP.value;
							var Limit	  	= 11;
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
						    
						    if(Nome == '' && CFOP=='') {
						    	url = "xml/cfop.php?Limit="+Limit;
							}else{
								url = "xml/cfop.php?NaturezaOperacao="+Nome+"&CFOPBusca="+CFOP;
							}
							xmlhttp.open("GET", url,true);
							
							xmlhttp.onreadystatechange = function(){ 
					
								// Carregando...
								carregando(true);
					
								if(xmlhttp.readyState == 4){ 
									if(xmlhttp.status == 200){
										if(xmlhttp.responseText == 'false'){
											document.getElementById('listaDadosQuadroCFOP').innerHTML = "";
											// Fim de Carregando
											carregando(false);
										}else{
											var dados = "<table id='listaDados' style='width: 368px'>\n<tr>\n<td class='listaDados_titulo' style='width: 70px'>CFOP</td>\n<td class='listaDados_titulo'>Natureza Operação</td>\n</tr>";
											for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("CFOP").length; i++){
													
												nameNode = xmlhttp.responseXML.getElementsByTagName("CFOP")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var CFOP = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("NaturezaOperacao")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var NaturezaOperacao = verifica_dado(nameTextNode.nodeValue);
												
												NaturezaOperacao	=	NaturezaOperacao.substr(0,44);
												
												dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+CFOP+"' onClick=\"aciona_busca_cfop(this,'"+CFOP+"')\">";
												dados += 	"\n<td>"+CFOP+"</td><td>"+NaturezaOperacao+"</td>";
												dados += "\n</tr>";
											}
											dados += "\n</table>";
											document.getElementById('listaDadosQuadroCFOP').innerHTML = dados;
										}
									}
								} 
								// Fim de Carregando
								carregando(false);
							}
							xmlhttp.send(null);
						}
						function aciona_busca_cfop(campo,valor){
							if(valorCampoCFOP!=''){
								document.getElementById('listaDados_td_'+valorCampoCFOP).style.backgroundColor = "#FFFFFF";
							}
							if(valorCampoCFOP == valor){
								busca_cfop(valor,true);
							}
							valorCampoCFOP = valor;
							document.BuscaCFOP.CFOP.value = valorCampoCFOP;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}
						enterAsTab(document.forms.formularioCFOP);	
					</script>
				</div>
			</div>
