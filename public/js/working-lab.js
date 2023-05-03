$(function(){
    $(".resizable-left").resizable(
        {
            autoHide: true,
            handles: 'e',
            resize: function(e, ui)
            {
                var parent = ui.element.parent();
                var remainingSpace = parent.width() - ui.element.outerWidth(),
                    divTwo = ui.element.next(),
                    divTwoWidth = (remainingSpace - (divTwo.outerWidth() - divTwo.width()))/parent.width()*100+"%";
                divTwo.width(divTwoWidth);
            },
            stop: function(e, ui)
            {
                var parent = ui.element.parent();
                ui.element.css(
                    {
                        width: ui.element.width()/parent.width()*100+"%"
                    });
            }
        });
});

function populate_content_sdncontroller(winId, win_main) {
    var pieces =winId.split('_');
    var course_id= pieces[pieces.length-1];
    var name_id="2015_T1";
    var clientHeight =$(window).height()-158;

    //var iframe_console = $(document.createElement('iframe')).appendTo($('#opendaylight'));
    //iframe_console.attr("name", "opendaylight_frame").attr("width", "100%").attr("style", "height: 100em")
    //    .attr("src", "https://www.google.com/search?q=%http://sdncontroller.mobisphere.asu.edu/index.html&btnI=Im+Feeling+Lucky");

    var container_div = $('<div />').attr('id', 'myContainer').appendTo(win_main);
    var left_pane = $('<div />').attr('id', 'left_pane').css('position', 'relative').css('min-width', '200px').css('height', clientHeight).appendTo(container_div);
    var right_pane = $('<div />').attr('id', 'right_pane').css('top', '2px').css('height', clientHeight).css('margin', '-10px').appendTo(container_div);
    //$('<button />').attr('onclick', 'scrollToRightDiv("right_pane","right1")').text('Scroll to div1').appendTo(left_pane);

    var tree1 = document.createElement('div');
    var trees = $(tree1).appendTo(left_pane);
    tree1.id="jstree1";
    tree1.className ="jstree1";
    $.getJSON("/cloud/getTreeContent/" + course_id + "/" +name_id, function (jsondata) {
        trees.append(JSON.stringify(jsondata));
        $(function() {

            $('#jstree1').jstree({
                'core': {
                    'themes': {
                        'name': 'proton',
                        'responsive': true
                    }
                },
                "state" : { "key" : "state_demo" },
                "plugins" : ["themes","state","ui"]
            });
            $('#left_pane').css('width',$('#myContainer').find('.jstree-anchor').width()+50);
            $('#right_pane').css('width',container_div.width()-$('#left_pane').width());
            $('.vsplitter').css('left',$('#myContainer').find('.jstree-anchor').width()+50);
        });
    });

    $('<button class="btn_create_lab" name="New lab" value="lab_new">Add New Lab</button><br />').appendTo(left_pane);

    //var right_html = '<div id="right1" style="height: 200px;">div1 Right: Splitter options and markup can modify splitter behavior, such as the style of the splitbar and whether the splitbar dynamically follows the mouse. By setting size limits on the two panes, you can prevent the splitter from making one of the panes too big or small. To move the splitbar position programmatically</div>'
    //    + '<div id="right2" style="height: 200px;">div2 Right: Splitter options and markup can modify splitter behavior, such as the style of the splitbar and whether the splitbar dynamically follows the mouse. By setting size limits on the two panes, you can prevent the splitter from making one of the panes too big or small. To move the splitbar position programmatically</div>'
    //    + '<div id="right3" style="height: 200px;">div3 Right: Splitter options and markup can modify splitter behavior, such as the style of the splitbar and whether the splitbar dynamically follows the mouse. By setting size limits on the two panes, you can prevent the splitter from making one of the panes too big or small. To move the splitbar position programmatically</div>'
    //    + '<div id="right4" style="height: 200px;">div4 Right: Splitter options and markup can modify splitter behavior, such as the style of the splitbar and whether the splitbar dynamically follows the mouse. By setting size limits on the two panes, you can prevent the splitter from making one of the panes too big or small. To move the splitbar position programmatically</div>'
    //    + '<div id="right5" style="height: 200px;">div5 Right: Splitter options and markup can modify splitter behavior, such as the style of the splitbar and whether the splitbar dynamically follows the mouse. By setting size limits on the two panes, you can prevent the splitter from making one of the panes too big or small. To move the splitbar position programmatically</div>';
    //right_pane.html(right_html);

    $.getJSON("/cloud/getLeaf/" + course_id + "/" + name_id, function (jsondata) {
        console.log(jsondata);
        $('#collapseLabContent').empty();

            $(jsondata).appendTo(right_pane);


        //right_pane.html(jsondata);
    });

    $('#myContainer').split({
        orientation: 'vertical',
        limit: 100,
        position: '50%'
    });

    //var touchSplitter1 = $('.split-me').touchSplit({leftMax:300, leftMin:100, dock:"left"});
    //touchSplitter1.getFirst().touchSplit({orientation:"vertical"})
}

