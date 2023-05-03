@extends('layout.main-no-nav')
@section('title')
  Tuorial and Demos
@stop
@section('head_css')

<meta name="_token" content="{{ csrf_token() }}" />
<link rel="stylesheet" href="{{ URL::asset('workspace-assets/css/reset.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('workspace-assets/css/workspace.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('workspace-assets/css/desktop.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('css/jquery-ui.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('packages/vis/dist/vis.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('packages/waitMe/waitMe.min.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('workspace-assets/css/conceptmap.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('packages/jQuery-File-Upload/css/jquery.fileupload.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('packages/jstree/dist/themes/proton/style.min.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('packages/splitter/splitter.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('packages/jAlert/src/jAlert-v3.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('packages/simple-dtpicker/jquery.simple-dtpicker.css') }}" />

@stop

@section('content')


<div class="abs" id="wrapper">
  <div class="abs" id="desktop">

      {{--<a class="abs icon" style="left:20px;top:20px;" href="#icon_dock_openlabs" id="global_openlabs_docker_id">--}}
          {{--<img src="{{ URL::asset('workspace-assets/images/icons/icon_64_openlabs-folder.png') }}" />--}}
          {{--Open Labs--}}
      {{--</a>--}}
      <a class="abs icon" style="left:20px;top:20px;" href="#icon_dock_tutorial">
          <img src="{{ URL::asset('workspace-assets/images/icons/icon_64_video-folder.png') }}" />
          Tutorial
      </a>
      <a class="abs icon" style="left:20px;top:100px;" href="#icon_dock_demos">
          <img src="{{ URL::asset('workspace-assets/images/icons/icon_64_video-folder.png') }}" />
          Students' Project Demos
      </a>

  </div>

  <div class="abs" id="bar_top">
    <span class="float_right" id="clock"></span>
    <ul>
      <li>
        <a class="menu" href="/"><i class="glyphicon glyphicon-home"></i></a>
      </li>
    </ul>


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
    </ul>


  </div>
  <div class="abs" id="bar_bottom">
    <a class="float_left" href="#" id="show_desktop" title="Show Desktop">
      <img src="{{ URL::asset('workspace-assets/images/icons/icon_22_desktop.png') }}" />
    </a>
    <ul id="dock">
    </ul>

  </div>
</div>

<script>
  {{--!window.jQuery && document.write(unescape('%3Cscript src="{{ URL::asset('workspace-assets/js/jquery.js'); }}"%3E%3C/script%3E'));--}}
  {{--!window.jQuery && document.write(unescape('%3Cscript src="{{ URL::asset('workspace-assets/js/jquery.ui.js'); }}"%3E%3C/script%3E'));--}}
  window.jQuery || document.write('<script src="js/jquery.min.js"><\/script>');
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
</script>
<script src="{{ URL::asset('workspace-assets/js/mousetrap.js') }}"></script>
<script src="{{ URL::asset('workspace-assets/js/jquery.desktop.js') }}"></script>
<script src="{{ URL::asset('js/openlabs.js') }}"></script>
<script src="{{ URL::asset('js/icon-img-assets.js') }}"></script>
<script src="{{ URL::asset('js/working-lab.js') }}"></script>
<script src="{{ URL::asset('js/labdesignwindow.js') }}"></script>
<script src="{{ URL::asset('/packages/jstree/dist/jstree.js') }}"></script>
<script src="{{ URL::asset('js/labs.js') }}"></script>






@stop