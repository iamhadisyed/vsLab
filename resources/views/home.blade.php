@extends('layout.main')
@section('title')
    ThoTh
@stop
@section('content')
    <!-- Header Carousel -->
    <header id="myCarousel" class="carousel slide">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
        </ol>

        <!-- Wrapper for slides -->
        <div  class="carousel-inner col-lg-12" >
            <div class="item active">
                <div class="fill" style="background-image:url('{{ URL::asset('/') }}img/CloudComputingBanner1.png');"></div>
                <div class="carousel-caption">
                    <h2 align="left">Mobile Cloud Computing</h2>
                </div>
            </div>
            <div class="item">
                <div class="fill" style="background-image:url('{{ URL::asset('/') }}img/CloudEducationBanner1.jpg');"></div>
                <div class="carousel-caption">
                    <h2 align="left">E-learning on the Cloud</h2>
                </div>
            </div>
            <div class="item">
                <div class="fill" style="background-image:url('{{ URL::asset('/') }}img/securitybanner.jpg');"></div>
                <div class="carousel-caption">
                    <h2 align="left"> Research, Development, and Experiments</h2>
                </div>
            </div>
        </div>

        <!-- Controls -->
        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
            <span class="icon-prev"></span>
        </a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next">
            <span class="icon-next"></span>
        </a>
        {{--<style>--}}
        {{--.carousel-inner .active.left { left: -33%; }--}}
        {{--.carousel-inner .active.right { left: 33%; }--}}
        {{--.carousel-inner .next        { left:  33%; }--}}
        {{--.carousel-inner .prev        { left: -33%; }--}}
        {{--.carousel-control.left,.carousel-control.right {background-image:none;}--}}

        {{--</style>--}}
    </header>

    <!-- Page Content -->
    <div class="container">

        <!-- Porjects Section -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    Mobicloud Projects
                </h1>
                <h3>
                    Mobicloud provides free research resources for researchers to conduct research projects. <a href="http://mobisphere.asu.edu/projects/new">Apply now.</a>
                </h3>
            </div>

