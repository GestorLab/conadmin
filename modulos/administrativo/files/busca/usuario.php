			<div id='quadroBuscaUsuario' style='width:365px;' class='quadroFlutuante'>	
				<!--div class='tit'>Busca Usuário<div class='fecha' onClick="vi_id('quadroBuscaUsuario', false);">X</div></div-->
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Usuário</td>
						<td class='fecha' onClick="vi_id('quadroBuscaUsuario', false);">X</div></td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='formularioUsuario' method='post'>
						<table>
							<tr>
								<td class='descCampo'>Login</td>
								<td class='descCampo'>Nome Usuário</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='Login' autocomplete="off" value='' style='width:120px' maxlength='20' onkeyup="busca_usuario_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
								</td>
								<td class='campo'>
									<input type='text' name='NomeUsuario' autocomplete="off" value='' style='width:206px' maxlength='30' onkeyup="busca_usuario_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 180px;'>
						<div id='listaDadosQuadroUsuario'>&nbsp;</div>
					</div>
					<form name='BuscaUsuario' method='post' action='busca_usuario.php' onSubmit='return validar_busca_usuario()'>
						<input type='hidden' name='Login' value=''>
						<table class='listaDados'>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaUsuario', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>
					<script>
						// Para que os quadros flutuem
						new Draggable('quadroBuscaUsuario');
								
						var valorCampoUsuario = '';
						function validar_busca_usuario(){
							if(valorCampoUsuario !=''){
								busca_usuario(valorCampoUsuario);	
							}
							return false;
						}
						function busca_usuario_lista(){
							var Login		= document.formularioUsuario.Login.value;
							var NomeUsuario	= document.formularioUsuario.NomeUsuario.value;
							var Local		= document.formulario.Local.value;
							var Limit	  	= <?=getCodigoInterno(7,4)?>;
							var nameNode, nameTextNode, url;
							
							var Busca = 'Busca';
							
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
						    
						    if(Login == '' && NomeUsuario=='') {
						    	url = "xml/usuario.php?Limit="+Limit+"&Busca="+Busca;
							}else{
								url = "xml/usuario.php?Login="+Login+"&NomeUsuario="+NomeUsuario+"&Busca="+Busca;
							}
   							
   							switch(Local){
   								case 'UsuarioGrupoPermissao':
   									url += "&IdStatus="+2;
   									break;
   								case 'UsuarioGrupoUsuario':
   									url += "&IdStatus="+2;
   									break;
   								case 'UsuarioPermissao':
   									url += "&IdStatus="+2;
   									break;
   							}
   							
							xmlhttp.open("GET", url,true);
							
							xmlhttp.onreadystatechange = function(){ 
					
								// Carregando...
								carregando(true);
					
								if(xmlhttp.readyState == 4){ 
									if(xmlhttp.status == 200){
										if(xmlhttp.responseText == 'false'){
											document.getElementById('listaDadosQuadroUsuario').innerHTML = "";
											// Fim de Carregando
											carregando(false);
										}else{
											var dados = "<table id='listaDados' style='width: 342px'>\n<tr>\n<td class='listaDados_titulo' style='width: 100px'>Login</td>\n<td class='listaDados_titulo'>Nome Usuário</td>\n</tr>";
											for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("Login").length; i++){
													
												nameNode = xmlhttp.responseXML.getElementsByTagName("Login")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var Login = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("NomeUsuario")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var NomeUsuario = verifica_dado(nameTextNode.nodeValue);
												
												NomeUsuario	=	NomeUsuario.substr(0,30);
												
												dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+Login+"' onClick=\"aciona_busca_usuario(this,'"+Login+"')\">";
												dados += 	"\n<td>"+Login+"</td><td>"+NomeUsuario+"</td>";
												dados += "\n</tr>";
											}
											dados += "\n</table>";
											document.getElementById('listaDadosQuadroUsuario').innerHTML = dados;
										}
									}
								} 
								// Fim de Carregando
								carregando(false);
							}
							xmlhttp.send(null);
						}
						function aciona_busca_usuario(campo,valor){
							if(valorCampoUsuario!=''){
								document.getElementById('listaDados_td_'+valorCampoUsuario).style.backgroundColor = "#FFFFFF";
							}
							if(valorCampoUsuario == valor){
								busca_usuario(valor,true);
							}
							valorCampoUsuario = valor;
							document.BuscaUsuario.Login.value = valorCampoUsuario;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}
						function limpa_form_usuario(){
							document.formularioUsuario.Login.value			=	"";
							document.formularioUsuario.NomeUsuario.value	=	"";
						
							valorCampoUsuario	=	"";
						}
						enterAsTab(document.forms.formularioUsuario);
					</script>
				</div>
			</div>
