$(document).ready(function(){
try {
  $(".nojs_handle").hide();
  $(".js_handle").show();
  if(!slimTrade) select_prices();
  toggleTransport(false);
} catch(e) {};
});

function time2string(time)
{
  var hours = Math.floor(time/3600);
  var m = (time - hours*3600);
  var minutes = Math.floor(m/60);
  var s = (m - minutes*60);
  var seconds = Math.floor(s);
  if (minutes < 10) minutes = '0' + minutes;
  if (seconds < 10) seconds = '0' + seconds;
  result = hours + ":" + minutes + ":" + seconds;
  return result;
}
function FlyTime(WhichForm)
{
  speed = 0;
  speed_start_flag = true;
  for (i=small_trans_index;i<=big_trans_index;i++)
  {
    if (WhichForm.elements["p_fleet["+i+"]"])
    {
      if (WhichForm.elements["p_fleet["+i+"]"].value > 0)
      {
        curr_speed = p_speed[i] + t_increase[p_tech[i]] * user_techs[p_tech[i]];
        if ((curr_speed < speed) || speed_start_flag)
        {
          speed_start_flag = false;
          speed = curr_speed;
        }
      }
    }
  }

  if (speed)
    return Math.round(tc_distance/speed * 3600);
  else
    return "";
}

function computeFlyingTime(id, distance)
{
  var speed = p_speed[id] + t_increase[p_tech[id]] * user_techs[p_tech[id]];
  return Math.round(distance/speed * 3600);
}

function showFlyingTime(formular, there, back)
{
  var speedThere = "";
  var speedBack = "";

  if (there)
    speedThere = time2string(computeFlyingTime(formular.up.value, tc_distance));
  if (back)
    speedBack = time2string(computeFlyingTime(formular.down.value, tc_distance));

  if (there && back)
    formular.duration.value = speedThere + " (" + speedBack + ")";
  else
    formular.duration.value = speedThere + speedBack;
}

function Capacity(WhichForm)
{
  var capacity = 0;

  for (i=small_trans_index;i<=big_trans_index;i++)
    if (WhichForm.elements["p_fleet["+i+"]"])
    {
      capacity += Math.floor( WhichForm.elements["p_fleet["+i+"]"].value * p_capacity[i] *
          Math.pow(t_increase[plane_size],user_techs[plane_size]));
    }
  if (capacity)
    return capacity;
  else
    return "";
}

function Consumption(WhichForm)
{
  var consume = 0;

  for (i=small_trans_index;i<=big_trans_index;i++)
    if (WhichForm.elements["p_fleet["+i+"]"])
    {
      consume += Math.round(600 / 1000 * p_consumption[i] * Math.pow(t_increase[consumption],user_techs[consumption]) * WhichForm.elements["p_fleet["+i+"]"].value);
    }
  if (consume)
    return Math.ceil(consume);
  else
    return "";
}

function plane_trade_consumption(formular, there, back)
{
  var consume = 0;
  if (there)
    consume += Math.round(tc_distance * p_consumption[formular.up.value] *
        Math.pow(t_increase[consumption],user_techs[consumption]) *
        formular.sends.value / 1000 / 2);
  if (back)
    consume += Math.round(tc_distance * p_consumption[formular.down.value] *
        Math.pow(t_increase[consumption],user_techs[consumption]) *
        formular.gets.value / 1000 / 2);

  if (consume)
    consume = Math.ceil(consume);
  else
    consume = "";
  formular.show_consumption.value = consume;
}

var giving = true;

function toggleTransport(enable)
{
  document.getElementById("transportBlock").className = (enable ? "open" : "close");
}

function isResource(tradeID)
{
  return  tradeID == 'iridium' ||
          tradeID == 'holzium' ||
          tradeID == 'water'   ||
          tradeID == 'oxygen';
}

function getResIndex(chosenValue)
{
  if (isResource(chosenValue))
    switch(chosenValue)
    {
      case 'iridium':
        return 0;
      case 'holzium':
        return 1;
      case 'water':
        return 2;
      case 'oxygen':
        return 3;
    }
  else
    return eval(chosenValue) + 4;
}