function edit_group_lab(winId, win_main) {

    var course_id= 'SWS_01';
    var name_id="2015_T1";
    var clientHeight =$(window).height()-158;

    //var iframe_console = $(document.createElement('iframe')).appendTo($('#opendaylight'));
    //iframe_console.attr("name", "opendaylight_frame").attr("width", "100%").attr("style", "height: 100em")
    //    .attr("src", "https://www.google.com/search?q=%http://sdncontroller.mobisphere.asu.edu/index.html&btnI=Im+Feeling+Lucky");

    var container_div = $('<div />').attr('id', 'myContainer').appendTo(win_main);
    var left_pane = $('<div />').attr('id', 'left_pane').css('position', 'relative').css('min-width', '200px').css('height', clientHeight).appendTo(container_div);
    var right_pane = $('<div />').attr('id', 'right_pane').css('top', '2px').css('height', clientHeight).css('margin', '-10px').appendTo(container_div);
    //$('<button />').attr('onclick', 'scrollToRightDiv("right_pane","right1")').text('Scroll to div1').appendTo(left_pane);

    var tree1 = document.createElement('div');
    var trees = $(tree1).appendTo(left_pane);
    tree1.id="jstree1";
    tree1.className ="jstree1";
    $.getJSON("/cloud/getTreeContent/" + course_id + "/" +name_id, function (jsondata) {
        trees.append(JSON.stringify(jsondata));
        $(function() {

            $('#jstree1').jstree({
                'core': {
                    'themes': {
                        'name': 'proton',
                        'responsive': true
                    }
                },
                "state" : { "key" : "state_demo" },
                "plugins" : ["themes","state","ui"]
            });
            $('#left_pane').css('width',$('#myContainer').find('.jstree-anchor').width()+50);
            $('#right_pane').css('width',container_div.width()-$('#left_pane').width());
            $('.vsplitter').css('left',$('#myContainer').find('.jstree-anchor').width()+50);
        });
    });

    $('<button class="btn_create_lab" name="New lab" value="lab_new">Add New Lab</button><br />').appendTo(left_pane);

    //var right_html = '<div id="right1" style="height: 200px;">div1 Right: Splitter options and markup can modify splitter behavior, such as the style of the splitbar and whether the splitbar dynamically follows the mouse. By setting size limits on the two panes, you can prevent the splitter from making one of the panes too big or small. To move the splitbar position programmatically</div>'
    //    + '<div id="right2" style="height: 200px;">div2 Right: Splitter options and markup can modify splitter behavior, such as the style of the splitbar and whether the splitbar dynamically follows the mouse. By setting size limits on the two panes, you can prevent the splitter from making one of the panes too big or small. To move the splitbar position programmatically</div>'
    //    + '<div id="right3" style="height: 200px;">div3 Right: Splitter options and markup can modify splitter behavior, such as the style of the splitbar and whether the splitbar dynamically follows the mouse. By setting size limits on the two panes, you can prevent the splitter from making one of the panes too big or small. To move the splitbar position programmatically</div>'
    //    + '<div id="right4" style="height: 200px;">div4 Right: Splitter options and markup can modify splitter behavior, such as the style of the splitbar and whether the splitbar dynamically follows the mouse. By setting size limits on the two panes, you can prevent the splitter from making one of the panes too big or small. To move the splitbar position programmatically</div>'
    //    + '<div id="right5" style="height: 200px;">div5 Right: Splitter options and markup can modify splitter behavior, such as the style of the splitbar and whether the splitbar dynamically follows the mouse. By setting size limits on the two panes, you can prevent the splitter from making one of the panes too big or small. To move the splitbar position programmatically</div>';
    //right_pane.html(right_html);

    $.getJSON("/cloud/getLeaf/" + course_id + "/" + name_id, function (jsondata) {
        console.log(jsondata);
        $('#collapseLabContent').empty();

        $(jsondata).appendTo(right_pane);


        //right_pane.html(jsondata);
    });

    $('#myContainer').split({
        orientation: 'vertical',
        limit: 100,
        position: '50%'
    });

    //var touchSplitter1 = $('.split-me').touchSplit({leftMax:300, leftMin:100, dock:"left"});
    //touchSplitter1.getFirst().touchSplit({orientation:"vertical"})
}

