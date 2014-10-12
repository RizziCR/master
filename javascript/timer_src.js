/**
* timer script for timers / page reload after times
* e.g.
* _initTimer('sessiontimeout',100,'Session abgelaufen',true,'läuft ab in: ');
*/
var startupDate = new Date();
var timers = {};

function _initTimer(whereDiv,duration,finalText,reload,beforeText,workboard) {
	if(typeof workboard == 'undefined') { workboard = false; }
	if($('#'+whereDiv)) {
		timers[whereDiv] = {'duration':duration,'finalText':finalText,'reload':reload,'beforeText':beforeText,'workboard':workboard};
	}
	checkTimer(whereDiv);
}

function checkTimer(id) {
	var stop = false;
	if(typeof(timers[id]) != 'undefined') {
		var n = new Date();
		var seconds = timers[id]['duration'];
		var remaining = seconds -Math.round((n.getTime()-startupDate.getTime())/1000.);
		remaining = Math.round(remaining);
		var minutes = 0;
		var hours = 0;
		if(remaining<0) {
			stop = true;
			$('#'+id).html(timers[id]['finalText']);
			if(timers[id]['reload']) {
				document.location.href = document.location.href;
			}
			return;
		} else if(remaining > 59) {
			minutes = Math.floor(remaining/60);
			remaining=remaining-minutes*60;
		}
		if(minutes > 59) {
			hours = Math.floor(minutes/60);
			minutes = minutes - hours*60;
		}
		if(remaining < 10) {
			remaining = "0" + remaining;
		}
		if(minutes < 10) {
			minutes = "0" + minutes;
		}
    if(timers[id]['workboard']) { $('#'+id).html(timers[id]['beforeText'] +hours+':'+minutes+':'+remaining); }
		else { $('#'+id).html(timers[id]['beforeText'] + minutes +':'+remaining); }
	}
	if(!stop)
		window.setTimeout('checkTimer("'+id+'");',1000);
}
