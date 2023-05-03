/**
 * Created by root on 3/18/15.
 */

function populate_content_conceptmap(winId, win_main) {
    //var tabs = {tabId: ['concept_list', 'concept_map_search', 'concept_map_create'],
    //    tabName: ['My Concept List', 'Search Wiki Concept Map', 'Create Concept Map']};
    //var buttons = {buttonId: ['btn_dlg_create_proj'], buttonName: ['Create Project']};
    var tabs = {tabId: ['concept_map_search', 'concept_map_create'],
        tabName: ['Search Wiki Concept Map', 'Create Concept Map']};


    create_tabs(winId, win_main, tabs, null);

    //var table = $(document.createElement('table')).appendTo($('#concept_list'));
    //table.addClass("data").attr("id", "tbl_concept_list").append('<thead><tr>' +
    //'<th class="shrink">&nbsp;</th>' + '' +
    //'<th>Concept</th>' +
    //'<th>Description</th>' +
    //'<th>Key Terms</th>' +
    //'<th>Filter Out</th>' +
    //'<th>Actions</th>' +
    //    //'<th>Notes</th>' +
    //'</tr></thead>');
    //var tbody = $(document.createElement('tbody')).appendTo(table);

    var table_map_search = $(document.createElement('table')).appendTo($('#concept_map_search'));
    table_map_search.attr("class", "data").css('width', '100%').append('<thead><tr>' +
    '<th><select id="wiki_select">' +
    '<option value="en.wikipedia.org">en.wikipedia.org</option>' +
    '<option value="zh.wikipedia.org">zh.wikipedia.org</option>' + '</select></th>' +
    '<th>Topic:&nbsp;<input id="concept_topic" type="text"></th>' +
    '<th>Filter Out:&nbsp;<input id="filter_out" type="text"></th>' +
    '<th><button id="btn_mindmap_search">Search</button>' +
    '<button onclick="save_Wiki_ConceptMap()">Save</button></th>' +
    '</tr></thead>');

    var flash_div = $(document.createElement('div')).appendTo($('#concept_map_search'));
    flash_div.attr('id', 'flashcontent').css('position', 'relative')
        .css('width', '100%').css('height', '100%').css('border', '1px solid lightgray');

    //load_concept_map_search();

    //var table_map_create = $(document.createElement('table')).appendTo($('#concept_map_create'));
    //table_map_create.attr("class", "data").css('width', '100%').append('<thead><tr>' +
    //'<th><button onclick="createNew(); return false;">New</button>' +
    //'<button onclick="loadFreeMind(\'test.mm\'); return false;">FreeMind (test.mm)</button>' +
    //'<button onclick="loadJSON(\'flare.json\'); return false;">Flare (flare.json)</button>' +
    //'<button onclick="loadJSON(\'data.json\'); return false;">JSON (test.json)</button><br />' +
    //'<button onclick="addNodes(\'right\'); return false;">Add nodes right</button>' +
    //'<button onclick="addNodes(\'left\'); return false;">Add nodes left</button>' +
    //'<button onclick="moveNodes(\'right\', \'left\'); return false;">Move right to left</button>' +
    //'<button onclick="moveNodes(\'left\', \'right\'); return false;">Move left to right</button>' +
    //'<button onclick="setConnector(\'diagonal\'); return false;">Diagonal connector</button>' +
    //'<button onclick="setConnector(\'elbow\'); return false;">Elbow connector</button><br />' +
    //'<span>[Enter] Change name, [Ins] Add new node, [Del] Delete selected node</span><br />' +
    //'<span>[Left] Move select left, [Right] Move selection right, [Up] Move selection up, [Down] Move selection down</span>' +
    //'</th></tr></thead>');

    var d3_div = $(document.createElement('div')).appendTo($('#concept_map_create'));
    d3_div.attr('id', 'create_map_div').css('position', 'relative')
        .css('width', '100%').css('height', '100%').css('border', '1px solid lightgray');

    $('<div><h1>Coming Soon !!!</h1></div>').appendTo(d3_div);
    //load_concept_map_create();
}