function populate_content_viewopenlab(winId, win_main) {
    win_main.css('overflow','hidden');
    win_main.parent().css('overflow','hidden');
    var pieces =winId.split('_');
    var project = winId.substring('window_open_lab_'.length);
    var course_id='';
    var name_id='';
    if(pieces[pieces.length-2] == '2015'||pieces[pieces.length-2] == '2014') {
        if(pieces.length==7){
            course_id = pieces[pieces.length-4] + '_' + pieces[pieces.length-3];
        }else{
         course_id = pieces[pieces.length - 3];
        if (course_id.substring(0, 3) == 'SSC' && course_id.length != 3) {
            course_id = course_id.substring(0, 3) + '_' + course_id.substring(3);
        }

        if (course_id.substring(0, 3) == 'SOD' && course_id.length != 3) {
            course_id = course_id.substring(0, 3) + '_' + course_id.substring(3);
        }

        if (course_id.substring(0, 3) == 'CNS' && course_id.length != 3) {
            course_id = course_id.substring(0, 3) + '_' + course_id.substring(3);
        }

        if (course_id.substring(0, 3) == 'CNE' && course_id.length != 3) {
            course_id = course_id.substring(0, 3) + '_' + course_id.substring(3);
        }

        if (course_id.substring(0, 3) == 'SWS' && course_id.length != 3) {
            course_id = course_id.substring(0, 3) + '_' + course_id.substring(3);
        }

        if (course_id.substring(0, 3) == 'CCL' && course_id.length != 3) {
            course_id = course_id.substring(0, 3) + '_' + course_id.substring(3);
        }
        }

         name_id = pieces[pieces.length-2]+"_T1";
    }else{
        course_id= pieces[pieces.length-2];
        name_id = pieces[pieces.length-1];
    }
    var clientHeight =$(window).height()-158;

    //var iframe_console = $(document.createElement('iframe')).appendTo($('#opendaylight'));
    //iframe_console.attr("name", "opendaylight_frame").attr("width", "100%").attr("style", "height: 100em")
    //    .attr("src", "https://www.google.com/search?q=%http://sdncontroller.mobisphere.asu.edu/index.html&btnI=Im+Feeling+Lucky");

    var container_div = $('<div />').attr('id', 'myContainer'+project).appendTo(win_main);

    var left_pane = $('<div />').attr('id', 'left_pane'+project).css('position', 'relative').css('min-width', '200px').css('height', clientHeight).appendTo(container_div);
    var right_pane = $('<div />').attr('id', 'right_pane'+project).css('top', '0px').css('height', clientHeight).css('margin', '0px').appendTo(container_div);
    //$('<button />').attr('onclick', 'scrollToRightDiv("right_pane","right1")').text('Scroll to div1').appendTo(left_pane);

    var tree1 = document.createElement('div');
    var trees = $(tree1).appendTo(left_pane);
    tree1.id="jstree"+project;
    tree1.className ="jstree"+project;
    $.getJSON("/cloud/getOpenTreeContent/" + course_id + "/" +name_id, function (jsondata) {
        trees.append(JSON.stringify(jsondata));
        $(function() {

            $('#jstree'+project).jstree({
                'core': {
                    'themes': {
                        'name': 'proton',
                        'responsive': true
                    }
                },
                "state" : { "key" : "state_demo" },
                "plugins" : ["themes","state","ui"]
            });
            $('#left_pane'+project).css('width',$('#myContainer'+project).find('.jstree-anchor').width()+50);
            $('#right_pane'+project).css('width',container_div.width()-$('#left_pane'+project).width()-10);
            $('.vsplitter').css('left',$('#myContainer'+project).find('.jstree-anchor').width()+50);
            var winheight = win_main.parent().height();
            $('#left_pane'+project).css('height',winheight);
            $('#right_pane'+project).css('height',winheight);
            $('.vsplitter').css('height',winheight);

        });
    });
    //$('<button class="btn_create_lab" name="New lab" value="lab_new">Add New Lab</button><br />').appendTo(left_pane);

    //var right_html = '<div id="right1" style="height: 200px;">div1 Right: Splitter options and markup can modify splitter behavior, such as the style of the splitbar and whether the splitbar dynamically follows the mouse. By setting size limits on the two panes, you can prevent the splitter from making one of the panes too big or small. To move the splitbar position programmatically</div>'
    //    + '<div id="right2" style="height: 200px;">div2 Right: Splitter options and markup can modify splitter behavior, such as the style of the splitbar and whether the splitbar dynamically follows the mouse. By setting size limits on the two panes, you can prevent the splitter from making one of the panes too big or small. To move the splitbar position programmatically</div>'
    //    + '<div id="right3" style="height: 200px;">div3 Right: Splitter options and markup can modify splitter behavior, such as the style of the splitbar and whether the splitbar dynamically follows the mouse. By setting size limits on the two panes, you can prevent the splitter from making one of the panes too big or small. To move the splitbar position programmatically</div>'
    //    + '<div id="right4" style="height: 200px;">div4 Right: Splitter options and markup can modify splitter behavior, such as the style of the splitbar and whether the splitbar dynamically follows the mouse. By setting size limits on the two panes, you can prevent the splitter from making one of the panes too big or small. To move the splitbar position programmatically</div>'
    //    + '<div id="right5" style="height: 200px;">div5 Right: Splitter options and markup can modify splitter behavior, such as the style of the splitbar and whether the splitbar dynamically follows the mouse. By setting size limits on the two panes, you can prevent the splitter from making one of the panes too big or small. To move the splitbar position programmatically</div>';
    //right_pane.html(right_html);

    $.getJSON("/cloud/getLeaf/" + course_id + "/" + name_id, function (jsondata) {
        console.log(jsondata);
        $('#collapseLabContent').empty();

        $(jsondata).appendTo(right_pane);


        //right_pane.html(jsondata);
    })

    $('#myContainer'+project).split({
        orientation: 'vertical',
        limit: 100,
        position: '50%'
    });

    //var touchSplitter1 = $('.split-me').touchSplit({leftMax:300, leftMin:100, dock:"left"});
    //touchSplitter1.getFirst().touchSplit({orientation:"vertical"})
}

