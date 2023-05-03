@extends('layout.main-no-nav')
@section('title')
  WorkSpace
@stop
@section('head_css')

<meta name="_token" content="{{ csrf_token() }}" />
<link rel="stylesheet" href="{{ URL::asset('workspace-assets/css/reset.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('workspace-assets/css/workspace.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('workspace-assets/css/desktop.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('css/modal-video.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('css/jquery-ui.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('css/tablesorter.theme.blue.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('css/tablesorter.theme.jui.css') }}" />
{{--<link rel="stylesheet" href="{{ asset('packages/jQuery-mobile/jquery.mobile-1.4.5.min.css') }}" />--}}
<link rel="stylesheet" href="{{ URL::asset('packages/vis/dist/vis.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('packages/waitMe/waitMe.min.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('workspace-assets/css/conceptmap.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('packages/jQuery-File-Upload/css/jquery.fileupload.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('packages/jstree/dist/themes/proton/style.min.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('packages/splitter/splitter.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('packages/jAlert/src/jAlert-v3.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('packages/simple-dtpicker/jquery.simple-dtpicker.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('workspace-assets/css/Intimidatetime.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('packages/feedback/feedback.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('packages/sweetalert2/sweetalert2.min.css') }}" />
{{--<link rel="stylesheet" href="{{ URL::asset('workspace-assets/css/feedback.css'); }}" />--}}
{{--<link rel="stylesheet" href="{{ URL::asset('workspace-assets/css/workspace.css'); }}" />--}}
{{--<!--[if lt IE 9]>--}}
{{--<link rel="stylesheet" href="{{ URL::asset('workspace-assets/css/ie.css'); }}" />--}}
{{--<![endif]-->--}}

@stop

@section('content')

