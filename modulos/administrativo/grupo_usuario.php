			<div id='quadroBuscaGrupoUsuario' style='width:365px;' class='quadroFlutuante'>	
				<div class='tit'>Busca Grupo Usuário<div class='fecha' onClick="vi_id('quadroBuscaGrupoUsuario', false);">X</div></div>
				<div class='filtro_busca'>
					<form name='formularioGrupoUsuario' method='post'>
						<table>
							<tr>
								<td class='descCampo'>Nome Grupo Usuário</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='Nome' autocomplete="off" value='' style='width:334px' maxlength='100' onkeyup="busca_grupo_usuario_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 180px;'>
						<div id='listaDadosQuadroGrupoUsuario'>&nbsp;</div>
					</div>
					<form name='BuscaGrupoUsuario' method='post' onSubmit='return validar_busca_grupo_usuario()'>
						<input type='hidden' name='IdGrupoUsuario' value=''>
						<table>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaGrupoUsuario', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>
					<script>
						// Para que os quadros flutuem
						new Draggable('quadroBuscaGrupoUsuario');
						
						var valorCampoGrupoUsuario = '';
						function validar_busca_grupo_usuario(){
							if(valorCampoGrupoUsuario !=''){
								busca_grupo_usuario(valorCampoGrupoUsuario);
							}
							return false;
						}
						function busca_grupo_usuario_lista(){
							var Nome 		= document.formularioGrupoUsuario.Nome.value;
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
						    	url = "../administrativo/xml/grupo_usuario.php?Limit="+Limit;
							}else{
								url = "../administrativo/xml/grupo_usuario.php?Nome="+Nome;
							}
							
							xmlhttp.open("GET", url,true);
							
							xmlhttp.onreadystatechange = function(){ 
					
								// Carregando...
								carregando(true);
					
								if(xmlhttp.readyState == 4){ 
									if(xmlhttp.status == 200){
										var dados = "<table id='listaDados' style='width: 343px'>\n<tr>\n<td class='listaDados_titulo' style='width: 80px'>Grupo Usuár.</td>\n<td class='listaDados_titulo'>Nome Grupo Usuário</td>\n</tr>";
										for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdGrupoUsuario").length; i++){
												
											nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoUsuario")[i]; 
											nameTextNode = nameNode.childNodes[0];
											IdGrupoUsuario = nameTextNode.nodeValue;
											
											nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoGrupoUsuario")[i]; 
											nameTextNode = nameNode.childNodes[0];
											DescricaoGrupoUsuario = nameTextNode.nodeValue;
											
											dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdGrupoUsuario+"' onClick='aciona_busca_grupo_usuario(this,"+IdGrupoUsuario+")'>";
											dados += 	"\n<td>"+IdGrupoUsuario+"</td><td>"+DescricaoGrupoUsuario+"</td>";
											dados += "\n</tr>";
										}
										dados += "\n</table>";
										document.getElementById('listaDadosQuadroGrupoUsuario').innerHTML = dados;
									}
								} 
								// Fim de Carregando
								carregando(false);
							}
							xmlhttp.send(null);
						}
						function aciona_busca_grupo_usuario(campo,valor){
							if(valorCampoGrupoUsuario!=''){
								document.getElementById('listaDados_td_'+valorCampoGrupoUsuario).style.backgroundColor = "#FFFFFF";
							}
							if(valorCampoGrupoUsuario == valor){
								busca_grupo_usuario(valor);
							}
							valorCampoGrupoUsuario = valor;
							document.BuscaGrupoUsuario.IdGrupoUsuario.value = valorCampoGrupoUsuario;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}
					</script>
				</div>
			</div>
