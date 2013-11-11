			<div id='quadroBuscaTipoEmail' style='width:365px;' class='quadroFlutuante'>	
				<!--div class='tit'>Busca Tipo E-mail<div class='fecha' onClick="vi_id('quadroBuscaTipoEmail', false);">X</div></div-->
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Tipo E-mail</td>
						<td class='fecha' onClick="vi_id('quadroBuscaTipoEmail', false);">X</div></td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='formularioTipoEmail' method='post'>
						<table>
							<tr>
								<td class='descCampo'>Nome Tipo E-mail</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='Nome' autocomplete="off" value='' style='width:334px' maxlength='100' onkeyup="busca_tipo_email_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 180px;'>
						<div id='listaDadosQuadroTipoEmail'>&nbsp;</div>
					</div>
					<form name='BuscaTipoEmail' method='post' onSubmit='return validar_busca_tipo_email()'>
						<input type='hidden' name='IdTipoEmail' value=''>
						<table class='listaDados'>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaTipoEmail', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>
					<script>
						// Para que os quadros flutuem
						new Draggable('quadroBuscaTipoEmail');
						
						var valorCampoTipoEmail = '';
						function validar_busca_tipo_email(){
							if(valorCampoTipoEmail !=''){
								busca_tipo_email(valorCampoTipoEmail);
							}
							return false;
						}
						function busca_tipo_email_lista(){
							var Nome 		= document.formularioTipoEmail.Nome.value;
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
						    	url = "../administrativo/xml/tipo_email.php?Limit="+Limit;
							}else{
								url = "../administrativo/xml/tipo_email.php?DescricaoTipoEmail="+Nome;
							}
							xmlhttp.open("GET", url,true);
							
							xmlhttp.onreadystatechange = function(){ 
					
								// Carregando...
								carregando(true);
					
								if(xmlhttp.readyState == 4){ 
									if(xmlhttp.status == 200){
										if(xmlhttp.responseText == 'false'){
											document.getElementById('listaDadosQuadroTipoEmail').innerHTML = "";
											// Fim de Carregando
											carregando(false);
										}else{
											var dados = "<table id='listaDados' style='width: 343px'>\n<tr>\n<td class='listaDados_titulo' style='width: 70px'>Tipo E-mail</td>\n<td class='listaDados_titulo'>Nome Tipo E-mail</td>\n</tr>";
											for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdTipoEmail").length; i++){
													
												nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoEmail")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var IdTipoEmail = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoTipoEmail")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var DescricaoTipoEmail = nameTextNode.nodeValue;
												
												DescricaoTipoEmail = DescricaoTipoEmail.substr(0,40);
												
												dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdTipoEmail+"' onClick='aciona_busca_tipo_email(this,"+IdTipoEmail+")'>";
												dados += 	"\n<td>"+IdTipoEmail+"</td><td>"+DescricaoTipoEmail+"</td>";
												dados += "\n</tr>";
											}
											dados += "\n</table>";
											document.getElementById('listaDadosQuadroTipoEmail').innerHTML = dados;
										}
									}
								} 
								// Fim de Carregando
								carregando(false);
							}
							xmlhttp.send(null);
						}
						function aciona_busca_tipo_email(campo,valor){
							if(valorCampoTipoEmail!=''){
								document.getElementById('listaDados_td_'+valorCampoTipoEmail).style.backgroundColor = "#FFFFFF";
							}
							if(valorCampoTipoEmail == valor){
								busca_tipo_email(valor);
							}
							valorCampoTipoEmail = valor;
							document.BuscaTipoEmail.IdTipoEmail.value = valorCampoTipoEmail;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}
						enterAsTab(document.forms.formularioTipoEmail);
					</script>
				</div>
			</div>
