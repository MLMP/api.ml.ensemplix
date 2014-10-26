<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;" name="viewport" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <script src="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" />
  
  <link rel="stylesheet" href="blocks_items.css" />
  <link rel="stylesheet" href="mc_font.css" />
  <style>
    <!-- .item-list .item {
      margin: 0 5px 5px 0;
    }
    .item-list {
      line-height: 0;
    }
    body {
      overflow-y: scroll;
    }
  .styled_input {
    border: 0;
    background: inherit;
    color: inherit;
    outline: 0;
    padding: 0;
  }
  ul#pagination {
    list-style: none;
    margin: 0;
    padding: 0;
    overflow: auto;
  }
  ul#pagination li {
    float: left;
    padding-right: 5px;
  }
  ul#pagination li .page.current {
    font-weight: bold;
  }
  </style>
  <script>
  function theme(theme_name) {
    link=document.getElementById('theme');
    link.setAttribute('href','http://bootswatch.com/'+theme_name.toLowerCase()+'/bootstrap.min.css');
    localStorage['config.theme']=theme_name.toLowerCase();
  }
  if(localStorage['config.theme'] != undefined) theme(localStorage['config.theme']);-->
  </script> 
</head>
<body>

<div class="container" id="breadcrumb"></div>

<div class="container" id="output"></div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

