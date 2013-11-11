	function busca_grupo_permissao(IdGrupoPermissao, Erro, Local){
		if(IdGrupoPermissao == ''){
			IdGrupoPermissao = 0;
		}
		
		var nameNode, nameTextNode, url;
		
		url = "xml/grupo_permissao.php?IdGrupoPermissao="+IdGrupoPermissao;
		
		call_ajax(url, function(xmlhttp){
			if(Erro != false){
				document.formulario.Erro.value = 0;
				verificaErro();
			}			
			if(xmlhttp.responseText == 'false'){
				
				document.formulario.IdGrupoPermissao.value 			= '';
				document.formulario.DescricaoGrupoPermissao.value 	= '';
				
				document.formulario.IdGrupoPermissao.focus();
				
				listar_grupo_permissao(document.formulario.IdLoja.value,IdGrupoPermissao)
				// Fim de Carregando
				carregando(false);
			}else{
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoPermissao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				IdGrupoPermissao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoGrupoPermissao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoGrupoPermissao = nameTextNode.nodeValue;
				
				addParmUrl("marGrupoPermissao","IdGrupoPermissao",IdGrupoPermissao);
				addParmUrl("marGrupoPermissaoPermissao","IdGrupoPermissao",IdGrupoPermissao);
				
				document.formulario.IdGrupoPermissao.value			= IdGrupoPermissao;
				document.formulario.DescricaoGrupoPermissao.value 	= DescricaoGrupoPermissao;
				
				listar_grupo_permissao(document.formulario.IdLoja.value,IdGrupoPermissao);
			}
			
			busca_modulo();
			
			if(document.getElementById("quadroBuscaGrupoPermissao") != null){
				if(document.getElementById("quadroBuscaGrupoPermissao").style.display	==	"block"){
					document.getElementById("quadroBuscaGrupoPermissao").style.display = "none";
				}
			}
			verificaAcao();
			verifica_root_grupo_admin();
		});
	}

	function busca_modulo(){
		var nameNode, nameTextNode, url;
		
		var Login 				= document.formulario.LoginEditor.value;
		var IdLoja 				= document.formulario.IdLoja.value;	
		var IdGrupoPermissao	= document.formulario.IdGrupoPermissao.value;
		
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
		
		url = "xml/usuario_modulo.php?Login="+Login+"&IdLoja="+IdLoja;
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false' || IdGrupoPermissao == ''){
						while(document.formulario.IdModulo.options.length > 0){
							document.formulario.IdModulo.options[0] = null;
						}
						// Fim de Carregando
						carregando(false);
					}else{
						while(document.formulario.IdModulo.options.length > 0){
							document.formulario.IdModulo.options[0] = null;
						}

						for(i=0; i<xmlhttp.responseXML.getElementsByTagName("IdModulo").length; i++){
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdModulo")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdModulo = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoModulo")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoModulo = nameTextNode.nodeValue;
							
							addOption(document.formulario.IdModulo,DescricaoModulo,IdModulo);
						}
						document.formulario.IdModulo.options[0].selected = true;
					}
					
					busca_operacao();
					
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}

	function busca_operacao(){
		var nameNode, nameTextNode, url;
		
		var Login 		= document.formulario.LoginEditor.value;
		var IdLoja 		= document.formulario.IdLoja.value;
		var IdModulo	= document.formulario.IdModulo.value;
		
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
		
		url = "xml/operacao.php?IdLoja="+IdLoja+"&IdModulo="+IdModulo;
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){					
						while(document.formulario.IdOperacao.options.length > 0){
							document.formulario.IdOperacao.options[0] = null;
						}					
						// Fim de Carregando
						carregando(false);
					}else{
						while(document.formulario.IdOperacao.options.length > 0){
							document.formulario.IdOperacao.options[0] = null;
						}

						for(i=0; i<xmlhttp.responseXML.getElementsByTagName("IdOperacao").length; i++){
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdOperacao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdOperacao = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeOperacao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							NomeOperacao = nameTextNode.nodeValue;
							
							addOption(document.formulario.IdOperacao,NomeOperacao,IdOperacao);
						}					
						document.formulario.IdOperacao.options[0].selected = true;					
					}
					atualiza_sub_operacao();
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}

	function atualiza_sub_operacao(){
		busca_sub_operacao_opcoes();
		busca_sub_operacao_permissoes();
	}

	function busca_sub_operacao_opcoes(){
		var nameNode, nameTextNode, url;
		
		var IdGrupoPermissao	= document.formulario.IdGrupoPermissao.value;
		var LoginEditor			= document.formulario.LoginEditor.value;
		var IdLoja 				= document.formulario.IdLoja.value;
		var IdModulo			= document.formulario.IdModulo.value;
		var IdOperacao			= document.formulario.IdOperacao.value;
		
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
		
		url = "xml/grupo_permissao_sub_operacao_opcoes.php?IdGrupoPermissao="+IdGrupoPermissao+"&LoginEditor="+LoginEditor+"&IdLoja="+IdLoja+"&IdModulo="+IdModulo+"&IdOperacao="+IdOperacao;
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
//					alert(xmlhttp.responseText+'\n'+IdGrupoPermissao);
					if(xmlhttp.responseText == 'false'){
						
						while(document.formulario.IdOperacaoOpcao.options.length > 0){
							document.formulario.IdOperacaoOpcao.options[0] = null;
						}
						
						// Fim de Carregando
						carregando(false);
					}else{
						while(document.formulario.IdOperacaoOpcao.options.length > 0){
							document.formulario.IdOperacaoOpcao.options[0] = null;
						}

						for(i=0; i<xmlhttp.responseXML.getElementsByTagName("IdSubOperacao").length; i++){
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdSubOperacao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdSubOperacao = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoSubOperacao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoSubOperacao = nameTextNode.nodeValue;
							
							addOption(document.formulario.IdOperacaoOpcao,DescricaoSubOperacao,IdSubOperacao);
						}
					}
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}

	function busca_sub_operacao_permissoes(){

		var nameNode, nameTextNode, url;
		
		var IdGrupoPermissao	= document.formulario.IdGrupoPermissao.value;
		var LoginEditor			= document.formulario.LoginEditor.value;
		var IdLoja 				= document.formulario.IdLoja.value;
		var IdModulo			= document.formulario.IdModulo.value;
		var IdOperacao			= document.formulario.IdOperacao.value;
		
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
		
		url = "xml/grupo_permissao_sub_operacao.php?IdGrupoPermissao="+IdGrupoPermissao+"&LoginEditor="+LoginEditor+"&IdLoja="+IdLoja+"&IdModulo="+IdModulo+"&IdOperacao="+IdOperacao;
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
//					alert(xmlhttp.responseText+'\n'+IdGrupoPermissao);
					while(document.formulario.IdOperacaoPermissao.options.length > 0){
						document.formulario.IdOperacaoPermissao.options[0] = null;
					}
					
					if(xmlhttp.responseText == 'false' || IdGrupoPermissao==''){
						// Fim de Carregando
						carregando(false);
					}else{
						while(document.formulario.IdOperacaoPermissao.options.length > 0){
							document.formulario.IdOperacaoPermissao.options[0] = null;
						}

						for(i=0; i<xmlhttp.responseXML.getElementsByTagName("IdSubOperacao").length; i++){
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdSubOperacao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdSubOperacao = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoSubOperacao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoSubOperacao = nameTextNode.nodeValue;
							
							addOption(document.formulario.IdOperacaoPermissao,DescricaoSubOperacao,IdSubOperacao);
						}
						
						document.formulario.LoginCriacao.value 	= "";
						document.formulario.DataCriacao.value	= "";
						
						busca_sub_operacao_permissoes_dados();
					}
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}

	function busca_sub_operacao_permissoes_dados(){
		var i=0, selecionado=0;
		
		document.formulario.LoginCriacao.value 	= "";
		document.formulario.DataCriacao.value	= "";
		
		for(i=0; i<document.formulario.IdOperacaoPermissao.length; i++){
			if(document.formulario.IdOperacaoPermissao[i].selected == true){
				selecionado++;
			}
		}
		if(selecionado != 1){
			return false;
		}
		
		var nameNode, nameTextNode, url;
		
		var IdGrupoPermissao	= document.formulario.IdGrupoPermissao.value;
		var LoginEditor 		= document.formulario.LoginEditor.value;
		var IdLoja 				= document.formulario.IdLoja.value;
		var IdModulo			= document.formulario.IdModulo.value;
		var IdOperacao			= document.formulario.IdOperacao.value;
		var IdSubOperacao		= document.formulario.IdOperacaoPermissao.value;
		
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
		
		url = "xml/grupo_permissao_sub_operacao.php?IdGrupoPermissao="+IdGrupoPermissao+"&LoginEditor="+LoginEditor+"&IdLoja="+IdLoja+"&IdModulo="+IdModulo+"&IdOperacao="+IdOperacao+"&IdSubOperacao="+IdSubOperacao;
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					//alert(xmlhttp.responseText);
					if(xmlhttp.responseText != 'false'){
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataCriacao = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var LoginCriacao = nameTextNode.nodeValue;
					
						document.formulario.LoginCriacao.value 	= LoginCriacao;
						document.formulario.DataCriacao.value	= dateFormat(DataCriacao);
					}
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);	
	}

	function quadro_permissao(Acao){
		var i, count=0, IdSubOperacao, DescricaoSubOperacao, Msg;
		var IdGrupoPermissao	= document.formulario.IdGrupoPermissao.value;
		var IdLoja 				= document.formulario.IdLoja.value;
		var IdModulo			= document.formulario.IdModulo.value;
		var IdOperacao			= document.formulario.IdOperacao.value;
		var Login				= document.formulario.LoginEditor.value;
		
		if(IdGrupoPermissao == ""){
			return false;
		}
		
		if(IdGrupoPermissao == 1){
			switch(Login){
				case 'root':
					switch(Acao){
						case 'add':
							Msg = "ATENÇÃO!\nDeseja realmente atribuir esta permissão a este usuário?";
							
							for(i=0; i<document.formulario.IdOperacaoOpcao.length; i++){
								if(document.formulario.IdOperacaoOpcao[i].selected == true){
									count++;
								}
							}
							
							if(count>0 && confirm(Msg) == false){
								return false;
							}
							
							while(count > 0){
								for(i=0; i<document.formulario.IdOperacaoOpcao.length; i++){
									if(document.formulario.IdOperacaoOpcao[i].selected == true){
										IdSubOperacao = document.formulario.IdOperacaoOpcao[i].value;
										DescricaoSubOperacao = document.formulario.IdOperacaoOpcao[i].text;
										document.formulario.IdOperacaoOpcao[i] = null;
										
			//							addOption(document.formulario.IdOperacaoPermissao,DescricaoSubOperacao,IdSubOperacao);
										seta_permissao_grupo(IdGrupoPermissao, IdLoja, IdModulo, IdOperacao, IdSubOperacao, Acao);
										count--;						
										break;
									}			
								}
							}	
							break;
						case 'rem':
							Msg = "ATENÇÃO!\nDeseja realmente remover esta permissão deste usuário?";
							
							for(i=0; i<document.formulario.IdOperacaoPermissao.length; i++){
								if(document.formulario.IdOperacaoPermissao[i].selected == true){
									count++;
								}
							}
							
							if(count>0 && confirm(Msg) == false){
								return false;
							}
							
							while(count > 0){
								for(i=0; i<document.formulario.IdOperacaoPermissao.length; i++){
									if(document.formulario.IdOperacaoPermissao[i].selected == true){
										IdSubOperacao = document.formulario.IdOperacaoPermissao[i].value;
										DescricaoSubOperacao = document.formulario.IdOperacaoPermissao[i].text;
										document.formulario.IdOperacaoPermissao[i] = null;
										
			//							addOption(document.formulario.IdOperacaoOpcao,DescricaoSubOperacao,IdSubOperacao);
										seta_permissao_grupo(IdGrupoPermissao, IdLoja, IdModulo, IdOperacao, IdSubOperacao, Acao);
										count--;
										break;
									}			
								}
							}
							break;
					}
					break;
			}
					
		}else{
			switch(Acao){
				case 'add':
					Msg = "ATENÇÃO!\nDeseja realmente atribuir esta permissão a este usuário?";
					
					for(i=0; i<document.formulario.IdOperacaoOpcao.length; i++){
						if(document.formulario.IdOperacaoOpcao[i].selected == true){
							count++;
						}
					}
					
					if(count>0 && confirm(Msg) == false){
						return false;
					}
					
					while(count > 0){
						for(i=0; i<document.formulario.IdOperacaoOpcao.length; i++){
							if(document.formulario.IdOperacaoOpcao[i].selected == true){
								IdSubOperacao = document.formulario.IdOperacaoOpcao[i].value;
								DescricaoSubOperacao = document.formulario.IdOperacaoOpcao[i].text;
								document.formulario.IdOperacaoOpcao[i] = null;
								
	//							addOption(document.formulario.IdOperacaoPermissao,DescricaoSubOperacao,IdSubOperacao);
								seta_permissao_grupo(IdGrupoPermissao, IdLoja, IdModulo, IdOperacao, IdSubOperacao, Acao);
								count--;						
								break;
							}			
						}
					}	
					break;
				case 'rem':
					Msg = "ATENÇÃO!\nDeseja realmente remover esta permissão deste usuário?";
					
					for(i=0; i<document.formulario.IdOperacaoPermissao.length; i++){
						if(document.formulario.IdOperacaoPermissao[i].selected == true){
							count++;
						}
					}
					
					if(count>0 && confirm(Msg) == false){
						return false;
					}
					
					while(count > 0){
						for(i=0; i<document.formulario.IdOperacaoPermissao.length; i++){
							if(document.formulario.IdOperacaoPermissao[i].selected == true){
								IdSubOperacao = document.formulario.IdOperacaoPermissao[i].value;
								DescricaoSubOperacao = document.formulario.IdOperacaoPermissao[i].text;
								document.formulario.IdOperacaoPermissao[i] = null;
								
	//							addOption(document.formulario.IdOperacaoOpcao,DescricaoSubOperacao,IdSubOperacao);
								seta_permissao_grupo(IdGrupoPermissao, IdLoja, IdModulo, IdOperacao, IdSubOperacao, Acao);
								count--;
								break;
							}			
						}
					}
					break;
			}
		}
		
		busca_sub_operacao_permissoes_dados();
		listar_grupo_permissao(IdLoja,IdGrupoPermissao);
	}

	function seta_permissao_grupo(IdGrupoPermissao, IdLoja, IdModulo, IdOperacao, IdSubOperacao, Acao){
		var xmlhttp   = false, retorno = true;
		
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
		
		url = "files/editar/editar_grupo_permissao_permissao.php?IdGrupoPermissao="+IdGrupoPermissao+"&IdLoja="+IdLoja+"&IdModulo="+IdModulo+"&IdOperacao="+IdOperacao+"&IdSubOperacao="+IdSubOperacao+"&Acao="+Acao;
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 
			// Carregando...
			carregando(true);
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
//					alert(xmlhttp.responseText);
					var res = xmlhttp.responseText;
					document.formulario.Erro.value = res;
					
					atualiza_sub_operacao();
					verificaErro();
				}
			}
			// Fim de Carregando
			carregando(false);
			return true;
		}
		xmlhttp.send(null);	
	}
	function verifica_root_grupo_admin(){
		var IdGrupoPermissao	= document.formulario.IdGrupoPermissao.value;
		var Login				= document.formulario.LoginEditor.value;
		
		if(IdGrupoPermissao == 1 &&  Login != 'root'){
			document.formulario.add.src = "../../img/estrutura_sistema/ico_seta_left_c.gif";
			document.formulario.add.onclick = function(){};
			document.formulario.rem.src = "../../img/estrutura_sistema/ico_seta_right_c.gif";
			document.formulario.rem.onclick = function(){};
		}else if(IdGrupoPermissao == 1 && Login == 'root'){
			document.formulario.add.src = "../../img/estrutura_sistema/ico_seta_left.gif";
			document.formulario.rem.src = "../../img/estrutura_sistema/ico_seta_right.gif";
		}else{
			document.formulario.add.src = "../../img/estrutura_sistema/ico_seta_left.gif";
			document.formulario.rem.src = "../../img/estrutura_sistema/ico_seta_right.gif";
		}
	}