function load_concept_map_search() {
    setTimeout(function () {
        runFlash($('#wiki_select').val(), $('#concept_topic').val());
        //runFlash('en.wikipedia.org', 'network security');
    }, 1);
}

function save_Wiki_ConceptMap() {
    //alert('This service is coming soon.');
    //return;

    var dlg_confirm = $(document.createElement('div')).appendTo($('#desktop'));
    dlg_confirm.attr("id", "dlg_savemap_confirm").attr("title", "Save the Concept Map?");
    //var html_str = '<table cellspacing="0" cellpadding="0">' +
    //        '<tr><td>Enter the concept name:</td><td><input type="text" name="concept_name"></td></tr>' +
    //        '<tr><td>Description:</td><td><textarea name="concept_description"></textarea></td></tr>' +
    //        '<tr><td>&nbsp;</td></tr></table>' +
    //        '<table cellspacing="0" cellpadding="0"><tr><th>Save the concept map to the server or a local file:</th></tr>' +
    //        '<tr><td><input type="radio" name="save_concept_option" value="database">&nbsp;Save to Server</td></tr>' +
    //        '<tr><td><input type="radio" name="save_concept_option" value="local">&nbsp;Save to Local</td></tr>' +
    //        '</table>';
    var form_html = '<form method="post" class="contact_form" id="save_concept_form" style="width:400px">' +
        '<ul><li><h2>Save Concept Map</h2>' +
        //'<span class="required_notification">* Denotes Required Field</span></li>' +
        '<li><label for="concept_name">Concept Name:</label>' +
        '<input type="text" name="concept_name" /></il>' +
        '<li><label for="concept_desc">Concept Description:</label>' +
        '<textarea name="concept_desc" cols="40" rows="6"></textarea></li>' +
        '<li><label for="save_concept_option">Save to </label>' +
        '<input type="radio" name="save_concept_option" value="database" style="height:0px; width:0px">&nbsp;Save to Server' +
        '&nbsp;&nbsp;<input type="radio" name="save_concept_option" value="local" style="height:0px; width:0px">&nbsp;Save to Local' +
        '</li></ul></form>';

    $(form_html).appendTo(dlg_confirm);
    //dlg_confirm.html(html_str);
    $('#dlg_savemap_confirm').dialog({
        resizable: false,
        width: 600,
        modal: true,
        buttons: {
            "Save": function() {
                $('body').append('<a id="save_map" href="getfreemind.php?Wiki=' + $('#wiki_select').val() + '&Topic=' + $('#concept_topic').val() + '"></a>');
                $('#save_map')[0].click();
                $(this).dialog("close");
            },
            Cancel: function() {
                $(this).dialog("close");
            }
        }
    });
}

function getFlashWindowHeight(){
    var canvas_height = parseInt($('#flashcontent').closest('div.window_inner').css('height'));
    var windowHeight = (canvas_height >= 550) ? canvas_height : 550;
    //var windowHeight = 0;
    //if (typeof(window.innerHeight) == 'number') {
    //    windowHeight = window.innerHeight;
    //}
    //else {
    //    if (document.documentElement && document.documentElement.clientHeight) {
    //        windowHeight = document.documentElement.clientHeight;
    //    }
    //    else {
    //        if (document.body && document.body.clientHeight) {
    //            windowHeight = document.body.clientHeight;
    //        }
    //    }
    //}
    return windowHeight - 150;
}

function runFlash(wiki_site, topic) {
    $('#concept_topic').val(topic);
    var h = getFlashWindowHeight();
    //h = h - 200;
    //document.getElementById("flashcontent").style.height = h;
    var fo = new FlashObject("visorFreemind.swf", "visorFreeMind", "100%", h, 6, "#9999ff");
    fo.addParam("quality", "high");
    fo.addParam("bgcolor", "#ffffff");
    fo.addVariable("openUrl", "_blank");
    fo.addVariable("initLoadFile", "getpages.php?Wiki=" + wiki_site + "&Topic=" + topic);
    fo.addVariable("startCollapsedToLevel", "1");
    fo.addVariable("mainNodeShape", "bubble");
    fo.write("flashcontent");
}