<script>
var api = {
  'player': function (player, icon) {
    if(icon != undefined && icon == true) {
      //return "<img src='https://ensemplix.ru/images/logos/" + player + ".png' onerror=\"this.src='https://ensemplix.ru/images/logos/default.jpg'\" style='width:16px;height:16px;'/>&nbsp;<a class='player' href='#player/" + player + "'>" + player + "</a>";
      return "<a class='player' href='#player/" + player + "' style='background-repeat:no-repeat;background-size:20px;background-image:url(https://ensemplix.ru/images/logos/" + player + ".png);line-height:20px;height:20px;padding-left:23px;display:inline-block;' onerror=\"this.style.backgroundImage='url(https://ensemplix.ru/images/logos/default.jpg)'\">" + player + "</a>";
    }
    return "<a class='player' href='#player/" + player + "'>" + player + "</a>";
  },
  'world': function (world, label) { return "<a class='world' href='#world/" + world + "'>" + (label || world) + "</a>"; },
  'clan': function (clan_id, label) { return "<a class='clan' href='#clan/" + clan_id + "'>" + (label || clan_id) + "</a>"; },
  'region': function (region, world) { return "<a class='region' href='#world/" + world + "/region/" + region + "'>" + region + "</a>"; },
  'warp': function (warp, world, label) { return "<a class='warp' href='#world/" + world + "/warp/" + warp + "'>" + (label || warp) + "</a>"; },
  'coord': function (coord, world, label) { return "<a class='coord' href='#world/" + world + "/coord/" + coord + "'>" + (label || coord) + "</a>"; },
  'till': function (till) { return till == -1 ? "навсегда" : api.time(till); },
  'time': function (timestamp) {
    var a = new Date(timestamp * 1000);
    
    var year = a.getFullYear();
    var month = a.getMonth() + 1 < 10 ? "0" + (a.getMonth() + 1) : a.getMonth() + 1;
    var date = a.getDate() < 10 ? "0" + a.getDate() : a.getDate();
    var hour = a.getHours() < 10 ? "0" + a.getHours() : a.getHours();
    var min = a.getMinutes() < 10 ? "0" + a.getMinutes() : a.getMinutes();
    var sec = a.getSeconds() < 10 ? "0" + a.getSeconds() : a.getSeconds();
    
    return year+"-"+month+"-"+date+" "+hour+":"+min+":"+sec;
  },
  'period': function (timestamp) { return (timestamp == -1) ? "навсегда" : api.time(timestamp); },
  'item': function (item_id, data, world) { return "<span class='item" + (world == undefined ? "" : " " + world) + " item_" + item_id + " item_" + item_id + "_" + data + "' title='#" + item_id + (data == 0 ? "" : ":" + data)+"'></span>"; },
  'number': function (e) { return (e || 0).toString().replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1&thinsp;'); },
  'html': function (str) { return String(str).replace(/</g, '&#60;').replace(/>/g, '&#62;'); },
  'info': function (message) { return "<div class='alert alert-info'>" + message + "</div>"; },
  'danger': function (message) { return "<div class='alert alert-danger'>" + message + "</div>"; },
  'warning': function (message) { return "<div class='alert alert-warning'>" + message + "</div>"; },
  'input': function (hash, value) { return "<span class='glyphicon glyphicon-pencil'></span> <input type='text' value='" + (decodeURIComponent(value) || "web93onv") + "' class='styled_input' onclick='this.select();' onkeypress='if(event.which==13){this.blur();document.location.hash=\"" + hash + "\".replace(/\%s/,encodeURIComponent(this.value));}' onchange='document.location.hash=\"" + hash + "\".replace(/\%s/,encodeURIComponent(this.value));' />" }
}

function actionsBox(e) { // {'action':"player/web93onv/regions",'title':"Регионы",'description':"Список регионов в которых игрок является жителем либо владеет регионом."}
  var result = [];
  
  for(var x = 0; x < e.length; x++) {
    result.push("<a href='#" + e[x]['action'] + "' class='list-group-item'>"
      + "<h4 class='list-group-item-heading'>" + e[x]['title'] + "</h4>"
      + "<p class='list-group-item-text'>" + e[x]['description'] + "</p>"
    + "</a>");
  }
  
  result = "<div class='list-group'>" + result.join("") + "</div>";
  
  return result;
}

function addParams(e) {
  var result = [];
  
  for(var x in e) {
    result.push(x + "=" + encodeURIComponent(e[x])); // name=value
  }
  
  result = "?" + result.join("&"); // ?name1=value1&name2=value2
  
  return result;
}

function pagination(e, api) {
  var count = Number(e.count);
  var offset = Number(e.offset);
  
  var pages = ((count/100)|0)+((offset/100)|0);
  var page = ((offset/100)|0);
  
  var prev = page - 1;
  var next = page + 1;
  
  var res = [];
  
  var hidden = false;
  
  if(pages > 0) {
    var min = page - 4;
    var max = page + 4 + (4 - page > 0 ? 4 - page : 0);
    for(var x = 0; x <= pages; x++) {
      if((x > min && x < max) || x == pages || x == 0) {
        if(x == page) {
          res.push("<li class='active'><a class='page current' href='#" + api + "/page" + (x + 1) + "'>" + (x + 1) + "</a></li>");
        } else if(x == prev) {
          res.push("<li><a class='page prev' href='#" + api + "/page" + (x + 1) + "'>" + (x + 1) + "&#60;</a></li>");
        } else if(x == next) {
          res.push("<li><a class='page next' href='#" + api + "/page" + (x + 1) + "'>&#62;" + (x + 1) + "</a></li>");
        } else if(x == 0) {
          res.push("<li><a class='page first' href='#" + api + "/page" + (x + 1) + "'>[" + (x + 1) + "]</a></li>");
        } else if(x == pages) {
          res.push("<li><a class='page last' href='#" + api + "/page" + (x + 1) + "'>[" + (x + 1) + "]</a></li>");
        } else {
          res.push("<li><a class='page' href='#" + api + "/page" + (x + 1) + "'>" + (x + 1) + "</a></li>");
        }
      } else {
        hidden = true;
      }
    }
    
    if(hidden) {
      return "<ul class='pagination pagination-sm' style='margin:0;'><li><a href='javascript:void(0)' onclick=\"event.preventDefault();page=prompt('Страница:','" + (page + 1) + "');if(page>0){document.location.href='#" + api + "/page'+page;}\">На страницу</a></li>" + res.join("") + "</ul>";
    } else {
      return "<ul class='pagination pagination-sm' style='margin:0;'>" + res.join("") + "</ul>";
    }
  } else {
    return "";
  }
}

function htmlEscape(str) {
  return String(str)
    .replace(/</g, '&#60;')
    .replace(/>/g, '&#62;');
}

function mcChatReplacer(str, p1, p2, offset, s) {
  return "<span class='mc mc-"+p1.toLowerCase()+"'>"+p2+"</span>";
}

function mcChat(str) {
  return htmlEscape(str)
    .replace(/\n/ig,"\n§f")
    .replace(/&([0-9a-fklmnor])/ig,"§$1")
      .replace(/§[klmnor]/ig, "") // удаление форматировочных кодов :-(
    .replace(/§([0-9a-f])([^§]+)/ig, mcChatReplacer)
    .replace(/§[0-9a-fklmnor]/ig, "");
}

function regionParse(region, params) {
  var player_regexp = RegExp("^"+params['player']+"$", "i");
  
  // длина региона, минимальная точка на оси x, средняя точка на оси x
  var xlength = Math.abs(region.min_x - region.max_x);
  var xmin = Math.min(region.min_x, region.max_x);
  var xcenter = ((xmin + xlength/2)|0);
  
  // ширина региона, минимальная точка на оси z, средняя точка на оси z
  var zlength = Math.abs(region.min_z - region.max_z);
  var zmin = Math.min(region.min_z, region.max_z);
  var zcenter = ((zmin + zlength/2)|0);
  
  // высота региона, минимальная точка на оси y, средняя точка
  var ylength = Math.max(region.min_y, region.max_y) - Math.min(region.min_y, region.max_y);
  var ymin = Math.min(region.min_y, region.max_y);
  var ycenter = ((ymin + ylength/2)|0);
  
  // объем региона
  var region_volume = api.number((xlength+1)*(ylength+1)*(zlength+1));
  
  // пометка "владелец" или "житель"
  var region_status = "";
  
  var members = "";
  if(region.members.length != 0) {
    members = [];
    for(var y = 0; y < region.members.length; y++) {
      //members.push("<a href='#player/" + region.members[y] + "'>" + region.members[y] + "</a>");
      members.push(api.player(region.members[y], true));
      
      if(player_regexp.test(region.members[y])) {
        region_status = "Житель";
      }
    }
    members = members.join(", ");
  }
  
  var owners = "";
  if(region.owners.length != 0) {
    owners = [];
    for(var y = 0; y < region.owners.length; y++) {
      //owners.push("<a href='#player/" + region.owners[y] + "'>" + region.owners[y] + "</a>");
      owners.push(api.player(region.owners[y], true));
      
      if(player_regexp.test(region.owners[y])) {
        region_status = "Владелец";
      }
    }
    owners = owners.join(", ");
  }
  
  // затенение регионов из удаленных миров (серверов)
  var removed_region = "";
  var removed_region_highlight = "";
  var whitelist = new RegExp("^(Sandbox|CarnageR4|Davids|Amber|DIM7|DIM1|DIM-1)($|_)");
  // DIM1 - Amber The End
  // DIM-1 - Amber Nether
  if(!whitelist.test(region.world)) {
    removed_region_highlight = " style='opacity:.6;'";
    removed_region = "<b style='display:inline-block;float:right;'> МИР УДАЛЕН</b>";
  }

  var children = "";
  if(region['children'].length != 0) {
    children = [];
    for(var x = 0; x < region['children'].length; x++) {
      children.push(api.region(region['children'][x], region['world']));
    }
  }
  

  var result = "<li class='list-group-item'"+removed_region_highlight+"><b>Регион:</b> " + api.region(region['region'], region['world'])
    + ", <span class='glyphicon glyphicon-globe'></span> <b>" + api.world(region['world']) + "</a></b>"

    +", <span class='glyphicon glyphicon-map-marker'></span> " + api.coord(xcenter + "," + ycenter + "," + zcenter, region.world)
    +removed_region
    +"<br/>"
    +"<span style='display:inline-block;float:right;'>"+region_status+"</span>"
    +(owners!=""?"<b>Владельцы:</b>&nbsp;"+owners+"<br/>":"")
    +(members!=""?"<b>Жители:</b>&nbsp;"+members+"<br/>":"")
    +(region['priority']==0||region['priority']==null?"":"<b>Приоритет:</b> "+region['priority']+"<br/>")
    +(region['flags'].length==0?"":"<b>Флаги:</b> "+region['flags'].join(", ")+"<br/>")
    +(region['parent']==null?"":"<b>Глобальный регион:</b> "+api.region(region['parent']['parent'] || region['parent'], region['world'])+"<br/>")
    +(children!=""?"<b>Локальные регионы:</b> "+children.join(", ")+"<br/>":"")
    +"<b>Объем:</b> "+(xlength+1)+"&times;"+(ylength+1)+"&times;"+(zlength+1)+" = "+region_volume+" блок(ов)<br/>"
    +"<b>Дата создания:</b> "+api.time(region.created)+"</li>";
  
  return result;
}

function violationsParse(response, params) {
  var result = "";
  
  if(response['banned'] == null && response['muted'] == null) {
    result += api.info(api.player(params['player'], true) + " сейчас не наказан");
  } else {
    if(response['banned']) {
      result += api.danger("<b>" + api.player(response['banned']['player'], true) + "</b> забанен администратором <b>" + api.player(response['banned']['admin'], true) + "</b> по причине: <b>" + response['banned']['reason'] + "</b><br/>"
          + "От <b>" + api.time(response['banned']['created']) + "</b> &mdash; до <b>" + api.period(response['banned']['till']) + "</b>"
        );
    }
    
    if(response['muted']) {
      result += api.warning("<b>" + api.player(response['muted']['player'], true) + "</b> замучен администратором <b>" + api.player(response['muted']['admin'], true) + "</b> по причине: <b>" + response['muted']['reason'] + "</b><br/>"
          + "От <b>" + api.time(response['muted']['created']) + "</b> &mdash; до <b>" + api.period(response['muted']['till']) + "</b>"
        );
    }
  }
  
  return result;
}

function blockParse(block) {
  var result = "<tr>"
      + "<td><span class='glyphicon glyphicon-" + (block['type'] == 0 ? "minus text-danger" : "plus text-success") + "'></span></td>"
      + "<td>" + api.item(block['block'], block['data'], block['world']) + "</td>"
      + "<td>#" + block['block'] + (block['data'] == 0 ? "" : ":" + block['data'] ) + "</td>"
      + "<td>" + api.player(block['player'], true) + "</td>"
      + "<td>"
        + "<span class='glyphicon glyphicon-globe'></span> <b>" + api.world(block['world']) + "</b>"
        + ", <span class='glyphicon glyphicon-map-marker'></span> " + api.coord(block['x'] + "," + block['y'] + "," + block['z'], block['world'])
      + "</td>"
      + "<td>" + api.time(block['created']) + "</td>"
    + "</tr>";
  
  return result;
}

var info = {
  'base_url': 'http://api.ensemplix.ru/v2/player/info/',
  'get': function (params) {
    var url = info.base_url + addParams({
      'player': params['player']
    });
    
    $("#breadcrumb").html("<ol class='breadcrumb'>"
        + "<li><a href='#'>Главная</a></li>"
        + "<li><a href='#player/%player%'>Игрок</a></li>"
        + "<li class='active'>" + api.input("player/%s", params['player']) + "</li>"
      + "</ol>");
    
    $("#output").html("<div class='progress progress-striped active'><div class='progress-bar' style='width:100%;'>Загрузка...</div></div>");
    
    if(info.history[url] !== undefined) {
      info.parse(info.history[url]);
      
      console.log('loaded from history');
      return;
    }
    
    $.ajax(url, {
      cache: false,
      success: function (e) {
        info.history[url] = {'response': e, 'params': params};
        
        info.parse(info.history[url]);
      },
      error: function (e) {
        response = e.responseJSON;
        
        if(response.error != undefined) {
          $("#output").html(api.danger(response.error));
        } else {
          $("#output").html(api.danger("Ошибка"));
        }
      }
    });
  },
  'parse': function (e) {
    var response = e.response;
    var params = e.params;
    
    if(response['error']) {
      $('#output').html(api.danger(response['error']));
      return;
    }
    
    var player = response[0];
    
    var actions = actionsBox([
      {'action': "player/" + params.player + "/violations", 'title': "Наказания", 'description': "Список активных банов и мутов игрока."},
      {'action': "player/" + params.player + "/regions", 'title': "Регионы", 'description': "Список регионов в которых игрок является жителем либо владеет регионом."},
      {'action': "player/" + params.player + "/warps", 'title': "Варпы", 'description': "Список платных точек перемещения созданных игроком."},
      {'action': "player/" + params.player + "/blocks", 'title': "Логи блоков", 'description': "Список поставленных и разрушенных игроком блоков."},
      {'action': "player/" + params.player + "/shops", 'title': "Логи магазина", 'description': "Список покупок и продаж игрока через свои или чужие магазины."}
    ]);
    
    switch (player['level']) {
      case 7:
        rank = "Главный Администратор";
        break;
      case 6:
        rank = "Администратор";
        break;
      case 5:
        rank = "Модератор";
        break;
      case 4:
        rank = "Super-VIP";
        break;
      case 3:
        rank = "VIP";
        break;
      case 2:
        rank = "Power-Player";
        break;
      case 1:
        rank = "Игрок";
        break;
    }
    
    var info = "<ul class='media-list'>"
        + "<li class='media'>"
          + "<img class='pull-left media-object' src='https://ensemplix.ru/images/logos/" + player['player'] + ".png' alt='" + player['player'] + " logo' style='width:96px;' onerror=\"this.src='https://ensemplix.ru/images/logos/default.jpg';\" />"
          + "<div class='media-body'>"
            + "<h4 class='media-heading'><a href='#player/" + player['player'] + "'>" + player['player'] + "</a><br/><small>" + rank + "</small></h4>"
            + "<table width='100%'>"
              + (player['clan'] == null ? "" : "<tr><td>Клан:</td><td>" + player['clan'] + "</td></tr>")
              + "<tr><td>Имущество:</td><td>" + api.number(player['coins']) + " койн</td></tr>"
              + "<tr><td>Опыт:</td><td>" + api.number(player['experience']) + " очков</td></tr>"
              + "<tr><td>Регистрация:</td><td>" + api.time(player['registration']) + "</td></tr>"
            + "</table>"
          + "</div>"
        + "</li>"
      + "</ul>";
    
    var violations_url = "http://api.ensemplix.ru/v2/player/violations/" + addParams(params);
    if(violations.history[violations_url]) {
      info += violationsParse(violations.history[violations_url].response, violations.history[violations_url].params);
    } else {
      $.ajax(violations_url, {
        cache: false,
        async: false,
        success: function (e) {
          violations.history[violations_url] = {'response': e, 'params': params};
          
          info += violationsParse(e, params);
        }
      });
    }
    
    var result = "<div class='row'><div class='col-md-6'>" + info + "</div><div class='col-md-6'>" + actions + "</div></div>";
    
    $('#output').html(result);
    /*$('#output').html("<b>" + player.player + "</b><br>"
      + "Клан " + player.clan + "<br>"
      + "Имущество " + player.coins + " койн<br>"
      + "<ol><li><a href='#player/" + player.player + "/violations'>Нарушения</a></li><li><a href='#player/" + player.player + "/regions'>Регионы</a></li><li><a href='#player/" + player.player + "/warps'>Варпы</a></li><li><a href='#player/" + player.player + "/blocks'>Логи блоков</a></li><li><a href='#player/" + player.player + "/shops'>Логи магазина</a></li></ol>");*/
  },
  'history': {}
}

var regions = {
  'base_url': 'http://api.ensemplix.ru/v2/regions/player/',
  'get': function (params) {
    var url = regions.base_url + addParams({
      'player': params['player'],
      'offset': params['offset'] || 0
    });
    
    $("#breadcrumb").html("<ol class='breadcrumb'>"
        + "<li><a href='#'>Главная</a></li>"
        + "<li><a href='#player/%player%'>Игрок</a></li>"
        + "<li><a href='#player/" + params['player'] + "'>" + params['player'] + "</a></li>"
        + "<li class='active'>Регионы</li>"
      + "</ol>");
    
    $("#output").html("<div class='progress progress-striped active'><div class='progress-bar' style='width:100%;'>Загрузка...</div></div>");
    
    if(regions.history[url] !== undefined) {
      regions.parse(regions.history[url]);
      
      console.log('loaded from history');
      return;
    }
    
    $.ajax(url, {
      cache: false,
      success: function (e) {
        regions.history[url] = {'response': e, 'params': params};
        
        regions.parse(regions.history[url]);
      },
      error: function (e) {
        response = e.responseJSON;
        
        if(response.error != undefined) {
          $("#output").html(api.danger(response.error));
        } else {
          $("#output").html(api.danger("Ошибка"));
        }
      }
    });
  },
  'parse': function (e) { // {response: *json response*, params: *object of get params*}
    var response = e.response;
    var params = e.params;
    
    var player_regexp = RegExp("^"+params.player+"$", "i");
    
    // пагинация
    var pages_list = pagination(response, "player/" + params.player + "/regions");
    pages_list = (pages_list != "") ? "<div class='panel-body'>" + pages_list + "</div>" : "";
    
    // список регионов
    var regions_list = "";
    for(var x = 0; x < response.regions.length; x++) {
      regions_list += regionParse(response.regions[x], params);
    }
    regions_list = "<ul class='list-group'>"+regions_list+"</ul>";
    
    var result = "<div class='panel panel-default'>" + pages_list + regions_list + pages_list + "</div>";
    
    $("#output").html(result);
  },
  'history': {}
}

var blocks = {
  'base_url': 'http://api.ensemplix.ru/v2/blocks/player/',
  'get': function (params) {
    var url = blocks.base_url + addParams({
      'player': params['player'],
      'offset': params['offset'] || 0
    });
    
    $("#breadcrumb").html("<ol class='breadcrumb'>"
        + "<li><a href='#'>Главная</a></li>"
        + "<li><a href='#player/%player%'>Игрок</a></li>"
        + "<li><a href='#player/" + params['player'] + "'>" + params['player'] + "</a></li>"
        + "<li class='active'>Логи блоков</li>"
      + "</ol>");
    
    $("#output").html("<div class='progress progress-striped active'><div class='progress-bar' style='width:100%;'>Загрузка...</div></div>");
    
    if(blocks.history[url] !== undefined) {
      blocks.parse(blocks.history[url]);
      
      console.log('loaded from history');
      return;
    }
    
    $.ajax(url, {
      cache: false,
      success: function (e) {
        blocks.history[url] = {'response': e, 'params': params};
        
        blocks.parse(blocks.history[url]);
      },
      error: function (e) {
        response = e.responseJSON;
        
        if(response.error != undefined) {
          $("#output").html(api.danger(response.error));
        } else {
          $("#output").html(api.danger("Ошибка"));
        }
      }
    });
  },
  'parse': function (e) { // {response: *json response*, params: *object of get params*}
    var response = e.response;
    var params = e.params;
    
    var blocks = response.blocks;
    
    var pages_list = pagination(response, "player/" + params.player + "/blocks");
    pages_list = pages_list == "" ? "" : "<div class='panel-body'>" + pages_list + "</div>";
    
    var result = "<tr>"
        + "<th></th>"
        + "<th colspan='2'>Блок</th>"
        + "<th>Игрок</th>"
        + "<th>Мир</th>"
        + "<th>Время</th>"
      + "</tr>";
      
    for(var x = 0; x < blocks.length; x++) {
      var block = blocks[x];
      
      result += blockParse(block);
    }
    
    result = "<style>.success a, .warning a { color: inherit; }</style><div class='panel panel-default'>" + pages_list + "<table class='table table-striped'>" + result + "</table>" + pages_list + "</div>";
    
    $("#output").html(result);
  },
  'history': {}
}

var regions_server = {
  'base_url': 'http://api.ensemplix.ru/v2/regions/',
  'get': function (params) {
    var url = regions_server.base_url + addParams({
      'world': params['world'],
      'offset': params['offset'] || 0
    });
    
    $("#breadcrumb").html("<ol class='breadcrumb'>"
        + "<li><a href='#'>Главная</a></li>"
        + "<li><a href='#world/%world%'>Мир</a></li>"
        + "<li><a href='#world/" + params['world'] + "'>" + params['world'] + "</a></li>"
        + "<li class='active'>Регионы</li>"
      + "</ol>");
    
    $("#output").html("<div class='progress progress-striped active'><div class='progress-bar' style='width:100%;'>Загрузка...</div></div>");
    
    if(regions_server.history[url] !== undefined) {
      regions_server.parse(regions_server.history[url]);
      
      console.log('loaded from history');
      return;
    }
    
    $.ajax(url, {
      cache: false,
      success: function (e) {
        regions_server.history[url] = {'response': e, 'params': params};
        
        regions_server.parse(regions_server.history[url]);
      },
      error: function (e) {
        response = e.responseJSON;
        
        if(response.error != undefined) {
          $("#output").html(api.danger(response.error));
        } else {
          $("#output").html(api.danger("Ошибка"));
        }
      }
    });
  },
  'parse': function (e) { // {response: *json response*, params: *object of get params*}
    var response = e.response;
    var params = e.params;
    
    if(response.error != undefined) {
      $("#output").html(response.error);
      return;
    }
    
    var player_regexp = RegExp("^"+params.player+"$", "i");
    
    // пагинация
    var pages_list = pagination(response, "world/" + params.world + "/regions");
    
    // список регионов
    var regions_list = "";
    for(var x = 0; x < response.regions.length; x++) {
      regions_list += regionParse(response.regions[x], params);
    }
    regions_list = "<ul class='list-group'>"+regions_list+"</ul>";
    
    var result = (pages_list!==""?"<div class='panel-body'>"+pages_list+"</div>":"") + regions_list + (pages_list!==""?"<div class='panel-body'>"+pages_list+"</div>":"");
    
    result = "<div class='panel panel-default'>"+result+"</div>";
    
    $("#output").html(result);
  },
  'history': {}
}

var region = {
  'base_url': 'http://api.ensemplix.ru/v2/region/', // ?region=w93_home&world=Davids
  'get': function (params) {
    var url = region.base_url + addParams({
      'region': params['region'],
      'world': params['world']
    });
    
    $("#breadcrumb").html("<ol class='breadcrumb'>"
        + "<li><a href='#'>Главная</a></li>"
        + "<li><a href='#world/%world%'>Мир</a></li>"
        + "<li><a href='#world/" + params['world'] + "'>" + params['world'] + "</a></li>"
        + "<li><a href='#world/" + params['world'] + "/region/%region%'>Регион</a></li>"
        + "<li class='active'>" + api.input("world/" + params['world'] + "/region/%s", params['region']) + "</li>"
      + "</ol>");
    
    $("#output").html("<div class='progress progress-striped active'><div class='progress-bar' style='width:100%;'>Загрузка...</div></div>");
    
    if(region.history[url] !== undefined) {
      region.parse(region.history[url]);
      
      console.log('loaded from history');
      return;
    }
    
    $.ajax(url, {
      cache: false,
      success: function (e) {
        region.history[url] = {'response': e, 'params': params};
        region.parse(region.history[url]);
      },
      error: function (e) {
        response = e.responseJSON;
        
        if(response.error != undefined) {
          $("#output").html(api.danger(response.error));
        } else {
          $("#output").html(api.danger("Ошибка"));
        }
      }
    });
  },
  'parse': function (e) { // {response: *json response*, params: *object of get params*}
    var response = e.response;
    var params = e.params;
    
    if(typeof response.region == "object") {
      var region = response.region;
      region.owners = response.owners;
      region.members = response.members;
      region.priority = region.priority;
      region.flags = response.flags;
      region.children = response.children;
      region.parent = response.parent;
    } else {
      var region = response;
    }
    
    var result = regionParse(region, params);
    
    $("#output").html(result);
  },
  'history': {}
}

var clans = {
  'base_url': 'http://api.ensemplix.ru/v2/clans/',
  'get': function (params) {
    var url = clans.base_url + addParams({
      'offset': params['offset'] || 0
    });
    
    $("#breadcrumb").html("<ol class='breadcrumb'>"
        + "<li><a href='#'>Главная</a></li>"
        + "<li class='active'>Кланы</li>"
      + "</ol>");
    
    $("#output").html("<div class='progress progress-striped active'><div class='progress-bar' style='width:100%;'>Загрузка...</div></div>");
    
    if(clans.history[url] !== undefined) {
      clans.parse(clans.history[url]);
      
      console.log('loaded from history');
      return;
    }
    
    $.ajax(url, {
      cache: false,
      success: function (e) {
        clans.history[url] = {'response': e, 'params': params};
        
        clans.parse(clans.history[url]);
      },
      error: function (e) {
        response = e.responseJSON;
        
        if(response.error != undefined) {
          $("#output").html(api.danger(response.error));
        } else {
          $("#output").html(api.danger("Ошибка"));
        }
      }
    });
  },
  'parse': function (e) {
    var response = e.response;
    var params = e.params;
    
    var clans = response.clans;
    var clans_list = [];

    /*for(var x = 0; x < clans.length; x++) {
      var clan = clans[x];
      
      clans_list.push(""
        + "<li class='media'>"
        + "<img class='media-object pull-left' src='https://ensemplix.ru/images/clans/" + encodeURI(clan.clan) + "_logo.png' alt='" + clan.clan + " logo' style='height:64px;' onerror=\"this.src='https://ensemplix.ru/images/clans/default.jpg';\">"
        + "<div class='media-body'><h4 class='media-heading'><a href='#clan/" + encodeURIComponent(clan.id) + "'>" + clan.clan + "</a></h4>"
          + "Лидер клана: <a href='#player/" + clan.leader + "'>" + clan.leader + "</a><br/>"
          + "Игроков: " + clan.members + "<br/>"
          + "Опыт: " + (clan.exp).toString().replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1&thinsp;')
        + "</div>"
        + "</li>");
    }
    
    clans_list = "<ul class='media-list'>" + clans_list.join("") + "</ul>";*/

//<div class="container">
//<div class="col-md-6"><ul class="media-list"><li class="media"><img class="pull-left media-object" src="https://ensemplix.ru/images/logos/web93onv.png" alt="undefined logo" style="min-height:64px;min-width:64px;max-width:64px;" onerror="this.src='https://ensemplix.ru/images/logos/default.jpg';"><div class="media-body"><h4 class="media-heading"><a href="#player/web93onv">web93onv</a></h4>Клан Dominion<br>Имущество 182 864 койн<br></div></li></ul></div>

    for(var x = 0; x < clans.length; x++) {
      var clan = clans[x];
      
      clans_list.push(""
        + "<div class='col-md-4'><ul class='media-list'><li class='media'>"
        + "<img class='media-object pull-left' src='https://ensemplix.ru/images/clans/" + encodeURI(clan['clan']) + "_logo.png' alt='" + clan['clan'] + " logo' style='height:64px;' onerror=\"this.src='https://ensemplix.ru/images/clans/default.jpg';\">"
        //+ "<div class='media-body'><h4 class='media-heading'><a href='#clan/" + encodeURIComponent(clan.id) + "'>" + clan.clan + "</a></h4>"
        + "<div class='media-body'><h4 class='media-heading'>" + api.clan(clan['id'], clan['clan']) + "</h4>"
          //+ "Лидер: <a href='#player/" + clan.leader + "'>" + clan.leader + "</a><br/>"
          + "Лидер: " + api.player(clan['leader'], true) + "<br/>"
          + "Игроков: " + clan['members'] + "<br/>"
          //+ "Опыт: " + (clan['exp']).toString().replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1&thinsp;')
          + "Опыт: " + api.number(clan['exp'])
        + "</div>"
        + "</li></ul></div>");
    }
    
    clans_list = clans_list.join("");
    
    $('#output').html(clans_list);
  },
  'history': {}
}

var clan = {
  'base_url': 'http://api.ensemplix.ru/v2/clan/',
  'get': function (params) {
    var url = clan.base_url + addParams({
      'id': params['id']
    });
    
    $("#breadcrumb").html("<ol class='breadcrumb'>"
        + "<li><a href='#'>Главная</a></li>"
        //+ "<li><a href='#clan/%clan%'>Клан</a></li>"
        + "<li>" + api.clan("%clan%", "Клан") + "</li>"
        + "<li class='active'>" + api.input("clan/%s", params['id']) + "</li>"
      + "</ol>");
    
    $("#output").html("<div class='progress progress-striped active'><div class='progress-bar' style='width:100%;'>Загрузка...</div></div>");
    
    if(clan.history[url] !== undefined) {
      clan.parse(clan.history[url]);
      
      console.log('loaded from history');
      return;
    }
    
    $.ajax(url, {
      cache: false,
      success: function (e) {
        clan.history[url] = {'response': e, 'params': params};
        
        clan.parse(clan.history[url]);
      },
      error: function (e) {
        response = e.responseJSON;
        
        if(response.error != undefined) {
          $("#output").html(api.danger(response.error));
        } else {
          $("#output").html(api.danger("Ошибка"));
        }
      }
    });
  },
  'parse': function (e) {
    var response = e.response;
    var params = e.params;
    
    var clan = response['clan'][0];
    var members = response['members'];
    
    var clan_info = "<ul class='media-list'>"
        + "<li class='media'>"
          + "<img class='media-object pull-left' src='https://ensemplix.ru/images/clans/" + (encodeURI(clan['logo']) || "default.jpg") + "' alt='" + clan['clan'] + " logo' style='width:96px;' onerror=\"this.src='https://ensemplix.ru/images/clans/default.jpg';\" />"
          + "<div class='media-body'><h4 class='media-heading'>" + api.clan(clan['id'], clan['clan']) + "</h4>"
            //+ "Лидер: <a href='#player/" + clan['leader'] + "'>" + clan['leader'] + "</a><br/>"
            + "Лидер: " + api.player(clan['leader'], true) + "<br/>"
            + "Игроков: " + clan['members'] + "<br/>"
            + "Опыт: " + api.number(clan['exp']) + "<br/>"
            + "Cоздан: " + api.time(clan['created']) + "<br/>"
          + "</div>"
        + "</li>"
      + "</ul>";
    
    var clan_members = [];
    for(var x = 0; x < members.length; x++) {
      var member = members[x];
      
      clan_members.push(""
        + "<div class='col-sm-4'><ul class='media-list'><li class='media'>"
        + "<img class='media-object pull-left' src='https://ensemplix.ru/images/logos/" + member['player'] + ".png' alt='" + member['player'] + " logo' style='min-height:48px;min-width:48px;max-width:48px;' onerror=\"this.src='https://ensemplix.ru/images/logos/default.jpg';\" />"
        + "<div class='media-body'><h4 class='media-heading'><a href='#player/" + encodeURIComponent(member['player']) + "'>" + member['player'] + "</a></h4>"
          + "<small>" + api.time(member['joined']) + "</small><br/>"
          + (member['invite'] ? "<small>права на инвайт</small>" : "")
          + "<br/>"
        + "</div>"
        + "</li></ul></div>");
    }
    clan_members = clan_members.join("");
    
    var result = "<div class='col-md-4'>"
      + clan_info
      + "</div>"
      + "<div class='col-md-8'>"
      + (clan['info'] != null ? "<p style='white-space:pre-wrap;'>" + clan['info'] + "</p>" : "")
      + "</div>"
      + "<div style='clear:both;' class='container'><hr/><p>Участники клана. Всего: " + members.length + "</p></div>" + clan_members + "";
    
    $('#output').html(result);
  },
  'history': {}
}

var server_worlds = {
  'base_url': 'http://api.ensemplix.ru/v2/server/game/',
  'get': function (params) {
    var url = server_worlds.base_url;
    
    $("#breadcrumb").html("<ol class='breadcrumb'>"
        + "<li><a href='#'>Главная</a></li>"
        + "<li><a href='#server'>Сервер</a></li>"
        + "<li class='active'>Игровые сервера</li>"
      + "</ol>");
    
    $("#output").html("<div class='progress progress-striped active'><div class='progress-bar' style='width:100%;'>Загрузка...</div></div>");
    
    if(server_worlds.history[url] !== undefined) {
      server_worlds.parse(server_worlds.history[url]);
      
      console.log('loaded from history');
      return;
    }
    
    $.ajax(url, {
      cache: false,
      success: function (e) {
        server_worlds.history[url] = {'response': e, 'params': params};
        
        server_worlds.parse(server_worlds.history[url]);
      },
      error: function (e) {
        response = e.responseJSON;
        
        if(response.error != undefined) {
          $("#output").html(api.danger(response.error));
        } else {
          $("#output").html(api.danger("Ошибка"));
        }
      }
    });
  },
  'parse': function (e) {
    var response = e.response;
    var params = e.params;
    
    var result = "";
    
    for(var x = 0; x < response.length; x++) {
      var server = response[x];
      
      var players = server['players'];
      var maximum = server['maximum'];
      
      var width = 100;
      var bar = "success";
      var label = "<b>" + players + "</b><small> / " + maximum + "</small>";
      
      if(server['online']) {
        if(maximum == 0) {
          bar = "danger";
          width = 100;
        } else if(maximum > 0) {
          width = (players / maximum) * 100;
        }
        
        if(players == 0) {
          bar = "warning";
          width = 100;
        }
      } else {
        bar = "danger";
        width = 100;
        label = "Оффлайн";
      }
      
      result += "<div class='col-sm-3'>"
        + "<h4><a href='#world/" + server['world'] + "'>" + server['name'] + "</a> <small>#" + server['id'] + "</small></h4>"
        + "<p>"
          + "Minecraft " + server['server_version'] + ", " + server['server_type'] + "<br/>"
          + "<small>" + server['ip'] + ":" + server['port'] + "</small>"
        + "</p>"
        + "<div class='progress'><div class='progress-bar progress-bar-" + bar + "' style='width:" + width + "%;min-width:50px;'>" + label + "</div></div>"
        + "</div>"
        + "";
    }
    
    result = "<div class='row'>" + result + "</div>";
    
    $('#output').html(result);
  },
  'history': {}
}

var server_blacklist = {
  'base_url': 'http://api.ensemplix.ru/v2/server/blacklist/',
  'get': function (params) {
    var url = server_blacklist.base_url;
    
    $("#breadcrumb").html("<ol class='breadcrumb'>"
        + "<li><a href='#'>Главная</a></li>"
        + "<li><a href='#server'>Сервер</a></li>"
        + "<li class='active'>Блеклист</li>"
      + "</ol>");
    
    $("#output").html("<div class='progress progress-striped active'><div class='progress-bar' style='width:100%;'>Загрузка...</div></div>");
    
    if(server_blacklist.history[url] !== undefined) {
      server_blacklist.parse(server_blacklist.history[url]);
      
      console.log('loaded from history');
      return;
    }
    
    $.ajax(url, {
      cache: false,
      success: function (e) {
        server_blacklist.history[url] = {'response': e, 'params': params};
        
        server_blacklist.parse(server_blacklist.history[url]);
      },
      error: function (e) {
        response = e.responseJSON;
        
        if(response.error != undefined) {
          $("#output").html(api.danger(response.error));
        } else {
          $("#output").html(api.danger("Ошибка"));
        }
      }
    });
  },
  'parse': function (e) {
    var response = e.response;
    var params = e.params;
    
    var items = {};
    
    for(var item = 0; item < response.length; item++) {
      if(items[response[item]['server']] == undefined) items[response[item]['server']] = [];
      
      items[(response[item]['server'])].push(response[item]);
    }
    
    var result = [];
    
    for(var server in items) {
      var tmp = ""
      
      for(var y = 0; y < items[server].length; y++) {
        var item_id = items[server][y]['item'].split(":")[0];
        var data = items[server][y]['item'].split(":")[1] || 0;
        
        //tmp += "<span class='item " + server + " item_" + items[server][y].item.replace(/:/,"_") + "' title='#" + items[server][y].item + "'></span>"
        tmp += api.item(item_id, data, server);
      }
      
      result.push("<div><h3>" + server + "</h3><div class='item-list'>" + tmp + "</div></div>");
    }
    
    result = result.join("<hr/>");
    
    $('#output').html(result);
    //$('.item').tooltip();
  },
  'history': {}
}

var warps_server = {
  'base_url': 'http://api.ensemplix.ru/v2/warps/',
  'get': function (params) {
    var url = warps_server.base_url + addParams({'world': params.world, 'offset': params.offset || 0});
    
    $("#breadcrumb").html("<ol class='breadcrumb'>"
        + "<li><a href='#'>Главная</a></li>"
        + "<li>" + api.world("%world%", "Мир") + "</li>"
        + "<li>" + api.world(params['world']) + "</li>"
        + "<li class='active'>Варпы</li>"
      + "</ol>");
    
    $("#output").html("<div class='progress progress-striped active'><div class='progress-bar' style='width:100%;'>Загрузка...</div></div>");
    
    if(warps_server.history[url] !== undefined) {
      warps_server.parse(warps_server.history[url]);
      
      console.log('loaded from history');
      return;
    }
    
    $.ajax(url, {
      cache: false,
      success: function (e) {
        warps_server.history[url] = {'response': e, 'params': params};
        
        warps_server.parse(warps_server.history[url]);
      },
      error: function (e) {
        response = e.responseJSON;
        
        if(response.error != undefined) {
          $("#output").html(api.danger(response.error));
        } else {
          $("#output").html(api.danger("Ошибка"));
        }
      }
    });
  },
  'parse': function (e) {
    var response = e.response;
    var params = e.params;
    
    var warps = response.warps;
    
    var pages_list = pagination(response, "world/" + params.world + "/warps");
    pages_list = pages_list !== "" ? "<div class='panel-body'>" + pages_list + "</div>" : "";
    
    var result = "";
    for(var x = 0; x < warps.length; x++) {
      var warp = warps[x];
      
      result += "<li class='list-group-item'>"
          + "<b>Варп:</b> " + api.warp(warp['warp'], warp['world']) + ", "
          + "<span class='glyphicon glyphicon-globe'></span> " + api.world(warp['world']) + ", "
          + "<span class='glyphicon glyphicon-map-marker'></span> " + api.coord(Math.round(warp['x']) + "," + Math.round(warp['y']) + "," + Math.round(warp['z']), warp['world'])
          + (warp['greeting'] == null ? "" : "<span class='mc mc-textarea pull-right'>" + mcChat(warp['greeting']) + "</span>")
          + "<br/>"
          + "<b>Владелец:</b> " + api.player(warp['owner'], true) + "<br/>"
          + "<b>Дата создания:</b> " + api.time(warp['created'])
        + "</li>";
    }
    result = "<div class='panel panel-default'>" + pages_list + "<ul class='list-group'>" + result + "</ul>" + pages_list + "</div>";
    
    $('#output').html(result);
  },
  'history': {}
}

var server_news = {
  'base_url': 'http://api.ensemplix.ru/v2/server/news/',
  'get': function (params) {
    var url = server_news.base_url;
    
    $("#breadcrumb").html("<ol class='breadcrumb'>"
        + "<li><a href='#'>Главная</a></li>"
        + "<li><a href='#server'>Сервер</a></li>"
        + "<li class='active'>Новости</li>"
      + "</ol>");
    
    $("#output").html("<div class='progress progress-striped active'><div class='progress-bar' style='width:100%;'>Загрузка...</div></div>");
    
    if(server_news.history[url] !== undefined) {
      server_news.parse(server_news.history[url]);
      
      console.log('loaded from history');
      return;
    }
    
    $.ajax(url, {
      cache: false,
      success: function (e) {
        server_news.history[url] = {'response': e, 'params': params};
        
        server_news.parse(server_news.history[url]);
      },
      error: function (e) {
        response = e.responseJSON;
        
        if(response.error != undefined) {
          $("#output").html(api.danger(response.error));
        } else {
          $("#output").html(api.danger("Ошибка"));
        }
      }
    });
  },
  'parse': function (e) {
    var response = e.response;
    var params = e.params;
    
    var result = [];
    for(var x = 0; x < response.length; x++) {
      //result.push("<div class='panel panel-default'><div class='panel-body'>"
      result.push(""
          + "<h3 style='margin-top:0;'>"
            + "<img class='pull-right img-thumbnail' style='margin:0 0 15px 15px;' src='https://ensemplix.ru/images/news/" + response[x]['image'] + "' />"
            + "<a href='https://ensemplix.ru/news/view/" + response[x]['id'] + "/' target='_blank'>" + response[x]['header'] + "</a>"
          + "</h3>"
          + "<p><span class='glyphicon glyphicon-time'></span> " + api.time(response[x]['created']) + ", "
          + "<span class='glyphicon glyphicon-eye-open'></span> " + response[x]['views'] + "</p>"
          + "<p style='white-space:pre-line;margin-bottom:0;'>" + response[x]['text'] + "</p>"
        //+ "</div></div>");
        + "");
    }
    //result = result.join("");
    result = result.join("<hr/>");
    
    $('#output').html(result);
  },
  'history': {}
}

var server_banlist = {
  'base_url': 'http://api.ensemplix.ru/v2/server/bans/',
  'get': function (params) {
    var url = server_banlist.base_url + addParams({
      'offset': params['offset'] || 0
    });
    
    $("#breadcrumb").html("<ol class='breadcrumb'>"
        + "<li><a href='#'>Главная</a></li>"
        + "<li><a href='#server'>Сервер</a></li>"
        + "<li class='active'>Банлист</li>"
      + "</ol>");
    
    $("#output").html("<div class='progress progress-striped active'><div class='progress-bar' style='width:100%;'>Загрузка...</div></div>");
    
    if(server_banlist.history[url] !== undefined) {
      server_banlist.parse(server_banlist.history[url]);
      
      console.log('loaded from history');
      return;
    }
    
    $.ajax(url, {
      cache: false,
      success: function (e) {
        server_banlist.history[url] = {'response': e, 'params': params};
        
        server_banlist.parse(server_banlist.history[url]);
      },
      error: function (e) {
        response = e.responseJSON;
        
        if(response.error != undefined) {
          $("#output").html(api.danger(response.error));
        } else {
          $("#output").html(api.danger("Ошибка"));
        }
      }
    });
  },
  'parse': function (e) {
    var response = e.response;
    var params = e.params;
    
    var bans = response.bans;
    
    var pages_list = pagination(response, "server/banlist");
    pages_list = pages_list == "" ? "" : "<div class='panel-body'>" + pages_list + "</div>";
    
    var result = "";
    result += "<tr>"
        + "<th>Игрок</th>"
        + "<th>Причина</th>"
        + "<th>До</th>"
        + "<th>Кто забанил</th>"
        + "<th>Время бана</th>"
      + "</tr>";
      
    for(var x = 0; x < bans.length; x++) {
      var ban = bans[x];
      
      result += "<tr>"
          + "<td>" + api.player(ban['player'], true) + "</td>"
          + "<td>" + ban['reason'] + "</td>"
          + "<td>" + api.till(ban['till']) + "</td>"
          + "<td>" + api.player(ban['admin'], true) + "</td>"
          + "<td>" + api.time(ban['created']) + "</td>"
        + "</tr>";
    }
    result = "<div class='panel panel-default'>" + pages_list + "<table class='table table-striped'>" + result + "</table>" + pages_list + "</div>";
    
    $('#output').html(result);
  },
  'history': {}
}

var server_shops = {
  'base_url': 'http://api.ensemplix.ru/v2/shops/',
  'get': function (params) {
    var url = server_shops.base_url + addParams({
      'world': params['world'],
      'offset': params['offset'] || 0
    });
    
    $("#breadcrumb").html("<ol class='breadcrumb'>"
        + "<li><a href='#'>Главная</a></li>"
        + "<li>" + api.world("%world%", "Мир") + "</li>"
        + "<li>" + api.world(params['world']) + "</li>"
        + "<li class='active'>Торговля</li>"
      + "</ol>");
    
    $("#output").html("<div class='progress progress-striped active'><div class='progress-bar' style='width:100%;'>Загрузка...</div></div>");
    
    if(server_shops.history[url] !== undefined) {
      server_shops.parse(server_shops.history[url]);
      
      console.log('loaded from history');
      return;
    }
    
    $.ajax(url, {
      cache: false,
      success: function (e) {
        server_shops.history[url] = {'response': e, 'params': params};
        
        server_shops.parse(server_shops.history[url]);
      },
      error: function (e) {
        response = e.responseJSON;
        
        if(response.error != undefined) {
          $("#output").html(api.danger(response.error));
        } else {
          $("#output").html(api.danger("Ошибка"));
        }
      }
    });
  },
  'parse': function (e) {
    var response = e.response;
    var params = e.params;
    
    var history = response.history;
    
    if(history.length == 0) {
      $('#output').html(api.danger("История пуста. Возможно не правильно введено название мира."));
      return;
    }
    
    var pages_list = pagination(response, "world/" + params.world + "/shops");
    pages_list = pages_list == "" ? "" : "<div class='panel-body'>" + pages_list + "</div>";
    
    var result = "<tr>"
        + "<th colspan='2'>Предмет</th>"
        + "<th>Клиент</th>"
        + "<th>Операция</th>"
        + "<th>Владелец</th>"
        + "<th>Мир</th>"
        + "<th>Время</th>"
      + "</tr>";
      
    for(var x = 0; x < history.length; x++) {
      var item = history[x];
      
      result += "<tr>"
          + "<td>" + api.item(item['item_id'], item['data'], item['world']) + "</td>"
          + "<td>" + item['item'] + "<br/>" + item['amount'] + " шт.</td>"
          //+ "<td><span class='glyphicon glyphicon-user'></span> " + api.player(item['from'], true) + "</td>"
          + "<td>" + api.player(item['from'], true) + "</td>"
          + "<td>" + (item['operation'] == false? "Купил" : "Продал") + " за " + api.number(item['price']) + " койн</td>"
          //+ "<td><span class='glyphicon glyphicon-user'></span> " + api.player(item['to'], true) + "</td>"
          + "<td>" + api.player(item['to'], true) + "</td>"
          + "<td>"
            + "<span class='glyphicon glyphicon-globe'></span> <b>" + api.world(item['world']) + "</b>"
            + ", <span class='glyphicon glyphicon-map-marker'></span> " + api.coord(item['x'] + "," + item['y'] + "," + item['z'], item['world'])
          + "</td>"
          + "<td>" + api.time(item['created']) + "</td>"
        + "</tr>";
    }
    result = "<div class='panel panel-default'>" + pages_list + "<table class='table table-striped'>" + result + "</table>" + pages_list + "</div>";
    
    $('#output').html(result);
  },
  'history': {}
}

var server_files = {
  'base_url': 'http://resources.ensemplix.ru/',
  'get': function (params) {
    var url = server_files.base_url;
    
    $("#breadcrumb").html("<ol class='breadcrumb'>"
        + "<li><a href='#'>Главная</a></li>"
        + "<li><a href='#server'>Сервер</a></li>"
        + "<li class='active'>Файлы клиента</li>"
      + "</ol>");
    
    $("#output").html("<div class='progress progress-striped active'><div class='progress-bar' style='width:100%;'>Загрузка...</div></div>");
    
    if(server_files.history[url] !== undefined) {
      server_files.parse(server_files.history[url]);
      
      console.log('loaded from history');
      return;
    }
    
    $.ajax(url, {
      cache: false,
      success: function (e) {
        server_files.history[url] = {'response': e, 'params': params};
        
        server_files.parse(server_files.history[url]);
      },
      error: function (e) {
        response = e.responseJSON;
        
        if(response.error != undefined) {
          $("#output").html(api.danger(response.error));
        } else {
          $("#output").html(api.danger("Ошибка"));
        }
      }
    });
  },
  'parse': function (e) {
    var response = JSON.parse(e.response);
    
    var files = response;
    var files_list = [];
    
    var result = "<tr>"
        + "<th>Файл</th>"
        + "<th>Размер файла</th>"
        + "<th>Etag</th>"
      "</tr>";
    
    for(var x = 0; x < files.length; x++) {
      var file = files[x];
      
      files_list.push("http://resources.ensemplix.ru/" + file['name']);
      result += "<tr>"
          + "<td>"
            + "<a href='http://resources.ensemplix.ru/" + file['name'] + "' download='" + file['name'].match(/[^\/]+$/)[0] + "' title='Скачать файл'><span class='glyphicon glyphicon-download-alt'></span></a> "
            + file['name']
          + "</td>"
          + "<td align='right'>" + api.number(file['size']) + " байт</td>"
          + "<td>" + file['etag'] + "</td>"
        + "</tr>";
    }
    
    files_list = files_list.join("\n");
    result = ""
      + "<h3>Лаунчер</h3>"
      + "<p>Лаунчер не требует установки (portable). Скачать и запустить.</p>"
      + "<ul>"
        + "<li><img src='http://image.chromefans.org/fileicons/format/exe.png' /> <a href='http://files.ensemplix.ru/enLauncher.exe'>enLauncher.exe</a> Windows</li>"
        + "<li><img src='http://image.chromefans.org/fileicons/format/jar.png' /> <a href='http://files.ensemplix.ru/enLauncher.jar'>enLauncher.jar</a> Linux/Mac</li>"
      + "</ul>"
      + "<h3>Архив клиента</h3>"
      + "<p>Файл содержит себе папку <em>.ensemplix</em> со всем её содержимым.<br/>Архив следует извлекать в папку %appdata%.</p>"
      + "<p>Обновляется вручную.</p>"
      + "<ul>"
        + "<li><img src='http://image.chromefans.org/fileicons/format/zip.png' /> <a href='http://webapi.ensemplix.ru/ensemplix-client-14-04-10.zip'>ensemplix-client-14-04-10.zip</a></li>"
        + "<li><img src='http://image.chromefans.org/fileicons/format/zip.png' /> <a href='http://webapi.ensemplix.ru/ensemplix-client-14-05-20.zip'>ensemplix-client-14-05-20.zip</a></li>"
      + "</ul>"
      + "<h3>Файлы клиента</h3>"
      + "<p>Актуальная информация о всех файлах клиента.</p>"
      + "<table class='table table-striped'>" + result + "</table>";
    
    $('#output').html(result);
  },
  'history': {}
}

var server_blocks = {
  'base_url': 'http://api.ensemplix.ru/v2/blocks/',
  'get': function (params) {
    var url = server_blocks.base_url + addParams({'world': params.world, 'offset': params.offset || 0});
    
    $("#breadcrumb").html("<ol class='breadcrumb'>"
        + "<li><a href='#'>Главная</a></li>"
        + "<li>" + api.world("%world%", "Мир") + "</li>"
        + "<li>" + api.world(params['world']) + "</li>"
        + "<li class='active'>Логи блоков</li>"
      + "</ol>");
    
    $("#output").html("<div class='progress progress-striped active'><div class='progress-bar' style='width:100%;'>Загрузка...</div></div>");
    
    if(server_blocks.history[url] !== undefined) {
      server_blocks.parse(server_blocks.history[url]);
      
      console.log('loaded from history');
      return;
    }
    
    $.ajax(url, {
      cache: false,
      success: function (e) {
        server_blocks.history[url] = {'response': e, 'params': params};
        
        server_blocks.parse(server_blocks.history[url]);
      },
      error: function (e) {
        response = e.responseJSON;
        
        if(response.error != undefined) {
          $("#output").html(api.danger(response.error));
        } else {
          $("#output").html(api.danger("Ошибка"));
        }
      }
    });
  },
  'parse': function (e) {
    var response = e.response;
    var params = e.params;
    
    var blocks = response.blocks;
    
    var pages_list = pagination(response, "world/" + params.world + "/blocks");
    pages_list = pages_list == "" ? "" : "<div class='panel-body'>" + pages_list + "</div>";
    
    var result = "<tr>"
        + "<th></th>"
        + "<th colspan='2'>Блок</th>"
        + "<th>Игрок</th>"
        + "<th>Мир</th>"
        + "<th>Время</th>"
      + "</tr>";
      
    for(var x = 0; x < blocks.length; x++) {
      var block = blocks[x];
      
      result += blockParse(block);
    }
    
    result = "<style>.success a, .warning a { color: inherit; }</style><div class='panel panel-default'>" + pages_list + "<table class='table table-striped'>" + result + "</table>" + pages_list + "</div>";
    
    $('#output').html(result);
  },
  'history': {}
}

var player_warps = {
  'base_url': 'http://api.ensemplix.ru/v2/warps/player/',
  'get': function (params) {
    var url = player_warps.base_url + addParams({
      'player': params['player'],
      'offset': params['offset'] || 0
    });
    
    $("#breadcrumb").html("<ol class='breadcrumb'>"
        + "<li><a href='#'>Главная</a></li>"
        + "<li><a href='#player/%player%'>Игрок</a></li>"
        + "<li>" + api.player(params['player']) + "</li>"
        + "<li class='active'>Варпы</li>"
      + "</ol>");
    
    $("#output").html("<div class='progress progress-striped active'><div class='progress-bar' style='width:100%;'>Загрузка...</div></div>");
    
    if(player_warps.history[url] !== undefined) {
      player_warps.parse(player_warps.history[url]);
      
      console.log('loaded from history');
      return;
    }
    
    $.ajax(url, {
      cache: false,
      success: function (e) {
        player_warps.history[url] = {'response': e, 'params': params};
        
        player_warps.parse(player_warps.history[url]);
      },
      error: function (e) {
        response = e.responseJSON;
        
        if(response.error != undefined) {
          $("#output").html(api.danger(response.error));
        } else {
          $("#output").html(api.danger("Ошибка"));
        }
      }
    });
  },
  'parse': function (e) {
    var response = e.response;
    var params = e.params;
    
    var warps = response.warps;
    
    var pages_list = pagination(response, "player/" + params['player'] + "/warps");
    pages_list = pages_list !== "" ? "<div class='panel-body'>" + pages_list + "</div>" : "";
    
    var result = "";
    for(var x = 0; x < warps.length; x++) {
      var warp = warps[x];
      
      result += "<li class='list-group-item'>"
          //+ "<b>Варп:</b> <a href='#world/" + warp['world'] + "/warp/" + encodeURI(warp['warp']) + "'>" + warp['warp'] + "</a>, "
          + "<b>Варп:</b> " + api.warp(warp['warp'], warp['world']) + ", "
          //+ "<span class='glyphicon glyphicon-globe'></span> <a href='#world/" + warp['world'] + "'>" + warp['world'] + "</a>, "
          + "<span class='glyphicon glyphicon-globe'></span> " + api.world(warp['world']) + "</a>, "
          + "<span class='glyphicon glyphicon-map-marker'></span> " + api.coord(Math.round(warp['x']) + "," + Math.round(warp['y']) + "," + Math.round(warp['z']), warp['world']) + ""
          + (warp['greeting'] == null ? "" : "<span class='mc mc-textarea pull-right'>" + mcChat(warp['greeting']) + "</span>")
          + "<br/>"
          //+ "<b>Владелец:</b> <a href='#player/" + warp['owner'] + "'>" + warp['owner'] + "</a><br/>"
          + "<b>Владелец:</b> " + api.player(warp['owner'], true) + "<br/>"
          + "<b>Дата создания:</b> " + api.time(warp['created'])
        + "</li>";
    }
    result = "<div class='panel panel-default'>" + pages_list + "<ul class='list-group'>" + result + "</ul>" + pages_list + "</div>";
    
    $('#output').html(result);
  },
  'history': {}
}

var warp = {
  'base_url': 'http://api.ensemplix.ru/v2/warp/',
  'get': function (params) {
    var url = warp.base_url + addParams({
      'world': params['world'],
      'warp': params['warp']
    });
    
    $("#breadcrumb").html("<ol class='breadcrumb'>"
        + "<li><a href='#'>Главная</a></li>"
        + "<li>" + api.world("%world%", "Мир") + "</li>"
        + "<li>" + api.world(params['world']) + "</li>"
        + "<li>" + api.warp("%warp%", params['world'], "Варп") + "</li>"
        + "<li class='active'>" + api.input("world/" + params['world'] + "/warp/%s", params['warp']) + "</li>"
      + "</ol>");
    
    $("#output").html("<div class='progress progress-striped active'><div class='progress-bar' style='width:100%;'>Загрузка...</div></div>");
    
    if(warp.history[url] !== undefined) {
      warp.parse(warp.history[url]);
      
      console.log('loaded from history');
      return;
    }
    
    $.ajax(url, {
      cache: false,
      success: function (e) {
        warp.history[url] = {'response': e, 'params': params};
        
        warp.parse(warp.history[url]);
      },
      error: function (e) {
        response = e.responseJSON;
        
        if(response.error != undefined) {
          $("#output").html(api.danger(response.error));
        } else {
          $("#output").html(api.danger("Ошибка"));
        }
      }
    });
  },
  'parse': function (e) {
    var response = e.response;
    var params = e.params;
    
    var warp = response;
    
    var result = "<ul class='list-group'><li class='list-group-item'>"
        + "<b>Варп:</b> " + api.warp(warp['warp'], warp['world']) + ", "
        + "<span class='glyphicon glyphicon-globe'></span> " + api.world(warp['world']) + ", "
        + "<span class='glyphicon glyphicon-map-marker'></span> " + api.coord(Math.round(warp['x']) + "," + Math.round(warp['y']) + "," + Math.round(warp['z']), warp['world'])
        + (warp['greeting'] == null ? "" : "<span class='mc mc-textarea pull-right'>" + mcChat(warp['greeting']) + "</span>")
        + "<br/>"
        + "<b>Владелец:</b> " + api.player(warp['owner'], true) + "<br/>"
        + "<b>Дата создания:</b> " + api.time(warp['created'])
      + "</li></ul>";
    
    $('#output').html(result);
  },
  'history': {}
}

var violations = {
  'base_url': 'http://api.ensemplix.ru/v2/player/violations/',
  'get': function (params) {
    var url = violations.base_url + addParams({
      'player': params['player']
    });
    
    $("#breadcrumb").html("<ol class='breadcrumb'>"
        + "<li><a href='#'>Главная</a></li>"
        + "<li><a href='#player/%player%'>Игрок</a></li>"
        + "<li>" + api.player(params['player']) + "</li>"
        + "<li class='active'>Наказания</li>"
      + "</ol>");
    
    $("#output").html("<div class='progress progress-striped active'><div class='progress-bar' style='width:100%;'>Загрузка...</div></div>");
    
    if(violations.history[url] !== undefined) {
      violations.parse(violations.history[url]);
      
      console.log('loaded from history');
      return;
    }
    
    $.ajax(url, {
      cache: false,
      success: function (e) {
        violations.history[url] = {'response': e, 'params': params};
        
        violations.parse(violations.history[url]);
      },
      error: function (e) {
        response = e.responseJSON;
        
        if(response.error != undefined) {
          $("#output").html(api.danger(response.error));
        } else {
          $("#output").html(api.danger("Ошибка"));
        }
      }
    });
  },
  'parse': function (e) {
    var response = e.response;
    var params = e.params;
    
    var result = "";
    
    if(response['banned'] == null && response['muted'] == null) {
      $('#output').html(api.info(
            api.player(params['player'], true) + " сейчас не наказан"
          )
        );
    } else {
      if(response['banned']) {
        result += api.danger("<b>" + api.player(response['banned']['player'], true) + "</b> забанен администратором <b>" + api.player(response['banned']['admin'], true) + "</b> по причине: <b>" + response['banned']['reason'] + "</b><br/>"
            + "От <b>" + api.time(response['banned']['created']) + "</b> &mdash; до <b>" + api.period(response['banned']['till']) + "</b>"
          );
      }
      
      if(response['muted']) {
        result += api.warning("<b>" + api.player(response['muted']['player'], true) + "</b> замучен администратором <b>" + api.player(response['muted']['admin'], true) + "</b> по причине: <b>" + response['muted']['reason'] + "</b><br/>"
            + "От <b>" + api.time(response['muted']['created']) + "</b> &mdash; до <b>" + api.period(response['muted']['till']) + "</b>"
          );
      }
      
      $('#output').html(result);
    }
  },
  'history': {}
}

var coord_blocks = {
  'base_url': 'http://api.ensemplix.ru/v2/blocks/location/',
  'get': function (params) {
    var url = coord_blocks.base_url + addParams({
      'world': params['world'],
      'x': params['x'],
      'y': params['y'],
      'z': params['z'],
      'offset': params['offset'] || 0
    });
    
    $("#breadcrumb").html("<ol class='breadcrumb'>"
        + "<li><a href='#'>Главная</a></li>"
        + "<li>" + api.world("%world%", "Мир") + "</li>"
        + "<li>" + api.world(params['world']) + "</a></li>"
        + "<li>" + api.coord("%x%,%y%,%z%", params['world'], "Координаты") + "</li>"
        + "<li>" + api.coord(params['x'] + "," + params['y'] + "," + params['z'], params['world']) + "</li>"
        + "<li class='active'>Логи блоков</li>"
      + "</ol>");
    
    $("#output").html("<div class='progress progress-striped active'><div class='progress-bar' style='width:100%;'>Загрузка...</div></div>");
    
    if(coord_blocks.history[url] !== undefined) {
      coord_blocks.parse(coord_blocks.history[url]);
      
      console.log('loaded from history');
      return;
    }
    
    $.ajax(url, {
      cache: false,
      success: function (e) {
        coord_blocks.history[url] = {'response': e, 'params': params};
        
        coord_blocks.parse(coord_blocks.history[url]);
      },
      error: function (e) {
        response = e.responseJSON;
        
        if(response.error != undefined) {
          $("#output").html(api.danger(response.error));
        } else {
          $("#output").html(api.danger("Ошибка"));
        }
      }
    });
  },
  'parse': function (e) {
    var response = e.response;
    var params = e.params;
    
    var blocks = response['blocks'];
    
    var pages_list = pagination(response, "world/" + params['world'] + "/blocks");
    pages_list = pages_list == "" ? "" : "<div class='panel-body'>" + pages_list + "</div>";
    
    var result = "<tr>"
        + "<th></th>"
        + "<th colspan='2'>Блок</th>"
        + "<th>Игрок</th>"
        + "<th>Мир</th>"
        + "<th>Время</th>"
      + "</tr>";
      
    for(var x = 0; x < blocks.length; x++) {
      var block = blocks[x];
      
      result += blockParse(block);
    }
    
    result = "<div class='panel panel-default'>" + pages_list + "<table class='table table-striped'>" + result + "</table>" + pages_list + "</div>";
    
    $('#output').html(result);
  },
  'history': {}
}

var coord_shops = {
  'base_url': 'http://api.ensemplix.ru/v2/shops/location/',
  'get': function (params) {
    var url = coord_shops.base_url + addParams({
      'world': params['world'],
      'x': params['x'],
      'y': params['y'],
      'z': params['z'],
      'offset': params['offset'] || 0
    });
    
    $("#breadcrumb").html("<ol class='breadcrumb'>"
        + "<li><a href='#'>Главная</a></li>"
        + "<li>" + api.world("%world%", "Мир") + "</li>"
        + "<li>" + api.world(params['world']) + "</a></li>"
        + "<li>" + api.coord("%x%,%y%,%z%", params['world'], "Координаты") + "</li>"
        + "<li>" + api.coord(params['x'] + "," + params['y'] + "," + params['z'], params['world']) + "</li>"
        + "<li class='active'>Логи магазина</li>"
      + "</ol>");
    
    $("#output").html("<div class='progress progress-striped active'><div class='progress-bar' style='width:100%;'>Загрузка...</div></div>");
    
    if(coord_shops.history[url] !== undefined) {
      coord_shops.parse(coord_shops.history[url]);
      
      console.log('loaded from history');
      return;
    }
    
    $.ajax(url, {
      cache: false,
      success: function (e) {
        coord_shops.history[url] = {'response': e, 'params': params};
        
        coord_shops.parse(coord_shops.history[url]);
      },
      error: function (e) {
        response = e.responseJSON;
        
        if(response.error != undefined) {
          $("#output").html(api.danger(response.error));
        } else {
          $("#output").html(api.danger("Ошибка"));
        }
      }
    });
  },
  'parse': function (e) {
    var response = e.response;
    var params = e.params;
    
    var history = response.history;
    
    if(history.length == 0) {
      $('#output').html(api.danger("История пуста."));
      return;
    }
    
    var pages_list = pagination(response, "world/" + params['world'] + "/coord/" + params['x'] + "," + params['y'] + "," + params['z'] + "/shops");
    pages_list = pages_list == "" ? "" : "<div class='panel-body'>" + pages_list + "</div>";
    
    var result = "<tr>"
        + "<th colspan='2'>Предмет</th>"
        + "<th>Клиент</th>"
        + "<th>Операция</th>"
        + "<th>Владелец</th>"
        + "<th>Мир</th>"
        + "<th>Время</th>"
      + "</tr>";
      
    for(var x = 0; x < history.length; x++) {
      var item = history[x];
      
      result += "<tr>"
          + "<td>" + api.item(item['item_id'], item['data'], item['world']) + "</td>"
          + "<td>" + item['item'] + "<br/>" + item['amount'] + " шт.</td>"
          + "<td>" + api.player(item['from'], true) + "</td>"
          + "<td>" + (item['operation'] == false? "Купил" : "Продал") + " за " + api.number(item['price']) + " койн</td>"
          + "<td>" + api.player(item['to'], true) + "</td>"
          + "<td>"
            + "<span class='glyphicon glyphicon-globe'></span> <b>" + api.world(item['world']) + "</b>"
            + ", <span class='glyphicon glyphicon-map-marker'></span> " + api.coord(item['x'] + "," + item['y'] + "," + item['z'], item['world'])
          + "</td>"
          + "<td>" + api.time(item['created']) + "</td>"
        + "</tr>";
    }
    result = "<div class='panel panel-default'>" + pages_list + "<table class='table table-striped'>" + result + "</table>" + pages_list + "</div>";
    
    $('#output').html(result);
  },
  'history': {}
}

var shops = {
  'base_url': 'http://api.ensemplix.ru/v2/shops/player/',
  'get': function (params) {
    var url = shops.base_url + addParams({
      'player': params['player'],
      'offset': params['offset'] || 0
    });
    
    $("#breadcrumb").html("<ol class='breadcrumb'>"
        + "<li><a href='#'>Главная</a></li>"
        + "<li><a href='#player/%player%'>Игрок</a></li>"
        + "<li>" + api.player(params['player']) + "</li>"
        + "<li class='active'>Логи магазина</li>"
      + "</ol>");
    
    $("#output").html("<div class='progress progress-striped active'><div class='progress-bar' style='width:100%;'>Загрузка...</div></div>");
    
    if(shops.history[url] !== undefined) {
      shops.parse(shops.history[url]);
      
      console.log('loaded from history');
      return;
    }
    
    $.ajax(url, {
      cache: false,
      success: function (e) {
        shops.history[url] = {'response': e, 'params': params};
        
        shops.parse(shops.history[url]);
      },
      error: function (e) {
        response = e.responseJSON;
        
        if(response.error != undefined) {
          $("#output").html(api.danger(response.error));
        } else {
          $("#output").html(api.danger("Ошибка"));
        }
      }
    });
  },
  'parse': function (e) {
    var response = e.response;
    var params = e.params;
    
    var history = response.history;
    
    if(history.length == 0) {
      $('#output').html(api.danger("История пуста."));
      return;
    }
    
    var pages_list = pagination(response, "player/" + params['player'] + "/shops");
    pages_list = pages_list == "" ? "" : "<div class='panel-body'>" + pages_list + "</div>";
    
    var result = "<tr>"
        + "<th colspan='2'>Предмет</th>"
        + "<th>Клиент</th>"
        + "<th>Операция</th>"
        + "<th>Владелец</th>"
        + "<th>Мир</th>"
        + "<th>Время</th>"
      + "</tr>";
      
    for(var x = 0; x < history.length; x++) {
      var item = history[x];
      
      result += "<tr>"
          + "<td>" + api.item(item['item_id'], item['data'], item['world']) + "</td>"
          + "<td>" + item['item'] + "<br/>" + item['amount'] + " шт.</td>"
          + "<td>" + api.player(item['from'], true) + "</td>"
          + "<td>" + (item['operation'] == false? "Купил" : "Продал") + " за " + api.number(item['price']) + " койн</td>"
          + "<td>" + api.player(item['to'], true) + "</td>"
          + "<td>"
            + "<span class='glyphicon glyphicon-globe'></span> <b>" + api.world(item['world']) + "</b>"
            + ", <span class='glyphicon glyphicon-map-marker'></span> " + api.coord(item['x'] + "," + item['y'] + "," + item['z'], item['world'])
          + "</td>"
          + "<td>" + api.time(item['created']) + "</td>"
        + "</tr>";
    }
    result = "<div class='panel panel-default'>" + pages_list + "<table class='table table-striped'>" + result + "</table>" + pages_list + "</div>";
    
    $('#output').html(result);
  },
  'history': {}
}

var coord_regions = {
  'base_url': 'http://api.ensemplix.ru/v2/regions/location/',
  'get': function (params) {
    var url = coord_regions.base_url + addParams({
      'world': params['world'],
      'x': params['x'],
      'y': params['y'],
      'z': params['z'],
      'offset': params['offset'] || 0
    });
    
    $("#breadcrumb").html("<ol class='breadcrumb'>"
        + "<li><a href='#'>Главная</a></li>"
        + "<li>" + api.world("%world%", "Мир") + "</li>"
        + "<li>" + api.world(params['world']) + "</a></li>"
        + "<li>" + api.coord("%x%,%y%,%z%", params['world'], "Координаты") + "</li>"
        + "<li>" + api.coord(params['x'] + "," + params['y'] + "," + params['z'], params['world']) + "</li>"
        + "<li class='active'>Ближайшие регионы</li>"
      + "</ol>");
    
    $("#output").html("<div class='progress progress-striped active'><div class='progress-bar' style='width:100%;'>Загрузка...</div></div>");
    
    if(coord_regions.history[url] !== undefined) {
      coord_regions.parse(coord_regions.history[url]);
      
      console.log('loaded from history');
      return;
    }
    
    $.ajax(url, {
      cache: false,
      success: function (e) {
        coord_regions.history[url] = {'response': e, 'params': params};
        
        coord_regions.parse(coord_regions.history[url]);
      },
      error: function (e) {
        response = e.responseJSON;
        
        if(response.error != undefined) {
          $("#output").html(api.danger(response.error));
        } else {
          $("#output").html(api.danger("Ошибка"));
        }
      }
    });
  },
  'parse': function (e) {
    var response = e.response;
    var params = e.params;
    
    var player_regexp = RegExp("^" + params['player'] + "$", "i");
    
    // пагинация
    var pages_list = pagination(response, "world/" + params['world'] + "/coord/" + params['x'] + "," + params['y'] + "," + params['z'] + "/regions");
    pages_list = (pages_list !== "" ? "<div class='panel-body'>" + pages_list + "</div>" : "");
    
    // список регионов
    var regions_list = "";
    for(var x = 0; x < response['regions'].length; x++) {
      regions_list += regionParse(response['regions'][x], params);
    }
    regions_list = "<ul class='list-group'>" + regions_list + "</ul>";
    
    var result = pages_list + regions_list + pages_list;
    
    result = "<div class='panel panel-default'>"+result+"</div>";
    
    $("#output").html(result);
  },
  'history': {}
}

var coord_warps = {
  'base_url': 'http://api.ensemplix.ru/v2/warps/location/',
  'get': function (params) {
    var url = coord_warps.base_url + addParams({
      'world': params['world'],
      'x': params['x'],
      'y': params['y'],
      'z': params['z'],
      'offset': params['offset'] || 0
    });
    
    $("#breadcrumb").html("<ol class='breadcrumb'>"
        + "<li><a href='#'>Главная</a></li>"
        + "<li>" + api.world("%world%", "Мир") + "</li>"
        + "<li>" + api.world(params['world']) + "</a></li>"
        + "<li>" + api.coord("%x%,%y%,%z%", params['world'], "Координаты") + "</li>"
        + "<li>" + api.coord(params['x'] + "," + params['y'] + "," + params['z'], params['world']) + "</li>"
        + "<li class='active'>Ближайшие варпы</li>"
      + "</ol>");
    
    $("#output").html("<div class='progress progress-striped active'><div class='progress-bar' style='width:100%;'>Загрузка...</div></div>");
    
    if(coord_warps.history[url] !== undefined) {
      coord_warps.parse(coord_warps.history[url]);
      
      console.log('loaded from history');
      return;
    }
    
    $.ajax(url, {
      cache: false,
      success: function (e) {
        coord_warps.history[url] = {'response': e, 'params': params};
        
        coord_warps.parse(coord_warps.history[url]);
      },
      error: function (e) {
        response = e.responseJSON;
        
        if(response.error != undefined) {
          $("#output").html(api.danger(response.error));
        } else {
          $("#output").html(api.danger("Ошибка"));
        }
      }
    });
  },
  'parse': function (e) {
    var response = e.response;
    var params = e.params;
    
    var warps = response.warps;
    
    var pages_list = pagination(response, "world/" + params.world + "/warps");
    pages_list = pages_list !== "" ? "<div class='panel-body'>" + pages_list + "</div>" : "";
    
    var result = "";
    for(var x = 0; x < warps.length; x++) {
      var warp = warps[x];
      
      result += "<li class='list-group-item'>"
          + "<b>Варп:</b> " + api.warp(warp['warp'], warp['world']) + ", "
          + "<span class='glyphicon glyphicon-globe'></span> " + api.world(warp['world']) + ", "
          + "<span class='glyphicon glyphicon-map-marker'></span> " + api.coord(Math.round(warp['x']) + "," + Math.round(warp['y']) + "," + Math.round(warp['z']), warp['world'])
          + (warp['greeting'] == null ? "" : "<span class='mc mc-textarea pull-right'>" + mcChat(warp['greeting']) + "</span>")
          + "<br/>"
          + "<b>Владелец:</b> " + api.player(warp['owner'], true) + "<br/>"
          + "<b>Дата создания:</b> " + api.time(warp['created'])
        + "</li>";
    }
    result = "<div class='panel panel-default'>" + pages_list + "<ul class='list-group'>" + result + "</ul>" + pages_list + "</div>";
    
    $('#output').html(result);
  },
  'history': {}
}

var urls = [
  { name: "Главная",           description: "Главная страница",                                  url: "",                 pattern: "",                 callback: null,            type: 'static', parent: null },
  { name: "Сервер",            description: "Информация о сервере",                              url: "server",           pattern: "server",           callback: null,            type: 'static', parent: "Главная" },
    { name: "Игровые сервера", description: "Список игровых серверов и общая информация о них.", url: "server/worlds",    pattern: "server/worlds",       parent: "Сервер",
      callback: function (hash) {
        $("#breadcrumb").html("<ol class='breadcrumb'>"
            + "<li><a href='#'>Главная</a></li>"
            + "<li><a href='#server'>Сервер</a></li>"
            + "<li class='active'>Игровые сервера</li>"
          + "</ol>");
        
        server_worlds.get({});
      }
    },
    { name: "Блеклист",        description: "Cписок запрещенных предметов и блоков.",            url: "server/blacklist", pattern: "server/blacklist", parent: "Сервер",
      callback: function (hash) {
        $("#breadcrumb").html("<ol class='breadcrumb'>"
            + "<li><a href='#'>Главная</a></li>"
            + "<li><a href='#server'>Сервер</a></li>"
            + "<li class='active'>Блеклист</li>"
          + "</ol>");
        
        server_blacklist.get({})
      }
    },
    { name: "Банлист",         description: "Список игроков забаненых на сервере.",              url: "server/banlist",   pattern: "server/banlist/page([^/]+)",   parent: "Сервер",
      callback: function (hash) {
        var pattern = RegExp("^" + this.pattern + "$");
        var params = hash.match(pattern);
        
        if(!params) {
          params = [];
        }
        
        $("#breadcrumb").html("<ol class='breadcrumb'>"
            + "<li><a href='#'>Главная</a></li>"
            + "<li><a href='#server'>Сервер</a></li>"
            + "<li class='active'>Банлист</li>"
          + "</ol>");
        
        server_banlist.get({'offset': ((params[1] || 1) - 1) * 100});
      }
    },
    { name: "Файлы клиента",   description: "Список всех файлов клиента.",                       url: "server/files",     pattern: "server/files",     parent: "Сервер",
      callback: function (hash) {
        
        $("#breadcrumb").html("<ol class='breadcrumb'>"
            + "<li><a href='#'>Главная</a></li>"
            + "<li><a href='#server'>Сервер</a></li>"
            + "<li class='active'>Файлы клиента</li>"
          + "</ol>");
        
        server_files.get({});
      }
    },
    { name: "Новости",         description: "Последние новости сервера.",                        url: "server/news",      pattern: "server/news",      parent: "Сервер",
      callback: function (hash) {
        $("#breadcrumb").html("<ol class='breadcrumb'>"
            + "<li><a href='#'>Главная</a></li>"
            + "<li><a href='#server'>Сервер</a></li>"
            + "<li class='active'>Новости</li>"
          + "</ol>");
        
        server_news.get({});
      }
    },
  
  { name: "Кланы",   description: "Кланы сервера",      url: "clans",  parent: "Главная",
    callback: function (hash) { clans.get({}); }
  },
  { name: "Клан",    description: "Информация о клане", url: "clan/%id%", pattern: "clan/([^/]+)",   parent: "Главная",
    callback: function (hash) {
      var pattern = RegExp("^" + this.pattern + "$");
      var params = hash.match(pattern);
      
      var params = {
        'id': params[1]
      };
      
      for(var param in params) {
        if(/%[^%]+/.test(params[param])) {
          params[param] = prompt("Введите значение '" + param + "'", "");
          hash = hash.replace(RegExp("%" + param + "%"), params[param]);
        }
      };
      
      location.hash = hash;
      
      $("#breadcrumb").html("<ol class='breadcrumb'>"
          + "<li><a href='#'>Главная</a></li>"
          + "<li><a href='#clan/%id%'>Мир</a></li>"
          + "<li class='active'>" + params['id'] + "</li>"
        + "</ol>");
      
      clan.get(params);
    }
  },
  
  { name: "Мир",             description: "Информация о мире",      url: "world/%world%",               pattern: "world/([^/]+)",                     parent: "Главная", /*type: 'static',*/
    callback: function (hash) {
      var pattern = RegExp("^" + this.pattern + "$");
      var params = hash.match(pattern);
      
      var params = {
        'world': params[1]
      };
      
      for(var param in params) {
        if(/%[^%]+/.test(params[param])) {
          params[param] = prompt("Введите значение '" + param + "'", "");
          hash = hash.replace(RegExp("%" + param + "%"), params[param]);
        }
      };
      
      location.hash = hash;
      
      $("#breadcrumb").html("<ol class='breadcrumb'>"
          + "<li><a href='#'>Главная</a></li>"
          + "<li>" + api.world("%world%", "Мир") + "</li>"
          + "<li class='active'>" + api.input("world/%s", params['world']) + "</li>"
        + "</ol>");
        
      var e = webWhereEquals(urls, "parent", this.name);
      
      var result = [];
      for(var x = 0; x < e.length; x++) {
        result.push("<a href='#" + e[x]['url'].replace(/%world%/,params['world']) + "' class='list-group-item'>"
          + "<h4 class='list-group-item-heading'>" + e[x]['name'] + "</h4>"
          + "<p class='list-group-item-text'>" + e[x]['description'] + "</p>"
        + "</a>");
      }
        
      result = "<div class='list-group'>" + result.join("") + "</div>";
      
      $("#output").html(result);
     // warp.get({'world': params[1]});
    }
  },
    { name: "Регионы",       description: "Список регионов мира.",  url: "world/%world%/regions", pattern: "world/([^/]+)/regions(?:/page([^/]+))?", parent: "Мир",
      callback: function (hash) {
        var pattern = RegExp("^" + this.pattern + "$");
        var params = hash.match(pattern);
        
        var params = {
          'world': params[1],
          'offset': ((params[2] || 1) - 1) * 100
        };
        
        for(var param in params) {
          if(/%[^%]+/.test(params[param])) params[param] = prompt("Введите значение '" + param + "'", "");
        };
        
        $("#breadcrumb").html("<ol class='breadcrumb'>"
            + "<li><a href='#'>Главная</a></li>"
            + "<li>" + api.world("%world%", "Мир") + " &mdash; " + api.world(params['world']) + "</li>"
            + "<li class='active' >Регионы</li>"
          + "</ol>");
        
        regions_server.get(params);
      }
    },
    { name: "Регион",       description: "",  url: "world/%world%/region/%region%", pattern: "world/([^/]+)/region/([^/]+)", parent: "Мир",
      callback: function (hash) {
        var pattern = RegExp("^" + this.pattern + "$");
        var params = hash.match(pattern);
        
        var params = {
          'world': params[1],
          'region': params[2]
        };
        
        for(var param in params) {
          if(/%[^%]+/.test(params[param])) params[param] = prompt("Введите значение '" + param + "'", "");
          hash = hash.replace(RegExp("%" + param + "%"), params[param]);
        };
        
        location.hash = hash;
        
        region.get(params);
      }
    },
    { name: "Варпы",         description: "Список созданных платных точек перемещения.",    url: "world/%world%/warps",   pattern: "world/([^/]+)/warps(?:/page([^/]+))?",   parent: "Мир",
      callback: function (hash) {
        var pattern = RegExp("^" + this.pattern + "$");
        var params = hash.match(pattern);
        
        var params = {
          'world': params[1],
          'offset': ((params[2] || 1) - 1) * 100
        };
        
        for(var param in params) {
          if(/%[^%]+/.test(params[param])) params[param] = prompt("Введите значение '" + param + "'", "");
        };
        
        /*$("#breadcrumb").html("<ol class='breadcrumb'>"
            + "<li><a href='#'>Главная</a></li>"
            + "<li>" + api.world("%world%", "Мир") + "</li>"
            + "<li>" + api.world(params['world']) + "</li>"
            + "<li class='active'>Варпы</li>"
          + "</ol>");*/
        
        warps_server.get(params);
      }
    },
    { name: "Варп",       description: "",  url: "world/%world%/warp/%warp%", pattern: "world/([^/]+)/warp/([^/]+)", parent: "Мир",
      callback: function (hash) {
        var pattern = RegExp("^" + this.pattern + "$");
        var params = hash.match(pattern);
        
        var params = {
          'world': params[1],
          'warp': params[2]
        };
        
        for(var param in params) {
          if(/%[^%]+/.test(params[param])) params[param] = prompt("Введите значение '" + param + "'", "");
          hash = hash.replace(RegExp("%" + param + "%"), params[param]);
        };
        
        location.hash = hash;
        
        warp.get(params);
      }
    },
    { name: "Логи блоков",   description: "Список поставленных и разрушенных блоков.",      url: "world/%world%/blocks",  pattern: "world/([^/]+)/blocks(?:/page([^/]+))?",  parent: "Мир",
      callback: function (hash) {
        var pattern = RegExp("^" + this.pattern + "$");
        var params = hash.match(pattern);
        
        var params = {
          'world': params[1],
          'offset': ((params[2] || 1) - 1) * 100
        };
        
        server_blocks.get(params);
      }
    },
    { name: "Торговля",      description: "Список всех покупок и продаж в выбранном мире.", url: "world/%world%/shops",   pattern: "world/([^/]+)/shops(?:/page([^/]+))?",   parent: "Мир",
      callback: function (hash) {
        var pattern = RegExp("^" + this.pattern + "$");
        var params = hash.match(pattern);
        
        var params = {
          'world': params[1],
          'offset': ((params[2] || 1) - 1) * 100
        };
        
        server_shops.get(params);
      }
    },
    { name: "Координаты",      description: "Поиск чего либо по координатам", url: "world/%world%/coord/%x%,%y%,%z%",   pattern: "world/([^/]+)/coord/([^/,]+),([^/,]+),([^/]+)",   type: 'static', parent: "Мир",
      callback: function (hash) {
        var pattern = RegExp("^" + this.pattern + "$");
        var params = hash.match(pattern);
        
        var params = {
          'world': params[1],
          'x': params[2],
          'y': params[3],
          'z': params[4]
        };
        
        for(var param in params) {
          if(/%[^%]+/.test(params[param])) params[param] = prompt("Введите значение '" + param + "'", "");
          hash = hash.replace(RegExp("%" + param + "%"), params[param]);
        };
        
        location.hash = hash;
        
        $("#breadcrumb").html("<ol class='breadcrumb'>"
            + "<li><a href='#'>Главная</a></li>"
            + "<li>" + api.world("%world%", "Мир") + "</li>"
            + "<li>" + api.world(params['world']) + "</li>"
            + "<li>" + api.coord("%x%,%y%,%z%", params['world'], "Координаты") + "</li>"
            + "<li class='active'>" + params['x'] + "," + params['y'] + "," + params['z'] + "</li>"
          + "</ol>");
        
          var e = webWhereEquals(urls, "parent", this.name);
          
          
          
          var regions_url = coord_regions.base_url + addParams({
            'world': params['world'],
            'x': params['x'],
            'y': params['y'],
            'z': params['z'],
            'radius': 0
          });
          
          var regions = "";
          /*if(violations.history[regions_url]) {
            info += regionParse(violations.history[regions_url].response, violations.history[regions_url].params);
          } else {*/
            $.ajax(regions_url, {
              cache: false,
              async: false,
              success: function (e) {
                if(e['regions'].length == 0) {
                  regions += api.info("Вы можете здесь строить");
                } else {
                  regions += api.warning("Вы не можете здесь строить");
                }
                
                var regions_array = "";
                for(var x = 0; x < e['regions'].length; x++) {
                  regions_array += regionParse(e['regions'][x], regions_url);
                }
                
                regions += "<div class='list-group'>" + regions_array + "</div>";
              },
              error: function (e) {
                if(e.responseJSON['error'] == "No regions found.") {
                  regions += api.info("Вы можете здесь строить");
                } else {
                  regions += api.danger(e.responseJSON['error']);
                }
              }
            });
          //}
          
          
          var actions = [];
          for(var x = 0; x < e.length; x++) {
            actions.push("<a href='#" + e[x]['url'].replace(/%world%/,params['world']).replace(/%x%/,params['x']).replace(/%y%/,params['y']).replace(/%z%/,params['z']) + "' class='list-group-item'>"
              + "<h4 class='list-group-item-heading'>" + e[x]['name'] + "</h4>"
              + "<p class='list-group-item-text'>" + e[x]['description'] + "</p>"
            + "</a>");
          }
          actions = "<div class='list-group'>" + actions.join("") + "</div>";
          
    
    var result = "<div class='row'><div class='col-md-6'>" + regions + "</div><div class='col-md-6'>" + actions + "</div></div>";
    
          
          $("#output").html(result);
        //server_shops.get({'world': params[1], 'offset': ((params[2] || 1) - 1) * 100});
      }
    },
      { name: "Логи блоков",      description: "Логи блоков на определенных координатах", url: "world/%world%/coord/%x%,%y%,%z%/blocks",   pattern: "world/([^/]+)/coord/([^/,]+),([^/,]+),([^/]+)/blocks(?:/page([^/]+))?",   parent: "Координаты",
        callback: function (hash) {
          var pattern = RegExp("^" + this.pattern + "$");
          var params = hash.match(pattern);
          
          var params = {
            'world': params[1],
            'x': params[2],
            'y': params[3],
            'z': params[4],
            'offset': ((params[5] || 1) - 1) * 100
          };
          
          /*$("#breadcrumb").html("<ol class='breadcrumb'>"
              + "<li><a href='#'>Главная</a></li>"
              + "<li>" + api.world("%world%", "Мир") + "</li>"
              + "<li>" + api.world(params['world']) + "</li>"
              + "<li>" + api.coord("%x%,%y%,%z%", params['world'], "Координаты") + "</li>"
              + "<li>" + api.coord(params['x'] + "," + params['y'] + "," + params['z'], params['world']) + "</li>"
              + "<li class='active'>Логи блоков</li>"
            + "</ol>");*/
          
          //$("#output").html("");
          
          coord_blocks.get(params);
          //server_shops.get({'world': params[1], 'offset': ((params[2] || 1) - 1) * 100});
        }
      },
      { name: "Логи магазина",      description: "Логи торговли на определенніх координатах", url: "world/%world%/coord/%x%,%y%,%z%/shops",   pattern: "world/([^/]+)/coord/([^/,]+),([^/,]+),([^/]+)/shops(?:/page([^/]+))?",   parent: "Координаты",
        callback: function (hash) {
          var pattern = RegExp("^" + this.pattern + "$");
          var params = hash.match(pattern);
          
          var params = {
            'world': params[1],
            'x': params[2],
            'y': params[3],
            'z': params[4],
            'offset': ((params[5] || 1) - 1) * 100
          };
          
          coord_shops.get(params);
        }
      },
      { name: "Ближайшие регионы",      description: "Список рядом находящихся регионов", url: "world/%world%/coord/%x%,%y%,%z%/regions",   pattern: "world/([^/]+)/coord/([^/,]+),([^/,]+),([^/]+)/regions(?:/page([^/]+))?",   parent: "Координаты",
        callback: function (hash) {
          var pattern = RegExp("^" + this.pattern + "$");
          var params = hash.match(pattern);
          
          var params = {
            'world': params[1],
            'x': params[2],
            'y': params[3],
            'z': params[4],
            'offset': ((params[5] || 1) - 1) * 100
          };
          
          coord_regions.get(params);
        }
      },
      { name: "Ближайшие варпы",      description: "Список рядом находящихся варпов", url: "world/%world%/coord/%x%,%y%,%z%/warps",   pattern: "world/([^/]+)/coord/([^/,]+),([^/,]+),([^/]+)/warps(?:/page([^/]+))?",   parent: "Координаты",
        callback: function (hash) {
          var pattern = RegExp("^" + this.pattern + "$");
          var params = hash.match(pattern);
          
          var params = {
            'world': params[1],
            'x': params[2],
            'y': params[3],
            'z': params[4],
            'offset': ((params[5] || 1) - 1) * 100
          };
          
          coord_warps.get(params);
        }
      },
  
  { name: "Игрок",           description: "Информация о игроке",                                                     url: "player/%player%",                pattern: "player/([^/]+)",                     type: 'static', parent: "Главная",
    callback: function (hash) {
      var pattern = RegExp("^" + this.pattern + "$");
      var params = hash.match(pattern);
        
      var params = {
        'player': params[1]
      };
      
      for(var param in params) {
        if(/%[^%]+/.test(params[param])) {
          params[param] = prompt("Введите значение '" + param + "'", "");
          hash = hash.replace(RegExp("%" + param + "%"), params[param]);
        }
      };
      
      location.hash = hash;
      
      $("#breadcrumb").html("<ol class='breadcrumb'>"
          + "<li><a href='#'>Главная</a></li>"
          + "<li class='active'>Игрок &mdash; " + api.player(params['player']) + "</li>"
        + "</ol>");
      
      info.get(params);
      /*$('#output').html(
        actionsBox3(
          webWhereEquals(urls, "parent", this.name),
          params[1]
        )
      );*/
    }
  },
    { name: "Наказания",     description: "Список активных банов и мутов игрока.",                                   url: "player/%player%/violations",     pattern: "player/([^/]+)/violations",          parent: "Игрок",
      callback: function (hash) {
        var pattern = RegExp("^" + this.pattern + "$");
        var params = hash.match(pattern);
        
        var params = {
          'player': params[1]
        };
        
        for(var param in params) {
          if(/%[^%]+/.test(params[param])) params[param] = prompt("Введите значение '" + param + "'", "");
        };
        
        $("#breadcrumb").html("<ol class='breadcrumb'>"
            + "<li><a href='#'>Главная</a></li>"
            + "<li>Игрок &mdash; " + api.player(params['player']) + "</li>"
            + "<li class='active'>Нарушения</li>"
          + "</ol>");
        
        violations.get(params);
      }
    },
    { name: "Регионы",       description: "Список регионов в которых игрок является жителем либо владеет регионом.", url: "player/%player%/regions",  pattern: "player/([^/]+)/regions(?:/page([^/]+))?", parent: "Игрок",
      callback: function (hash) {
        var pattern = RegExp("^" + this.pattern + "$");
        var params = hash.match(pattern);
        
        $("#breadcrumb").html("<ol class='breadcrumb'>"
            + "<li><a href='#'>Главная</a></li>"
            + "<li><a href='#player/%player%'>Игрок</a></li>"
            + "<li><a href='#player/" + params[1] + "'>" + params[1] + "</a></li>"
            + "<li class='active'>Регионы</li>"
          + "</ol>");
        
        regions.get({'player': params[1], 'offset': ((params[2] || 1) - 1) * 100});
      }
    },
    { name: "Варпы",         description: "Список платных точек перемещения созданных игроком.",                     url: "player/%player%/warps",    pattern: "player/([^/]+)/warps(?:/page([^/]+))?",   parent: "Игрок",
      callback: function (hash) {
        var pattern = RegExp("^" + this.pattern + "$");
        var params = hash.match(pattern);
        
        player_warps.get({
          'player': params[1],
          'offset': ((params[2] || 1) - 1) * 100
        });
      }
    },
    { name: "Логи блоков",   description: "Список поставленных и разрушенных игроком блоков.",                       url: "player/%player%/blocks",  pattern: "player/([^/]+)/blocks(?:/page([^/]+))?",  parent: "Игрок",
      callback: function (hash) {
        var pattern = RegExp("^" + this.pattern + "$");
        var params = hash.match(pattern);
        
        blocks.get({
          'player': params[1],
          'offset': ((params[2] || 1) - 1) * 100
        });
      }
    },
    { name: "Логи магазина", description: "Список покупок и продаж игрока через свои или чужие магазины.",           url: "player/%player%/shops",   pattern: "player/([^/]+)/shops(?:/page([^/]+))?",   parent: "Игрок",
      callback: function (hash) {
        var pattern = RegExp("^" + this.pattern + "$");
        var params = hash.match(pattern);
        
        var params = {
          'player': params[1],
          'offset': ((params[2] || 1) - 1) * 100
        }
        
        shops.get(params);
      }
    },

]

function webWhereEquals(e, key, value) { // e - array of objects, a - key name, b - value
  var result = [];
  
  for(var x = 0; x < e.length; x++) {
    if(e[x][key] == value) result.push(e[x]);
  }
  
  return result;
}

function webPattern(e, value) { // e - array of objects, a - key name, b - value
  for(var x = 0; x < e.length; x++) {
    var pattern = RegExp("^" + (e[x]['pattern'] || "") + "$");
    
    if(e[x]['url'] == value) {
      console.log("'" + e[x]['url'] + "' '" + value + "'");
      
      return e[x];
    } else if(pattern.test(value)) {
      console.log(pattern + " '" + value + "'");
      
      return e[x];
    }
  }
  
  return false;
}

function actionsBox2(e) { // {'action':"player/web93onv/regions",'title':"Регионы",'description':"Список регионов в которых игрок является жителем либо владеет регионом."}
  var result = [];
  
  for(var x = 0; x < e.length; x++) {
    result.push("<a href='#" + e[x]['url'] + "' class='list-group-item'>"
      + "<h4 class='list-group-item-heading'>" + e[x]['name'] + "</h4>"
      + "<p class='list-group-item-text'>" + e[x]['description'] + "</p>"
    + "</a>");
  }
  
  result = "<div class='list-group'>" + result.join("") + "</div>";
  
  return result;
}

function actionsBox3(e, player) {
  var result = [];
  
  for(var x = 0; x < e.length; x++) {
    result.push("<a href='#" + e[x]['url'].replace(/%player%/,player) + "' class='list-group-item'>"
      + "<h4 class='list-group-item-heading'>" + e[x]['name'] + "</h4>"
      + "<p class='list-group-item-text'>" + e[x]['description'] + "</p>"
    + "</a>");
  }
  
  result = "<div class='list-group'>" + result.join("") + "</div>";
  
  return result;
}

function magic() {
  var hash = document.location.hash;
  hash = hash.substr(1, hash.length);
  
  var view = webPattern(urls, hash);
  
  if(view == false) {
    document.title = "Ошибка";
    $("#breadcrumb").html("<ol class='breadcrumb'><li><a href='#'>Главная</a></li></ol>");
    $("#output").html(api.danger("Отсутствует контроллер для " + hash));
    
    return;
  }
  
  document.title = view['name'];
  
  $("#breadcrumb").html(""
    + "<ol class='breadcrumb'>"
    + (view['parent'] ? "<li><a href='#" + webWhereEquals(urls, "name", view['parent'])[0]['url'] + "'>" + view['parent'] + "</a></li>" : "")
    + "<li class='active'>" + view['name'] + "</li>"
    + "</ol>"
  );
  
  if(view['type'] == "static") {
    $("#output").html(
      actionsBox2(
        webWhereEquals(urls, "parent", view['name'])
      )
    );
  }
  
  if(typeof view['callback'] == "function") view['callback'](hash);
}

window.addEventListener("hashchange", magic);

magic();

document.addEventListener("keydown", function(e) {
  if(e.which == 37) { // left
    if(e.ctrlKey) {
      var el = document.querySelector('a.page.prev');
      if(el) el.click();
    }
  } else if(e.which == 39) { // right
    if(e.ctrlKey) {
      var el = document.querySelector('a.page.next');
      if(el) el.click();
    }
  }
});
</script>

<!--<style>
body:hover .player, body:hover .coord, body:hover .warp, body:hover .world, body:hover .region {
  outline: 1px dashed red;
}
</style>-->
</body>
</html>