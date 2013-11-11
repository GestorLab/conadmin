	//Inicio da funcão para adicionar um título em um objeto.
	function LatLngControl(map,content){   
		this.ANCHOR_OFFSET_ = new google.maps.Point(8, 8);
		this.node_ = this.createHtmlNode_();
		map.controls[google.maps.ControlPosition.TOP].push(this.node_);
		this.setMap(map);
		this.set('visible', false);
		this.content = content;
	}
	
	
	LatLngControl.prototype = new google.maps.OverlayView();
	LatLngControl.prototype.draw = function() {};

	LatLngControl.prototype.createHtmlNode_ = function() {		
		var divNode = document.createElement('div');
		divNode.id = 'latlng-control';
		divNode.style.background 	= '#FFFFE1';
		divNode.style.borderLeft	= "1px solid #9D9D9D";
		divNode.style.borderTop		= "1px solid #9D9D9D";
		divNode.style.borderRight	= "1px solid #000";
		divNode.style.borderBottom  = "1px solid #000";
		divNode.style.marginTop 	= "10px";		
		
		divNode.index = 100;
		return divNode;
	};

	LatLngControl.prototype.visible_changed = function() {
		this.node_.style.display = this.get('visible') ? '' : 'none';
	};

	LatLngControl.prototype.updatePosition = function(latLng) {
		var projection = this.getProjection();
		var point = projection.fromLatLngToContainerPixel(latLng);


		this.node_.style.left = point.x + this.ANCHOR_OFFSET_.x + 'px';
		this.node_.style.top = point.y + this.ANCHOR_OFFSET_.y + 'px';

		this.node_.innerHTML = [this.content].join('');
	};