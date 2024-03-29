/* Copyright (c) 2004-2005 The Dojo Foundation, Licensed under the Academic Free License version 2.1 or above */dojo.provide("dojo.widget.ComboBox");

dojo.require("dojo.widget.*");
dojo.require("dojo.event.*");

dojo.widget.ComboBoxDataProvider = function(dataPairs, limit, timeout){
	// NOTE: this data provider is designed as a naive reference
	// implementation, and as such it is written more for readability than
	// speed. A deployable data provider would implement lookups, search
	// caching (and invalidation), and a significantly less naive data
	// structure for storage of items.

	this.data = [];
	this.searchTimeout = 500;
	this.searchLimit = 30;
	this.searchType = "STARTSTRING"; // may also be "STARTWORD" or "SUBSTRING"
	this.caseSensitive = false;
	// for caching optimizations
	this._lastSearch = "";
	this._lastSearchResults = null;

	this.startSearch = function(searchStr, type, ignoreLimit){
		// FIXME: need to add timeout handling here!!
		this._preformSearch(searchStr, type, ignoreLimit);
	}

	this._preformSearch = function(searchStr, type, ignoreLimit){
		//
		//	NOTE: this search is LINEAR, which means that it exhibits perhaps
		//	the worst possible speed charachteristics of any search type. It's
		//	written this way to outline the responsibilities and interfaces for
		//	a search.
		//
		var st = type||this.searchType;
		// FIXME: this is just an example search, which means that we implement
		// only a linear search without any of the attendant (useful!) optimizations
		var ret = [];
		if(!this.caseSensitive){
			searchStr = searchStr.toLowerCase();
		}
		for(var x=0; x<this.data.length; x++){
			if((!ignoreLimit)&&(ret.length >= this.searchLimit)){
				break;
			}
			// FIXME: we should avoid copies if possible!
			var dataLabel = new String((!this.caseSensitive) ? this.data[x][0].toLowerCase() : this.data[x][0]);
			if(dataLabel.length < searchStr.length){
				// this won't ever be a good search, will it? What if we start
				// to support regex search?
				continue;
			}

			if(st == "STARTSTRING"){
				// jum.debug(dataLabel.substr(0, searchStr.length))
				// jum.debug(searchStr);
				if(searchStr == dataLabel.substr(0, searchStr.length)){
					ret.push(this.data[x]);
				}
			}else if(st == "SUBSTRING"){
				// this one is a gimmie
				if(dataLabel.indexOf(searchStr) >= 0){
					ret.push(this.data[x]);
				}
			}else if(st == "STARTWORD"){
				// do a substring search and then attempt to determine if the
				// preceeding char was the beginning of the string or a
				// whitespace char.
				var idx = dataLabel.indexOf(searchStr);
				if(idx == 0){
					// implicit match
					ret.push(this.data[x]);
				}
				if(idx <= 0){
					// if we didn't match or implicily matched, march onward
					continue;
				}
				// otherwise, we have to go figure out if the match was at the
				// start of a word...
				// this code is taken almost directy from nWidgets
				var matches = false;
				while(idx!=-1){
					// make sure the match either starts whole string, or
					// follows a space, or follows some punctuation
					if(" ,/(".indexOf(dataLabel.charAt(idx-1)) != -1){
						// FIXME: what about tab chars?
						matches = true; break;
					}
					idx = dataLabel.indexOf(searchStr, tti+1);
				}
				if(!matches){
					continue;
				}else{
					ret.push(this.data[x]);
				}
			}
		}
		this.provideSearchResults(ret);
	}

	this.provideSearchResults = function(resultsDataPairs){
	}

	this.addData = function(pairs){
		// FIXME: incredibly naive and slow!
		this.data = this.data.concat(pairs);
	}

	this.setData = function(pdata){
		// populate this.data and initialize lookup structures
		this.data = pdata;
	}
	
	if(dataPairs){
		this.setData(dataPairs);
	}
}

dojo.widget.ComboBox = function(){
	dojo.widget.Widget.call(this);

	this.widgetType = "ComboBox";
	this.isContainer = false;

	this.forceValidOption = false;
	this.searchType = "stringstart";
	this.dataProvider = new dojo.widget.ComboBoxDataProvider();

	this.startSearch = function(searchString){}
	this.openResultList = function(results){}
	this.clearResultList = function(){}
	this.hideResultList = function(){}
	this.selectNextResult = function(){}
	this.selectPrevResult = function(){}
	this.setSelectedRsult = function(){}
}

dj_inherits(dojo.widget.ComboBox, dojo.widget.Widget);

dojo.widget.DomComboBox = function(){
	dojo.widget.ComboBox.call(this);
	dojo.widget.DomWidget.call(this, true);
}

dj_inherits(dojo.widget.DomComboBox, dojo.widget.DomWidget);
dojo.widget.tags.addParseTreeHandler("dojo:combobox");

