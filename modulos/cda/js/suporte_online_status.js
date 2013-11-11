	function createXMLHttpRequest(){
		if(window.ActiveXObject){
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		} else{
			if(window.XMLHttpRequest){
				xmlHttp = new XMLHttpRequest();
			}
		}
	}
	function startRequest(invitee){
		createXMLHttpRequest();
		
		var url = "./xml/suporte_online_status.php?invitee="+invitee;
		xmlHttp.open("GET", url, true);
		xmlHttp.onreadystatechange = function(){
			if(xmlHttp.readyState==4){
				if(xmlHttp.status==200){
					if(xmlHttp.responseText!=""){
						var elementIcon = document.getElementById('icon');
						var response = xmlHttp.responseText;
						response = response.replace(/&quot;/gi,"");
						response = response.replace(/icon:/gi,"");
						response = response.split(',');
						
						try{
							var statusText = response[3].split(':');
							
							if (elementIcon.title!=statusText[1]){
								elementIcon.src = "./img/"+statusText[1]+".gif";
								elementIcon.alt = statusText[1];
								elementIcon.title = statusText[1];
								document.getElementById('status').innerHTML = "("+statusText[1]+")";
							}
							
							setTimeout("startRequest('"+invitee+"');",300);
						} catch(e){
							setTimeout("startRequest('"+invitee+"');",100);
						}
					}
				}
			}
			return true;
		}
		xmlHttp.send(null);
	}