/*
 * FlashObject embed
 * by Geoff Stearns (geoff@deconcept.com, http://www.deconcept.com/)
 *
 * v1.1.1 - 05-17-2005
 *
 * writes the embed code for a flash movie, includes plugin detection
 *
 * Usage:
 *
 * myFlash = new FlashObject("path/to/swf.swf", "swfid", "width", "height", flashversion, "backgroundcolor");
 * myFlash.write("objId");
 *
 * for best practices, see:
 * http://blog.deconcept.com/2005/03/31/proper-flash-embedding-flashobject-best-practices/
 *
 */
var FlashObject = function(swf, id, w, h, ver, c) {
    this.swf = swf;
    this.id = id;
    this.width = w;
    this.height = h;
    this.version = ver;
    this.align = "middle";
    this.params = new Object();
    this.variables = new Object();
    this.redirect = "";
    this.sq = document.location.search.split("?")[1] || "";
    this.bypassTxt = "<p>Already have Macromedia Flash Player? <a href='?detectflash=false&"+ this.sq +"'>Click here if you have Flash Player "+ this.version +" installed</a>.</p>";
    if (c) this.color = this.addParam('bgcolor', c);
    this.addParam('quality', 'high'); // default to high
    this.doDetect = getQueryParamValue('detectflash');
};

var FOP = FlashObject.prototype;
FOP.addParam = function(name, value) { this.params[name] = value; };
FOP.getParams = function() { return this.params; };
FOP.getParam = function(name) { return this.params[name]; };
FOP.addVariable = function(name, value) { this.variables[name] = value; };
FOP.getVariable = function(name) { return this.variables[name]; };
FOP.getVariables = function() { return this.variables; };
FOP.getParamTags = function() {
    var paramTags = "";
    for (var param in this.getParams()) {
        paramTags += '<param name="' + param + '" value="' + this.getParam(param) + '" />';
    }
    return (paramTags == "") ? false:paramTags;
};

FOP.getHTML = function() {
    var flashHTML = "";
    if (navigator.plugins && navigator.mimeTypes.length) { // netscape plugin architecture
        flashHTML += '<embed type="application/x-shockwave-flash" src="mindmap-assets/' + this.swf + '" width="' + this.width + '" height="' + this.height + '" id="' + this.id + '" align="' + this.align + '"';
        for (var param in this.getParams()) {
            flashHTML += ' ' + param + '="' + this.getParam(param) + '"';
        }
        if (this.getVariablePairs()) {
            flashHTML += ' flashVars="' + this.getVariablePairs() + '"';
        }
        flashHTML += '></embed>';
    } else { // PC IE
        flashHTML += '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="' + this.width + '" height="' + this.height + '" name="' + this.id + '" align="' + this.align + '">';
        flashHTML += '<param name="movie" value="' + this.swf + '" />';
        if (this.getParamTags()) {
            flashHTML += this.getParamTags();
        }
        if (this.getVariablePairs() != null) {
            flashHTML += '<param name="flashVars" value="' + this.getVariablePairs() + '" />';
        }
        flashHTML += '</object>';
    }
    return flashHTML;
};

FOP.getVariablePairs = function() {
    var variablePairs = new Array();
    for (var name in this.getVariables()) {
        variablePairs.push(name + "=" + escape(this.getVariable(name)));
    }
    return (variablePairs.length > 0) ? variablePairs.join("&") : false;
};

FOP.write = function(elementId) {
    if(detectFlash(this.version) || this.doDetect=='false') {
        if (elementId) {
            document.getElementById(elementId).innerHTML = this.getHTML();
        } else {
            document.write(this.getHTML());
        }
    } else {
        if (this.redirect != "") {
            document.location.replace(this.redirect);
        } else if (this.altTxt) {
            if (elementId) {
                document.getElementById(elementId).innerHTML = this.altTxt +""+ this.bypassTxt;
            } else {
                document.write(this.altTxt +""+ this.bypassTxt);
            }
        }
    }
};

