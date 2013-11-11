			<div id='quadroBuscaGrupoPessoa' style='width:365px;' class='quadroFlutuante'>			
				<!--div class='tit'>Busca Grupo Pessoa<div class='fecha' onClick="vi_id('quadroBuscaGrupoPessoa', false);">X</div></div-->
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Grupo Pessoa</td>
						<td class='fecha' onClick="vi_id('quadroBuscaGrupoPessoa', false);">X</div></td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='formularioGrupoPessoa' method='post'>
						<table>
							<tr>
								<td class='descCampo'>Nome Grupo Pessoa</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='Nome' autocomplete="off" value='<?=$local_Nome?>' style='width:334px' maxlength='100' onkeyup="busca_grupo_pessoa_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 180px;'>
						<div id='listaDadosQuadroGrupoPessoa'>&nbsp;</div>
					</div>
					<form name='BuscaGrupoPessoa' method='post' onSubmit='return validar_grupo_pessoa()'>
						<input type='hidden' name='IdGrupoPessoa' value=''>
						<table class='listaDados'>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaGrupoPessoa', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>
					<script>
						// Para que os quadros flutuem
						new Draggable('quadroBuscaGrupoPessoa');	
											
						var valorCampoGrupoPessoa = '';
						function validar_grupo_pessoa(){
							if(valorCampoGrupoPessoa !=''){
								busca_grupo_pessoa(valorCampoGrupoPessoa);
							}
							return false;
						}
						function busca_grupo_pessoa_lista(){
							var Nome 		= document.formularioGrupoPessoa.Nome.value;
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
						    	url = "../administrativo/xml/grupo_pessoa.php?Limit="+Limit;
							}else{
								url = "../administrativo/xml/grupo_pessoa.php?Nome="+Nome;
							}
							xmlhttp.open("GET", url,true);
							
							xmlhttp.onreadystatechange = function(){ 
					
								// Carregando...
								carregando(true);
					
								if(xmlhttp.readyState == 4){ 
									if(xmlhttp.status == 200){
										if(xmlhttp.responseText == 'false'){
											document.getElementById('listaDadosQuadroGrupoPessoa').innerHTML = "";
											// Fim de Carregando
											carregando(false);
										}else{
											var dados = "<table id='listaDados' style='width: 343px'>\n<tr>\n<td class='listaDados_titulo' style='width: 80px'>Grupo Pess.</td>\n<td class='listaDados_titulo'>Nome Grupo Pessoa</td>\n</tr>";
											for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdGrupoPessoa").length; i++){
													
												nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoPessoa")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var IdGrupoPessoa = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoGrupoPessoa")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var DescricaoGrupoPessoa = nameTextNode.nodeValue;
												
												dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdGrupoPessoa+"' onClick='aciona(this,"+IdGrupoPessoa+")'>";
												dados += 	"\n<td>"+IdGrupoPessoa+"</td><td>"+DescricaoGrupoPessoa+"</td>";
												dados += "\n</tr>";
											}
											dados += "\n</table>";
											document.getElementById('listaDadosQuadroGrupoPessoa').innerHTML = dados;
										}
									}
								} 
								// Fim de Carregando
								carregando(false);
							}
							xmlhttp.send(null);
						}
						function aciona(campo,valor){
							if(valorCampoGrupoPessoa!=''){
								document.getElementById('listaDados_td_'+valorCampoGrupoPessoa).style.backgroundColor = "#FFFFFF";
							}
							if(valorCampoGrupoPessoa == valor){
								busca_grupo_pessoa(valor);
							}
							valorCampoGrupoPessoa = valor;
							document.BuscaGrupoPessoa.IdGrupoPessoa.value = valorCampoGrupoPessoa;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}
						function limpa_form_grupo_pessoa(){
							document.formularioGrupoPessoa.Nome.value	=	"";
							
							valorCampoGrupoPessoa = "";
						}
						enterAsTab(document.forms.formularioGrupoPessoa);	
					</script>
				</div>
			</div>