<div class="col-md-12">
<div class="carousel slide" id="myCarousel1">
 <div class="slideshow">
  <div class="carousel-inner">
    <div class="item active">
      <div class="col-md-4">
        <div class="panel panel-default">

                        <div class="panel-heading">
                        <h4> A RESTful Cloud Management Approach</h4>
                    </div>
                      <div class="panel-body">
                        <p>Project aims at allowing users to manage and monitor various cloud services provided by the cloud platform, it integrates the ability to manage and monitor cloud services with a user-friendly web portal.</p>
                        <a href="http://mobisphere.asu.edu/classta/cirrus-a-restful-approach-to-the-management-of-an-integrated-cloud-infrastructure/wikis/home" class="btn btn-default">Learn More</a>
                      </div>
                </div>
      </div>
    </div>
    <div class="item">
      <div class="col-md-4"><div class="panel panel-default">

                        <div class="panel-heading">
                        <h4> Location-Based Shopping Deal recommendation</h4>
                    </div>
                       <div class="panel-body">
                        <p>This project's goal is to build an Android application to help the retail shops to publicize the deals offered to the customers and the customers to view the hot deals from the retail shops near their current location.</p>
                        <a href="http://mobisphere.asu.edu/classta/aishwaryas-group-cloud-assisted-location-based-shopping-deal-recommendation-platform-for-mobile-devices/wikis/home" class="btn btn-default">Learn More</a>
                       </div>
                </div></div>
    </div>
    <div class="item">
      <div class="col-md-4"><div class="panel panel-default">

                        <div class="panel-heading">
                        <h4>Genesis - Cloud Single Sign On</h4>
                    </div>
                       <div class="panel-body">
                        <p>The goal of the project is to integrate the Django authentication with Openstack identity, using custom authentication by implementing Central Authentication System (CAS). </p>
                        <a href="http://mobisphere.asu.edu/classta/genesis-cloud-single-sign-on/wikis/home" class="btn btn-default">Learn More</a>
                       </div>
                </div></div>
    </div>
    <div class="item">
      <div class="col-md-4"><div class="panel panel-default">

                        <div class="panel-heading">
                        <h4>Geolocation related Twitter Analysis Using Hadoop</h4>
                    </div>
                       <div class="panel-body">
                        <p>he project goal is to create a data analytics application that analyzes twitter data at real time and answers user queries by extracting useful information. The main functionality is to extract twitter feed and answer a series of user queries.</p>
                        <a href="http://mobisphere.asu.edu/classta/cloudranger-deploy-a-hadoop-mapreduce-environment-in-a-private-cloud/wikis/home" class="btn btn-default">Learn More</a>
                       </div>
                </div></div>
    </div>
    <div class="item">
      <div class="col-md-4"><div class="panel panel-default">

                        <div class="panel-heading">
                        <h4>Central Service Module</h4>
                    </div>
                       <div class="panel-body">
                        <p>This project creates an application which exposes RESTful web services of OpenStack, a open source cloud platform. The main objective behind this is to have a centralized mechanism to control access to various systems within the virtual lab.</p>
                        <a href="http://mobisphere.asu.edu/classta/central-service-module/wikis/home" class="btn btn-default">Learn More</a>
                       </div>
                </div></div>
    </div>
    <div class="item">
      <div class="col-md-4"><div class="panel panel-default">

                        <div class="panel-heading">
                        <h4>WebBoard</h4>
                    </div>
                        <div class="panel-body">
                        <p>This project is to meet the necessary requirements of online lectures and to make it easier for the instructors and the students to communicate more effectively and interactively, especially for remote students.</p>
                        <a href="http://mobisphere.asu.edu/classta/webboard/blob/master/README.md" class="btn btn-default">Learn More</a>
                        </div>
                </div></div>
    </div>
  </div>
  {{--<a class="left carousel-control" href="#myCarousel1" data-slide="prev"><i class="glyphicon glyphicon-chevron-left"></i></a>--}}
  {{--<a class="right carousel-control" href="#myCarousel1" data-slide="next"><i class="glyphicon glyphicon-chevron-right"></i></a>--}}
     <a class="left carousel-control" href="#myCarousel" data-slide="prev">
         <span class="icon-prev"></span>
     </a>
     <a class="right carousel-control" href="#myCarousel" data-slide="next">
         <span class="icon-next"></span>
     </a>
 </div>
