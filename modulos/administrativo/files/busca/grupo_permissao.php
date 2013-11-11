			<div id='quadroBuscaGrupoPermissao' style='width:365px;' class='quadroFlutuante'>	
				<!--div class='tit'>Busca Grupo Permissão<div class='fecha' onClick="vi_id('quadroBuscaGrupoPermissao', false);">X</div></div-->
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Grupo Permissão</td>
						<td class='fecha' onClick="vi_id('quadroBuscaGrupoPermissao', false);">X</div></td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='formularioGrupoPermissao'method='post'>
						<table>
							<tr>
								<td class='descCampo'>Nome Grupo Permissão</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='Nome' autocomplete="off" value='' style='width:330px' maxlength='100' onkeyup="busca_grupo_permissao_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 180px;'>
						<div id='listaDadosQuadroGrupoPermissao'>&nbsp;</div>
					</div>
					<form name='BuscaGrupoPermissao' method='post' onSubmit='return validar_busca_grupo_permissao()'>
						<input type='hidden' name='IdGrupoPermissaoSistema' value=''>
						<table class='listaDados'>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaGrupoPermissao', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>	
					<script>
						// Para que os quadros flutuem
						new Draggable('quadroBuscaGrupoPermissao');
						
						var valorCampoGrupoPermissao = '';
						function validar_busca_grupo_permissao(){
							if(valorCampoGrupoPermissao !=''){
								busca_grupo_permissao(valorCampoGrupoPermissao);
							}
							return false;
						}
						function busca_grupo_permissao_lista(){
							var Nome 		= document.formularioGrupoPermissao.Nome.value;
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
						    	url = "xml/grupo_permissao.php?Limit="+Limit;
							}else{
								url = "xml/grupo_permissao.php?DescricaoGrupoPermissao="+Nome;
							}
							
							xmlhttp.open("GET", url,true);
							
							xmlhttp.onreadystatechange = function(){ 
					
								// Carregando...
								carregando(true);
					
								if(xmlhttp.readyState == 4){ 
									if(xmlhttp.status == 200){
										if(xmlhttp.responseText == 'false'){
											document.getElementById('listaDadosQuadroGrupoPermissao').innerHTML = "";
											// Fim de Carregando
											carregando(false);
										}else{
											var dados = "<table id='listaDados' style='width: 343px'>\n<tr>\n<td class='listaDados_titulo' style='width: 90px'>Grup. Perm.</td>\n<td class='listaDados_titulo'>Nome</td>\n</tr>";
											for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdGrupoPermissao").length; i++){
													
												nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoPermissao")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var IdGrupoPermissao = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoGrupoPermissao")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var DescricaoGrupoPermissao = verifica_dado(nameTextNode.nodeValue);
												
												dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdGrupoPermissao+"' onClick='aciona_busca_grupo_permissao(this,"+IdGrupoPermissao+")'>";
												dados += 	"\n<td>"+IdGrupoPermissao+"</td><td>"+DescricaoGrupoPermissao+"</td>";
												dados += "\n</tr>";
											}
											dados += "\n</table>";
											document.getElementById('listaDadosQuadroGrupoPermissao').innerHTML = dados;
										}
									}
								} 
								// Fim de Carregando
								carregando(false);
							}
							xmlhttp.send(null);
						}
						function aciona_busca_grupo_permissao(campo,valor){
							if(valorCampoGrupoPermissao!=''){
								document.getElementById('listaDados_td_'+valorCampoGrupoPermissao).style.backgroundColor = "#FFFFFF";
							}
							if(valorCampoGrupoPermissao == valor){
								busca_grupo_permissao(valor);
							}
							valorCampoGrupoPermissao = valor;
							document.BuscaGrupoPermissao.IdGrupoPermissaoSistema.value = valorCampoGrupoPermissao;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}
						enterAsTab(document.forms.formularioGrupoPermissao);
					</script>
				</div>
			</div>