function populate_content_viewownlab(winId, win_main) {
    win_main.css('overflow','hidden');
    win_main.parent().css('overflow','hidden');
    var pieces =winId.split('_');
    var project = winId.substring('window_open_lab_'.length);
    var course_id='';
    var name_id='';
    if(pieces[pieces.length-2] == '2015' ||pieces[pieces.length-2] == '2014') {
        course_id = pieces[pieces.length - 3];
        if (course_id.substring(0, 3) == 'SSC' && course_id.length != 3) {
            course_id = course_id.substring(0, 3) + '_' + course_id.substring(3);
        }
        ;
        if (course_id.substring(0, 3) == 'SOD' && course_id.length != 3) {
            course_id = course_id.substring(0, 3) + '_' + course_id.substring(3);
        }
        ;
        if (course_id.substring(0, 3) == 'CNS' && course_id.length != 3) {
            course_id = course_id.substring(0, 3) + '_' + course_id.substring(3);
        }
        ;
        if (course_id.substring(0, 3) == 'CNE' && course_id.length != 3) {
            course_id = course_id.substring(0, 3) + '_' + course_id.substring(3);
        }
        ;
        if (course_id.substring(0, 3) == 'SWS' && course_id.length != 3) {
            course_id = course_id.substring(0, 3) + '_' + course_id.substring(3);
        }
        ;

        name_id = "2015_T1";
    }else{
        course_id= pieces[pieces.length-2];
        name_id = pieces[pieces.length-1];
    }
    var clientHeight =$(window).height()-158;

    //var iframe_console = $(document.createElement('iframe')).appendTo($('#opendaylight'));
    //iframe_console.attr("name", "opendaylight_frame").attr("width", "100%").attr("style", "height: 100em")
    //    .attr("src", "https://www.google.com/search?q=%http://sdncontroller.mobisphere.asu.edu/index.html&btnI=Im+Feeling+Lucky");

    var container_div = $('<div />').attr('id', 'myContainer'+project).appendTo(win_main);

    var left_pane = $('<div />').attr('id', 'left_pane'+project).css('position', 'relative').css('min-width', '200px').css('height', clientHeight).appendTo(container_div);
    var right_pane = $('<div />').attr('id', 'right_pane'+project).css('top', '0px').css('height', clientHeight).css('margin', '0px').appendTo(container_div);
    //$('<button />').attr('onclick', 'scrollToRightDiv("right_pane","right1")').text('Scroll to div1').appendTo(left_pane);

    var tree1 = document.createElement('div');
    var trees = $(tree1).appendTo(left_pane);
    tree1.id="jstree"+project;
    tree1.className ="jstree"+project;
    $.getJSON("/cloud/getOpenTreeContent/" + course_id + "/" +name_id, function (jsondata) {
        trees.append(JSON.stringify(jsondata));
        $(function() {

            $('#jstree'+project).jstree({
                'core': {
                    'themes': {
                        'name': 'proton',
                        'responsive': true
                    }
                },
                "state" : { "key" : "state_demo" },
                "plugins" : ["themes","state","ui"]
            });
            $('#left_pane'+project).css('width',$('#myContainer'+project).find('.jstree-anchor').width()+50);
            $('#right_pane'+project).css('width',container_div.width()-$('#left_pane'+project).width()-10);
            $('.vsplitter').css('left',$('#myContainer'+project).find('.jstree-anchor').width()+50);
            var winheight = win_main.parent().height();
            $('#left_pane'+project).css('height',winheight);
            $('#right_pane'+project).css('height',winheight);
            $('.vsplitter').css('height',winheight);

        });
    });
    //$('<button class="btn_create_lab" name="New lab" value="lab_new">Add New Lab</button><br />').appendTo(left_pane);

    //var right_html = '<div id="right1" style="height: 200px;">div1 Right: Splitter options and markup can modify splitter behavior, such as the style of the splitbar and whether the splitbar dynamically follows the mouse. By setting size limits on the two panes, you can prevent the splitter from making one of the panes too big or small. To move the splitbar position programmatically</div>'
    //    + '<div id="right2" style="height: 200px;">div2 Right: Splitter options and markup can modify splitter behavior, such as the style of the splitbar and whether the splitbar dynamically follows the mouse. By setting size limits on the two panes, you can prevent the splitter from making one of the panes too big or small. To move the splitbar position programmatically</div>'
    //    + '<div id="right3" style="height: 200px;">div3 Right: Splitter options and markup can modify splitter behavior, such as the style of the splitbar and whether the splitbar dynamically follows the mouse. By setting size limits on the two panes, you can prevent the splitter from making one of the panes too big or small. To move the splitbar position programmatically</div>'
    //    + '<div id="right4" style="height: 200px;">div4 Right: Splitter options and markup can modify splitter behavior, such as the style of the splitbar and whether the splitbar dynamically follows the mouse. By setting size limits on the two panes, you can prevent the splitter from making one of the panes too big or small. To move the splitbar position programmatically</div>'
    //    + '<div id="right5" style="height: 200px;">div5 Right: Splitter options and markup can modify splitter behavior, such as the style of the splitbar and whether the splitbar dynamically follows the mouse. By setting size limits on the two panes, you can prevent the splitter from making one of the panes too big or small. To move the splitbar position programmatically</div>';
    //right_pane.html(right_html);

    $.getJSON("/cloud/getLeaf/" + course_id + "/" + name_id, function (jsondata) {
        console.log(jsondata);
        $('#collapseLabContent').empty();

        $(jsondata).appendTo(right_pane);


        //right_pane.html(jsondata);
    })

    $('#myContainer'+project).split({
        orientation: 'vertical',
        limit: 100,
        position: '50%'
    });

    //var touchSplitter1 = $('.split-me').touchSplit({leftMax:300, leftMin:100, dock:"left"});
    //touchSplitter1.getFirst().touchSplit({orientation:"vertical"})
}


function scrollToRightDiv(container_id, div_id) {
    var topDiff = $('#'+div_id).position().top - $('#'+container_id).position().top + $('#'+container_id).scrollTop()+10;
    $('#'+container_id).animate({
        scrollTop: topDiff //$('#'+div_id).offset().top
    }, 200);
}