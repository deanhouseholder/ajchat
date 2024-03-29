/* Copyright (c) 2004-2005 The Dojo Foundation, Licensed under the Academic Free License version 2.1 or above */dojo.provide("dojo.widget.PopUpButton");
dojo.provide("dojo.widget.DomPopUpButton");
dojo.provide("dojo.widget.HtmlPopUpButton");

dojo.require("dojo.widget.Button");
dojo.require("dojo.widget.HtmlButton");

dojo.require("dojo.widget.Menu");
dojo.require("dojo.widget.MenuItem");

dojo.widget.tags.addParseTreeHandler("dojo:PopUpButton");

/* PopUpButton
 **************/
 
dojo.widget.PopUpButton = function () {
	dojo.widget.PopUpButton.superclass.constructor.call(this);
}
dj_inherits(dojo.widget.PopUpButton, dojo.widget.Widget);

dojo.lang.extend(dojo.widget.PopUpButton, {
	widgetType: "PopUpButton",
	
	label: ""
});


/* DomPopUpButton
 *****************/
dojo.widget.DomPopUpButton = function(){
	dojo.widget.DomPopUpButton.superclass.constructor.call(this);
}
dj_inherits(dojo.widget.DomPopUpButton, dojo.widget.DomWidget);

dojo.lang.extend(dojo.widget.DomPopUpButton, {
	widgetType: dojo.widget.PopUpButton.prototype.widgetType
});


/* HtmlPopUpButton
 ******************/

dojo.widget.HtmlPopUpButton = function () {
	dojo.widget.HtmlPopUpButton.superclass.constructor.call(this);
}
dj_inherits(dojo.widget.HtmlPopUpButton, dojo.widget.HtmlWidget);

dojo.lang.extend(dojo.widget.HtmlPopUpButton, {
	widgetType: dojo.widget.PopUpButton.prototype.widgetType,
	templateString: null,
	templateCssPath: dojo.uri.dojoUri("src/widget/templates/PopUpButton.css"),
	
	buildRendering: function (args, frag) {
		dojo.xml.htmlUtil.insertCssFile(this.templateCssPath, null, true);
	
		this.domNode = document.createElement("a");
		this.domNode.className = "PopUpButton";
		dojo.event.connect(this.domNode, "onmousedown", this, "onMouseDown");
		
		// draw the arrow
		var arrow = document.createElement("img");
		arrow.src = dojo.uri.dojoUri("src/widget/templates/images/dropdownButtonsArrow.gif");
		dojo.xml.htmlUtil.setClass(arrow, "downArrow");
		this.domNode.appendChild(arrow);

		this.menu = dojo.widget.fromScript("Menu");
		dojo.xml.htmlUtil.addClass(this.menu.domNode, "PopUpButtonMenu");
		dojo.event.connect(this.menu, "onSelect", this, "onSelect");
		
		if (frag["dojo:" + this.widgetType.toLowerCase()].nodeRef) {
			var node = frag["dojo:" + this.widgetType.toLowerCase()].nodeRef;
			var options = node.getElementsByTagName("option");
			for (var i = 0; i < options.length; i++) {
				var properties = {
					title: dojo.xml.domUtil.textContent(options[i]),
					value: options[i].value
				}
				this.addItem(dojo.widget.fromScript("MenuItem", properties));
			}
		}
	},

	addItem: function (item) {
		// TODO: should be dojo.widget.MenuItem
		if (item instanceof dojo.widget.HtmlMenuItem) {
			this.menu.push(item);
		} else {
			// TODO: create one
			var menuItem = dojo.widget.fromScript("MenuItem", {title: item});
			this.menu.push(menuItem);
		}
	},
	
	
/* Enabled utility methods
 **************************/
	
	_enabled: true,
	
	isEnabled: function() { return this._enabled; },
	
	setEnabled: function(enabled, force, preventEvent) {
		enabled = Boolean(enabled);
		if (force || this._enabled != enabled) {
			this._enabled = enabled;
			if (!preventEvent) {
				this._fireEvent(this._enabled ? "onEnable" : "onDisable");
				this._fireEvent("onChangeEnabled");
			}
		}
		
		dojo.xml.htmlUtil[(this._enabled ? "add" : "remove")
			+ "Class"](this.domNode, "disabled");
		
		return this._enabled;
	},
	
	enable: function(force, preventEvent) {
		return this.setEnabled(true, force, preventEvent);
	},
	
	disable: function(force, preventEvent) {
		return this.setEnabled(false, force, preventEvent);
	},
	
	toggleEnabled: function(force, preventEvent) {
		return this.setEnabled(!this._enabled, force, preventEvent);
	},


/* Select utility methods
 **************************/

	onSelect: function (item, e) {
		this.domNode.firstChild.nodeValue = item.title;
	},
	
	onMouseDown: function (e) {
		if (!this._menuVisible) {
			this._showMenu(e);
			dojo.lang.setTimeout(dojo.event.connect, 1, document, "onmousedown", this, "_hideMenu");
		}
	},
	
	
	_fireEvent: function(evt) {
		if(typeof this[evt] == "function") {
			var args = [this];
			for(var i = 1; i < arguments.length; i++) {
				args.push(arguments[i]);
			}
			this[evt].apply(this, args);
		}
	},

	
	_showMenu: function (e) {
		if (!this._enabled) { return; }
		this._menuVisible = true;
		with (dojo.xml.htmlUtil) {
			var y = getAbsoluteY(this.domNode) + getInnerHeight(this.domNode);
			var x = getAbsoluteX(this.domNode);
		}
	
		document.body.appendChild(this.menu.domNode);
		with (this.menu.domNode.style) {
			top = y + "px";
			left = x + "px";
		}
	},
	
	_hideMenu: function (e) {
		this.menu.domNode.parentNode.removeChild(this.menu.domNode);
		dojo.event.disconnect(document, "onmousedown", this, "_hideMenu");
		this._menuVisible = false;
	}

});