function getIndex(chosenFromValue, chosenToValue)
{
  var i = getResIndex(chosenFromValue);
  var j = getResIndex(chosenToValue);
  return i * (array_size) + j;
}

function show_relation()
{
  //var give_plane = false, get_plane = false;
  if (document.formular.up.value == document.formular.down.value)
  {
    document.formular.show_down.value = "";
    document.formular.show_up.value = "";
    toggleTransport(false);
    document.formular.show_consumption.value = "";
    document.formular.duration.value = "";
  }
  else
  {
    var index = getIndex(document.formular.up.value, document.formular.down.value);
    document.formular.show_up.value = prices_x[index];
    document.formular.show_down.value = prices_y[index];
    if (!isResource(document.formular.up.value) ||
        !isResource(document.formular.down.value))
    {
      toggleTransport(false);
      showFlyingTime(document.formular, !isResource(document.formular.up.value),
        !isResource(document.formular.down.value));
    }
    else
    {
      toggleTransport(true);
      document.formular.duration.value = "2 x  " + time2string(FlyTime(document.formular));
    }

    if (giving)
      show_gets();
    else
      show_give();
  }
}

function show_gets()
{
  giving = true;
  document.formular.value_source.value = "give";
  var gain;
  gain = Math.round(document.formular.sends.value) * document.formular.show_down.value / document.formular.show_up.value;
  if (gain > 0 && gain != "Infinity")
    document.formular.gets.value = Math.floor(gain);
  else
    document.formular.gets.value = "";
  if (isResource(document.formular.up.value) && isResource(document.formular.down.value))
    document.formular.show_consumption.value = Consumption(document.formular);
  else
    plane_trade_consumption(document.formular, !isResource(document.formular.up.value),
      !isResource(document.formular.down.value));
}

function show_give()
{
  giving = false;
  document.formular.value_source.value = "get";
  var gain;
    gain = Math.round(document.formular.gets.value) * document.formular.show_up.value / document.formular.show_down.value;
  if (gain > 0 && gain != "Infinity")
    document.formular.sends.value = Math.ceil(gain);
  else
    document.formular.sends.value = "";
  if (isResource(document.formular.up.value) && isResource(document.formular.down.value))
    document.formular.show_consumption.value = Consumption(document.formular);
  else
    plane_trade_consumption(document.formular, !isResource(document.formular.up.value),
      !isResource(document.formular.down.value));
}

function calculate_max_content()
{
  document.formular.duration.value = "2 x  " + time2string(FlyTime(document.formular));
  document.formular.show_max_content.value = Capacity(document.formular);
  document.formular.show_consumption.value = Consumption(document.formular);
}

function select_prices()
{
  var x = 0, y = 0, s = array_size;
  var chosen = document.showOnly.wareSelection.value;
  var give = document.showOnly.giveOrGet.value == "give";
  var name, v_close = "close", v_open = "open";
  //var name, v_close = "none", v_open = "block";
  while (x < s)
  {
    while (y < s)
    {
      if (x != y)
      {
        if (give)
          name = (x == chosen ? v_open : v_close);
        else
          name = (y == chosen ? v_open : v_close);
        document.getElementById("x" + x + "y" + y).className = name;
        //document.getElementById("x" + x + "y" + y).style.display = name;
      }
      y++;
    }
    x++;
    y = 0;
  }
  $("#chartimg").attr('rel', '/stats/graphs.php?g='+chosen);
  $("#chartimg").cluetip();
}

