function calc_hours() { /* Wieviel X habe ich in Y Stunden? */
  form = document.formular;
  switch (form.resource.value) {
    case 'iridium' :
    case 'holzium' :
    case 'water' :
      $('#c_1').css('display','block');
      $('#c_2').css('display','none');

      var resultat = foerderung[form.resource.value][1] * form.hours.value;

      if (form.c_res.checked == true)
        var ergebnis = Math.round(resultat + menge[form.resource.value]);
      else
        var ergebnis = Math.round(resultat);
      break;

    case 'oxygen' :
      $('#c_1').css('display','block');
      $('#c_2').css('display','block');

      if (form.c_water_ignore.checked == true)
        var resultat = foerderung[form.resource.value][0] * form.hours.value;
      else {
        t_water = menge['water'] / Math.abs(foerderung['water'][1]);
        if (foerderung['water'][1] > 0 || t_water >= form.hours.value) // Wasserturm > Reaktor || mehr Wasser als Zeit
          var resultat = foerderung[form.resource.value][1] * form.hours.value;
        else { // Wasserturm < Reaktor
          var resultat =
              foerderung[form.resource.value][0] * ( t_water ) +
              foerderung[form.resource.value][2] * ( parseInt(form.hours.value) - t_water );
        }
      }

      if (form.c_res.checked == true)
        var ergebnis = Math.round(resultat + menge[form.resource.value]);
      else
        var ergebnis = Math.round(resultat);
      break;

    case 'depot' :
      $('#c_1').css('display','none');
      $('#c_2').css('display','none');

      var fuellung = (menge['iridium'] + menge['holzium'] + menge['water']) +
                     (foerderung['iridium'][1] + foerderung['holzium'][1] + foerderung['water'][1]) * form.hours.value;
      var ergebnis = Math.round(10000 / depot_size_depot * fuellung) / 100;

      ergebnis = Math.max(0, Math.min(ergebnis, 100))+" %";
      break;

    case 'oxygen_depot' :
      $('#c_1').css('display','none');
      $('#c_2').css('display','block');

      if (form.c_water_ignore.checked == true)
        var fuellung = menge['oxygen'] + foerderung['oxygen'][0] * form.hours.value;
      else {
        t_water = menge['water'] / Math.abs(foerderung['water'][1]);
        if (foerderung['water'][1] > 0 || t_water >= form.hours.value) // Wasserturm > Reaktor || mehr Wasser als Zeit
          var fuellung = menge['oxygen'] + foerderung['oxygen'][1] * form.hours.value;
        else { // Wasserturm < Reaktor
          var fuellung = menge['oxygen'] +
              foerderung['oxygen'][0] * ( t_water ) +
              foerderung['oxygen'][2] * ( parseInt(form.hours.value) - t_water );
        }
      }
      var ergebnis = Math.round(10000 / depot_size_ox * fuellung) / 100;

      ergebnis = Math.max(0, Math.min(ergebnis, 100))+" %";
      break;
  }
  form.result_hours.value = ergebnis;

  calc_result_time1(form.hours.value);
}

