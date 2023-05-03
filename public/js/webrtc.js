/**
 * Created by huijun on 6/3/16.
 */

var signalingUrl = 'wss://signaling.mobicloud.asu.edu';
var dataChannelName = "one2oneData";
var configuration = {
    "iceServers": [
        {url:'stun:stun01.sipphone.com'},
        {url:'stun:stun.ekiga.net'},
        {url:'stun:stun.fwdnet.net'},
        {url:'stun:stun.ideasip.com'},
        {url:'stun:stun.iptel.org'},
        {url:'stun:stun.rixtelecom.se'},
        {url:'stun:stun.schlund.de'},
        {url:'stun:stun.l.google.com:19302'},
        {url:'stun:stun1.l.google.com:19302'},
        {url:'stun:stun2.l.google.com:19302'},
        {url:'stun:stun3.l.google.com:19302'},
        {url:'stun:stun4.l.google.com:19302'},
        {url:'stun:stunserver.org'},
        {url:'stun:stun.softjoys.com'},
        {url:'stun:stun.voiparound.com'},
        {url:'stun:stun.voipbuster.com'},
        {url:'stun:stun.voipstunt.com'},
        {url:'stun:stun.voxgratia.org'},
        {url:'stun:stun.xten.com'},
        {
            url: 'turn:numb.viagenie.ca',
            credential: '2016Test',
            username: 'huijun.wu.2010@gmail.com'
        },
        {
            url: 'turn:numb.viagenie.ca',
            credential: 'muazkh',
            username: 'webrtc@live.com'
        },
        {
            url: 'turn:192.158.29.39:3478?transport=udp',
            credential: 'JZEOEt2V3Qb0y27GRntt2u2PAYA=',
            username: '28224511:1379330808'
        },
        {
            url: 'turn:192.158.29.39:3478?transport=tcp',
            credential: 'JZEOEt2V3Qb0y27GRntt2u2PAYA=',
            username: '28224511:1379330808'
        }
    ]
};
var dataChannelOptions = {
    reliable: true,
    ordered: true
};

var options = {
    blur_radius: 2,
    low_threshold: 30,
    high_threshold: 60
};

var signalingConnection = new WebSocket(signalingUrl);
signalingConnection.onerror = function(err) {
    console.log("WebSocket got error", err);
};

function hasUserMedia() {
    navigator.getUserMedia = navigator.getUserMedia ||
    navigator.webkitGetUserMedia || navigator.mozGetUserMedia ||
    navigator.msGetUserMedia;
    return !!navigator.getUserMedia;
}

function hasRTCPeerConnection() {
    window.RTCPeerConnection = window.RTCPeerConnection ||
    window.webkitRTCPeerConnection || window.mozRTCPeerConnection;
    window.RTCSessionDescription = window.RTCSessionDescription ||
    window.webkitRTCSessionDescription ||
    window.mozRTCSessionDescription;
    window.RTCIceCandidate = window.RTCIceCandidate ||
    window.webkitRTCIceCandidate || window.mozRTCIceCandidate;
    return !!window.RTCPeerConnection;
}

// Alias for sending messages in JSON format
function send(message) {
    signalingConnection.send(JSON.stringify(message));
}


//// the above is copied from midas demo common.js
var webrtcConnectionOut = null;
var webrtcConnectionIn = null;


signalingConnection.onopen = function() {
    console.log("webrtc websocket nodejs signaling Connected");
    var myName = document.querySelector('#menu_username').innerHTML;
    send({
        type: "login",
        from: myName
    });
};

