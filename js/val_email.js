function isEmail(nform){
		if (nform == "") {
			return false;
		}else {
			prim = nform.indexOf("@")
			if(prim < 2) {
				return false;
			}
			if(nform.indexOf("@",prim + 1) != -1) {
				return false;
			}
			if(nform.indexOf(".") < 1) {
				return false;
			}
			if(nform.indexOf(" ") != -1) {
				return false;
			}
			if(nform.indexOf(".@") > 0) {
				return false;
			}
			if(nform.indexOf("@.") > 0) {
				return false;
			}
			if(nform.indexOf("/") > 0) {
				return false;
			}
			if(nform.indexOf("[") > 0) {
				return false;
			}
			if(nform.indexOf("]") > 0) {
				return false;
			}
			if(nform.indexOf("(") > 0) {
				return false;
			}
			if(nform.indexOf(")") > 0) {
				return false;
			}
			if(nform.indexOf("..") > 0) {
				return false;
			}
			if(nform.indexOf("*") > 0) {
				return false;
			}
			if(nform.indexOf("+") > 0) {
				return false;
			}
		}
		return true;
	}
