			<div id='quadroBuscaProduto' style='width:700px;' class='quadroFlutuante'>	
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Produto</td>
						<td class='fecha' onClick="vi_id('quadroBuscaProduto', false);">X</div></td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='formularioProduto' method='post'>
						<table>
							<tr>
								<td class='descCampo'>Nome Produto</td>								
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='DescricaoProduto' autocomplete="off" value='' style='width:664px' maxlength='100' onkeyup="busca_produto_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
								</td>								
							</tr>
						</table>
						<table>
							<tr>
								<td class='descCampo'>Nome Fabricante</td>
								<td class='descCampo'>Nome Grupo Produto</td>
								<td class='descCampo'>Nome SubGrupo Produto</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='DescricaoFabricante' autocomplete="off" value='' style='width:216px' maxlength='100' onkeyup="busca_produto_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
								</td>
								<td class='campo'>
									<input type='text' name='DescricaoGrupoProduto' autocomplete="off" value='' style='width:216px' maxlength='100' onkeyup="busca_produto_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
								</td>
								<td class='campo'>
									<input type='text' name='DescricaoSubGrupoProduto' autocomplete="off" value='' style='width:216px' maxlength='100' onkeyup="busca_produto_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 200px;'>
						<div id='listaDadosQuadroProduto'>&nbsp;</div>
					</div>
					<form name='BuscaProduto' method='post' onSubmit='return validar_busca_produto()'>
						<input type='hidden' name='IdProduto' value=''>
						<input type='hidden' name='pos' value=''>
						<table class='listaDados'>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaProduto', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>
					<script>
						// Para que os quadros flutuem
						new Draggable('quadroBuscaProduto');
						
						var valorCampoProduto = '';
						function validar_busca_produto(){
							if(valorCampoProduto !=''){							
								busca_produto(valorCampoProduto,'false',document.formulario.Local.value,document.BuscaProduto.pos.value);
							}
							return false;
						}
						function busca_produto_lista(){				
							var DescricaoProduto 			= document.formularioProduto.DescricaoProduto.value;
							var DescricaoFabricante			= document.formularioProduto.DescricaoFabricante.value;
							var DescricaoGrupoProduto		= document.formularioProduto.DescricaoGrupoProduto.value;
							var DescricaoSubGrupoProduto	= document.formularioProduto.DescricaoSubGrupoProduto.value;
							var Local						= document.formulario.Local.value;
							var Limit	  					= <?=getCodigoInterno(7,4)?>;
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
						    if(DescricaoProduto == '' && DescricaoFabricante == '' && DescricaoGrupoProduto == '' && DescricaoSubGrupoProduto == ''){
						    	url = "../administrativo/xml/produto_busca.php?Limit="+Limit;
							}else{
								url = "../administrativo/xml/produto_busca.php?DescricaoProduto="+DescricaoProduto+"&DescricaoFabricante="+DescricaoFabricante+"&DescricaoGrupoProduto="+DescricaoGrupoProduto+"&DescricaoSubGrupoProduto="+DescricaoSubGrupoProduto;
							}
							
							xmlhttp.open("GET", url,true);
							
							xmlhttp.onreadystatechange = function(){ 
					
								// Carregando...
								carregando(true);
					
								if(xmlhttp.readyState == 4){ 
									if(xmlhttp.status == 200){										
										if(xmlhttp.responseText == 'false'){
											document.getElementById('listaDadosQuadroProduto').innerHTML = "";
											// Fim de Carregando
											carregando(false);
										}else{
											var dados = "<table id='listaDados' style='width: 673px'>\n<tr>\n<td class='listaDados_titulo' style='width: 60px'>Produto</td>\n<td class='listaDados_titulo'>Nome Produto</td>\n<td class='listaDados_titulo' style='width:130px'>Nome Fabricante</td>\n</tr>";
											for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdProduto").length; i++){
													
												nameNode = xmlhttp.responseXML.getElementsByTagName("IdProduto")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var IdProduto = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoProduto")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var DescricaoProduto = verifica_dado(nameTextNode.nodeValue);
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoFabricante")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var DescricaoFabricante = verifica_dado(nameTextNode.nodeValue);
												
												DescricaoFabricante 	= DescricaoFabricante.substring(0,30);

												dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdProduto+"' onClick='aciona(this,"+IdProduto+")'>";
												dados += 	"\n<td>"+IdProduto+"</td><td>"+DescricaoProduto+"</td><td>"+DescricaoFabricante+"</td>";
												dados += "\n</tr>";
											}
											dados += "\n</table>";
											document.getElementById('listaDadosQuadroProduto').innerHTML = dados;
										}
									}
								} 
								// Fim de Carregando
								carregando(false);
							}
							xmlhttp.send(null);
						}
						function aciona(campo,valor){
							if(valorCampoProduto!=''){
								document.getElementById('listaDados_td_'+valorCampoProduto).style.backgroundColor = "#FFFFFF";
							}
							if(valorCampoProduto == valor){
								busca_produto(valor,false,document.formulario.Local.value,document.BuscaProduto.pos.value);
							}
							valorCampoProduto = valor;
							document.BuscaProduto.IdProduto.value = valorCampoProduto;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}
						function limpa_form_produto(){
							document.formularioProduto.DescricaoProduto.value			=	"";
							document.formularioProduto.DescricaoFabricante.value		=	"";
							document.formularioProduto.DescricaoGrupoProduto.value		=	"";
							document.formularioProduto.DescricaoSubGrupoProduto.value	=	"";
							
							valorCampoProduto = "";
						}
						enterAsTab(document.forms.formularioProduto);
					</script>
				</div>
			</div>	
