			<div id='quadroBuscaGrupoParametro' style='width:365px;' class='quadroFlutuante'>	
				<!--div class='tit'>Busca Grupo Parâmetros do Sistema<div class='fecha' onClick="vi_id('quadroBuscaGrupoParametro', false);">X</div></div-->
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Grupo Parâmetros do Sistema</td>
						<td class='fecha' onClick="vi_id('quadroBuscaGrupoParametro', false);">X</div></td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='formularioGrupoParametro'method='post'>
						<table>
							<tr>
								<td class='descCampo'>Nome Grupo Parâmetros do Sistema</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='Nome' autocomplete="off" value='' style='width:330px' maxlength='100' onkeyup="busca_grupo_parametro_sistema_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 180px;'>
						<div id='listaDadosQuadroGrupoParametro'>&nbsp;</div>
					</div>
					<form name='BuscaGrupoParametro' method='post' onSubmit='return validar_busca_grupo_parametro()'>
						<input type='hidden' name='IdGrupoParametroSistema' value=''>
						<table class='listaDados'>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaGrupoParametro', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>	
					<script>
						// Para que os quadros flutuem
						new Draggable('quadroBuscaGrupoParametro');
						
						var valorCampoGrupoParametro = '';
						function validar_busca_grupo_parametro(){
							if(valorCampoGrupoParametro !=''){
								busca_grupo_parametro_sistema(valorCampoGrupoParametro);
							}
							return false;
						}
						function busca_grupo_parametro_sistema_lista(){
							var Nome 		= document.formularioGrupoParametro.Nome.value;
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
						    	url = "xml/grupo_parametro_sistema.php?Limit="+Limit;
							}else{
								url = "xml/grupo_parametro_sistema.php?Nome="+Nome;
							}
							xmlhttp.open("GET", url,true);
							
							xmlhttp.onreadystatechange = function(){ 
					
								// Carregando...
								carregando(true);
					
								if(xmlhttp.readyState == 4){ 
									if(xmlhttp.status == 200){
										if(xmlhttp.responseText == 'false'){
											document.getElementById('listaDadosQuadroGrupoParametro').innerHTML = "";
											// Fim de Carregando
											carregando(false);
										}else{
											var dados = "<table id='listaDados' style='width: 343px'>\n<tr>\n<td class='listaDados_titulo' style='width: 90px'>Grup. Par. Sist.</td>\n<td class='listaDados_titulo'>Nome</td>\n</tr>";
											for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdGrupoParametroSistema").length; i++){
													
												nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoParametroSistema")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var IdGrupoParametroSistema = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoGrupoParametroSistema")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var DescricaoGrupoParametroSistema = verifica_dado(nameTextNode.nodeValue);
												
												dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdGrupoParametroSistema+"' onClick='aciona_busca_grupo_parametro(this,"+IdGrupoParametroSistema+")'>";
												dados += 	"\n<td>"+IdGrupoParametroSistema+"</td><td>"+DescricaoGrupoParametroSistema+"</td>";
												dados += "\n</tr>";
											}
											dados += "\n</table>";
											document.getElementById('listaDadosQuadroGrupoParametro').innerHTML = dados;
										}
									}
								} 
								// Fim de Carregando
								carregando(false);
							}
							xmlhttp.send(null);
						}
						function aciona_busca_grupo_parametro(campo,valor){
							if(valorCampoGrupoParametro!=''){
								document.getElementById('listaDados_td_'+valorCampoGrupoParametro).style.backgroundColor = "#FFFFFF";
							}
							if(valorCampoGrupoParametro == valor){
								busca_grupo_parametro_sistema(valor);
							}
							valorCampoGrupoParametro = valor;
							document.BuscaGrupoParametro.IdGrupoParametroSistema.value = valorCampoGrupoParametro;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}
						enterAsTab(document.forms.formularioGrupoParametro);
					</script>
				</div>
			</div>