function select_max()
{
  if ( document.formular.up.value != document.formular.down.value ) {
	var max_trade = 0;
    if ( isResource(document.formular.up.value) && isResource(document.formular.down.value) ) {
	var max_plane_capa = 0;
	var trans_index = 0;
	for (i=big_trans_index;i>=small_trans_index;i--) {
		if ( ( max_plane_capa == 0 ) && ( p_in_hangar[i] > 0 ) ) {
			max_plane_capa = Math.floor(p_in_hangar[i] * p_capacity[i] *  Math.pow(t_increase[plane_size],user_techs[plane_size]));
			trans_index = i;
		}
	}
	max_trade = Math.min(remainder,max_plane_capa);
	var gotRes = document.getElementById("count_" + document.formular.up.value);
		var got_res=gotRes.innerHTML
		got_res = got_res.replace(/\./g, "");
		got_res = parseInt(got_res);
		got_res--;
		if(document.formular.up.value == "oxygen") {
			got_res -= parseInt(document.formular.show_consumption.value) || 0;
			}
		if(parseInt(document.formular.show_up.value) < parseInt(document.formular.show_down.value))
			got_res = Math.round(got_res / parseInt(document.formular.show_up.value) * parseInt(document.formular.show_down.value))-1;
		if(got_res < max_trade) {
				max_trade=got_res;
			}
		
      for (i=small_trans_index;i<=big_trans_index;i++) if ( p_in_hangar[i] > 0 ) document.getElementById('transID'+i).value = '';
      document.getElementById('transID'+trans_index).value = Math.ceil(max_trade / p_capacity[trans_index] / Math.pow(t_increase[plane_size],user_techs[plane_size]));
      calculate_max_content();
    }
    if ( isResource(document.formular.up.value) && !isResource(document.formular.down.value) ) {
	  max_trade = Math.floor(Math.min(Math.floor(remainder/ document.formular.show_up.value * document.formular.show_down.value),Math.min(p_in_store[document.formular.down.value],p_hangar_free))
			  * document.formular.show_up.value / document.formular.show_down.value);
		var gotRes = document.getElementById("count_" + document.formular.up.value);
		var got_res=gotRes.innerHTML
		got_res = got_res.replace(/\./g, "");
		got_res = parseInt(got_res);
		got_res--;
		if(got_res < max_trade) {
				max_trade=got_res;
			}
			  
	}
    if ( !isResource(document.formular.up.value) && isResource(document.formular.down.value) ) 
  	  max_trade = Math.floor(Math.min(Math.floor(remainder* document.formular.show_up.value / document.formular.show_down.value),p_in_hangar[document.formular.up.value])
			  / document.formular.show_up.value * document.formular.show_down.value);
    if ( !isResource(document.formular.up.value) && !isResource(document.formular.down.value) ) {
	  if (document.formular.show_up.value < document.formular.show_down.value) { 
	    max_trade = Math.floor(Math.min(Math.floor(Math.min(remainder,Math.floor(Math.min(p_in_store[document.formular.down.value],p_hangar_free))) 
		  * document.formular.show_up.value / document.formular.show_down.value),p_in_hangar[document.formular.up.value])/ document.formular.show_up.value * document.formular.show_down.value);
	  } else {
		max_trade = Math.floor(Math.min(Math.floor(Math.min(remainder,Math.floor(p_in_hangar[document.formular.up.value])) 
		  / document.formular.show_up.value * document.formular.show_down.value),Math.min(p_in_store[document.formular.down.value],p_hangar_free))* document.formular.show_up.value / document.formular.show_down.value);
	  }
    }
 	
    if ( max_trade > 0 ) {
      if ( 1*document.formular.show_up.value < 1*document.formular.show_down.value ) {     // convert to integer
	    document.formular.gets.value = max_trade;
	    show_give();
      } else {
        document.formular.sends.value = max_trade;
	    show_gets();
      }
    } else {
      document.formular.gets.value = '';
      show_give();
    }
  }
}

jQuery.fn.cluetip.defaults = jQuery.extend(jQuery.fn.cluetip.defaults,
{
            showTitle:  false,
            positionBy: 'bottomTop',
            topOffset:  20,
            leftOffset: -600,
            cursor:     'auto',
            width:      610,
            fx: {
                open:       'fadeIn', // can be 'show' or 'slideDown' or 'fadeIn'
                openSpeed:  'slow'
            },
            // settings for when hoverIntent plugin is used
            hoverIntent: {
                sensitivity:  5,
                interval:     500,
                timeout:      0
            }}
);
