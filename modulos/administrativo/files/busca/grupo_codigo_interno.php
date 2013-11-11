			<div id='quadroBuscaGrupoCodigo' style='width:365px;' class='quadroFlutuante'>	
				<!--div class='tit'>Busca Grupo Código Interno<div class='fecha' onClick="vi_id('quadroBuscaGrupoCodigo', false);">X</div></div-->
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Grupo Código Interno</td>
						<td class='fecha' onClick="vi_id('quadroBuscaGrupoCodigo', false);">X</div></td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='formularioGrupoCodigo'method='post'>
						<table>
							<tr>
								<td class='descCampo'>Nome Grupo Código Interno</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='Nome' autocomplete="off" value='' style='width:330px' maxlength='100' onkeyup="busca_grupo_codigo_interno_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 180px;'>
						<div id='listaDadosQuadroGrupoCodigo'>&nbsp;</div>
					</div>
					<form name='BuscaGrupoCodigo' method='post' onSubmit='return validar_busca_grupo_codigo()'>
						<input type='hidden' name='IdGrupoCodigoInterno' value=''>
						<table>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaGrupoCodigo', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>	
					<script>
						var valorCampoGrupoCodigo = '';
						function validar_busca_grupo_codigo(){
							if(valorCampoGrupoCodigo !=''){
								busca_grupo_codigo_interno(valorCampoGrupoCodigo);
							}
							return false;
						}
						function busca_grupo_codigo_interno_lista(){
							var Nome 		= document.formularioGrupoCodigo.Nome.value;
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
						    	url = "xml/grupo_codigo_interno.php?Limit="+Limit;
							}else{
								url = "xml/grupo_codigo_interno.php?Nome="+Nome;
							}
							xmlhttp.open("GET", url,true);
							
							xmlhttp.onreadystatechange = function(){ 
					
								// Carregando...
								carregando(true);
					
								if(xmlhttp.readyState == 4){ 
									if(xmlhttp.status == 200){
										if(xmlhttp.responseText == 'false'){
											document.getElementById('listaDadosQuadroGrupoCodigo').innerHTML = "";
											// Fim de Carregando
											carregando(false);
										}else{
											var dados = "<table id='listaDados' style='width: 343px'>\n<tr>\n<td class='listaDados_titulo' style='width: 60px'>Código</td>\n<td class='listaDados_titulo'>Descrição Grupo Código Interno</td>\n</tr>";
											for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdGrupoCodigoInterno").length; i++){
													
												nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoCodigoInterno")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var IdGrupoCodigoInterno = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoGrupoCodigoInterno")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var DescricaoGrupoCodigoInterno = verifica_dado(nameTextNode.nodeValue);
												
												dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdGrupoCodigoInterno+"' onClick='aciona(this,"+IdGrupoCodigoInterno+")'>";
												dados += 	"\n<td>"+IdGrupoCodigoInterno+"</td><td>"+DescricaoGrupoCodigoInterno+"</td>";
												dados += "\n</tr>";
											}
											dados += "\n</table>";
											document.getElementById('listaDadosQuadroGrupoCodigo').innerHTML = dados;
										}
									}
								} 
								// Fim de Carregando
								carregando(false);
							}
							xmlhttp.send(null);
						}
						function aciona(campo,valor){
							if(valorCampoGrupoCodigo!=''){
								document.getElementById('listaDados_td_'+valorCampoGrupoCodigo).style.backgroundColor = "#FFFFFF";
							}
							if(valorCampoGrupoCodigo == valor){
								busca_grupo_codigo_interno(valor);
							}
							valorCampoGrupoCodigo = valor;
							document.formulario.IdGrupoCodigoInterno.value = valorCampoGrupoCodigo;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}
						enterAsTab(document.forms.formularioGrupoCodigo);
					</script>
				</div>
			</div>
