@extends('adminlte::layouts.app')

@section('htmlheader_title')
	{{ trans('adminlte_lang::message.home') }}
@endsection

@section('contentheader_title')
	Home
@endsection

@section('contentheader_description')

@endsection

@section('main-content')
	<div class="container-fluid spark-screen">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">

				<!-- Default box -->
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title">Welcome {{ Auth::user()->email }}!</h3>

						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
								<i class="fa fa-minus"></i></button>
							<button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
								<i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						@if (count($errors) > 0)
							<div class="alert alert-warning">
								<strong>Notification</strong> You have new notification!<br><br>
								<ul>
									@foreach ($errors->all() as $error)
										<li>{{ $error }}</li>
									@endforeach
								</ul>
							</div>
						@endif
							@if ($role == 'instructor')
								<div class="alert alert-info"><strong>Please check <a href="https://www.thothlab.com/doc/lab-instructor-guide.pdf" target="_blank">Guidance for Instructors</a>, <a href="https://www.thothlab.com/doc/lab-student-guide.pdf" target="_blank">Guidance for Students</a> and <a href="https://submissions.storage.mobicloud.asu.edu/submissions/1133/FAQ.pdf" target="_blank">FAQ</a> before you start.</strong><br/></div>
							@endif
							@if ($role == 'student')
								<div class="alert alert-info"><strong>Please check <a href="https://www.thothlab.com/doc/lab-student-guide.pdf" target="_blank">Guidance for Students</a> and <a href="https://submissions.storage.mobicloud.asu.edu/submissions/1125/FAQ.pdf" target="_blank">FAQ</a> before you start.</strong><br/></div>
							@endif
							@if ($lastlab != '')




								<strong>Go back to the <a href="https://www.thothlab.com/labenv/{{ $lastlab }}">{{ $labname }}</a> Lab you accessed on {{ $lastaccesstime->format('m/d/Y') }}.</strong>



							@endif
					</div>

					<!-- /.box-body -->
				</div>
				<div class="col-md-12">
					@if ($role == 'instructor')
						<div class="box">
							<div class="box-header with-border">
								<h3 class="box-title">Your Activity History:</h3>

								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
										<i class="fa fa-minus"></i></button>
									<button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
										<i class="fa fa-times"></i></button>
								</div>
							</div>
							<div class="box-body">
								<div>
									{{--<h3>A stacked timeline with hover, click, and scroll events</h3>--}}
									<h3 id="startandend"></h3>
									<div class="form-group">From <input type="date" id="timelinestarttime"> To <input type="date"  id="timelineendtime"> <button onclick="loadusertimeline(0);">Reload</button></div>
									<div id="timeline3"></div>
									<div id="hoverRes">
										<div class="coloredDiv"></div>
										<div id="name"></div>
										<div id="scrolled_date"></div>
										<br/>
										<H4 id="totaltime"></H4>
									</div>
								</div>
							</div>

						<!-- /.box-body -->
						</div>
					@endif
				<!-- /.box -->

			</div>
		</div>
	</div>
	</div>
@endsection

@section('javascript')
	@if ($role == 'instructor')
		<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.16/d3.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/d3-tip/0.6.7/d3-tip.min.js"></script>
		<script src="{{ URL::asset('js/d3-timeline.js') }}"></script>
		<script src="{{ URL::asset('js/timeline.js') }}"></script>
		<style type="text/css">
			.axis path,
			.axis line {
				fill: none;
				stroke: black;
				shape-rendering: crispEdges;
			}

			.axis text {
				font-family: sans-serif;
				font-size: 10px;
			}

			.timeline-label {
				font-family: sans-serif;
				font-size: 12px;
			}

			#timeline2 .axis {
				transform: translate(0px,40px);
				-ms-transform: translate(0px,40px); /* IE 9 */
				-webkit-transform: translate(0px,40px); /* Safari and Chrome */
				-o-transform: translate(0px,40px); /* Opera */
				-moz-transform: translate(0px,40px); /* Firefox */
			}

			.coloredDiv {
				height:20px; width:20px; float:left;
			}
		</style>
		<script type="text/javascript">
			window.onload = function() {
				loadusertimeline(0);
			}

		</script>
	@endif
@endsection