</div>
</div>
</div>


            {{--<div class="col-md-4">--}}
                {{--<div class="panel panel-default">--}}
                    {{--<div class="panel-heading">--}}
                        {{--<h4><i class="fa fa-fw fa-check"></i> Mobicloud Example project 1</h4>--}}
                    {{--</div>--}}
                    {{--<div class="panel-body">--}}
                        {{--<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Itaque, optio corporis quae nulla aspernatur in alias at numquam rerum ea excepturi expedita tenetur assumenda voluptatibus eveniet incidunt dicta nostrum quod?</p>--}}
                        {{--<a href="#" class="btn btn-default">Learn More</a>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-md-4">--}}
                {{--<div class="panel panel-default">--}}
                    {{--<div class="panel-heading">--}}
                        {{--<h4><i class="fa fa-fw fa-gift"></i> Mobicloud Example project 2</h4>--}}
                    {{--</div>--}}
                    {{--<div class="panel-body">--}}
                        {{--<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Itaque, optio corporis quae nulla aspernatur in alias at numquam rerum ea excepturi expedita tenetur assumenda voluptatibus eveniet incidunt dicta nostrum quod?</p>--}}
                        {{--<a href="#" class="btn btn-default">Learn More</a>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-md-4">--}}
                {{--<div class="panel panel-default">--}}
                    {{--<div class="panel-heading">--}}
                        {{--<h4><i class="fa fa-fw fa-compass"></i> Mobicloud Example project 3</h4>--}}
                    {{--</div>--}}
                    {{--<div class="panel-body">--}}
                        {{--<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Itaque, optio corporis quae nulla aspernatur in alias at numquam rerum ea excepturi expedita tenetur assumenda voluptatibus eveniet incidunt dicta nostrum quod?</p>--}}
                        {{--<a href="#" class="btn btn-default">Learn More</a>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        <!-- /.row -->

        <!-- E-Learning Section -->
        <div class="row">
            <div class="col-lg-12">
                <h2 class="page-header">Mobicloud Eduction</h2>
                <h3>
                    Mobicloud provides variety open courses for users and teachers to use and create E-Learning services.
                    <a href="http://edx.mobicloud.asu.edu">Access to the E-Learning platform.</a>
                </h3>
            </div>
            <div class="col-md-4 col-sm-6">
                <a href="portfolio-item.html">
                    <img class="img-responsive img-portfolio img-hover" src="http://placehold.it/700x450" alt="">
                </a>
            </div>
            <div class="col-md-4 col-sm-6">
                <a href="portfolio-item.html">
                    <img class="img-responsive img-portfolio img-hover" src="http://placehold.it/700x450" alt="">
                </a>
            </div>
            <div class="col-md-4 col-sm-6">
                <a href="portfolio-item.html">
                    <img class="img-responsive img-portfolio img-hover" src="http://placehold.it/700x450" alt="">
                </a>
            </div>
        </div>
        <!-- /.row -->

        <!-- Portfolio Section -->
        <div class="row">
            <div class="col-lg-12">
                <h2 class="page-header">Mobicloud Community</h2>
                <h3>
                    Mobicloud provides resources for research community to broadcast and exchange research information. Access to the community.
                </h3>
            </div>
            <div class="col-md-4 col-sm-6">
                <a href="portfolio-item.html">
                    <img class="img-responsive img-portfolio img-hover" src="http://placehold.it/700x450" alt="">
                </a>
            </div>
            <div class="col-md-4 col-sm-6">
                <a href="portfolio-item.html">
                    <img class="img-responsive img-portfolio img-hover" src="http://placehold.it/700x450" alt="">
                </a>
            </div>
            <div class="col-md-4 col-sm-6">
                <a href="portfolio-item.html">
                    <img class="img-responsive img-portfolio img-hover" src="http://placehold.it/700x450" alt="">
                </a>
            </div>
        </div>
        <!-- /.row -->


        <!-- Features Section
        <div class="row">
            <div class="col-lg-12">
                <h2 class="page-header">Modern Business Features</h2>
            </div>
            <div class="col-md-6">
                <p>The Modern Business template by Start Bootstrap includes:</p>
                <ul>
                    <li><strong>Bootstrap v3.2.0</strong>
                    </li>
                    <li>jQuery v1.11.0</li>
                    <li>Font Awesome v4.1.0</li>
                    <li>Working PHP contact form with validation</li>
                    <li>Unstyled page elements for easy customization</li>
                    <li>17 HTML pages</li>
                </ul>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis, omnis doloremque non cum id reprehenderit, quisquam totam aspernatur tempora minima unde aliquid ea culpa sunt. Reiciendis quia dolorum ducimus unde.</p>
            </div>
            <div class="col-md-6">
                <img class="img-responsive" src="http://placehold.it/700x450" alt="">
            </div>
        </div> -->
        <!-- /.row -->

        <hr>

        <!-- Call to Action Section -->
        {{--<div class="well">--}}
            {{--<div class="row">--}}
                {{--<div class="col-md-8">--}}
                    {{--<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Molestias, expedita, saepe, vero rerum deleniti beatae veniam harum neque nemo praesentium cum alias asperiores commodi.</p>--}}
                {{--</div>--}}
                {{--<div class="col-md-4">--}}
                    {{--<a class="btn btn-lg btn-default btn-block" href="#">Call to Action</a>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}

        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; Mobicloud 2014</p>
                </div>
            </div>
        </footer>

    </div>
    <!-- /.container -->

    <!-- jQuery Version 1.11.0 -->
    <script src="js/jquery-3.4.1.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Script to Activate the Carousel -->
    <script>
    $('.carousel').carousel({
        interval: 5000 //changes the speed
    })
    </script>
<script>$('#myCarousel1').carousel({
  interval: 100000
})

$('.carousel .item').each(function(){
  var next = $(this).next();
  if (!next.length) {
    next = $(this).siblings(':first');
  }
  next.children(':first-child').clone().appendTo($(this));

  if (next.next().length>0) {
    next.next().children(':first-child').clone().appendTo($(this));
  }
  else {
  	$(this).siblings(':first').children(':first-child').clone().appendTo($(this));
  }
});
</script>
@stop