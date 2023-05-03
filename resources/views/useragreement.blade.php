@extends('layout.landing-main')
@section('title')
    User Agreement
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
    <div class="container" style="font-weight: normal">

  <p style="font-size: x-large;font-weight: bold;text-align: center">
      ThoTh Lab User Agreement
  </p>
        <p>
            This Use Agreement (the "Agreement") is between you and Athena Network Solutions LLC (the "Company") with executive offices at 688 W Coconino PL, Chandler Arizona, 85248, USA. Your use of the Company Internet based hands-on Lab (the "ThoTh Lab") is subject to the following terms and conditions of use:
        </p><p>
            (1) YOU AGREE TO READ THESE TERMS AND CONDITIONS OF USE CAREFULLY BEFORE USING THIS THOTH LAB. Use of the ThoTh Lab signifies your unconditional agreement to the terms and conditions of this Agreement. If you do not agree to these terms and conditions of use, do not access or otherwise use this ThoTh Lab.
        </p><p>

            (2) The Company will not gather, process and use information and materials received from you (e.g., name, physical address, e-mail address) or collected through your use of the ThoTh Lab for any lawful reason or purpose.
        </p><p>
            (3) The Company reserves the right, at its sole discretion, from time to time to change, modify, add or remove any portion of this Agreement, in whole or in part, at any time. Notification of changes in the Agreement will be posted on the website of the ThoTh Lab.
        </p><p>
            (4) The ThoTh Lab is protected by one or more copyrights pursuant to U.S. copyright laws, international conventions and other intellectual property laws. You will abide by any and all copyright notices, trademark notices, ownership information or restrictions contained in any Content on the ThoTh Lab. You may download and make copies of the Content and other downloadable items displayed on this ThoTh Lab, provided that you maintain all copyright and other notices contained in such Content. Copying or storing of any Content on the ThoTh Lab for sole education purpose is permitted. Copying or storing of any Content on the ThoTh Lab for reproduction, redistribution or publication to third parties for commercial purposes is expressly prohibited without prior written permission from the Company. All rights to the Company’s copyrighted materials not expressly granted herein are reserved by the Company.
        </p><p>
            (5) The Company, at its sole discretion, may change, suspend or discontinue any aspect of the ThoTh Lab at any time, including the availability of any ThoTh Lab feature, database or Content. Company may also impose limits on certain features and services or restrict your access to parts or all of the ThoTh Lab without notice or liability.
        </p><p>
            (6) You represent, warrant and covenant that you shall not upload, post or transmit to or distribute or otherwise publish through the ThoTh Lab any materials which: (i) restrict or inhibit any other user from using and enjoying the ThoTh Lab; (ii) are unlawful, threatening, abusive, libelous, defamatory, obscene, vulgar, offensive, pornographic, profane, sexually explicit or indecent; (iii) constitute or encourage conduct that would constitute a criminal offense, give rise to civil liability or otherwise violate any law or governmental regulation; (iv) violate, plagiarize or infringe the rights of third parties including, without limitation, copyright, trademark, patent, rights of privacy or publicity or any other proprietary right; (v) contain a virus or other harmful or destructive elements; (vi) contain any information, software or other material of a commercial nature; (vii) contain advertising of any kind; or (viii) constitute or contain false or misleading indications of origin or statements of fact.
        </p><p>
            (7) The ThoTh Lab may contain hypertext links and pointers to the other World Wide Web Internet sites and resources operated and controlled by parties other than the Company. Links to and from the ThoTh Lab to such third party sites do not imply or constitute an endorsement by the Company of any third party material or contents.
        </p><p>
            (8) The Company reserves the right at all times to disclose any information as necessary to satisfy any law, regulation or government request, or to edit, refuse to post or to remove any information or materials, in whole or in part, that in the Company's sole discretion are objectionable or in violation of this Agreement.
        </p><p>
            (9) The ThoTh Lab allows you to create educational materials. As long as you publish these materials as “Public” through the ThoTh Lab, the Company has the right to collect and reorganize it for other users of the company. For “Private” educational labs and materials, the Company can only reuse it by the permission of the Provider.
        </p>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; Thoth Lab 2015</p>
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