/*
 * BetterTip
 * Created by Edgar Verle
 * BetterTip is made for the jQuery library.
 */
jQuery(function($) {
$(BT_init);

var BT_open_wait = 500; //time in millis to wait before showing dialog
var BT_close_wait = 0; //time in millis to wait before closing dialog
var BT_cache_enabled = true;
var BT_events = new Array();
var BT_titles = new Array();

function BT_init(){
	
    jQuery("a.betterTip").parent("div.betterTip")
    .hover(function(){BT_hoverIn(jQuery(this).children("a.betterTip")[0]); return false;},
    	   function(){BT_hoverOut(jQuery(this).children("a.betterTip")[0])})
    ;
    
    jQuery("a.betterTip").filter(function(index){
    	return jQuery(this).parent("div.betterTip").length == 0;
    })
    .hover(function(){BT_hoverIn(this)}, function(){BT_hoverOut(this)})
	.click(function(){return false});	
}

function BT_setOptions(hash)
{
	if(hash["openWait"] != null)
		BT_open_wait = hash["openWait"];
	if(hash["closeWait"] != null)
		BT_close_wait = hash["closeWait"];
	if(hash["cacheEnabled"] != null)
		BT_cache_enabled = hash["cacheEnabled"];
}

function BT_hoverIn(a)
{
	var timeout = BT_open_wait;
	
	if(jQuery('#BT_cache_'+a.id).length > 0)
		timeout = 0;
	
	var title = a.title;
	
	if(!BT_titles[a.id])
	{
		if(!title || title.toLowerCase() == "$none")
			title = "";
		else if(title.toLowerCase() == "$content")
			title = jQuery(a).text();
		
		BT_titles[a.id] = title;
		a.title = "";
	}
		
	BT_events[a.id] = 1;
	setTimeout(function(){BT_show(a.id)}, BT_open_wait);
}

function BT_hoverOut(a)
{
	BT_events[a.id] = 0;
	setTimeout(function(){BT_remove();}, BT_close_wait);
}

function BT_remove()
{
	jQuery('#BT').remove();
}

function BT_show(id) {

	if(BT_events[id] == 0)
		return;
	
	var obj = jQuery("#"+id);
	var url = obj[0].href;
	var title = BT_titles[id];
	
	jQuery("#BT").remove();

	var parents = jQuery("#"+id).parent("div.betterTip");
	
	if(parents.length > 0)
		id = jQuery("#"+id).parent("div.betterTip")[0].id;

	obj = jQuery("#"+id);
	
	var showTitle = true;

	if(title.length == 0)
		showTitle = false;
	
	var urlParts = url.split("\?", 2);
	var query = BT_parseQuery(urlParts[1]);
	urlParts[0] = urlParts[0].substr(urlParts[0].lastIndexOf('/')+1);
	
	if(!query["width"] || query["width"].length == 0)
		query["width"] = 250;
	
	var tipWidth = parseInt(query["width"]);
	
	var act_left = BT_getLeft(id);
	var act_width = BT_getWidth(id);
				
	var left = act_left + act_width + 12;
	var top = BT_getTop(id);
	
	var arrowDir = "left";
	
	var docWidth = self.innerWidth || (document.documentElement&&document.documentElement.clientWidth) || document.body.clientWidth;
	var right = act_left + act_width + 11 + tipWidth + 20;
	var arrowLeft = -10;
	var arrowTop = -3;
	var shadowTop = -7;
	var shadowLeft = -7;

	if(docWidth < right)
	{
		arrowDir = "right";
		left = act_left - 12 - tipWidth;
		arrowLeft = tipWidth;
		arrowTop = -1;
		
		if(document.all)
			arrowLeft -= 2;
	}
	else if(showTitle)
		arrowTop = -2;
	
	if(showTitle)
		arrowDir = "title_" +arrowDir;
	
	jQuery("body").append(
		"<div id='BT' class='BT_shadow0' style='top:"+(top-shadowTop-8)+"px; left:"+(left-shadowLeft - 8)+"px;'>" +
		"<div class='BT_shadow1'>"+
		"<div class='BT_shadow2'>" +
		"<div id='BT_main' style='width:"+query["width"]+"px; top:"+shadowTop+"px; left:"+shadowLeft+"px;'>" +
			"<div id='BT_arrow_"+arrowDir+"' style='top: "+arrowTop+"px; left:"+arrowLeft+"px;'></div>" +
			(showTitle?"<div id='BT_title'>"+title+"</div>":"") +
			"<div style='padding:5px'>" +
				"<div id='BT_content'>" +
					"<div class='BT_loader'></div>" +
				"</div>" +
			"</div>"+
		"</div></div></div></div>");
	
	if(urlParts[0].charAt(0) == '$')
	{
		jQuery('#BT_content').html(jQuery("#"+urlParts[0].substr(1)).html());
		jQuery('#BT').show();
	}
	else if(BT_cache_enabled)
	{
		if(jQuery('#BT_cache_'+id).length > 0)
			BT_loadCache(id);
		else
			$.post(url, {}, function(data){
				BT_createCacheElement(id, data);
			});
	}
	else
	{
		$.post(url, {}, function(data){
			jQuery('#BT_content').html(data);
			jQuery('#BT').show();
		})
	}
}

function BT_createCacheElement(id, data)
{
	jQuery("body").append("<div id='BT_cache_"+id+"' style='display:none'>"+
		data+"</div>");
	
	BT_loadCache(id);
}

function BT_loadCache(id)
{
	jQuery('#BT_content').html(jQuery('#BT_cache_'+id).html());
	jQuery('#BT').show();
}

function BT_getWidth(id) {
	var x = document.getElementById(id);
	return x.offsetWidth;
}

function BT_getLeft(id) {
	
	var obj = jQuery('#'+id)[0];
	var left = obj.offsetLeft;
	var parent = obj.offsetParent;
	
	while(parent) {
		left += parent.offsetLeft;
		parent = parent.offsetParent;
	}
	
	return left
}

function BT_getTop(id) {
	var obj = jQuery('#'+id)[0];
	var top = obj.offsetTop;
	var parent = obj.offsetParent;
	
	while(parent) {
		top += parent.offsetTop;
		parent = parent.offsetParent;
	}
	
	return top;
}

function BT_parseQuery ( query ) {
	
	var params = new Object ();
   
	if ( ! query ) 
		return params;
		
	var pairs = query.split(/[;&]/);
	
	for ( var i = 0; i < pairs.length; i++ ) {
		
		var kv = pairs[i].split('=');
		
		if ( ! kv || kv.length != 2 ) 
			continue;
			
		var key = unescape( kv[0] );	
		var val = unescape( kv[1] );
		
		val = val.replace(/\+/g, ' ');
		params[key] = val;
	}
	
	return params;
}

});