// Handle all messages through this callback
signalingConnection.onmessage = function(message) {
    console.log("Got message", message.data);
    var data = JSON.parse(message.data);
    switch (data.type) {
        case "login":
            onLogin(data.success);
            break;
        case "answer":
            onAnswer(data.answer);
            break;
        case "candidate":
            onCandidate(data.candidate);
            break;
        case "candidateIn":
            onCandidateIn(data.candidate);
            break;

        // the below is copied from monitor.js
        case "offer":
            onOffer(data.offer, data.name);
            break;
        case "leave":
            onLeave(data.name);
            break;
        // end of monitor.js

        default:
            break;
    }
};
var peerBody = "";
var outstream = null;
var instream = null;
var datachannellIn = null;
var datachannellOut = null;
function onLogin(success) {
    if (success === false) {
        console.log("webrtc Login unsuccessful, already logined.");
    } else {
        console.log("webrtc Login successful.");
    }

    if (hasUserMedia()) {
        navigator.getUserMedia({
            video: true,
            audio: true
        }, function(myStream) {
            instream = myStream;
            //document.querySelector('#yours').src = window.URL.createObjectURL(myStream);
            if (hasRTCPeerConnection()) {
                webrtcConnectionIn = new RTCPeerConnection(configuration);
                webrtcConnectionIn.onicecandidate = function(event) {
                    if (event.candidate) {
                        send({
                            type: "candidate",
                            candidate: event.candidate,
                            to: document.querySelector('#callstatus').innerHTML
                        });
                    }
                };
                webrtcConnectionIn.addStream(myStream);

                // copied from monitor.js
                webrtcConnectionIn.onaddstream = function(e) {
                    document.querySelector('#peerone').src = window.URL.createObjectURL(e.stream);
                };
                webrtcConnectionIn.ondatachannel = function(event) {
                    console.log('Receive Channel Callback: event --> ' + event);
                    datachannellIn = event.channel;
                    event.channel.onmessage = function(event) {
                        console.log('Received message: ' + event.data);
                        //cell3.innerHTML = event.data;
                        var ta = document.getElementById("one2one_text_diaply");
                        ta.innerHTML = ta.innerHTML + "\n"+peerBody+":"+event.data;
                        ta.scrollTop = ta.scrollHeight;
                    };
                };
                // end monitor.js

                // Begin GPS
                //openDataChannelIn(peerEmail, webrtcConnectionIn);


            } else {
                console.log("hasRTCPeerConnection: Sorry, your browser does not support WebRTC.");
                alert("Sorry, your browser does not support WebRTC.");
            }
        }, function(error) {
            console.log(error);
        });
    } else {
        console.log("hasUserMedia: Sorry, your browser does not support WebRTC.");
        alert("Sorry, your browser does not support WebRTC.");
    }
}


function openDataChannelIn(peerEmail, webrtcConnection) {
    var dataChannel = webrtcConnection.createDataChannel(dataChannelName,
        dataChannelOptions);
    dataChannel.onerror = function(error) {
        console.log("Data Channel Error:", error);
    };
    dataChannel.onmessage = function(event) {
        console.log("Got Data Channel Message:", event.data);
    };
    dataChannel.onopen = function() {
        datachannellIn = dataChannel;
    };
    dataChannel.onclose = function() {
        console.log("The Data Channel is Closed");
    };
}
function openDataChannelOut(peerEmail, webrtcConnection) {
    var dataChannel = webrtcConnection.createDataChannel(dataChannelName,
        dataChannelOptions);
    dataChannel.onerror = function(error) {
        console.log("Data Channel Error:", error);
    };
    dataChannel.onmessage = function(event) {
        console.log("Got Data Channel Message:", event.data);
        var ta = document.getElementById("one2one_text_diaply");
        ta.innerHTML = ta.innerHTML + "\n"+peerBody+":"+event.data;
        ta.scrollTop = ta.scrollHeight;
    };
    dataChannel.onopen = function() {
        datachannellOut = dataChannel;
    };
    dataChannel.onclose = function() {
        console.log("The Data Channel is Closed");
    };
}

function onAnswer(answer) {
    var callstatustext = document.querySelector('#callstatus');
    callstatustext.innerHTML = peerBody;
    webrtcConnectionOut.setRemoteDescription(new RTCSessionDescription(answer));
}

