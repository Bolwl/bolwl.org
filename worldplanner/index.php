
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">

<html>
<!---------------------------- HEADER ---------------------------------->
  <head>
    <title>Bolwl's World Planner</title>
    <script type="text/javascript" src="./data.json"></script>
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
   
	<meta name="viewport" content="width=device-width, user-scalable=no, maximum-scale=1.0, target-densitydpi=device-dpi, initial-scale=1.0">
    <meta name="description" content="Web-based world planner for growtopiagame.com."/>
    <meta property="og:title" content="Bolwl's World Planner"/>
    <meta property="og:description" content="Web-based world planner for growtopiagame.com"/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="https://www.bolwl.ml/worldplanner/"/>
    <meta property="og:image" content="https://www.bolwl.ml/images/bolwlnobg.png"/>
    <meta property="og:image:width" content="96"/>
    <meta property="og:image:height" content="96"/>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
	<script src="./reflect.js"></script>
	
	<script>var lastDate = "22.04.2021";</script>
	<script>var maxLd = 66;</script>
	
<!--------------------------- POLYFILL --------------------------------->
	<script>
		/* Polyfill for older browsers */
const reduce = Function.bind.call(Function.call, Array.prototype.reduce);
const isEnumerable = Function.bind.call(Function.call, Object.prototype.propertyIsEnumerable);
const concat = Function.bind.call(Function.call, Array.prototype.concat);
const keys = Reflect.ownKeys;

if (!Object.values) {
	Object.values = function values(O) {
		return reduce(keys(O), (v, k) => concat(v, typeof k === 'string' && isEnumerable(O, k) ? [O[k]] : []), []);
	};
}

if (!Object.entries) {
	Object.entries = function entries(O) {
		return reduce(keys(O), (e, k) => concat(e, typeof k === 'string' && isEnumerable(O, k) ? [[k, O[k]]] : []), []);
	};
}
	</script>
	
<!--------------------------- PROMISES --------------------------------->
	<script src="https://cdn.jsdelivr.net/npm/es6-promise@4/dist/es6-promise.auto.min.js"></script> 

  </head>

<!----------------------------- BODY ----------------------------------->
  <body style="margin:0;padding:0;">
    <div id="menu" style="color:white;">
      <img style="margin:8px;cursor:pointer;" onclick="openApp()" src="./assets/planner/menu.png">
      <h2>Cernodile's World Planner <small><small>hosted on okass.net by healLV</small></small></h2>
	  <center><div id="label_currentWorld">Current world: </div></center>
    </div>

<!----------------------- PLANNER FUNCTIONALITY ------------------------>
	<script>
	window.addEventListener("beforeunload", function (e) {
		var c = "It looks like you're attempting to leave the page. "
		+ 'If you leave before saving, your changes will be lost.';

		(e || window.event).returnValue = c;
		return c;
	});
    var appDrawer = false;
    var inventory = true;
    var cameraOnly = false;
    var loaded = false;
    var usingRect = false;
	var invHeight = 25;
    var rectProgress = {};
	var zoom = 1;
    function openApp () {
      if (!loaded) return;
      if (appDrawer) {
        appDrawer = false;
        document.getElementById("appDrawer").style.visibility="hidden";
        document.getElementById("cover").style.visibility="hidden";
      } else {
        appDrawer = true;
        document.getElementById("appDrawer").style.visibility="visible";
        document.getElementById("cover").style.visibility="visible";
      }
    }
	function heightLogic () {
		// 5 States, none, 5%, 10%, 25% and 50%
		switch (invHeight) {
			case 0:
				invHeight = 5;
				break;
			case 5:
				invHeight = 10;
				break;
			case 10:
				invHeight = 25;
				break;
			case 25:
				invHeight = 50;
				break;
			case 50:
				invHeight = 0;
				break;
		}
		document.getElementById("hLogic").textContent = "Toggle backpack space: " + invHeight + "%";
		changeHeight(invHeight)
	}
	function changeHeight (vh) {
		document.getElementById("container").style.height = "calc(" + (100 - vh) + "vh - 52px - 21.5px)";
		document.getElementById("inventory").style.height = vh + "vh";
	}
    function preset () {
      var select = document.getElementById("preset");
      var link = select.value;
      var xhr = new XMLHttpRequest();
      xhr.open("GET", "./presets/" + link, true);
      xhr.onload = function (e) {
        if (xhr.status === 200) {
          loadSave(xhr.responseText);
		  setWorldName(select.value.split(".")[0])
        }
      }
      xhr.send();
    }
    function changeCam (el) {
      if (cameraOnly) {
        cameraOnly = false;
        el.textContent = "Camera Mode: Build";
      } else {
        cameraOnly = true;
        el.textContent = "Camera Mode: Move";
      }
    }
    var grid = false;
    function toggleGrid () {
      if (grid) {
        document.getElementById("grid").style.visibility = "hidden";
        grid = false;
      } else {
        document.getElementById("grid").style.visibility = "visible";
        grid = true;
      }
    }

    function rectangleToggle () {
      var el = document.getElementById("rect");
      if (usingRect) {
        var ddd = document.getElementById("planner").getContext("2d");
        ddd.clearRect(rectProgress["start"].x*boxS,rectProgress["start"].y*boxS,32,32);
        usingRect = false;
        el.style.color= "#ffffff";
        el.textContent = "Create Rectangle";
      } else {
        usingRect = true;
        rectProgress = {};
        el.style.color = "#929292";
        el.textContent = "Cancel Rectangle";
      }
    }
    </script>

	<div id="cover" onclick="openApp()" style="width: 100%;height:100%;background-color: #000;background: rgba(20, 20, 20, 0.54);position: absolute;z-index:2;visibility:hidden;"></div>