/* ---- detection functions ---- */
function getFlashVersion() {
    var flashversion = 0;
    if (navigator.plugins && navigator.mimeTypes.length) {
        var x = navigator.plugins["Shockwave Flash"];
        if(x && x.description) {
            var y = x.description;
            var z = y.substring(y.indexOf('.')-2, y.indexOf('.'));
            flashversion = parseInt(z);
        }
    } else {
        result = false;
        for(var i = 15; i >= 3 && result != true; i--){
            execScript('on error resume next: result = IsObject(CreateObject("ShockwaveFlash.ShockwaveFlash.'+i+'"))','VBScript');
            flashversion = i;
        }
    }
    return flashversion;
}

function detectFlash(ver) { return (getFlashVersion() >= ver) ? true : false; }
// get value of query string param

function getQueryParamValue(param) {
    var q = document.location.search || document.location.href.split("#")[1];
    if (q) {
        var detectIndex = q.indexOf(param +"=");
        var endIndex = (q.indexOf("&", detectIndex) > -1) ? q.indexOf("&", detectIndex) : q.length;
        if (q.length > 1 && detectIndex > -1) {
            return q.substring(q.indexOf("=", detectIndex)+1, endIndex);
        } else {
            return "";
        }
    }
}

/* add Array.push if needed */
if (Array.prototype.push == null) {
    Array.prototype.push = function(item) { this[this.length] = item; return this.length; }
}

//function load_concept_map_create() {
//    /*
//     // Add a new item
//     root.right.push({name: 'bar'}, {name: 'none'}, {name: 'some'}, {name: 'value'});
//     update(root);
//
//     // Move from the first to the last
//     root.right.push(root.right.shift());
//     update(root);
//
//     // Move from right to left
//     var tmp = root.right.shift();
//     tmp.position = 'left';
//     root.left.push(tmp);
//     update(root);
//
//     // Move from left to right
//     var tmp = root.left.shift();
//     tmp.position = 'right';
//     root.right.push(tmp);
//     update(root);
//
//     // Switch connector type
//     connector = diagonal;
//     update(root);
//
//     */
//
//    prepare_mouse_event();
//
//
//
//    vis_map = d3.select("#create_map_div")
//            .append("svg:svg")
//            .attr("width", w
//            + m[1] + m[3])
//            .attr("height", h + m[0] + m[2])
//            .append("svg:g")
//            //.attr("transform", "translate(" + m[3] + "," + m[0] + ")")
//            .attr("transform", "translate(" + (w/2+m[3]) + "," + m[0] + ")")
//        ;
//
//    loadJSON('data.json');
//}

