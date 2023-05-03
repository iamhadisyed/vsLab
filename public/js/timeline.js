       function loadusertimeline(userid) {
           $( "#timeline3" ).empty();
           if($('#timelinestarttime').val()==""){
               // var starttime=1589526000000;
               // var endtime=1593163349000;
               var endtime=new Date().valueOf();
               var starttime=endtime-2592000000;
           }else{
               var starttime=(new Date($('#timelinestarttime').val())).getTime();
               var endtime=(new Date($('#timelineendtime').val())).getTime();
           }

            var totaltime=0;
           $.post("/geteventlog", {
                   "userid": userid
               },
               function (data) {
                   // alert(JSON.stringify(data));
                   //alert("send ajax for own group list0");
                   var newlabelTestData = [{label: data[0].email, times: []}];
                   var istarttime;
                   var lastevent;
                   $.each(data, function (index,item) {
                        if(item.time*1000>=starttime){
                            if(item.type=='Session'&&item.desc=='User Logged In.'){
                                if(lastevent==='start'){
                                    if(item.time*1000-istarttime>7200000){
                                        newlabelTestData[0].times.push({"starting_time": istarttime, "ending_time": istarttime+7200000});
                                        totaltime+=7200000;
                                    }else{
                                        newlabelTestData[0].times.push({"starting_time": istarttime, "ending_time": item.time*1000});
                                        totaltime+=(item.time*1000-istarttime);
                                    }
                                }
                                istarttime=item.time*1000;
                                lastevent='login';
                            }
                            if(item.type=='LabAccess'){
                                if(lastevent==='start'){
                                    if(item.time*1000-istarttime>7200000){
                                        newlabelTestData[0].times.push({"starting_time": istarttime, "ending_time": istarttime+7200000});
                                        totaltime+=7200000;
                                    }else{
                                        newlabelTestData[0].times.push({"starting_time": istarttime, "ending_time": item.time*1000});
                                        totaltime+=(item.time*1000-istarttime);
                                    }
                                }
                                lastevent='start';
                                istarttime=item.time*1000;
                            }
                            if(item.desc=='User Logged Out.'||item.desc=='User\'s session timeout.'){
                                if((typeof istarttime !== "undefined") && lastevent==='start'){
                                    newlabelTestData[0].times.push({"starting_time": istarttime, "ending_time": item.time*1000});
                                    totaltime+=(item.time*1000-istarttime);
                                    istarttime =  item.time*1000;
                                }
                            }

                        }

                   });
                   timelineHover(starttime,endtime,newlabelTestData,totaltime)
               },
               'json'
           ).fail(function (xhr, testStatus, errorThrown) {
               // alert(xhr.responseText);
           });
       }

        var labelTestData = [
            {label: "ydeng19@asu.edu", times: [{"starting_time": 1593051562000, "display": "circle"}, {"starting_time": 1593150562000, "ending_time": 1593151562000}]},
        ];

        function timelineHover(starttime,endtime,dataset,totaltime) {
            if(window.location.href=="https://www.thothlab.com/userhome"){
                var width = document.getElementsByClassName("box-body")[0].clientWidth-50;
            }else{
                // var width = document.getElementsByClassName("content-header")[0].clientWidth-100;
                var width = 800;
            }
            var parse = d3.time.format("%c");
            var parseday = d3.time.format("%Y-%m-%d");
            var chart = d3.timeline()
                .beginning(starttime) // we can optionally add beginning and ending times to speed up rendering a little
                .ending(endtime)
                .width(width)
                .stack()
                .margin({left:100, right:30, top:0, bottom:0})
                .hover(function (d, i, datum) {
                    // d is the current rendering object
                    // i is the index during d3 rendering
                    // datum is the id object
                    var div = $('#hoverRes');
                    var colors = chart.colors();

                    var starting_time = parse(new Date(d.starting_time));
                    var ending_time=parse(new Date(d.ending_time));
                    div.find('.coloredDiv').css('background-color', colors(i));
                    div.find('#name').text('From '+starting_time+' to '+ending_time);
                })
                .tickFormat( //
                    {format: d3.time.format("%d %b"),
                        tickTime: d3.time.days,
                        tickInterval: 2,
                        tickSize: 30})
                .click(function (d, i, datum) {
                    var starting_time = parse(new Date(d.starting_time));
                    var ending_time=parse(new Date(d.ending_time));
                    alert('From '+starting_time+' to '+ending_time);
                })
                .scroll(function (x, scale) {
                    $("#scrolled_date").text(scale.invert(x) + " to " + scale.invert(x+width));
                });

            var svg = d3.select("#timeline3").append("svg").attr("width", width)
                .datum(dataset).call(chart);
            var seconds=totaltime/1000;
            $('#totaltime').text('Total Time Spent:'+secondsToDhms(seconds));
            // $('#startandend').text('From '+parseday(new Date(starttime))+' to '+parseday(new Date(endtime)));
            $('#timelinestarttime').val(parseday(new Date(starttime)));
            $('#timelineendtime').val(parseday(new Date(endtime)));
        }


       function secondsToDhms(seconds) {
           seconds = Number(seconds);
           var d = Math.floor(seconds / (3600*24));
           var h = Math.floor(seconds % (3600*24) / 3600);
           var m = Math.floor(seconds % 3600 / 60);
           var s = Math.floor(seconds % 60);

           var dDisplay = d > 0 ? d + (d == 1 ? " day, " : " days, ") : "";
           var hDisplay = h > 0 ? h + (h == 1 ? " hour, " : " hours, ") : "";
           var mDisplay = m > 0 ? m + (m == 1 ? " minute, " : " minutes, ") : "";
           var sDisplay = s > 0 ? s + (s == 1 ? " second" : " seconds") : "";
           return dDisplay + hDisplay + mDisplay + sDisplay;
       }