<!-------------------------- SIDE PANEL -------------------------------->
    <div id="appDrawer" style="visibility:hidden;">
      <ul style="list-style-type: none;overflow-y: visible;">
        <li onclick="countBlocks()">Count Blocks</li>
        <label>Change Weather <select id="weatherSelect" style="width: 70%;" onchange="changeWeather()">
          <option value="sunny">Sunny</option>
          <option value="beach">Beach Blast</option>
		  <option value="night">Night</option>
          <option value="arid">Arid</option>
		  <!--  04  -->
		  <option value="rainy">Rainy</option>
          <option value="harvest">Harvest Blast</option>
          <option value="mars">Mars Blast</option>
          <option value="spooky">Spooky</option>
          <!--  09  -->
		  <option value="nothing">Nothingness</option>
          <option value="snowy">Snowy</option>
          <!--  12  -->
		  <!--  13  -->
		  <option value="undersea">Undersea Blast</option>
          <option value="warp">Warp</option>
          <!--  16  -->
		  <option value="comet">Comet</option>
          <option value="party">Party</option>
		  <option value="19_pineapple">Pineapples</option>
          <option value="snowy_night">Snowy Night</option>
          <option value="spring">Spring</option>
          <option value="howling_sky">Howling Sky</option>
          <!--  23  -->
		  <!--  24  -->
		  <!--  25  -->
		  <!--  26  -->
		  <!--  27  -->
		  <!--  28  -->
		  <!--
		  <option value="stuff_cobweb">Stuff - Cobweb</option>
		  -->
          <option value="pagoda">Pagoda</option>
          <option value="31_apocalypse">Apocalypse</option>
          <option value="jungle">Jungle</option>
          <option value="balloon">Balloon Warz</option>
		  <!--
		  <option value="34_background">Background</option>
		  -->
		  <option value="35_autumn">Autumn</option>
		  <option value="36_love">Valentine's</option>
		  <option value="37_st_patricks">St. Paddy's Day</option>
		  <option value="38_epoch_iceberg">Epoch Machine - Iceberg</option>
		  <option value="39_epoch_lava">Epoch Machine - Lava</option>
		  <option value="40_epoch_skylands">Epoch Machine - Skylands</option>
		  <!-- 41 -->
		  <option value="42_hacker">Digital Rain</option>
		  <option value="43_monochrome">Monochromatic Blast</option>
		  <option value="44_treasure">Treasure Blast</option>
		  <option value="45_hospital">Hospital Blast</option>
		  <option value="46_bontiful">Bountiful Blast</option>
		  <option value="47_shooting_stars">Meteor shower</option>
		  <option value="48_stargaze">Stargazing</option>
		  <option value="49_landed_ship">Successful Lactus landing</option>
		  <option value="50_failed_landing">Failed Lactus landing</option>
        </select></label>
        <li onclick="toggleGrid()">Toggle Grid</li>
        <li onclick="changeCam(this)">Camera Mode: Build</li>
        <li onclick="render()">Render</li>
        <li id="zIn" onclick="zoomIn()">Zoom In (+25%)</li>
        <li id="zOut" onclick="zoomOut()">Zoom Out (-25%)</li>
        <li id="zoom" style="color: #929292;cursor:default;">Current zoom: 100%</li>
		<li id="hLogic" onclick="heightLogic()">Toggle backpack space: 25%</li>
        <li onclick="rectangleToggle()" id="rect">Create Rectangle</li>
		<label>World name:</label>
		<label>
			<input type="text" id="worldName" style="float:none;">
			<div style="float: right;">
			<button id="btn_setWorldName" onclick="setWorldName()">set</button>
			</div>
		</label>
		<li onclick="save()">Save</li>
        <li onclick="clickFile()">Load <span style="color:red;float:none;">(Big worlds take long to load)</span></li>
        <label>Select preset:</label>
		<label>
			<select id="preset" style="width: 75%;float:none;" onchange="preset()">
          <optgroup label="Miscellanious">
            <option value="EMPTYWORLDNOGRID.gtworld">Empty World [Default]</option>
            <option value="EMPTYWORLD.gtworld">Empty World with numbers</option>
            <option value="DIRTWORLD.gtworld">Default dirt world</option>
            <option value="UGLYCOMP.gtworld">topazi's ugly world competition winner - ChuckinDuckins</option>
          </optgroup>
          <optgroup label="World Replicas">
            <option value="PARKOUR.gtworld">PARKOUR by Artemis</option>
            <option value="BRETZRAEI.gtworld">BRETZRAEI by BretZraei</option>
            <option value="MONEYCHANGER.gtworld">MONEYCHANGER by MoneyChanger</option>
          </optgroup>
          <optgroup label="Event Worlds">
			<optgroup label="Blarney (missing 9)"></optgroup>
			  <option value="BLARNEY1.gtworld">BLARNEY1 by @Hamumu</option>
			  <option value="BLARNEY2.gtworld">BLARNEY2 by @Hamumu</option>
			  <option value="BLARNEY3.gtworld">BLARNEY3 by @Hamumu</option>
			  <option value="BLARNEY4.gtworld">BLARNEY4 by @Hamumu</option>
			  <option value="BLARNEY5.gtworld">BLARNEY5 by @Hamumu</option>
			  <option value="BLARNEY6.gtworld">BLARNEY6 by @Hamumu</option>
			  <option value="BLARNEY7.gtworld">BLARNEY7 by @Hamumu</option>
			  <option value="BLARNEY8.gtworld">BLARNEY8 by @Hamumu</option>
          </optgroup>
		  <optgroup label="Technical worlds">
		    <option value="START_OLD.gtworld">Old START by @Jenuine</option>
		  </optgroup>
        </select><br><span style="color:red;float:none;">(Slower devices may take a few minutes to process, don't freak out)</span></label>
        <input type="file" id="fileinput" accept=".gtworld" style="width:0;height:0;position:absolute;visibility:hidden;">
		<li onclick="createChangeLog()">Show changelog</li>
		<label style="height:64px"> </label>
		<label style="font-size:12px;">
			<div>Image material used belongs to Growtopia and Ubisoft.</div>
			<div>Originally developed by Joann M&otilde;ndresku (@cernodile).</div>
			<div>Maintained by healLV (Discord: healLV#8021)</div>
			<div>Discord server: 
				<div style="float: right;"><a target="_blank" rel="noopener noreferrer external" 
				href="https://discord.gg/Av6yg28">https://discord.gg/Av6yg28</a></div>
			</div>
		</label>
	  </ul>
<!--      
	  <span id="version" style="font-size:12px;bottom:4em;left:1%;
								display:block;color:#fff;position:absolute;
								">
				Build ID 40421-005658<br>
				Image material used belongs to Growtopia and Ubisoft.<br>
				Originally developed by Joann M&otilde;ndresku (@cernodile).<br>
				Maintained by healLV (Discord: healLV#8021)<br>
				Discord server: <a target="_blank" rel="noopener noreferrer external" href="https://discord.gg/Av6yg28">https://discord.gg/Av6yg28</a>
	  </span>
-->
    </div>

<!----------------------- COMPABILITY CHECK ---------------------------->
    <div id="container" style="position: relative;height: calc(75vh - 52px - 21.5px);width: 100%;overflow: scroll;margin:0 auto;background:#001414;">
      <canvas id="paintSheet" width="0" height="0" style="visibility:hidden;">.</canvas>
      <canvas id="background" width="3200" height="1920">Sorry, this world planner is not supported in your browser.</canvas>
      <canvas id="foreground" width="3200" height="1920">Sorry</canvas>
      <canvas id="grid" width="3200" height="1920" style="visibility:hidden;">Sorry</canvas>
      <canvas id="water" width="3200" height="1920">Sorry</canvas>
      <canvas id="planner" width="3200" height="1920">Sorry</canvas>
      <canvas id="render" width="1920" height="1080" style="visibility:hidden;">Sorry</canvas>
    </div>
    <div id="loaded" style="position:absolute;background:#4cad6b;bottom:calc(60% + 5px);left:calc(30% + 5px);width:0%;height:1.25em;display:block;"></div>
    <div id="bar" style="bottom:60%;left:30%;display:block;color:white;position:absolute;pointer-events:none;padding:3px;width:37%;height:1.25em;text-align:center;"><span id="load">Loading Planner - Please wait!</span></div>
    
<!---------------------------- SEARCH ----------------------------------->
	<script>
      function loadedAsset (e) {
        setTimeout(() => {
          loadedAsset(e);
        }, 10);
      }
      function arrToLowerCase (a) {
        for (var itr = 0; itr < a.length; itr++) {
          a[itr] = a[itr].toLowerCase();
        }
        return a;
      }
      function query () {
        if (!loaded) return;
        var queryData = arrToLowerCase(Object.keys(data));
        var results = [];
        var priority = [];
        var value = document.getElementById("plannerSearch").value;
	    var filtered = queryData.filter(function(a) { if (a.search(value.toLowerCase()) > -1) { return true; } else return false;});
		for (var d in queryData) {
          if (filtered.indexOf(queryData[d]) > -1 || data[Object.keys(data)[d]].priority) {
            if (data[Object.keys(data)[d]].priority) priority.push(Object.keys(data)[d]);
            results.push(Object.keys(data)[d]);
          }
        }
        if ((results.length - priority.length) > 0) {
          document.getElementById("inventory").innerHTML = '<form action="javascript:query()"><label>Search: <input id="plannerSearch" type="search" placeholder="Item"></label><input type="submit" value="Search"></form>';
          document.getElementById("plannerSearch").value = value;
          for (var k in results) {
            var slot = document.createElement("div");
            slot.id = "slot";
            slot.dataset.name = results[k];
			slot.style = "border-color: " + catColor(results[k]) + ";";
            var pos;
            switch(data[results[k]].spread) {
			  case 3:
			  case 7:
			  case 9:
				pos = 'margin: ' + ((data[results[k]].y) * 32 * (-1)) + 'px ' + ((data[results[k]].x+3) * 32 + 32 * (-1)) + 'px'
						+ ' ' + ((data[results[k]].y) * 32 + 32 * (-1)) + 'px ' + ((data[results[k]].x+3) * 32 * (-1)) + 'px;'
				break;
			  case 6:
			  case 8:
			  case 1:
				pos = 'margin: ' + ((data[results[k]].y) * 32 * (-1)) + 'px ' + ((data[results[k]].x) * 32 + 32 * (-1)) + 'px'
						+ ' ' + ((data[results[k]].y) * 32 + 32 * (-1)) + 'px ' + ((data[results[k]].x) * 32 * (-1)) + 'px;'
				break;
			  case 4:
				pos = 'margin: ' + ((data[results[k]].y) * 32 * (-1)) + 'px ' + ((data[results[k]].x+4) * 32 + 32 * (-1)) + 'px'
						+ ' ' + ((data[results[k]].y) * 32 + 32 * (-1)) + 'px ' + ((data[results[k]].x+4) * 32 * (-1)) + 'px;'
				break;
			  default:
				pos = 'margin: ' + ((data[results[k]].y+1) * 32 * (-1)) + 'px ' + ((data[results[k]].x+4) * 32 + 32 * (-1)) + 'px'
						+ ' ' + ((data[results[k]].y+1) * 32 + 32 * (-1)) + 'px ' + ((data[results[k]].x+4) * 32 * (-1)) + 'px;'
				break;
			}
			
			//console.log(k, data[k].x, data[k].y, pos)
			slot.innerHTML = 
				'<span class="tip">' + results[k] + '</span>'
				+'<span onclick="javascript:switchBlock(\''
				+ results[k].replace(new RegExp("'", "g"), "\\'") + '\');"'
				+ 'style="width: 32px; height: 32px; overflow: hidden;">'
				
				+ '<img preload id="' + k + '" src="assets/' + data[results[k]].file 
						+ '" style="' + pos + '">'
				
				+ '</span>';
			document.getElementById("inventory").appendChild(slot);
          }
        }
      }
    </script>
    <div id="inventory" style="background:#3D6773;display: block;text-align:center;height:25vh;width:100%;overflow-y:scroll;margin:0 auto;">
      <form action="javascript:query()">
        <label>Search: <input id="plannerSearch" type="search" placeholder="Item"></label><input type="submit" value="Search">
      </form>
      <span id="invNotice" style="color: #fff;display: block;">Your inventory will appear once assets have been loaded...</span>
    </div>

<!---------------------------- ASSETS ----------------------------------->
	<img src="assets/weathers/sunny.png" id="sunny" class="load" onload="loadedAsset(this)">
	<img src="assets/weathers/beach.png" id="beach" class="load" onload="loadedAsset(this)">
	<img src="assets/weathers/night.png" id="night" class="load" onload="loadedAsset(this)">
    <img src="assets/weathers/arid.png" id="arid" class="load" onload="loadedAsset(this)">
    <!--  04  -->
	<img src="assets/weathers/rainy.png" id="rainy" class="load" onload="loadedAsset(this)">
    <img src="assets/weathers/harvest.png" id="harvest" class="load" onload="loadedAsset(this)">
    <img src="assets/weathers/mars.png" id="mars" class="load" onload="loadedAsset(this)">
	<img src="assets/weathers/spooky.png" id="spooky" class="load" onload="loadedAsset(this)">
    <!--  09  -->
	<img src="assets/weathers/nothing.png" id="nothing" class="load" onload="loadedAsset(this)">
	<img src="assets/weathers/snowy.png" id="snowy" class="load" onload="loadedAsset(this)">
    <!--  12  -->
	<!--  13  -->
	<img src="assets/weathers/undersea.png" id="undersea" class="load" onload="loadedAsset(this)">
    <img src="assets/weathers/warp.png" id="warp" class="load" onload="loadedAsset(this)">
    <!--  16  -->
	<img src="assets/weathers/comet.png" id="comet" class="load" onload="loadedAsset(this)">
    <img src="assets/weathers/party.png" id="party" class="load" onload="loadedAsset(this)">
    <img src="assets/weathers/19_pineapple.png" id="19_pineapple" class="load" onload="loadedAsset(this)">
	<img src="assets/weathers/snowy_night.png" id="snowy_night" class="load" onload="loadedAsset(this)">
    <img src="assets/weathers/spring.png" id="spring" class="load" onload="loadedAsset(this)">
    <img src="assets/weathers/howling_sky.png" id="howling_sky" class="load" onload="loadedAsset(this)">
    <!--  23  -->
	<!--  24  -->
	<!--  25  -->
	<!--  26  -->
	<!--  27  -->
	<!--  28  -->
	<!--
	<img src="assets/weathers/stuff_cobweb.png" id="stuff_cobweb" class="load" onload="loadedAsset(this)">
	-->
	<img src="assets/weathers/pagoda.png" id="pagoda" class="load" onload="loadedAsset(this)">
    <img src="assets/weathers/31_apocalypse.png" id="31_apocalypse" class="load" onload="loadedAsset(this)">
	<img src="assets/weathers/jungle.png" id="jungle" class="load" onload="loadedAsset(this)">
    <img src="assets/weathers/balloon.png" id="balloon" class="load" onload="loadedAsset(this)">
    <!--
	<img src="assets/weathers/34_background.png" id="34_background" class="load" onload="loadedAsset(this)">
	-->
    <img src="assets/weathers/35_autumn.png" id="35_autumn" class="load" onload="loadedAsset(this)">
    <img src="assets/weathers/36_love.png" id="36_love" class="load" onload="loadedAsset(this)">
    <img src="assets/weathers/37_st_patricks.png" id="37_st_patricks" class="load" onload="loadedAsset(this)">
    <img src="assets/weathers/38_epoch_iceberg.png" id="38_epoch_iceberg" class="load" onload="loadedAsset(this)">
    <img src="assets/weathers/39_epoch_lava.png" id="39_epoch_lava" class="load" onload="loadedAsset(this)">
    <img src="assets/weathers/40_epoch_skylands.png" id="40_epoch_skylands" class="load" onload="loadedAsset(this)">
    <!-- 41 -->
	<img src="assets/weathers/42_hacker.png" id="42_hacker" class="load" onload="loadedAsset(this)">
    <img src="assets/weathers/43_monochrome.png" id="43_monochrome" class="load" onload="loadedAsset(this)">
    <img src="assets/weathers/44_treasure.png" id="44_treasure" class="load" onload="loadedAsset(this)">
	<img src="assets/weathers/45_hospital.png" id="45_hospital" class="load" onload="loadedAsset(this)">
	<img src="assets/weathers/46_bontiful.png" id="46_bontiful" class="load" onload="loadedAsset(this)">
	<img src="assets/weathers/47_shooting_stars.png" id="47_shooting_stars" class="load" onload="loadedAsset(this)">
	<img src="assets/weathers/48_stargaze.png" id="48_stargaze" class="load" onload="loadedAsset(this)">
	<img src="assets/weathers/49_landed_ship.png" id="49_landed_ship" class="load" onload="loadedAsset(this)">
	<img src="assets/weathers/50_failed_landing.png" id="50_failed_landing" class="load" onload="loadedAsset(this)">
	
	<img src="assets/paint.png" id="paint.png" class="load" onload="loadedAsset(this)">
    <img src="assets/pipes.png" id="pipes.png" class="load" onload="loadedAsset(this)">
    <img src="assets/amorkolg.png" id="amorkolg.png"class="load" onload="loadedAsset(this)">
    <img src="assets/bunting.png" id="bunting.png"class="load" onload="loadedAsset(this)">
    <img src="assets/vilpix.png" id="vilpix.png"class="load" onload="loadedAsset(this)">
    <img src="assets/water.png" id="water.png"class="load" onload="loadedAsset(this)">
    <img src="assets/fire.png" id="fire.png"class="load" onload="loadedAsset(this)">
    <img src="assets/crystals.png" id="crystals.png"class="load" onload="loadedAsset(this)">
    <img src="assets/guild_lock.png" id="guild_lock.png" class="load" onload="loadedAsset(this)">
   
	<img src="assets/tiles_page1.png" id="tiles_page1.png" class="load" onload="loadedAsset(this)">
    <img src="assets/tiles_page2.png" id="tiles_page2.png" class="load" onload="loadedAsset(this)">
    <img src="assets/tiles_page3.png" id="tiles_page3.png" class="load" onload="loadedAsset(this)">
    <img src="assets/tiles_page4.png" id="tiles_page4.png" class="load" onload="loadedAsset(this)">
    <img src="assets/tiles_page5.png" id="tiles_page5.png" class="load" onload="loadedAsset(this)">
    <img src="assets/tiles_page6.png" id="tiles_page6.png" class="load" onload="loadedAsset(this)">
    <img src="assets/tiles_page7.png" id="tiles_page7.png" class="load" onload="loadedAsset(this)">
    <img src="assets/tiles_page8.png" id="tiles_page8.png" class="load" onload="loadedAsset(this)">
    <img src="assets/tiles_page9.png" id="tiles_page9.png" class="load" onload="loadedAsset(this)">
	<img src="assets/tiles_page10.png" id="tiles_page10.png" class="load" onload="loadedAsset(this)">
	<img src="assets/tiles_page11.png" id="tiles_page11.png" class="load" onload="loadedAsset(this)">
	<img src="assets/tiles_page12.png" id="tiles_page12.png" class="load" onload="loadedAsset(this)">
	<img src="assets/tiles_page13.png" id="tiles_page13.png" class="load" onload="loadedAsset(this)">
	<img src="assets/tiles_page14.png" id="tiles_page14.png" class="load" onload="loadedAsset(this)">
	<img src="assets/tiles_page15.png" id="tiles_page15.png" class="load" onload="loadedAsset(this)">
	<img src="assets/stopia_page1.png" id="stopia_page1.png" class="load" onload="loadedAsset(this)">
	<img src="assets/gd_page1.png" id="gd_page1.png" class="load" onload="loadedAsset(this)">
	<img src="assets/gd_page2.png" id="gd_page2.png" class="load" onload="loadedAsset(this)">
	<img src="assets/gd_page3.png" id="gd_page3.png" class="load" onload="loadedAsset(this)">
	<img src="assets/tiles_gch.png" id="tiles_gch.png" class="load" onload="loadedAsset(this)">
	
<!---------------------------- PLANNER ---------------------------------->
	<script type="text/javascript" src="notifs.js"></script>
	<script type="text/javascript" src="planner.js"></script>
	
	</body>
</html>
