// Site open popup message
function createNotif () {	
	var cov = document.createElement("div");
	cov.id = "cov"
	cov.style["top"] = "0";
	cov.style["width"] = "100%";
	cov.style["height"] = "100%";
	cov.style["background-color"] = "#000";
	cov.style["background"] = "rgba(20,20,20,0.54)";
	cov.style["position"] = "absolute";
	cov.style["z-index"] = "2";
	document.body.appendChild(cov)
	

	var d = document.createElement("div");
	d.id = "notif";
	d.style.width = "50%";
	d.style.position = "absolute";
	d.style.top = "25%";
	d.style.left = "25%";
	d.style.color = "#ffffff";
	d.style.background = "#000000";
	d.style.padding = "10px";
	d.style["border-radius"] = "10px";
	d.style.width = "50%;"
	d.style["z-index"] = 5;
	d.innerHTML = "Thank you for choosing to use Bolwl's Growtopia \
World Planner! This is a official fork of the project endorsed by the author \
which includes all the new items and several bugfixes. Help spread the word by \
bumping the thread on Growtopia forums.<br><br>\
<div style='border: 3px solid #fff;\
display:inline-block;\
padding:3px;\
cursor:pointer;\
border-radius: 10px;' \
onclick='neverAsk()'>Never ask me again</div>\
<div style='border: 3px solid #fff;\
display:inline-block;\
padding: 3px;\
margin-left:10px;\
cursor: pointer;\
border-radius: 10px;' \
onclick='discardNotif()'>Later</div>";
	
	cov.appendChild(d);
}

function discardNotif () {
	document.getElementById("cov").remove();
}

function createChangeLog () {
	var clog = document.createElement("div");
	clog.id = "clog"
	clog.style["top"] = "0";
	clog.style["width"] = "100%";
	clog.style["height"] = "100%";
	clog.style["background-color"] = "#000";
	clog.style["background"] = "rgba(20,20,20,0.54)";
	clog.style["position"] = "absolute";
	clog.style["z-index"] = "2";
	document.body.appendChild(clog)
	
	var c = document.createElement("div");
	c.id = "notif_clog";
	c.style.width = "50%";
	c.style.position = "absolute";
	c.style.top = "20%";
	c.style.bottom = "20%";
	c.style.left = "25%";
	c.style.color = "#ffffff";
	c.style.background = "#000000";
	c.style.padding = "10px";
	c.style["border-radius"] = "10px";
	c.style.width = "50%;"
	c.style["z-index"] = 5;
	c.style["overflow-y"] = "scroll";
	inviteLink = "<a target=\"_blank\" rel=\"noopener noreferrer\" href=\"https://discord.gg/ShtEEHFuPf\">https://discord.gg/ShtEEHFuPf</a>";
	c.innerHTML = "<h2>Changelog</h2>\
<h3>22.04.2021</h3>\
- Updated item data to Grow4Good.<br>\
<br>\
<h3>04.04.2021</h3>\
- Updated item data to Easter 2021.<br>\
<br>\
<h3>11.03.2021</h3>\
- Updated item data to Blarney 2021.<br>\
<br>\
<h3>13.02.2021</h3>\
- Updated item data to Valentines 2021.<br>\
<br>\
<h3>10.09.2020</h3>\
- HOTFIX: Fixed planner breaking on chromium based browsers.(Chrome, Edge, etc.)<br>\
- Fixed regression of horizontal slider bar disappearing when resizing inventory.<br>\
- Fixed world name \"set\" button location.<br>\
- Fixed file name for when world is rendered.<br>\
<br>\
<h3>22.08.2020</h3>\
- Updated item data.<br>\
I created discord server for bug reports and stuff so you can join if you want to here: " + inviteLink + "<br>\
<br>\
<h3>16.08.2020</h3>\
- HOTFIX: Fixed commas in item names causing world to become corrupted (e.g. Up, Up and Away Block).<br>\
If your world got corrupted when saving with one please join https://discord.gg/ShtEEHFuPf and message me at @bolwl.gt or just ping me.<br>\
<br>\
- Fixed world renderer wrong resolution.<br>\
<h3>02.06.2020</h3>\
- Updated item data.<br>\
- Added Hospital blast, Bontiful blast, Meteor shower, Stargazing, and both of new Startopia 2.0 weathers.<br>\
- Added some more red borders for restricted items.<br>\
<br>\
<h3>30.05.2020</h3>\
- Updated item data.<br>\
- Fixed some broken textures like copper plumbing and steam engine.<br>\
- Unobtainable items now have a red border in item picker.<br>\
- Removed some restricted items which weren't useful anyways.<br>\
Planner will remove the deleted items if you had any in the save(e.g. roots and guild flags).<br>\
- Added ability to change world name when saving.<br>\
- Added BLARNEY worlds in templates(except for 9th).<br>\
- Added old START (Jenuine's one) to templates.<br>\
<br><br>\
<div style='border: 3px solid #fff;\
display:inline-block;\
padding: 3px;\
margin-left:10px;\
cursor: pointer;\
border-radius: 10px;\
'onclick='discardChangeLog()'>Dismiss</div><br><br>";

	clog.appendChild(c);
}

function discardChangeLog () {
	document.getElementById("clog").remove();
	localStorage.lastDate = lastDate;
}

function neverAsk () {
	localStorage.neverAsk = true;
	document.getElementById("cov").remove();
	var li = document.createElement("li");
	li.textContent = "Re-enable Daily Notification";
	li.addEventListener("click", function (e) {
		e.srcElement.remove();
		localStorage.neverAsk = false;
		delete localStorage.neverAsk;
	});
	document.getElementById("appDrawer")
			.children[0]
			.insertBefore(li, document.getElementById("appDrawer")
			.children[0].children[0]);
}