function calc_quantity() { /* Wie lange braucht die Förderung von Y? */
  form = document.formular;
  switch (form.q_resource.value) {
    case 'iridium' :
    case 'holzium' :
    case 'water' :
      $('#q_c_1').css('display','block');
      $('#q_c_2').css('display','none');

      if (form.q_c_res.checked == true)
        var h = (form.quantity.value - menge[form.q_resource.value]) / foerderung[form.q_resource.value][1];
      else
        var h = form.quantity.value / foerderung[form.q_resource.value][1];
      break;

    case 'oxygen' :
      $('#q_c_1').css('display','block');
      $('#q_c_2').css('display','block');

      if (form.q_c_res.checked == true)
        var m = form.quantity.value - menge['oxygen'];
      else
        var m = form.quantity.value;

      if (form.q_c_water_ignore.checked == true)
        var h = m / foerderung['oxygen'][0];
      else {
        t_water = menge['water']/Math.abs(foerderung['water'][1]) // Zeit, die das Wasser reicht
        m_max = foerderung['oxygen'][0] * t_water;
        if (foerderung['water'][1] > 0 || m_max >= m) // Wasserturm > Reaktor || Max länger als gewünscht
          var h = m / foerderung['oxygen'][0];
        else { // Wasserturm < Reaktor
          m  = Math.max(m - m_max, 0);
          t2 = m / foerderung['oxygen'][2];
          var h = t_water + t2;
        }
      }
      break;

    case 'depot' :
      $('#q_c_1').css('display','none');
      $('#q_c_2').css('display','none');

      form.quantity.value = Math.max(0, Math.min(form.quantity.value ,100));

      var foerd = foerderung['iridium'][1] + foerderung['holzium'][1] + foerderung['water'][1];
      var inhalt = menge['iridium'] + menge['holzium'] + menge['water'];

      var h = ((depot_size_depot * form.quantity.value)/100 - inhalt) / foerd;
      break;

    case 'oxygen_depot' :
      $('#q_c_1').css('display','none');
      $('#q_c_2').css('display','block');

      var m = (depot_size_ox * form.quantity.value)/100 - menge['oxygen'];

      if (form.q_c_water_ignore.checked == true)
        var h = m / foerderung['oxygen'][0];
      else {
        t_water = menge['water']/Math.abs(foerderung['water'][1]) // Zeit, die das Wasser reicht
        m_max = foerderung['oxygen'][0] * t_water;
        if (foerderung['water'][1] > 0 || m_max >= form.quantity.value) // Wasserturm > Reaktor || Max länger als gewünscht
          var h = m / foerderung['oxygen'][0];
        else { // Wasserturm < Reaktor
          m  = m - m_max;
          t2 = m / foerderung['oxygen'][2];
          var h = t_water + t2;
        }
      }
      form.quantity.value = Math.max(0, Math.min(form.quantity.value ,100));
      break;
  }


  if (h >= 0)
  {
    var hours = Math.floor(h);
    var m = (h - hours) * 60;
    var minutes = Math.floor(m);
    var s = (m - minutes) * 60;
    var seconds = Math.floor(s);
  }
  else
  {
    var hours = Math.ceil(h);
    var m = (h - hours) * 60;
    var minutes = Math.ceil(m);
    var s = (m - minutes) * 60;
    var seconds = Math.ceil(s);
  }

  form.quantity_hours.value = hours;
  form.quantity_minutes.value = minutes;
  form.quantity_seconds.value = seconds;

  calc_result_time2(hours,minutes,seconds);
}


function calc_result_time1(hours) {
  form = document.formular;
  if (hours) {
    today = new Date();
    newHours = today.getHours() + Math.floor(hours);
    newMinutes = today.getMinutes() + Math.floor((hours-Math.floor(hours))*60);
    newSeconds = today.getSeconds() + Math.floor((((hours-Math.floor(hours))*60) - Math.floor((hours-Math.floor(hours))*60))*60);

    finishTime = formatDate( new Date(today.getFullYear(),today.getMonth(),today.getDate(),newHours,newMinutes,newSeconds) );
  }
  else
    finishTime = '';

  form.result_time1.value = finishTime;
}

function calc_result_time2(hours,minutes,seconds) {
  form = document.formular;
  if(hours != '' || minutes != '' || seconds != '') {
    today = new Date();
    newHours = today.getHours()+ parseInt(hours);
    newMinutes = today.getMinutes()+ parseInt(minutes);
    newSeconds = today.getSeconds()+ parseInt(seconds);

    finishTime = formatDate( new Date(today.getFullYear(),today.getMonth(),today.getDate(),newHours,newMinutes,newSeconds) );
  }
  else
    finishTime = '';

  form.result_time2.value = finishTime;
}

function formatDate(newDate) {
    // fuehrende NUll fuer den Tag
    if(newDate.getDate()<10)
      newDay = '0'+newDate.getDate();
    else
      newDay = newDate.getDate();

    // fuehrende NUll fuer den Monat
    if((newDate.getMonth()+1)<10)
      newMonth = '0'+(newDate.getMonth()+1);
    else
      newMonth = (newDate.getMonth()+1);

    // fuehrende NUll fuer die Minuten
    if((newDate.getMinutes())<10)
      newMinutes = '0'+(newDate.getMinutes());
    else
      newMinutes = (newDate.getMinutes());

    // fuehrende NUll fuer die Sekunden
    if((newDate.getSeconds())<10)
      newSeconds = '0'+(newDate.getSeconds());
    else
      newSeconds = (newDate.getSeconds());

    return newDate.getHours()+':'+newMinutes+':'+newSeconds+' Uhr, '+newDay+'.'+newMonth+'.'+newDate.getFullYear();
}

$(document).ready(function(){
      $('#c_2').css('display','none');
      $('#q_c_2').css('display','none');
});