function onCandidate(candidate) {
    webrtcConnectionOut.addIceCandidate(new RTCIceCandidate(candidate));
}
var inDelayCandidateCache = [];
function onCandidateIn(candidate) {
    inDelayCandidateCache.push(new RTCIceCandidate(candidate));
    //webrtcConnectionIn.addIceCandidate();
}

///// the above is copied from midas demo pict.js
var currentOffer = null;
function onOffer(offer, name) {
    var callstatustext = document.querySelector('#callstatus');
    callstatustext.innerHTML = name;
    currentOffer = offer;
    inDelayCandidateCache = []; // reset cache
    //answerPaging();// debug only
}
function onLeave() {
    if (webrtcConnectionIn!==null) {
        webrtcConnectionIn.close();
        delete webrtcConnectionIn;

        currentOffer = null;
        instream = null;
        webrtcConnectionIn = null;
        inDelayCandidateCache = []; // reset cache

        onLogin(false); // fake login to reset webrtcIn server
    }

    if (webrtcConnectionOut!==null) {
        webrtcConnectionOut.close();
        delete webrtcConnectionOut;

        outstream = null;
        webrtcConnectionOut = null;
    }
    document.querySelector('#peerone').src = "";

    var callstatustext = document.querySelector('#callstatus');
    callstatustext.innerHTML = "";


}

///// the above is copied from midas demo monitor.js

function callSomebody(peerEmail) {

    if (hasUserMedia()) {
        navigator.getUserMedia({
            video: true,
            audio: true
        }, function(myStream) {
            outstream = myStream;
            //document.querySelector('#yours').src = window.URL.createObjectURL(myStream);
            if (hasRTCPeerConnection()) {
                webrtcConnectionOut = new RTCPeerConnection(configuration);
                webrtcConnectionOut.onicecandidate = function(event) {
                    if (event.candidate) {
                        send({
                            type: "candidateIn",
                            candidate: event.candidate,
                            to: peerEmail
                        });
                    }
                };
                webrtcConnectionOut.addStream(myStream); // self does not show, added before listener

                // copied from monitor.js
                webrtcConnectionOut.onaddstream = function(e) {
                    document.querySelector('#peerone').src = window.URL.createObjectURL(e.stream);
                };
                webrtcConnectionOut.ondatachannel = function(event) {
                    console.log('Receive Channel Callback: event --> ' + event);
                    event.channel.onmessage = function(event) {
                        console.log('Received message: ' + event.data);
                        //cell3.innerHTML = event.data;
                        var ta = document.getElementById("one2one_text_diaply");
                        ta.innerHTML = ta.innerHTML + "\n"+peerBody+":"+event.data;
                    };
                };
                // end monitor.js

                // Begin GPS
                openDataChannelOut(peerEmail, webrtcConnectionOut);

                // Begin the offer
                peerBody = peerEmail;
                webrtcConnectionOut.createOffer(function(offer) {
                    send({
                        type: "offer",
                        offer: offer,
                        to: peerEmail
                    });
                    webrtcConnectionOut.setLocalDescription(offer);
                }, function(error) {
                    alert("An error has occurred.");
                });
            } else {
                alert("Sorry, your browser does not support WebRTC.");
            }
        }, function(error) {
            console.log(error);
        });
    } else {
        alert("Sorry, your browser does not support WebRTC.");
    }
}

function answerPaging() {
    if (currentOffer === null /*|| webrtcConnectionIn!==null*/) return;

    var offer = currentOffer;
    var name = document.querySelector('#callstatus').innerHTML;

    // answer
    webrtcConnectionIn.setRemoteDescription(new RTCSessionDescription(offer));
    for (var idx=0; idx<inDelayCandidateCache.length; idx++) {
        webrtcConnectionIn.addIceCandidate(inDelayCandidateCache[idx]);
    }
    //inDelayCandidateCache= [];
    peerBody = name;
    webrtcConnectionIn.createAnswer(function(answer) {
        webrtcConnectionIn.setLocalDescription(answer);
        send({
            type: "answer",
            answer: answer,
            to: name
        });
    }, function(error) {
        alert("An error has occurred");
    });
}

