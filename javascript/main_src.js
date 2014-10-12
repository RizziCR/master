jQuery.fn.extend({
    show: function(speed,callback){
        return speed ?
            this.animate({
                height: "show", width: "show", opacity: "show"
            }, speed, callback) :

            this.filter(":hidden").each(function(){
                this.style.display = this.oldblock || "none";
                if ( jQuery.css(this,"display") == "none" ) {
                    var elem = jQuery("<" + this.tagName + " />").appendTo("body");
                    this.style.display = elem.css("display");
                    // handle an edge condition where css is - div { display:none; } or similar
                    if (this.style.display == "none")
                        this.style.display = "block";
                    elem.remove();
                }
            }).end();
    }
});

$(document).ready(function(){
    $("#tabContainer > ul").tabs({ cookie: { expires: 30 } });

    $(".tooltip").cluetip({
        width: 'auto',
        showTitle: false,
        // effect and speed for opening clueTips
        fx: {
            open:       'show', // can be 'show' or 'slideDown' or 'fadeIn'
            openSpeed:  ''
        },
        // settings for when hoverIntent plugin is used
        hoverIntent: {
            sensitivity:  1,
            interval:     0,
            timeout:      0
        }
    });
    $(".info").cluetip({
        splitTitle: '|',
        showTitle:  false,
        positionBy: 'mouse',
        local:      true,
        cursor:     'auto',
        width:      'auto',
        fx: {
            open:       'fadeIn', // can be 'show' or 'slideDown' or 'fadeIn'
            openSpeed:  'slow'
        },
        // settings for when hoverIntent plugin is used
        hoverIntent: {
            sensitivity:  5,
            interval:     500,
            timeout:      0
        }
    });
});

function decryptCharcode(n,start,end,offset)	{
    n = n + offset;
    if (offset > 0 && n > end)	{
    n = start + (n - end - 1);
    } else if (offset < 0 && n < start)	{
    n = end - (start - n - 1);
    }
    return String.fromCharCode(n);
}
    // decrypt string
function decryptString(enc,offset)	{
    var dec = "";
    var len = enc.length;
    for(var i=0; i < len; i++)	{
    var n = enc.charCodeAt(i);
    if (n >= 0x2B && n <= 0x3A)	{
        dec += decryptCharcode(n,0x2B,0x3A,offset);	// 0-9 . , - + / :
    } else if (n >= 0x40 && n <= 0x5A)	{
        dec += decryptCharcode(n,0x40,0x5A,offset);	// A-Z @
    } else if (n >= 0x61 && n <= 0x7A)	{
        dec += decryptCharcode(n,0x61,0x7A,offset);	// a-z
    } else {
        dec += enc.charAt(i);
    }
    }
    return dec;
}
    // decrypt spam-protected emails
function linkTo_UnCryptMailto(s)	{
    location.href = 'mailto:'+decryptString(s,-6);
}
function decryptCharcode(n,start,end,offset)	{
    n = n + offset;
    if (offset > 0 && n > end)	{
    n = start + (n - end - 1);
    } else if (offset < 0 && n < start)	{
    n = end - (start - n - 1);
    }
    return String.fromCharCode(n);
}
    // decrypt string
function decryptString(enc,offset)	{
    var dec = "";
    var len = enc.length;
    for(var i=0; i < len; i++)	{
    var n = enc.charCodeAt(i);
    if (n >= 0x2B && n <= 0x3A)	{
        dec += decryptCharcode(n,0x2B,0x3A,offset);	// 0-9 . , - + / :
    } else if (n >= 0x40 && n <= 0x5A)	{
        dec += decryptCharcode(n,0x40,0x5A,offset);	// A-Z @
    } else if (n >= 0x61 && n <= 0x7A)	{
        dec += decryptCharcode(n,0x61,0x7A,offset);	// a-z
    } else {
        dec += enc.charAt(i);
    }
    }
    return dec;
}
    // decrypt spam-protected emails
function linkTo_UnCryptMailto(s)	{
    location.href = 'mailto:'+decryptString(s,-6);
}


/* regeneriert das captcha bild*/
function regenerateCaptcha() {
    var ci = document.getElementById('captchacode');
    if(ci) {
           ci.src=ci.src+"1";
    }
}

/* don't sent form without captcha klick ;) this is called twice if actually clicked - once by input
 * and once by form */
var canSubmitLoginForm = false;
function submitLoginForm(fromCode) {
    if(fromCode) {
        canSubmitLoginForm = true;
        return true;
    }
    if(canSubmitLoginForm != true) {
        alert('Sicherheitscode vergessen?');
        return false;
    } else {
        return true;
    }
}

function start_clock(init)
{
  last=0;
  d = new Date();
  versatz = (init * 1000) - (d.getTime());
  clock();
}


function clock()
{
  d = new Date();
  var t = d.getTime() + versatz;
  d.setTime(t);
  var stunde = d.getHours();
  var mint = d.getMinutes();

  if(stunde < 10)
    stunde = '0' + stunde;


  if(mint < 10)
    mint = '0' + mint;

  var sec = d.getSeconds();

  if(sec<10)
    sec = '0' + sec;

  if(sec != last)
  {
    last = sec;
    time.innerHTML = stunde + ':' + mint + ':' + sec;
  }
  window.setTimeout('clock()',200);
}

function number_format(number,laenge,sep,th_sep)
{
  number = Math.round(number * Math.pow(10, laenge)) / Math.pow(10,laenge);
  str_number = number + '';

  arr_int = str_number.split('.');

  if (!arr_int[0])
    arr_int[0] = '0';

  if (!arr_int[1])
    arr_int[1] = '';

  if (arr_int[1].length < laenge)
  {
    nachkomma = arr_int[1];
    for (i=arr_int[1].length+1; i <= laenge; i++)
      nachkomma += '0';
    arr_int[1] = nachkomma;
  }

  if (th_sep != '' && arr_int[0].length > 3)
  {
    Begriff = arr_int[0];
    arr_int[0] = '';

    for(j = 3; j < Begriff.length ; j+=3)
    {
      Extrakt = Begriff.slice(Begriff.length - j, Begriff.length - j + 3);
      arr_int[0] = th_sep + Extrakt +  arr_int[0] + '';
    }
    str_first = Begriff.substr(0, (Begriff.length % 3 == 0)?3:(Begriff.length % 3));
    arr_int[0] = str_first + arr_int[0];
  }
  return arr_int[0]+sep+arr_int[1];
}

var menge = new Array();
