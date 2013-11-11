	function busca_device(IdDevice,IdGrupoDevice,Erro, Local){
		if(IdDevice == ''){
			IdDevice = document.formulario.IdDevice.value;
		}
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
	    
	   	url = "xml/device.php?IdDevice="+IdDevice+"&IdGrupoDevice="+IdGrupoDevice;
		call_ajax(url,function (xmlhttp){	
			if(Erro != false){
				document.formulario.Erro.value = 0;
				verificaErro();
			}			
			if(xmlhttp.responseText == 'false'){						
					document.formulario.IdDevice.value				= '';
					document.formulario.DescricaoDevice.value		= '';						
					document.formulario.IdTipoDevice.value			= '';						
					document.formulario.Obs.value					= '';						
					document.formulario.DescricaoGrupoDevice.value	= '';						
					document.formulario.IdGrupoDevice.value			= '';						
					document.formulario.LoginCriacao.value			= '';						
					document.formulario.DataCriacao.value			= '';						
					document.formulario.LoginAlteracao.value		= '';						
					document.formulario.DataAlteracao.value			= '';
					document.formulario.Acao.value					= 'inserir';						
					document.formulario.IdDevice.focus();	
				
				// Fim de Carregando
				carregando(false);
			}else{
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdDevice")[0]; 
				nameTextNode = nameNode.childNodes[0];
				IdDevice = nameTextNode.nodeValue;						
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoDevice")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoDevice = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoDevice")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdTipoDevice = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoDevice")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdGrupoDevice = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Observacao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Observacao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LoginCriacao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataCriacao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAlteracao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LoginAlteracao = nameTextNode.nodeValue;

				nameNode = xmlhttp.responseXML.getElementsByTagName("DataAlteracao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataAlteracao = nameTextNode.nodeValue;	
				
				document.formulario.IdDevice.value				= IdDevice;
				document.formulario.DescricaoDevice.value		= DescricaoDevice;						
				document.formulario.IdTipoDevice.value			= IdTipoDevice;						
				document.formulario.IdGrupoDevice.value			= IdGrupoDevice;						
				document.formulario.Obs.value					= Observacao;						
				document.formulario.LoginCriacao.value			= LoginCriacao;						
				document.formulario.DataCriacao.value			= DataCriacao;						
				document.formulario.LoginAlteracao.value		= LoginAlteracao;						
				document.formulario.DataAlteracao.value			= DataAlteracao;						
				document.formulario.Acao.value					= 'alterar';
				busca_grupo_device(document.formulario.IdGrupoDevice.value,true,document.formulario.Local.value);
				
			}					
			verificaAcao();				
		})
	}		