			<div id='quadroBuscaCidade' style='width:365px;' class='quadroFlutuante'>
				<!--div class='tit'>Busca Cidade<div class='fecha' onClick="vi_id('quadroBuscaCidade', false);">X</div></div-->
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Cidade</td>
						<td class='fecha' onClick="vi_id('quadroBuscaCidade', false);">X</div></td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='formularioCidade' method='post'>
						<table>
							<tr>
								<td class='descCampo'>Nome Cidade</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='NomeCidade' value='' style='width:334px' maxlength='100' onkeyup="busca_cidade_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 180px;'>
						<div id='listaDadosQuadroCidade'>&nbsp;</div>
					</div>
					<form name='BuscaCidade' method='post' onSubmit='return validar_busca_cidade()'>
						<input type='hidden' name='IdCidade' value=''>
						<table class='listaDados'>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaCidade', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>	
					<script language='JavaScript' type='text/javascript'>
						// Para que os quadros flutuem
						new Draggable('quadroBuscaCidade');
						
						var valorCampoCidade = '';
						function validar_busca_cidade(){
							if(valorCampoCidade !=''){
								busca_cidade(document.formulario.IdPais.value,document.formulario.IdEstado.value,valorCampoCidade);
							}
							return false;
						}
						function busca_cidade_lista(){
							var IdPais 	  	= document.formulario.IdPais.value;
							var IdEstado  	= document.formulario.IdEstado.value;
							var NomeCidade	= document.formularioCidade.NomeCidade.value;
							var Limit	  	= <?=getCodigoInterno(7,4)?>;
							var nameNode, nameTextNode, url;
							
							if(IdPais == ''){
								window.close();
							}
							
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
						    
						    if(NomeCidade == ''){
						    	url = "xml/cidade.php?IdPais="+IdPais+"&IdEstado="+IdEstado+"&Limit="+Limit;
							}else{
								url = "xml/cidade.php?IdPais="+IdPais+"&IdEstado="+IdEstado+"&NomeCidade="+NomeCidade;
							}
							
							xmlhttp.open("GET", url,true);
							
							xmlhttp.onreadystatechange = function(){ 
					
								// Carregando...
								carregando(true);
					
								if(xmlhttp.readyState == 4){ 
									if(xmlhttp.status == 200){
										if(xmlhttp.responseText == 'false'){
											document.getElementById('listaDadosQuadroCidade').innerHTML = "";
											// Fim de Carregando
											carregando(false);
										}else{
											var dados = "<table id='listaDados' style='width: 343px'>\n<tr>\n<td class='listaDados_titulo' style='width: 60px'>Cidade</td>\n<td class='listaDados_titulo'>Nome Cidade</td>\n</tr>";
											for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdCidade").length; i++){
													
												nameNode = xmlhttp.responseXML.getElementsByTagName("IdCidade")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var IdCidade = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("NomeCidade")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var NomeCidade = verifica_dado(nameTextNode.nodeValue);
												
												dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdCidade+"' onClick='aciona_busca_cidade(this,"+IdCidade+")'>";
												dados += 	"\n<td>"+IdCidade+"</td><td>"+NomeCidade+"</td>";
												dados += "\n</tr>";
											}
											dados += "\n</table>";
											document.getElementById('listaDadosQuadroCidade').innerHTML = dados;
										}
									}
								} 
								// Fim de Carregando
								carregando(false);
							}
							xmlhttp.send(null);
						}
						function aciona_busca_cidade(campo,valor){	
							var IdPais 	 = document.formulario.IdPais.value;
							var IdEstado = document.formulario.IdEstado.value;
							if(valorCampoCidade!=''){
								document.getElementById('listaDados_td_'+valorCampoCidade).style.backgroundColor = "#FFFFFF";
							}
							if(valorCampoCidade == valor){
								busca_cidade(IdPais,IdEstado,valor,true);
							}
							valorCampoCidade = valor;
							document.BuscaCidade.IdCidade.value = valorCampoCidade;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}
						function limpa_form_cidade(){
							document.formularioCidade.NomeCidade.value	=	"";
							
							valorCampoCidade = "";
						}
						enterAsTab(document.forms.formularioCidade);	
					</script>
				</div>
			</div>