//var vis_map = null;
//
//    var m = [20, 120, 20, 120],
//    //w = 1280 - m[1] - m[3],
//        w = 900 - m[1] - m[3],
//        h = 500 - m[0] - m[2],
//        i = 0,
//        root;
//
//
//
//    var selectNode = function(target){
//        if(target){
//            var sel = d3.selectAll('#create_map_div svg .concept-node').filter(function(d){return d.id==target.id})[0][0];
//            if(sel){
//                select(sel);
//            }
//        }
//    };
//
//    var addNodes = function(dir){
//        root[dir].push({name: 'bar', position: dir}, {name: 'none', position: dir}, {name: 'some', position: dir}, {name: 'value', position: dir});
//        update(root);
//    };
//
//    var moveNodes = function(from, to){
//        var tmp = root[from].shift();
//        tmp.position = to;
//        root[to].push(tmp);
//        update(root);
//    };
//
//    var setConnector = function(type){
//        connector = window[type];
//        update(root);
//    };
//
//    var select = function(node){
//        // Find previously selected, unselect
//        d3.select(".selected").classed("selected", false);
//        // Select current item
//        d3.select(node).classed("selected", true);
//    };
//
//    var createNew = function(){
//        root = {name: 'Root', children: [], left: [], right: []};
//        update(root, true);
//        selectNode(root);
//    };
//
//    var handleClick = function(d, index){
//        select(this);
//        update(d);
//    };
//
//    var tree = d3.layout.tree()
//        .size([h, w]);
//
//    var calcLeft = function(d){
//        var l = d.y;
//        if(d.position==='left'){
//            l = (d.y)-w/2;
//            l = (w/2) + l;
//        }
//        return {x : d.x, y : l};
//    };
//
//    var diagonal = d3.svg.diagonal()
//        .projection(function(d) { return [d.y, d.x]; });
//    var elbow = function (d, i){
//        var source = calcLeft(d.source);
//        var target = calcLeft(d.target);
//        var hy = (target.y-source.y)/2;
//        return "M" + source.y + "," + source.x
//            + "H" + (source.y+hy)
//            + "V" + target.x + "H" + target.y;
//    };
//
//var connector = elbow;
//
////*
//    var loadJSON = function(fileName){
//        fileName = 'workspace-assets/data/' + fileName;
//        //d3.json("/data/data.json", function(json) {
//        d3.json(fileName, function(json) {
//            var i=0, l=json.children.length;
//            window.data = root = json;
//            root.x0 = h / 2;
//            root.y0 = 0;
//
//            json.left = [];
//            json.right = [];
//            for(; i<l; i++){
//                if(i%2){
//                    json.left.push(json.children[i]);
//                    json.children[i].position = 'left';
//                }else{
//                    json.right.push(json.children[i]);
//                    json.children[i].position = 'right';
//                }
//            }
//
//            update(root, true);
//            selectNode(root);
//        });
//    };
////*/
//
////*
//    var loadFreeMind = function(fileName){
//        fileName = 'workspace-assets/data/' + fileName;
//        d3.xml(fileName, 'application/xml', function(err, xml){
//            // Changes XML to JSON
//            function xmlToJson(xml) {
//
//                // Create the return object
//                var obj = {};
//
//                if (xml.nodeType == 1) { // element
//                    // do attributes
//                    if (xml.attributes.length > 0) {
//                        obj["@attributes"] = {};
//                        for (var j = 0; j < xml.attributes.length; j++) {
//                            var attribute = xml.attributes.item(j);
//                            obj["@attributes"][attribute.nodeName] = attribute.nodeValue;
//                        }
//                    }
//                } else if (xml.nodeType == 3) { // text
//                    obj = xml.nodeValue;
//                }
//
//                // do children
//                if (xml.hasChildNodes()) {
//                    for(var i = 0; i < xml.childNodes.length; i++) {
//                        var item = xml.childNodes.item(i);
//                        var nodeName = item.nodeName;
//                        if (typeof(obj[nodeName]) == "undefined") {
//                            obj[nodeName] = xmlToJson(item);
//                        } else {
//                            if (typeof(obj[nodeName].push) == "undefined") {
//                                var old = obj[nodeName];
//                                obj[nodeName] = [];
//                                obj[nodeName].push(old);
//                            }
//                            obj[nodeName].push(xmlToJson(item));
//                        }
//                    }
//                }
//                return obj;
//            };
//            var js = xmlToJson(xml);
//            var data = js.map.node;
//            var parseData = function(data, direction){
//                var key, i, l, dir = direction, node = {}, child;
//                for(key in data['@attributes']){
//                    node[key.toLowerCase()] = data['@attributes'][key];
//                }
//                node.direction = node.direction || dir;
//                l = (data.node || []).length;
//                if(l){
//                    node.children = [];
//                    for(i=0; i<l; i++){
//                        dir = data.node[i]['@attributes'].POSITION || dir;
//                        child = parseData(data.node[i], {}, dir);
//                        (node[dir] = node[dir] || []).push(child);
//                        node.children.push(child);
//                    }
//                }
//                return node;
//            };
//            root = parseData(data, 'right');
//            root.x0 = h / 2;
//            root.y0 = w / 2;
//            update(root, true);
//            selectNode(root);
//        });
//    };
////*/
//
//    var toArray = function(item, arr, d){
//        arr = arr || [];
//        var dr = d || 1;
//        var i = 0, l = item.children?item.children.length:0;
//        arr.push(item);
//        if(item.position && item.position==='left'){
//            dr = -1;
//        }
//        item.y = dr * item.y;
//        for(; i < l; i++){
//            toArray(item.children[i], arr, dr);
//        }
//        return arr;
//    };
//
//    function update(source, slow) {
//        var duration = (d3.event && d3.event.altKey) || slow ? 1000 : 100;
//
//        // Compute the new tree layout.
//        var nodesLeft = tree
//            .size([h, (w/2)-20])
//            .children(function(d){
//                return (d.depth===0)?d.left:d.children;
//            })
//            .nodes(root)
//            .reverse();
//        var nodesRight = tree
//            .size([h, w/2])
//            .children(function(d){
//                return (d.depth===0)?d.right:d.children;
//            })
//            .nodes(root)
//            .reverse();
//        root.children = root.left.concat(root.right);
//        root._children = null;
//        var nodes = toArray(root);
//
//        // Normalize for fixed-depth.
//        //nodes.forEach(function(d) { d.y = d.depth * 180; });
//
//        // Update the nodes…
//        var node = vis_map.selectAll("g.concept-node")
//            .data(nodes, function(d) { return d.id || (d.id = ++i); });
//
//        // Enter any new nodes at the parent's previous position.
//        var nodeEnter = node.enter().append("svg:g")
//            .attr("class", function(d){ return d.selected?"concept-node selected":"concept-node"; })
//            .attr("transform", function(d) { return "translate(" + source.y0 + "," + source.x0 + ")"; })
//            .on("click", handleClick);
//
//        nodeEnter.append("svg:circle")
//            .attr("r", 1e-6);
//        //.style("fill", function(d) { return d._children ? "lightsteelblue" : "#fff"; });
//
//        nodeEnter.append("svg:text")
//            .attr("x", function(d) { return d.children || d._children ? -10 : 10; })
////            .attr("dy", ".35em")
////            .attr("text-anchor", function(d) { return d.children || d._children ? "end" : "start"; })
//            .attr("dy", 14)
//            .attr("text-anchor", "middle")
//            .text(function(d) { return (d.name || d.text); })
//            .style("fill-opacity", 1);
//
//        // Transition nodes to their new position.
//        var nodeUpdate = node.transition()
//            //.attr("class", function(d){ return d.selected?"concept-node selected":"concept-node"; })
//            .duration(duration)
//            .attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; });
//
//        nodeUpdate.select("text")
//            .text(function(d) { return (d.name || d.text); });
//
//        nodeUpdate.select("circle")
//            .attr("r", 4.5);
//        //.style("fill", function(d) { return d._children ? "lightsteelblue" : "#fff"; });
//
//        /*
//         nodeUpdate.select("text")
//         .attr("dy", 14)
//         .attr("text-anchor", "middle")
//         .style("fill-opacity", 1);
//         */
//
//        // Transition exiting nodes to the parent's new position.
//        var nodeExit = node.exit().transition()
//            .duration(duration)
//            .attr("transform", function(d) { return "translate(" + source.y + "," + source.x + ")"; })
//            .remove();
//
//        nodeExit.select("circle")
//            .attr("r", 1e-6);
//
//        nodeExit.select("text")
//            .style("fill-opacity", 1e-6);
//
//        // Update the links…
//        var link = vis_map.selectAll("path.concept-link")
//            .data(tree.links(nodes), function(d) { return d.target.id; });
//
//        // Enter any new links at the parent's previous position.
//        link.enter().insert("svg:path", "g")
//            .attr("class", "concept-link")
//            .attr("d", function(d) {
//                var o = {x: source.x0, y: source.y0};
//                return connector({source: o, target: o});
//            })
//            .transition()
//            .duration(duration)
//            .attr("d", connector);
//
//        // Transition links to their new position.
//        link.transition()
//            .duration(duration)
//            .attr("d", connector);
//
//        // Transition exiting nodes to the parent's new position.
//        link.exit().transition()
//            .duration(duration)
//            .attr("d", function(d) {
//                var o = {x: source.x, y: source.y};
//                return connector({source: o, target: o});
//            })
//            .remove();
//
//        // Stash the old positions for transition.
//        nodes.forEach(function(d) {
//            d.x0 = d.x;
//            d.y0 = d.y;
//        });
//    }
//
//    // Toggle children.
//    function toggle(d) {
//        if (d.children) {
//            d._children = d.children;
//            d.children = null;
//        } else {
//            d.children = d._children;
//            d._children = null;
//        }
//    }
//
//
//
//function prepare_mouse_event() {
//    Mousetrap.bind('left', function () {
//        // left key pressed
//        var selection = d3.select(".concept-node.selected")[0][0];
//        if (selection) {
//            var data = selection.__data__;
//            var dir = getDirection(data);
//            switch (dir) {
//                case('right'):
//                case('root'):
//                    selectNode(data.parent || data.left[0]);
//                    break;
//                case('left'):
//                    selectNode((data.children || [])[0]);
//                    break;
//                default:
//                    break;
//            }
//        }
//    });
//    Mousetrap.bind('right', function () {
//        // right key pressed
//        var selection = d3.select(".concept-node.selected")[0][0];
//        if (selection) {
//            var data = selection.__data__;
//            var dir = getDirection(data);
//            switch (dir) {
//                case('left'):
//                case('root'):
//                    selectNode(data.parent || data.right[0]);
//                    break;
//                case('right'):
//                    selectNode((data.children || [])[0]);
//                    break;
//                default:
//                    break;
//            }
//        }
//    });
//    Mousetrap.bind('up', function () {
//        // up key pressed
//        var selection = d3.select(".concept-node.selected")[0][0];
//        if (selection) {
//            var data = selection.__data__;
//            var dir = getDirection(data);
//            switch (dir) {
//                case('root'):
//                    break;
//                case('left'):
//                case('right'):
//                    var p = data.parent, nl = p.children || [], i = 1;
//                    if (p[dir]) {
//                        nl = p[dir];
//                    }
//                    l = nl.length;
//                    for (; i < l; i++) {
//                        if (nl[i].id === data.id) {
//                            selectNode(nl[i - 1]);
//                            break;
//                        }
//                    }
//                    break;
//            }
//        }
//        return false;
//    });
//    Mousetrap.bind('down', function () {
//        // down key pressed
//        // up key pressed
//        var selection = d3.select(".concept-node.selected")[0][0];
//        if (selection) {
//            var data = selection.__data__;
//            var dir = getDirection(data);
//            switch (dir) {
//                case('root'):
//                    break;
//                case('left'):
//                case('right'):
//                    var p = data.parent, nl = p.children || [], i = 0;
//                    if (p[dir]) {
//                        nl = p[dir];
//                    }
//                    l = nl.length;
//                    for (; i < l - 1; i++) {
//                        if (nl[i].id === data.id) {
//                            selectNode(nl[i + 1]);
//                            break;
//                        }
//                    }
//                    break;
//            }
//        }
//        return false;
//    });
//
//    Mousetrap.bind('ins', function () {
//        var selection = d3.select(".concept-node.selected")[0][0];
//        if (selection) {
//            var data = selection.__data__;
//            var dir = getDirection(data);
//            var name = prompt('New name');
//            if (name) {
//                if (dir === 'root') {
//                    dir = data.right.length > data.left.length ? 'left' : 'right';
//                }
//                var cl = data[dir] || data.children || data._children;
//                if (!cl) {
//                    cl = data.children = [];
//                }
//                cl.push({name: name, position: dir});
//                update(root);
//            }
//        }
//    });
//
//    Mousetrap.bind('del', function () {
//        var selection = d3.select(".concept-node.selected")[0][0];
//        if (selection) {
//            var data = selection.__data__;
//            var dir = getDirection(data);
//            if (dir === 'root') {
//                alert('Can\'t delete root');
//                return;
//            }
//            var cl = data.parent[dir] || data.parent.children;
//            if (!cl) {
//                alert('Could not locate children');
//                return;
//            }
//            var i = 0, l = cl.length;
//            for (; i < l; i++) {
//                if (cl[i].id === data.id) {
//                    if (confirm('Sure you want to delete ' + data.name + '?') === true) {
//                        cl.splice(i, 1);
//                    }
//                    break;
//                }
//            }
//            selectNode(root);
//            update(root);
//        }
//    });
//
//    Mousetrap.bind('enter', function () {
//        var selection = d3.select(".concept-node.selected")[0][0];
//        if (selection) {
//            var data = selection.__data__;
//            data.name = prompt('New text:', data.name) || data.name;
//            update(root);
//        }
//    });
//
//    var getDirection = function (data) {
//        if (!data) {
//            return 'root';
//        }
//        if (data.position) {
//            return data.position;
//        }
//        return getDirection(data.parent);
//    };
//}