function forceleave() {
    send({
        type: "leave",
        to: peerBody
    });
    send({
        type: "leave",
        to: document.querySelector('#menu_username').innerHTML
    });
}

////////// end webrtc

function mute_speaker() {
    var vid = document.getElementById("peerone");
    vid.muted = !vid.muted;
    if (!vid.muted) {
        document.getElementById("mutespeakerlabel").src = "workspace-assets/images/biggreendot.png";
    } else {
        document.getElementById("mutespeakerlabel").src = "workspace-assets/images/bigreddot.png";
    }
    //document.getElementById("mutespeakerlabel").innerHTML = vid.muted;
}
function mute_microphone() {
    var at = null;
    if (outstream!=null) {
        at = outstream;
    } else if (instream!=null) {
        at = instream;
    } else {
        console.log("both stream in and out are null!!! cannot mute microphone");
    }
    var atrack = at.getAudioTracks()[0];
    atrack.enabled = !atrack.enabled;
    if (atrack.enabled) {
        document.getElementById("mutemicrophonelabel").src = "workspace-assets/images/biggreendot.png";
    } else {
        document.getElementById("mutemicrophonelabel").src = "workspace-assets/images/bigreddot.png";
    }
}
function sendtext() {
    var text = document.getElementById("one2one_text_input").value;
    if (datachannellOut!==null) {
        datachannellOut.send(text);
    } else if (datachannellIn!==null){
        datachannellIn.send(text);
    } else {
        console.log("both data channels are null!! cannot send text");
    }
    var ta = document.getElementById("one2one_text_diaply");
    ta.innerHTML = ta.innerHTML + "\n"+document.getElementById("menu_username").innerHTML+":"+text;
    ta.scrollTop = ta.scrollHeight;
}
function display_webrtc_window(winId, win_main) {
    var tabs = {
        tabId: ['one_one', 'one_group'],
        tabName: ['One to One Video', 'Live Stream to Cloud']
    };

    create_tabs(winId, win_main, tabs, null);

    var html = '<div id="webrtc_one2one">' +
        '<div id="jstree_demo_div" ></div>' +
        //'<div id="jstree_demo_div" style="display:block;float:right;height: 450px;overflow-y: scroll;"></div>' +
        '<table id="p1_gm_tree" class="data" style="display:none;float:right;height: 450px;overflow-y: scroll;"></table>' +
        //'<div id="p1_all" >' +
        //'<div id="p1_v" style="border: dashed; height:300px; width: 400px">' +
        //'<div id="v_control">' +
        //'<button id="pickupcall" onclick="answerPaging()">pick up</button><button id="hangoffcall" onclick="forceleave()">hang off</button><label id="callstatus"></label>' +
        //'</div>' +
        //'<video id="peerone" height="270" width="390" autoplay></video>' +
        //'</div>' +
        //'<div id="p1_a" style="border: dashed; width: 400px" title="camera and microphone access authorization is required in the browser">' +
        //'<button id="muteself" onclick="mute_microphone()">microphone</button><img src = "workspace-assets/images/biggreendot.png" height="20" width="20"  id="mutemicrophonelabel" />' +
        //'<button id="mutepeer" onclick="mute_speaker()">speaker</button><img src = "workspace-assets/images/biggreendot.png" height="20" width="20" id="mutespeakerlabel" />' +
        //'</div>' +
        //'<div id="p1_t" style="border: dashed; height:120px; width: 400px">' +
        //'<textarea id="one2one_text_diaply" disabled rows="3" cols="50" style="resize: none"></textarea><br>' +
        //'<input type="text" id="one2one_text_input" size="45"/><button onclick="sendtext()">send</button>' +
        //'</div>' +
        '</div>' +
        '</div>';
    $(html).appendTo($('#one_one'));
    //display_rtc_one2one();

    // Opera 8.0+
    var isOpera = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
    // Firefox 1.0+
    var isFirefox = typeof InstallTrigger !== 'undefined';
    // At least Safari 3+: "[object HTMLElementConstructor]"
    var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
    // Internet Explorer 6-11
    var isIE = /*@cc_on!@*/false || !!document.documentMode;
    // Edge 20+
    var isEdge = !isIE && !!window.StyleMedia;
    // Chrome 1+
    var isChrome = !!window.chrome && !!window.chrome.webstore;
    // Blink engine detection
    var isBlink = (isChrome || isOpera) && !!window.CSS;

    if (isFirefox){

    } else if (isChrome) {

    }

    var html2 = '<div id="webrtc_group">live stream to cloud page in development</div>';
    $(html2).appendTo($('#one_group'));
    display_rtc_group();

    $(winId).find('div.window_bottom').text('Video conference');
    $(winId).css("height","600px");
    $(winId).css("width","400px");
}
function toggle_webrtc(){
    $("#window_webrtc").slideToggle();
    //
    $("#window_videowebrtc").slideToggle();
}
function display_videowebrtc_window(winId, win_main) {
    var html = '<div id="webrtc_one2one" style="position: relative">' +

        '<div id="p1_v" style=" width: 70%; float: left; ">' +

        '<div id="v_control" style="float: top; ">' +
        '<button id="pickupcall" onclick="answerPaging()">pick up</button>' +
        '<button id="hangoffcall" onclick="forceleave()">hang off</button>' +
        '<label id="callstatus"></label>' +
        '</div>' +

        '<video id="peerone"  autoplay style="width: 100%; border:solid"></video>' +

        '<div id="p1_a" style="float: bottom; " title="camera and microphone access authorization is required in the browser">' +
        '<button id="muteself" onclick="mute_microphone()">microphone</button>' +
        '<img src = "workspace-assets/images/biggreendot.png" height="20" width="20"  id="mutemicrophonelabel" />' +
        '<button id="mutepeer" onclick="mute_speaker()">speaker</button>' +
        '<img src = "workspace-assets/images/biggreendot.png" height="20" width="20" id="mutespeakerlabel" />' +
        '</div>' +

        '</div>' +


        '<div id="p1_t" style="width: 30%; float: right">' +
        '<textarea id="one2one_text_diaply" disabled  style="resize:none;  width: 100%"></textarea>' +
        '<div style="float: bottom; ">' +
        '<input type="text" id="one2one_text_input" style="  width: 80%"/><button onclick="sendtext()">send</button>' +
        '</div>' +
        '</div>' +

        '</div>';
    $(html).appendTo(win_main);
    display_rtc_one2one();

    $(winId).find('div.window_bottom').text('Video');
    $(winId).css("height","600px");
    $(winId).css("left","600px");

    //var winheight = $(winId).height();
    //var winwidth = $('#one2one_text_diaply').width();
    //$(winId).find($('#peerone')).css('height',winheight-130);
    //$(winId).find($('#one2one_text_diaply')).css('height',winheight-100);
    //$(winId).find($('#one2one_text_input')).css('width',winwidth-10);
    var winheight = $(winId).height();
    var win1width = $(winId).width();
    $('#peerone').css('height',winheight-130);
    $('#one2one_text_diaply').css('height',winheight-100);
    $('#one2one_text_input').css('width',win1width *.3-50);
}

