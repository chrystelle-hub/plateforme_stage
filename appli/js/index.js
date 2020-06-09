var url = location.search;
var param = url.substr(1);
param = param.split('&');
var tab = param[0].split('=');
var token = tab[1];

if (param[1] != undefined) {
  var tab2 = param[1].split('=');
  var id = tab2[1];
} else {
  id = null;
}

$(document).ready(function () {
  $.ajax({
    url: 'https://127.0.0.1:8000/testconnexion',
    type: 'POST',
    data: {
      'X-AUTH-TOKEN': token,
    },
    dataType: 'json',
    crossDomain: true,
  })
    .done(function (data) {
      if (data['login'] === 'ok') {
        //$( "#header" ).load( "template2.html" );
      } else {
        $('#header').load('template1.html');
      }
    })
    .fail(function () {
      $('#header').load('template1.html');
    })
    .always(function () {
      //url a changer
      $('#contenu').load('accueil.html', function () {
        //action spécifiques
      });
      $("#footer").load("footer.html");
    });
});