@if(Cas::isAuthenticated())
<div class="abs" id="wrapper">
  <div class="abs" id="desktop">
    {{--<a class="abs icon" style="left:20px;top:20px;" href="#icon_dock_projectlist">--}}
      {{--<img src="{{ URL::asset('workspace-assets/images/icons/icon_32_computer.png'); }}" />--}}
      {{--<img src="{{ URL::asset('workspace-assets/images/icons/icon_64_cloud-folder.png'); }}" />--}}
      {{--My Projects--}}
    {{--</a>--}}
      {{--@if(strlen(strstr($role,'global_admin_docker_id'))>0)--}}

          <a class="abs icon" style="left:20px;top:20px;" href="#icon_dock_mylabs" id="global_labs_docker_id">
              <img src="{{ URL::asset('workspace-assets/images/icons/icon_64_hammer-folder.png') }}" />
              My Labs
          </a>
          <a class="abs icon" style="left:20px;top:100px;" href="#icon_dock_openlabs" id="global_openlabs_docker_id">
              <img src="{{ URL::asset('workspace-assets/images/icons/icon_64_openlabs-folder.png') }}" />
              Open Labs
          </a>
          <a class="abs icon" style="left:20px;top:180px;" href="#icon_dock_profile" id="global_profile_docker_id">
              <img src="{{ URL::asset('workspace-assets/images/icons/icon_64_settings-folder.png') }}" />
              Settings
          </a>

      @if(in_array('system_admin', $roles))
          <a class="abs icon" style="left:20px;top:260px;" href="#icon_dock_wordpress" id="global_wordpress_docker_id">
              <img src="{{ URL::asset('workspace-assets/images/icons/icon_64_cloud-folder.png') }}" />
              Blog
          </a>
          {{--<a class="abs icon" style="left:20px;top:340px;" href="#icon_dock_webrtc" id="global_webrtc_docker_id">--}}
              {{--<img src="{{ URL::asset('workspace-assets/images/icons/icon_64_video-folder.png'); }}" />--}}
              {{--Video Conference--}}
          {{--</a>--}}
          <a class="abs icon" style="left:20px;top:340px;" href="#icon_dock_conceptmap" id="global_conceptmap_docker_id">
              <img src="{{ URL::asset('workspace-assets/images/icons/icon_64_concept-folder.png') }}" />
              Knowledge Map
          </a>
          <a class="abs icon" style="left:20px;top:420px;" href="#icon_dock_help" id="global_help_docker_id">
              <img src="{{ URL::asset('workspace-assets/images/icons/icon_64_help-folder.png') }}" />
              Help Desk
          </a>
      @endif
      @if(in_array('system_admin', $roles) or in_array('super_user', $roles))
            {{--// col 2--}}
          <a class="abs icon" style="left:120px;top:20px;" href="#icon_dock_group" id="global_group_docker_id">
              <img src="{{ URL::asset('workspace-assets/images/icons/icon_64_group-green-folder.png') }}" />
              Group Management
          </a>
          <a class="abs icon" style="left:120px;top:100px;" href="#icon_dock_labdesign" id="global_labdesign_docker_id">
              <img src="{{ URL::asset('workspace-assets/images/icons/icon_64_labdesign_folder.png') }}" />
              Lab Design
          </a>
          <a class="abs icon" style="left:120px;top:180px;" href="#icon_dock_labmanagement" id="global_labmanagement_docker_id">
              <img src="{{ URL::asset('workspace-assets/images/icons/icon_64_labmanagement-folder.png') }}" />
              Lab Management
          </a>
      @endif
      @if(in_array('system_admin', $roles) or in_array('site_admin', $roles))
            {{--// col 3--}}
          <a class="abs icon" style="left:220px;top:20px;" href="#icon_dock_siteadmin" id="global_siteadmin_docker_id">
              <img src="{{ URL::asset('workspace-assets/images/icons/icon_64_admin.png') }}" />
              Site Resource Admin
          </a>
      @endif
      @if(in_array('system_admin', $roles))
          <a class="abs icon" style="left:220px;top:100px;" href="#icon_dock_useradmin" id="global_useradmin_docker_id">
              <img src="{{ URL::asset('workspace-assets/images/icons/icon_64_admin.png') }}" />
              User Admin
          </a>
          <a class="abs icon" style="left:220px;top:180px;" href="#icon_dock_sysadmin" id="global_sysadmin_docker_id">
              <img src="{{ URL::asset('workspace-assets/images/icons/icon_64_admin.png') }}" />
              System Admin
          </a>
          <a class="abs icon" style="left:220px;top:260px;" href="#icon_dock_sysmonitor" id="global_sysmonitor_docker_id">
              <img src="{{ URL::asset('workspace-assets/images/icons/icon_64_admin.png') }}" />
              System Monitor
          </a>

          <a class="abs icon" style="left:220px;top:340px;" href="#icon_dock_securityanalyzer" id="global_securityanalyzer_docker_id">
              <img src="{{ URL::asset('workspace-assets/images/icons/icon_64_admin.png') }}" />
              Security Analyzer
          </a>
      @endif

    {{--<a class="abs icon" style="left:20px;top:260px;" href="#icon_dock_studio">--}}
      {{--<img src="{{ URL::asset('workspace-assets/images/icons/icon_64_studio-folder.png'); }}" />--}}
      {{--Available Labs--}}
    {{--</a>--}}
    {{--<a class="abs icon" style="left:20px;top:260px;" href="#icon_dock_owncloud" id="global_owncloud_docker_id">
      <img src="{{ URL::asset('workspace-assets/images/icons/icon_64_owncloud-folder.png'); }}" />
        My Files
    </a>--}}

    {{--<a class="abs icon" style="left:20px;top:340px;" href="#icon_dock_clients">
      <img src="{{ URL::asset('workspace-assets/images/icons/icon-64-tutorial.png'); }}" />
      Tutorials
    </a>--}}

      {{--<a class="abs icon" style="left:100px;top:100px;" href="#icon_dock_lab_design">--}}
          {{--<img src="{{ URL::asset('workspace-assets/images/icons/icon_64_studio-folder.png'); }}" />--}}
          {{--Design Lab Templates--}}
      {{--</a>--}}
    {{--<a class="abs icon" style="left:20px;top:260px;" href="#icon_dock_conceptmap">--}}
      {{--<img src="{{ URL::asset('workspace-assets/images/icons/icon_64_knowledge-folder.png'); }}" />--}}
      {{--Knowledge Base--}}
    {{--</a>--}}
    {{--<a class="abs icon" style="left:20px;top:260px;" href="#icon_dock_network">--}}
      {{--<img src="{{ URL::asset('workspace-assets/images/icons/icon_32_network.png'); }}" />--}}
      {{--Network--}}
    {{--</a>--}}
  </div>

  <div class="abs" id="bar_top">
    <span class="float_right" id="clock"></span>
    <ul>
      <li>
        {{--<a class="menu" href="{{ URL::route('home') }}"><i class="glyphicon glyphicon-home"></i></a>--}}
      </li>
    </ul>
      <ul>
          <li>
              <img id="avatar_icon" height="18" width="18" src="{{{ $avatar }}}">
          </li>
      </ul>
    <ul>
      <li>
        <a class="menu_trigger" id="menu_username" href="#">{{{ $user }}}</a>
        <ul class="menu">
          <li>
            <a class="user-logout" href="{{ URL::route('user.logout') }}">Log Out</a>
          </li>
        </ul>
      </li>
    </ul>
    {{--<div id="user_all_role" style="display:none">{{{ implode(",", $roles); }}}</div>--}}
    <ul>
      <li>
          <a class="menu_trigger" href="#">Wallpaper</a>
          {{--<div id="wallpaper_select_menu" class="menu">--}}
          <ul id="wallpaper_select" class="menu">
              <li><a href="#" class="wallpaper_selection" name="default">Default&nbsp;&nbsp;</a></li>
              <li><a href="#" class="wallpaper_selection" name="beach">Beach&nbsp;&nbsp;</a></li>
              <li><a href="#" class="wallpaper_selection" name="mountain_lake">Mountain Lake&nbsp;&nbsp;</a></li>
              <li><a href="#" class="wallpaper_selection" name="apple_mac_blue">Apple Mac OSX&nbsp;&nbsp;</a></li>
              <li><a href="#" class="wallpaper_selection" name="toulouse">Toulouse&nbsp;&nbsp;</a></li>
              <li><a href="#" class="wallpaper_selection" name="cinderella">Cinderella&nbsp;&nbsp;</a></li>
              <li><a href="#" class="wallpaper_selection" name="city-building-1">City Building 1&nbsp;&nbsp;</a></li>
              <li><a href="#" class="wallpaper_selection" name="city-building-2">City Building 2&nbsp;&nbsp;</a></li>
              <li><a href="#" class="wallpaper_selection" name="city-nightview-1">City Night View 1&nbsp;&nbsp;</a></li>
              <li><a href="#" class="wallpaper_selection" name="sunset-1">Sunset 1&nbsp;&nbsp;</a></li>
              <li><a href="#" class="wallpaper_selection" name="sunset-2">Sunset 2&nbsp;&nbsp;</a></li>
              <li><a href="#" class="wallpaper_selection" name="park">Park&nbsp;&nbsp;</a></li>
              <li><a href="#" class="wallpaper_selection" name="palm-lake">Palm Lake&nbsp;&nbsp;</a></li>
              {{--<li><a href="#" class="upload-file" onclick="upload_file('wallpaper'); ">Upload Wallpaper</a></li>--}}
          </ul>
              {{--</div>--}}
      </li>
        <li>
            <a class="menu_trigger" href="#">Help</a>
            <ul class="menu">
                <li><a href="#" class="workspace-help" name="Help" value="my-lab">My Lab</a></li>
                <li><a href="#" class="workspace-help" name="Help" value="open-labs">Open Labs</a></li>
                <li><a href="#" class="workspace-help" name="Help" value="my-settings">My Settings</a></li>
            @if(in_array('system_admin', $roles) or in_array('super_user', $roles))
                <li><a href="#" class="workspace-help">Group Management</a></li>
                <li><a href="#" class="workspace-help">Lab Design</a></li>
                <li><a href="#" >Lab Management</a></li>
            @endif
            @if(in_array('system_admin', $roles) or in_array('site_admin', $roles))
                <li><a href="#" >Site Resource Admin</a></li>
            @endif
            @if(in_array('system_admin', $roles))
                <li><a href="#" >User Admin</a></li>
                <li><a href="#" >System Admin</a></li>
                <li><a href="#" >System Monitor</a></li>
                <li><a href="#" >Security Analyzer</a></li>
            @endif
            </ul>
        </li>
    </ul>
    {{--<ul>--}}
      {{--<li>--}}
        {{--<a class="menu" target="_blank" href="{{ URL::route('myworkspace') }}">New Workspace</a>--}}
      {{--</li>--}}
    {{--</ul>--}}
      {{--<ul>--}}
          {{--<li>--}}
              {{--<a onclick="toggle_webrtc()">Video conference</a>--}}
          {{--</li>--}}
      {{--</ul>--}}
    {{--<ul>--}}
      {{--<li>--}}
        {{--<a class="menu" target="_blank" href="https://projects.thothlab.org/projects/thoth-lab/issues/new">Bug Report</a>--}}
      {{--</li>--}}
    {{--</ul>--}}
  </div>
  <div class="abs" id="bar_bottom">
    <a class="float_left" href="#" id="show_desktop" title="Show Desktop">
      <img src="{{ URL::asset('workspace-assets/images/icons/icon_22_desktop.png') }}" />
    </a>
    <ul id="dock">
    </ul>
      <a class="float_right" href="#" title="v1.0.2 release @4/1/2016">
         <img src="{{ URL::asset('workspace-assets/images/misc/thothlab1.png') }}" />
      </a>
    {{--<a class="float_right" href="/" title="Mobicloud">--}}
      {{--<img src="{{ URL::asset('workspace-assets/images/misc/firehost.png'); }}" />--}}
    {{--</a>--}}
  </div>
</div>
{{--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>--}}
{{--<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>--}}
<script>
  {{--!window.jQuery && document.write(unescape('%3Cscript src="{{ URL::asset('workspace-assets/js/jquery.js'); }}"%3E%3C/script%3E'));--}}
  {{--!window.jQuery && document.write(unescape('%3Cscript src="{{ URL::asset('workspace-assets/js/jquery.ui.js'); }}"%3E%3C/script%3E'));--}}
  window.jQuery || document.write('<script src="js/jquery.min.js"><\/script>');
  window.jQuery || document.write('<script src="js/sorttable.js"><\/script>');
  window.jQuery || document.write('<script src="js/bootstrap.min.js"><\/script>');
  window.jQuery || document.write('<script src="js/jquery-ui.min.js"><\/script>');
  window.jQuery || document.write('<script src="js/jquery.validate.min.js"><\/script>');
  window.jQuery || document.write('<script src="packages/vis/dist/vis.min.js"><\/script>');
  window.jQuery || document.write('<script src="js/d3.min.js"><\/script>');
  //  window.jQuery || document.write('<script src="packages/jQuery-mobile/jquery.mobile-1.4.5.min.js"><\/script>');
  window.jQuery || document.write('<script src="packages/waitMe/waitMe.js"><\/script>');
  window.jQuery || document.write('<script src="packages/session-timeout/js/bootstrap-session-timeout.js"><\/script>');
  window.jQuery || document.write('<script src="packages/imgLiquid/js/imgLiquid.js"><\/script>');
  window.jQuery || document.write('<script src="packages/jQuery-File-Upload/js/vendor/load-image.all.min.js"><\/script>');
  window.jQuery || document.write('<script src="packages/jQuery-File-Upload/js/vendor/jquery.ui.widget.js"><\/script>');
  window.jQuery || document.write('<script src="packages/jQuery-File-Upload/js/jquery.iframe-transport.js"><\/script>');
  window.jQuery || document.write('<script src="packages/jQuery-File-Upload/js/jquery.fileupload.js"><\/script>');
  window.jQuery || document.write('<script src="packages/jQuery-File-Upload/js/jquery.fileupload-process.js"><\/script>');
  window.jQuery || document.write('<script src="packages/jQuery-File-Upload/js/jquery.fileupload-image.js"><\/script>');
  window.jQuery || document.write('<script src="packages/jQuery-File-Upload/js/jquery.fileupload-validate.js"><\/script>');
  window.jQuery || document.write('<script src="packages/splitter/splitter.js"><\/script>');
  window.jQuery || document.write('<script src="packages/jAlert/src/jAlert-v3.min.js"><\/script>');
  window.jQuery || document.write('<script src="packages/jAlert/src/jAlert-functions.min.js"><\/script>');
  window.jQuery || document.write('<script src="packages/simple-dtpicker/jquery.simple-dtpicker.js"><\/script>');
  window.jQuery || document.write('<script src="packages/jquery.wakeup/jquery.wakeup.js"><\/script>');
//  window.jQuery || document.write('<script src="packages/jquery.tablesorter/jquery.tablesorter.min.js"><\/script>');
  window.jQuery || document.write('<script src="https://www.promisejs.org/polyfills/promise-7.0.4.min.js"><\/script>');
  window.jQuery || document.write('<script src="packages/FileSaver/FileSaver.min.js"><\/script>');
  window.jQuery || document.write('<script src="packages/html2canvas/html2canvas.js"><\/script>');
  window.jQuery || document.write('<script src="packages/html2canvas/canvas2image.js"><\/script>');
  //window.jQuery || document.write('<script src="packages/html2canvas/feedback.js"><\/script>');
  window.jQuery || document.write('<script src="packages/feedback/feedback.js"><\/script>');
  window.jQuery || document.write('<script src="js/Intimidatetime.js"><\/script>');
  window.jQuery || document.write('<script src="packages/viz/viz.js"><\/script>');
  window.jQuery || document.write('<script src="packages/panzoom/jquery.panzoom.min.js"><\/script>');
  window.jQuery || document.write('<script src="packages/sweetalert2/sweetalert2.all.min.js"><\/script>');
</script>
<script src="{{ URL::asset('workspace-assets/js/mousetrap.js') }}"></script>
<script src="{{ URL::asset('workspace-assets/js/jquery.desktop.js') }}"></script>
<script src="{{ URL::asset('js/workspace.js') }}"></script>
<script src="{{ URL::asset('js/profilesetting.js') }}"></script>
{{--<script src="{{ URL::asset('js/owncloud.js') }}"></script>--}}
{{--<script src="{{ URL::asset('js/webrtc.js') }}"></script>--}}
{{--<script src="{{ URL::asset('js/subgroupwindow.js'); }}"></script>--}}
<script src="{{ URL::asset('js/team-management.js') }}"></script>
<script src="{{ URL::asset('js/windowgroup.js') }}"></script>
<script src="{{ URL::asset('js/user-admin.js') }}"></script>
<script src="{{ URL::asset('js/system-admin.js') }}"></script>
<script src="{{ URL::asset('js/site-admin.js') }}"></script>
<script src="{{ URL::asset('js/system-monitor.js') }}"></script>
<script src="{{ URL::asset('js/groups-admin.js') }}"></script>
<script src="{{ URL::asset('js/my-settings.js') }}"></script>
<script src="{{ URL::asset('js/labs.js') }}"></script>
<script src="{{ URL::asset('js/Mylabs.js') }}"></script>
<script src="{{ URL::asset('js/concept-map.js') }}"></script>
<script src="{{ URL::asset('js/icon-img-assets.js') }}"></script>
<script src="{{ URL::asset('js/ws-topology-canvas.js') }}"></script>
<script src="{{ URL::asset('js/working-lab.js') }}"></script>
<script src="{{ URL::asset('js/lab-design.js') }}"></script>
<script src="{{ URL::asset('js/labdesignwindow.js') }}"></script>
<script src="{{ URL::asset('js/helpdesk.js') }}"></script>
<script src="{{ URL::asset('js/security-analyzer.js') }}"></script>
<script src="{{ URL::asset('js/jquery.tablesorter.js') }}"></script>
<script src="{{ URL::asset('js/jquery.tablesorter.widgets.js') }}"></script>
<script src="{{ URL::asset('js/Chart.js') }}"></script>

{{--<script>--}}
    {{--$(document).ready(--}}
            {{--function(){--}}
                {{--//hide_icon_login();--}}
                {{--makeShowFirstLoginDig_do();--}}
                {{--$("#global_webrtc_docker_id").dblclick();--}}
                {{--toggle_webrtc();--}}
            {{--}--}}
    {{--);--}}
{{--</script>--}}
{{--<script>--}}
  {{--var _gaq = [['_setAccount', 'UA-166674-8'], ['_trackPageview']];--}}
{{--<script src="{{ URL::asset('jstree/jquery.jstree.js'); }}"></script>--}}
<script src="{{ URL::asset('/packages/jstree/dist/jstree.js') }}"></script>
<script>
    history.pushState(null, null, 'myworkspace');
    window.addEventListener('popstate', function(event) {
        history.pushState(null, null, 'myworkspace');
    });
{{--//  var _gaq = [['_setAccount', 'UA-166674-8'], ['_trackPageview']];--}}
{{--//--}}
{{--//  (function(d, t) {--}}
{{--//    if (window.location.hostname !== 'desktop.sonspring.com') {--}}
{{--//      return;--}}
{{--//    }--}}
{{--//--}}
{{--//    var g = d.createElement(t),--}}
{{--//    s = d.getElementsByTagName(t)[0];--}}
{{--//    g.async = true;--}}
{{--//    g.src = '//www.google-analytics.com/ga.js';--}}
{{--//    s.parentNode.insertBefore(g, s);--}}
{{--//  })(this.document, 'script');--}}

</script>

  {{--(function(d, t) {--}}
    {{--if (window.location.hostname !== 'desktop.sonspring.com') {--}}
      {{--return;--}}
    {{--}--}}

    {{--var g = d.createElement(t),--}}
    {{--s = d.getElementsByTagName(t)[0];--}}
    {{--g.async = true;--}}
    {{--g.src = '//www.google-analytics.com/ga.js';--}}
    {{--s.parentNode.insertBefore(g, s);--}}
  {{--})(this.document, 'script');--}}
{{--</script>--}}


@endif

@stop