var one2oneTimer = null;

function display_rtc_one2one() {
    $('#jstree_demo_div').jstree({
        'state' : {'key' : 'state_demo' },
        'plugins' : ['themes', 'state', 'ui', 'types', 'search'],
        'types' : { 'default' : { 'icon' : 'fa fa-file-text-o'}, 'folder' : { 'icon' : 'fa fa-folder-o'}},
        "core": {
            'themes': {
                'name': 'proton',
                'responsive': false
            },
            "check_callback":true,
            "data":[]
        }
    });
    //var treenode_json = $('#jstree_demo_div').jstree(true).get_json();

    console.log("ask for webrtc user tree");
    $.getJSON("cloud/get_all_group_member_tree", function (jsondata) {
        console.log("destroy webrtc user tree");
        var treenode_json = $('#jstree_demo_div').jstree(true).get_json('#', {flat:false});
        for (var no in treenode_json) {
            $('#jstree_demo_div').jstree().delete_node(treenode_json[no].id);
        }

        console.log("draw webrtc user tree ");
        var tree = $("#p1_gm_tree");
        tree.empty();
        tree.append('<tr><th>group</th><th>member</th><th>on/off</th></tr>');
        $.each(jsondata, function (index, item) {
            $('#jstree_demo_div').jstree().create_node(null, {"id":index, "text":index}, "last", null);
            $('#jstree_demo_div').jstree().create_node(index, {"id":index+"online", "text":"online"}, "last", null);
            $('#jstree_demo_div').jstree().create_node(index, {"id":index+"offline", "text":"offline"}, "last", null);
            $.each(item, function (index2, item2) {
                tree.append('<tr><td>'+index+'</td><td>'+item2+'</td><td>off</td></tr>');
                $('#jstree_demo_div').jstree().create_node(index+"offline", {"id":index+"offline"+item2, "text":item2}, "last", null);
            });
        });

        if (null === one2oneTimer) {
            one2oneTimer = window.setInterval(function () {
                var tablebody121 = $("#p1_gm_tree")[0];
                var peers = "";
                for (var i = 1; i<tablebody121.rows.length; i++) {
                    var rowww = tablebody121.rows[i];
                    var peeremail = rowww.cells[1].innerHTML;
                    if (i>1) {
                        peers += ",";
                    }
                    peers += "'"+peeremail +"'";
                }

                //$.getJSON("cloud/queryHearbeatBatch", {
                //    'query_user': peers
                //}, function (data) {
                //    for (var i = 1; i<tablebody121.rows.length; i++) {
                //        var rowww = tablebody121.rows[i];
                //        var peeremail = rowww.cells[1].innerHTML;
                //        if ($.inArray(peeremail, data)>-1) {
                //            console.log(" user  online "+peeremail);
                //            rowww.cells[2].innerHTML = "<button class=\"btn-webrtc-callone\">call</button>";
                //            if ($('#jstree_demo_div').jstree().get_node(rowww.cells[0].innerHTML+"offline"+peeremail)) {
                //                $('#jstree_demo_div').jstree().delete_node(rowww.cells[0].innerHTML+"offline"+peeremail);
                //                $('#jstree_demo_div').jstree().create_node(rowww.cells[0].innerHTML+"online",
                //                    {"id":rowww.cells[0].innerHTML+"online"+peeremail, "text":peeremail+"<button peeremail='"+peeremail+"' class='btn-webrtc-callone-tree'>call</button>"},
                //                    "last", null);
                //                console.log("move user from offline to online "+peeremail);
                //            }
                //        } else {
                //            rowww.cells[2].innerHTML = "off";
                //            if ($('#jstree_demo_div').jstree().get_node(rowww.cells[0].innerHTML+"online"+peeremail)) {
                //                $('#jstree_demo_div').jstree().delete_node(rowww.cells[0].innerHTML+"online"+peeremail);
                //                $('#jstree_demo_div').jstree().create_node(rowww.cells[0].innerHTML+"offline",
                //                    {"id":rowww.cells[0].innerHTML+"offline"+peeremail, "text":peeremail},
                //                    "last", null);
                //                console.log("move user from online to offline "+peeremail);
                //            }
                //        }
                //    }
                //});
            }, 5000);
        }
    });
}

function display_rtc_group() {

}