			<div id='quadroBuscaPais' style='width:365px;' class='quadroFlutuante'>
				<!--div class='tit'>Busca País<div class='fecha' onClick="vi_id('quadroBuscaPais', false);">X</div></div-->
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca País</td>
						<td class='fecha' onClick="vi_id('quadroBuscaPais', false);">X</div></td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='formularioPais' method='post'>
						<table>
							<tr>
								<td class='descCampo'>Nome País</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='NomePais' autocomplete="off" value='' style='width:334px' maxlength='100' onkeyup="busca_pais_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'>
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 180px;'>
						<div id='listaDadosQuadroPais'>&nbsp;</div>
					</div>
					<form name='BuscaPais' method='post' onSubmit='return validar_busca_pais()'>
						<input type='hidden' name='IdPais' value=''>
						<table class='listaDados'>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaPais', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>
					<script language='JavaScript' type='text/javascript'>
						// Para que os quadros flutuem
						new Draggable('quadroBuscaPais');						
						
						var valorCampoPais = '';
						function validar_busca_pais(){
							if(valorCampoPais !=''){
								busca_pais(valorCampoPais);	
							}
							return false;
						}
						function busca_pais_lista(){
							var NomePais	= document.formularioPais.NomePais.value;
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
						    
						    if(NomePais == ''){
						    	url = "xml/pais.php?Limit="+Limit;
							}else{
								url = "xml/pais.php?NomePais="+NomePais;
							}
							xmlhttp.open("GET", url,true);
							
							xmlhttp.onreadystatechange = function(){ 
					
								// Carregando...
								carregando(true);
					
								if(xmlhttp.readyState == 4){ 
									if(xmlhttp.status == 200){
										if(xmlhttp.responseText == 'false'){
											document.getElementById('listaDadosQuadroPais').innerHTML = "";
											// Fim de Carregando
											carregando(false);
										}else{
											var dados = "<table id='listaDados' style='width: 343px;'>\n<tr>\n<td class='listaDados_titulo' style='width: 60px'>País</td>\n<td class='listaDados_titulo'>Nome País</td>\n</tr>";
											for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdPais").length; i++){
													
												nameNode = xmlhttp.responseXML.getElementsByTagName("IdPais")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var IdPais = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("NomePais")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var NomePais = verifica_dado(nameTextNode.nodeValue);
												
												dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdPais+"' onClick='aciona_busca_pais(this,"+IdPais+")'>";
												dados += 	"\n<td>"+IdPais+"</td><td>"+NomePais+"</td>";
												dados += "\n</tr>";
											}
											dados += "\n</table>";
											document.getElementById('listaDadosQuadroPais').innerHTML = dados;
										}
									}
								} 
								// Fim de Carregando
								carregando(false);
							}
							xmlhttp.send(null);
						}
						function aciona_busca_pais(campo,valor){
							if(valorCampoPais!=''){
								document.getElementById('listaDados_td_'+valorCampoPais).style.backgroundColor = "#FFFFFF";
							}
							if(valorCampoPais == valor){
								busca_pais(valor,true);
							}
							valorCampoPais = valor;
							document.BuscaPais.IdPais.value = valorCampoPais;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}	
						function limpa_form_pais(){
							document.formularioPais.NomePais.value	=	"";
							
							valorCampoPais = "";
						}
						enterAsTab(document.forms.formularioPais);		
					</script>
				</div>
			</div>

