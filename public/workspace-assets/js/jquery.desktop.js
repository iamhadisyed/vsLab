//
// Namespace - Module Pattern.
//
var JQD = (function($, window, document, undefined) {
  // Expose innards of JQD.
  return {
    go: function() {
      for (var i in JQD.init) {
        JQD.init[i]();
      }
    },
    init: {
      frame_breaker: function() {
        if (window.location !== window.top.location) {
          window.top.location = window.location;
        }
      },
      //
      // Initialize the clock.
      //
      clock: function() {
        var clock = $('#clock');

        if (!clock.length) {
          return;
        }

        // Date variables.
        var date_obj = new Date();
        var hour = date_obj.getHours();
        var minute = date_obj.getMinutes();
        var day = date_obj.getDate();
        var year = date_obj.getFullYear();
        var suffix = 'AM';

        // Array for weekday.
        var weekday = [
          'Sunday',
          'Monday',
          'Tuesday',
          'Wednesday',
          'Thursday',
          'Friday',
          'Saturday'
        ];

        // Array for month.
        var month = [
          'January',
          'February',
          'March',
          'April',
          'May',
          'June',
          'July',
          'August',
          'September',
          'October',
          'November',
          'December'
        ];

        // Assign weekday, month, date, year.
        weekday = weekday[date_obj.getDay()];
        month = month[date_obj.getMonth()];

        // AM or PM?
        if (hour >= 12) {
          suffix = 'PM';
        }

        // Convert to 12-hour.
        if (hour > 12) {
          hour = hour - 12;
        }
        else if (hour === 0) {
          // Display 12:XX instead of 0:XX.
          hour = 12;
        }

        // Leading zero, if needed.
        if (minute < 10) {
          minute = '0' + minute;
        }

        // Build two HTML strings.
        var clock_time = weekday + ' ' + hour + ':' + minute + ' ' + suffix;
        var clock_date = month + ' ' + day + ', ' + year;

        // Shove in the HTML.
        clock.html(clock_time).attr('title', clock_date);

        // Update every 60 seconds.
        setTimeout(JQD.init.clock, 60000);
      },
      //
      // Initialize the desktop.
      //
      desktop: function() {
        // Alias to document.
        var d = $(document);

        // Cancel mousedown.
        d.mousedown(function(ev) {
          var tags = [
            'a',
            'button',
            'input',
            'select',
            'textarea',
            'tr'
          ].join(',');

          if (!$(ev.target).closest(tags).length) {
            JQD.util.clear_active();
            ev.preventDefault();
            ev.stopPropagation();
          }
        });

        //d.on('click', 'input', function() {
        //  alert('my input clck');
        //});

        // Cancel right-click.
        d.on('contextmenu', function() {
          return false;
        });

        d.on('click', 'button.btn-tooltip.get-console', function() {
            var winId= create_a_window('Console: ' + $(this).attr('name'), $(this).attr('value'), ICON_computer_sm, ICON_computer_sm);
            //var x = $(this).attr('href');
            //var y = $(x).find('a').attr('href');
            var x = '#' + winId.icon_dock_id;
            var y = '#' + winId.win_div_id;

            // Show the taskbar button.
            if ($(x).is(':hidden')) {
                $(x).remove().appendTo('#dock');
                $(x).show('fast');
            }

            // Bring window to front.
            JQD.util.window_flat();
            $(y).addClass('window_stack').show();
        });

        d.on('click', 'button.btn_create_temp', function() {
          var winId= create_a_window($(this).attr('name'), $(this).attr('value'), ICON_computer_sm, ICON_computer_sm);
          //var x = $(this).attr('href');
          //var y = $(x).find('a').attr('href');
          var x = '#' + winId.icon_dock_id;
          var y = '#' + winId.win_div_id;

          // Show the taskbar button.
          if ($(x).is(':hidden')) {
            $(x).remove().appendTo('#dock');
            $(x).show('fast');
          }

          // Bring window to front.
          JQD.util.window_flat();
          $(y).addClass('window_stack').show();
        });

          d.on('click', 'button.btn_lab_studio', function() {
              var winId= create_a_window($(this).attr('name'), $(this).attr('value'), ICON_computer_sm, ICON_computer_sm);
              //var x = $(this).attr('href');
              //var y = $(x).find('a').attr('href');
              var x = '#' + winId.icon_dock_id;
              var y = '#' + winId.win_div_id;

              // Show the taskbar button.
              if ($(x).is(':hidden')) {
                  $(x).remove().appendTo('#dock');
                  $(x).show('fast');
              }

              // Bring window to front.
              JQD.util.window_flat();
              $(y).addClass('window_stack').show();
          });

          d.on('click', 'button.btn_create_lab', function() {
              var winId= create_a_window($(this).attr('name'), $(this).attr('value'), ICON_computer_sm, ICON_computer_sm);
              //var x = $(this).attr('href');
              //var y = $(x).find('a').attr('href');
              var x = '#' + winId.icon_dock_id;
              var y = '#' + winId.win_div_id;

              // Show the taskbar button.
              if ($(x).is(':hidden')) {
                  $(x).remove().appendTo('#dock');
                  $(x).show('fast');
              }

              // Bring window to front.
              JQD.util.window_flat();
              $(y).addClass('window_stack').show();
          });

          d.on('click', 'button.btn_show_lab_topo', function() {
              var winId= create_a_window($(this).attr('name'), $(this).attr('value'), ICON_computer_sm, ICON_computer_sm);
              //var x = $(this).attr('href');
              //var y = $(x).find('a').attr('href');
              var x = '#' + winId.icon_dock_id;
              var y = '#' + winId.win_div_id;

              // Show the taskbar button.
              if ($(x).is(':hidden')) {
                  $(x).remove().appendTo('#dock');
                  $(x).show('fast');
              }

              // Bring window to front.
              JQD.util.window_flat();
              $(y).addClass('window_stack').show();
          });

          d.on('click', 'a.btn_view_open_lab', function() {
              var winId= create_a_window($(this).attr('name'), $(this).attr('value'), ICON_computer_sm, ICON_computer_sm);
              //var x = $(this).attr('href');
              //var y = $(x).find('a').attr('href');
              var x = '#' + winId.icon_dock_id;
              var y = '#' + winId.win_div_id;

              // Show the taskbar button.
              if ($(x).is(':hidden')) {
                  $(x).remove().appendTo('#dock');
                  $(x).show('fast');
              }

              // Bring window to front.
              JQD.util.window_flat();
              $(y).addClass('window_stack').show();
          });

          d.on('click', 'a.ownclass-view', function() {
              var winId= create_a_window($(this).attr('name'), $(this).attr('value'), ICON_computer_sm, ICON_computer_sm);
              //var x = $(this).attr('href');
              //var y = $(x).find('a').attr('href');
              var x = '#' + winId.icon_dock_id;
              var y = '#' + winId.win_div_id;

              // Show the taskbar button.
              if ($(x).is(':hidden')) {
                  $(x).remove().appendTo('#dock');
                  $(x).show('fast');
              }

              // Bring window to front.
              JQD.util.window_flat();
              $(y).addClass('window_stack').show();
          });

          d.on('click', 'a.labDesign-view', function() {
              var winId= create_a_window($(this).attr('name'), $(this).attr('value'), ICON_computer_sm, ICON_computer_sm);
              //var x = $(this).attr('href');
              //var y = $(x).find('a').attr('href');
              var x = '#' + winId.icon_dock_id;
              var y = '#' + winId.win_div_id;

              // Show the taskbar button.
              if ($(x).is(':hidden')) {
                  $(x).remove().appendTo('#dock');
                  $(x).show('fast');
              }

              // Bring window to front.
              JQD.util.window_flat();
              $(y).addClass('window_stack').show();
          });

          d.on('click', 'a.proj-view', function() {
              var winId= create_a_window($(this).attr('name'), $(this).attr('value'), ICON_computer_sm, ICON_computer_sm);
              //var x = $(this).attr('href');
              //var y = $(x).find('a').attr('href');
              var x = '#' + winId.icon_dock_id;
              var y = '#' + winId.win_div_id;

              // Show the taskbar button.
              if ($(x).is(':hidden')) {
                  $(x).remove().appendTo('#dock');
                  $(x).show('fast');
              }

              // Bring window to front.
              JQD.util.window_flat();
              $(y).addClass('window_stack').show();
          });


        d.on('click', 'a.temp_edit', function() {
          var winId= create_a_window_for_dropdown($(this).attr('name'), $(this).attr('value'), ICON_computer_sm, ICON_computer_sm);
          //var x = $(this).attr('href');
          //var y = $(x).find('a').attr('href');
          var x = '#' + winId.icon_dock_id;
          var y = '#' + winId.win_div_id;

          // Show the taskbar button.
          if ($(x).is(':hidden')) {
            $(x).remove().appendTo('#dock');
            $(x).show('fast');
          }

          // Bring window to front.
          JQD.util.window_flat();
          $(y).addClass('window_stack').show();

            //workspace_window_resize($(y));

        });

          d.on('click', 'a.workinglab_topo', function() {
              var winId= create_a_window_for_dropdown($(this).attr('name'), $(this).attr('value'), ICON_computer_sm, ICON_computer_sm);
              //var x = $(this).attr('href');
              //var y = $(x).find('a').attr('href');
              var x = '#' + winId.icon_dock_id;
              var y = '#' + winId.win_div_id;

              // Show the taskbar button.
              if ($(x).is(':hidden')) {
                  $(x).remove().appendTo('#dock');
                  $(x).show('fast');
              }

              // Bring window to front.
              JQD.util.window_flat();
              $(y).addClass('window_stack').show();

              //workspace_window_resize($(y));

          });

          d.on('click', 'a.workinglab-content', function() {
              var winId= create_a_window_for_dropdown($(this).attr('name'), $(this).attr('value'), ICON_computer_sm, ICON_computer_sm);
              //var x = $(this).attr('href');
              //var y = $(x).find('a').attr('href');
              var x = '#' + winId.icon_dock_id;
              var y = '#' + winId.win_div_id;

              // Show the taskbar button.
              if ($(x).is(':hidden')) {
                  $(x).remove().appendTo('#dock');
                  $(x).show('fast');
              }

              // Bring window to front.
              JQD.util.window_flat();
              $(y).addClass('window_stack').show();

              //workspace_window_resize($(y));

          });

          d.on('click', 'a.ownclass-edit', function() {
              var winId= create_a_window_for_dropdown($(this).attr('name'), $(this).attr('value'), ICON_computer_sm, ICON_computer_sm);
              //var x = $(this).attr('href');
              //var y = $(x).find('a').attr('href');
              var x = '#' + winId.icon_dock_id;
              var y = '#' + winId.win_div_id;

              // Show the taskbar button.
              if ($(x).is(':hidden')) {
                  $(x).remove().appendTo('#dock');
                  $(x).show('fast');
              }

              // Bring window to front.
              JQD.util.window_flat();
              $(y).addClass('window_stack').show();

              //workspace_window_resize($(y));

          });

          d.on('click', 'a.lab-view', function() {
              var winId= create_a_window_for_dropdown($(this).attr('name'), $(this).attr('value'), ICON_computer_sm, ICON_computer_sm);
              //var x = $(this).attr('href');
              //var y = $(x).find('a').attr('href');
              var x = '#' + winId.icon_dock_id;
              var y = '#' + winId.win_div_id;

              // Show the taskbar button.
              if ($(x).is(':hidden')) {
                  $(x).remove().appendTo('#dock');
                  $(x).show('fast');
              }

              // Bring window to front.
              JQD.util.window_flat();
              $(y).addClass('window_stack').show();
          });

          d.on('click', 'a.sub_group', function() {
        //d.on('mousedown', 'a.sub_group', function() {
          var winId= create_a_window($(this).attr('name'), $(this).attr('value'), ICON_win_profile, ICON_win_profile);
          //var x = $(this).attr('href');
          //var y = $(x).find('a').attr('href');
          var x = '#' + winId.icon_dock_id;
          var y = '#' + winId.win_div_id;

          // Show the taskbar button.
          if ($(x).is(':hidden')) {
            $(x).remove().appendTo('#dock');
            $(x).show('fast');
          }

          // Bring window to front.
          JQD.util.window_flat();
          $(y).addClass('window_stack').show();
          //workspace_window_resize($(y));
        });

          d.on('click', 'a.workspace-help', function () {
              if ($('#window_workspace-help').length <= 0) {
                  var winId = create_a_window($(this).attr('name'), 'workspace-help', ICON_computer_sm, ICON_computer_sm);
                  //var x = $(this).attr('href');
                  //var y = $(x).find('a').attr('href');
                  var x = '#' + winId.icon_dock_id;
                  var y = '#' + winId.win_div_id;

                  // Show the taskbar button.
                  if ($(x).is(':hidden')) {
                      $(x).remove().appendTo('#dock');
                      $(x).show('fast');
                  }
              }
                  // Bring window to front.
                  JQD.util.window_flat();
                  $('#window_workspace-help').addClass('window_stack').show();
                  //workspace_window_resize($(y));

              load_workspace_help($(this).attr('value'));
          });

          d.on('click', 'a.edit_group_lab', function() {
              var winId= create_a_window($(this).attr('name'), $(this).attr('value'), ICON_win_profile, ICON_win_profile);
              //var x = $(this).attr('href');
              //var y = $(x).find('a').attr('href');
              var x = '#' + winId.icon_dock_id;
              var y = '#' + winId.win_div_id;

              // Show the taskbar button.
              if ($(x).is(':hidden')) {
                  $(x).remove().appendTo('#dock');
                  $(x).show('fast');
              }

              // Bring window to front.
              JQD.util.window_flat();
              $(y).addClass('window_stack').show();
              //workspace_window_resize($(y));
          });

        d.on('click', 'button', function() {
          button_click($(this));
        });

        d.on('change', 'select', function() {
          select_change($(this));
        });

        // Relative or remote links?
        d.on('click', 'a', function(ev) {
          var url = $(this).attr('href');
          this.blur();

          if (url.match(/^#/)) {
            ev.preventDefault();
            ev.stopPropagation();
          }
          else {
            //$(this).attr('target', '_blank');
          }
        });

        d.on('click', 'a.wallpaper_selection', function() {
            set_wallpaper($(this), $(this).attr('name'));
        });

        // Make top menus active.
        //d.on('mousedown', 'a.menu_trigger', function() {
        //  if ($(this).next('ul.menu').is(':hidden')) {
        //    JQD.util.clear_active();
        //    $(this).addClass('active').next('ul.menu').show();
        //  }
        //  else {
        //    JQD.util.clear_active();
        //  }
        //});

        // Transfer focus, if already open.
        d.on('mouseenter', 'a.menu_trigger', function() {
          //if ($('ul.menu').is(':visible')) {
            JQD.util.clear_active();
            $(this).addClass('active').next('ul.menu').show();
          //}
        });

          //d.on('mouseout', 'a.menu_trigger', function() {
          //    if ($('ul.menu').is(':visible')) {
          //        //JQD.util.clear_active();
          //        $(this).addClass('active').next('ul.menu').hide();
          //        $(this).removeClass('active');
          //    }
          //});

        // Cancel single-click.
        d.on('mousedown', 'a.icon', function() {
          // Highlight the icon.
          JQD.util.clear_active();
          $(this).addClass('active');
        });

        // Respond to double-click.
        d.on('dblclick', 'a.icon', function(ev) {

            ev.preventDefault();
          // Get the link's target.
          var x = $(this).attr('href');
          var icon_type = x.substring('#icon_dock_'.length);
          var icon = ICON_win_cloud;
          switch (icon_type) {
              case 'projectlist':
                  icon = ICON_win_cloud;
                  break;
              case 'profile':
                  icon = ICON_settings;
                  break;
              case 'group':
                  icon = ICON_group;
                  break;
              case 'conceptmap':
                  icon = ICON_concept;
                  break;
              case 'studio':
                  icon = ICON_labdesign;
                  //window.open('https://edxstudio.mobicloud.asu.edu/signin');
                  break;
            case 'owncloud':
              icon = ICON_owncloud;
              break;
            case 'clients':
              icon = ICON_owncloud;
              break;
              case 'mylabs':
                  icon = ICON_mylabs;
                  break;
              case 'labdesign':
                  icon = ICON_labdesign;
                  break;
              case 'labmanagement':
                  icon = ICON_labmanagement;
                  break;
              case 'webrtc':
                  icon = ICON_video;
                  break;
              case 'openlabs':
                  icon = ICON_openlabs;
                  break;
              case 'help':
                  icon = ICON_help;
                  break;
              case 'admin':
                  icon = ICON_admin;
                  break;

          }
          // dynamically create a window instead of static window, changed by James
          var winId = create_a_window($(this).text(), x.substring('#icon_dock_'.length), icon, icon);
          //var y = $(x).find('a').attr('href');
          var y = "#" + winId.win_div_id;

          // add a new function to handle the content for each active window by James.
          //load_window_content(y); <-- move to workspace.js

          // Show the taskbar button.
          if ($(x).is(':hidden')) {
            $(x).remove().appendTo('#dock');
            $(x).show('fast');
          }

          // Bring window to front.
          JQD.util.window_flat();
          $(y).addClass('window_stack').show();

            if (icon_type == 'webrtc') {
                var x = '#icon_dock_videowebrtc'; // defined in workspace.blade.php
                var winId = create_a_window('Video', x.substring('#icon_dock_'.length), icon, icon);
                //var y = $(x).find('a').attr('href');
                var y = "#" + winId.win_div_id;

                // add a new function to handle the content for each active window by James.
                //load_window_content(y); <-- move to workspace.js

                // Show the taskbar button.
                if ($(x).is(':hidden')) {
                    $(x).remove().appendTo('#dock');
                    $(x).show('fast');
                }

                // Bring window to front.
                JQD.util.window_flat();
                $(y).addClass('window_stack').show();
            }
        });

        // Make icons draggable.
        d.on('mouseenter', 'a.icon', function() {
          $(this).off('mouseenter').draggable({
            revert: true,
            containment: 'parent'
          });
        });

        // Taskbar buttons.
        d.on('click', '#dock a', function() {
          // Get the link's target.
          var x = $($(this).attr('href'));

          // Hide, if visible.
          if (x.is(':visible')) {
            x.hide();
          }
          else {
            // Bring window to front.
            JQD.util.window_flat();
            x.show().addClass('window_stack');
          }
        });

        // Focus active window.
        d.on('mousedown', 'div.window', function() {
          // Bring window to front.
          JQD.util.window_flat();
          $(this).addClass('window_stack');
        });

        // Make windows draggable.
        d.on('mouseenter', 'div.window', function() {
          $(this).off('mouseenter').draggable({
            // Confine to desktop.
            // Movable via top bar only.
            cancel: 'a',
            containment: 'parent',
            handle: 'div.window_top'
          }).resizable({
            containment: 'parent',
            minWidth: 400,
            minHeight: 200,
              resize: function(event, ui) {
                  workspace_window_resize($(this));
              }
          });
        });

        // Double-click top bar to resize, ala Windows OS.
        d.on('dblclick', 'div.window_top', function() {
          JQD.util.window_resize(this);
        });

        // Double click top bar icon to close, ala Windows OS.
        d.on('dblclick', 'div.window_top img', function() {
          // Traverse to the close button, and hide its taskbar button.
          $($(this).closest('div.window_top').find('a.window_close').attr('href')).hide('fast');

          // Close the window itself.
          $(this).closest('div.window').hide();

          // Stop propagation to window's top bar.
          return false;
        });

        // Minimize the window.
        d.on('click', 'a.window_min', function() {
          $(this).closest('div.window').hide();
        });

        // Maximize or restore the window.
        d.on('click', 'a.window_resize', function() {
          JQD.util.window_resize(this);
        });

        // Close the window.
        d.on('click', 'a.window_close', function() {
          //$(this).closest('div.window').hide();
          //$($(this).attr('href')).hide('fast');
          // Remove the window resources after close instead of hiding them, changed by James
          $('#' + $(this).closest('div.window').attr('id')).remove();
          $('#' + $($(this).attr('href')).attr('id')).remove();
        });

        // Show desktop button, ala Windows OS.
        d.on('mousedown', '#show_desktop', function() {
          // If any windows are visible, hide all.
          if ($('div.window:visible').length) {
            $('div.window').hide();
          }
          else {
            // Otherwise, reveal hidden windows that are open.
            $('#dock li:visible a').each(function() {
              $($(this).attr('href')).show();
            });
          }
        });

        d.on('mousedown', 'a.tab', function() {
          tab_click($(this));
        });

        $('table.data').each(function() {
          // Add zebra striping, ala Mac OS X.
          $(this).find('tbody tr:odd').addClass('zebra');
        });

          //d.on('mousedown', 'table.data thead tr th', function() {
          //   var col = $(this).prevAll().length;
          //   var sorting = [col, 1];
          //    $(this).closest('table').trigger('sorton', [sorting]);
          //});

        d.on('mousedown', 'table.data tbody tr', function() {
          // Clear active state.
          JQD.util.clear_active();

          // Highlight row, ala Mac OS X.
          $(this).closest('tr').addClass('active');
        });

        d.on('mousedown', 'a.proj-actionButton', function() {
          dropdown_actions($(this));
        });

          d.on('mousedown', 'a.sys-admin-proj-actionButton', function() {
              dropdown_actions($(this));
          });

          d.on('mousedown', 'a.user-admin-users-actionButton', function() {
              dropdown_actions($(this));
          });

          d.on('mousedown', 'a.group-admin-groups-actionButton', function() {
              dropdown_actions($(this));
          });

          d.on('mousedown', 'a.group-actionButton', function() {
              dropdown_actions($(this));
              //$(this).closest('tr').addClass('active');
          });
          d.on('mousedown', 'a.group2-actionButton', function() {
              dropdown_actions($(this));
              //$(this).closest('tr').addClass('active');
          });

        d.on('mousedown', 'a.labDeploy-actionButton', function() {
          dropdown_actions($(this));
          //$(this).closest('tr').addClass('active');
        });

          d.on('mousedown', 'a.labDesign-actionButton', function() {
              dropdown_actions($(this));
              //$(this).closest('tr').addClass('active');
          });

          d.on('mousedown', 'a.workinglab-actionButton', function() {
              dropdown_actions($(this));
              //$(this).closest('tr').addClass('active');
          });

        d.on('mousedown', 'a.vm-actionButton', function() {
          dropdown_actions($(this));
        });

          d.on('mousedown', 'a.ownclass-actionButton', function() {
              dropdown_actions($(this));
          });

        d.on('mousedown', 'a.temp-actionButton', function() {
          dropdown_actions($(this));
        });

        d.on('mousedown', 'a.team-actionButton', function() {
          dropdown_actions($(this));
        });

        d.on('mousedown', 'a.upload-wallpaper', function() {
          upload_wallpaper($(this));
        });

        d.on("mouseleave", 'td.open-menu', function() {
          $(this).removeClass("open-menu");
        });

        // activate the table item, created by James
        //d.on('dblclick', 'table.data tr', function() {
        //
        //  var winId= create_a_window_for_table_item($(this));
        //  //var x = $(this).attr('href');
        //  //var y = $(x).find('a').attr('href');
        //  var x = '#' + winId.icon_dock_id;
        //  var y = '#' + winId.win_div_id;
        //
        //  // Show the taskbar button.
        //  if ($(x).is(':hidden')) {
        //    $(x).remove().appendTo('#dock');
        //    $(x).show('fast');
        //  }
        //
        //  // Bring window to front.
        //  JQD.util.window_flat();
        //  $(y).addClass('window_stack').show();
        //
        //});

      },
      wallpaper: function() {
        // Add wallpaper last, to prevent blocking.
        if ($('#desktop').length) {
          //$('body').prepend('<img id="wallpaper" class="abs" src="workspace-assets/images/misc/wallpaper.jpg" />');
           $('body').prepend('<div id="bg"><img class="abs ws-wallpaper" src="" /></div>');
        }
      }
    },
    util: {
      //
      // Clear active states, hide menus.
      //
      clear_active: function() {
        $('a.active, tr.active').removeClass('active');
        $('ul.menu').hide();
      },
      //
      // Zero out window z-index.
      //
      window_flat: function() {
        $('div.window').removeClass('window_stack');
      },
      //
      // Resize modal window.
      //
      window_resize: function(el) {
        // Nearest parent window.
        var win = $(el).closest('div.window');

        // Is it maximized already?
        if (win.hasClass('window_full')) {
          // Restore window position.
          win.removeClass('window_full').css({
            'top': win.attr('data-t'),
            'left': win.attr('data-l'),
            'right': win.attr('data-r'),
            'bottom': win.attr('data-b'),
            'width': win.attr('data-w'),
            'height': win.attr('data-h')
          });
        }
        else {
          win.attr({
            // Save window position.
            'data-t': win.css('top'),
            'data-l': win.css('left'),
            'data-r': win.css('right'),
            'data-b': win.css('bottom'),
            'data-w': win.css('width'),
            'data-h': win.css('height')
          }).addClass('window_full').css({
            // Maximize dimensions.
            'top': '0',
            'left': '0',
            'right': '0',
            'bottom': '0',
            'width': '100%',
            'height': '100%'
          });
        }

        // Bring window to front.
        JQD.util.window_flat();
        win.addClass('window_stack');
          workspace_window_resize(win);
      }
    }
  };
// Pass in jQuery.
})(jQuery, this, this.document);

//
// Kick things off.
//
jQuery(document).ready(function() {
  JQD.go();
});