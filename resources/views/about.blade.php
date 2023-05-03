@extends('layout.landing-main')
@section('title')
    ThoTh :About
@stop
@section('content')

    <!-- Page Content -->
    <div class="container">

        <!-- Page Heading/Breadcrumbs -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">

                    {{--<small>Subheading</small>--}}
                </h1>
                {{--<ol class="breadcrumb">--}}
                {{--<li><a href="./">Home</a>--}}
                {{--</li>--}}
                {{--<li class="active">About</li>--}}
                {{--</ol>--}}

            </div>
        </div>
        <!-- /.row -->

        <!-- Intro Content -->
        <div class="row">
            <div class="col-md-6">
                <img class="img-responsive" src="img/snac-group-members.jpg" alt="">
            </div>
            <div class="col-md-6">
                <h2>About Mobicloud@ASU</h2>
                <p>MobiCloud system has been developed since August 2010. It was developed based on the Virtual Laboratory (vLab) system initially established by Aniruddha Kadne for his master thesis during the summer of 2010. Later a research group was established at the beginning of the Fall semester of 2010, the research was sponsored by Office of Naval Research Young Investigator Program and NSF CCLI project. The initial group includes Xinyi Dong, Dijiang Huang, Aniruddha Kadne, Yang Qin, Tianyi Xing, Le Xu, Yunji Zhong, and Zhibin Zhou. Later, Abdullah Alshalan, Chun-Jen Chung, Yuli Deng, Pankaj Kumar Khatkar, Bing Li, Mahmoud Saada, Zhijie Wang, Huijun Wu, Sandeep Pisharody, and Qingyu Li joined the MobiCloud research and development group.</p>
                <p>MobiCloud system is still evolving; from its infant stage to now, it has experienced several major redesigns and from-the-scratch developments. New functions and features are added and removed due to the changes of research focuses. A featured position and vision article of MobiCloud can be found at the <a href="http://ieeexplore.ieee.org/xpl/articleDetails.jsp?arnumber=6616109">link</a>.</p>
            </div>
        </div>
        <!-- /.row -->

        <!-- Team Members -->
        <div class="row">
            <div class="col-lg-12">
                <h2 class="page-header">Our Team</h2>
            </div>
            <div class="col-lg-3 col-sm-4 col-xs-6 text-center">
                <div class="thumbnail">
                    <img class="img-responsive" src="img/members/Dijiang_Huang_350x300.png" alt="">
                    <div class="caption">
                        <h3>Dr. Dijiang Huang<br>
                            <small>Associate Professor</small>
                        </h3>
                        <p class="collapse" id="Dijiang">He is currently an associate Professor in CIDSE. His  interests are in computer and network security, mobile ad hoc networks, network virtualization, and mobile cloud computing. His research  supported by NSF, ONR, ARO, and NATO, Hewlett-Packard, China Mobile, etc. He is leading the Secure Networking and Computing (SNAC) research group.</p>
                        <p><a class="btn" data-toggle="collapse" data-target="#Dijiang">View details &raquo;</a></p>
                        <ul class="list-inline">
                            <li><a href="#"><i class="fa fa-2x fa-facebook-square"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-2x fa-linkedin-square"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-2x fa-envelope"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-4 col-xs-6 text-center">
                <div class="thumbnail">
                    <img class="img-responsive" src="http://placehold.it/350x300" alt="">
                    <div class="caption">
                        <h3>Chun-Jen Chung<br>
                            <small>PhD Candidate</small>
                        </h3>
                        <p class="collapse" id="James">James is working toward the Ph.D. degree in CIDSE. Prior to that, he received the MS degree in CS from NYU. He also worked Microsoft and Oracle for more than 6 years and has experience in IT management for more than 2 years. He was author and co-author of more than 20 books. His current research interests include network security, SDN security, and trusted computing in mobile devices and cloud computing.</p>
                        <p><a class="btn" data-toggle="collapse" data-target="#James">View details &raquo;</a></p>
                        <ul class="list-inline">
                            <li><a href="#"><i class="fa fa-2x fa-facebook-square"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-2x fa-linkedin-square"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-2x fa-envelope"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-4 col-xs-6 text-center">
                <div class="thumbnail">
                    <img class="img-responsive" src="img/members/AB_350x300.jpg" alt="">
                    <div class="caption">
                        <h3>Abdullah Alshalan<br>
                            <small>PhD Student</small>
                        </h3>
                        <p class="collapse" id="Ab">Abdullah is pursing his Ph.D in Computer Science at ASU. He earned his bachelors degree in Computer Science from King Saud University in 2003. He received a Masters of Science degree in Computer Science from Indiana University in 2009. His research interests include Computer Networks, Mobility, Information Security and Cloud Computing. His current research is focused on mobile VPNs.</p>
                        <p><a class="btn" data-toggle="collapse" data-target="#Ab">View details &raquo;</a></p>
                        <ul class="list-inline">
                            <li><a href="#"><i class="fa fa-2x fa-facebook-square"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-2x fa-linkedin-square"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-2x fa-envelope"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-4 col-xs-6 text-center">
                <div class="thumbnail">
                    <img class="img-responsive" src="img/members/Yuli_Deng_350x300.png" alt="">
                    <div class="caption">
                        <h3>Yuli Deng<br>
                            <small>PhD Student</small>
                        </h3>
                        <p class="collapse" id="Yuli">Yuli is a Ph.D student in CIDSE at ASU. He received his B.E. in Computer Science and Technology from Huazhong University of Science & Technology in 2011 and M.S. in Computer Science from ASU in 2013. His research interests are mobile cloud application, cloud computing and big data.</p>
                        <p><a class="btn" data-toggle="collapse" data-target="#Yuli">View details &raquo;</a></p>
                        <ul class="list-inline">
                            <li><a href="#"><i class="fa fa-2x fa-facebook-square"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-2x fa-linkedin-square"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-2x fa-envelope"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-4 col-xs-6 text-center">
                <div class="thumbnail">
                    <img class="img-responsive" src="img/members/Bing_Li_350x300.jpg" alt="">
                    <div class="caption">
                        <h3>Bing Li<br>
                            <small>PhD Student</small>
                        </h3>
                        <p class="collapse" id="Bing">Bing received his BS degree from Shandong University in 2008 and his MS degree from Beijing University of Posts & Telecommunications in 2011. He is currently a PhD student with Secure Networking and Computing (SNAC) group at ASU. His research interests include wireless networking, attribute based network management and cloud computing.</p>
                        <p><a class="btn" data-toggle="collapse" data-target="#Bing">View details &raquo;</a></p>
                        <ul class="list-inline">
                            <li><a href="#"><i class="fa fa-2x fa-facebook-square"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-2x fa-linkedin-square"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-2x fa-envelope"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-4 col-xs-6 text-center">
                <div class="thumbnail">
                    <img class="img-responsive" src="img/members/Sandeep_Pisharody_350x300.jpg" alt="">
                    <div class="caption">
                        <h3>Sandeep Pisharody<br>
                            <small>PhD Student</small>
                        </h3>
                        <p class="collapse" id="Sandeep">Sandeep earned his BS Electrical Engineering (distinction) and BS Computer Engineering (distinction) from the University of Nebraska in 2004; and MS Electrical Engineering from the University of Nebraska in 2006. He has over 8 years experience in designing, building and maintaining enterprise and carrier class networks while working in various capacities for Sprint, Iveda, University of Phoenix and Insight. His current research interests lie in the areas of secure cloud computing and software designed networking.</p>
                        <p><a class="btn" data-toggle="collapse" data-target="#Sandeep">View details &raquo;</a></p>
                        <ul class="list-inline">
                            <li><a href="#"><i class="fa fa-2x fa-facebook-square"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-2x fa-linkedin-square"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-2x fa-envelope"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-4 col-xs-6 text-center">
                <div class="thumbnail">
                    <img class="img-responsive" src="img/members/Zhijie_Wang_350x300.jpg" alt="">
                    <div class="caption">
                        <h3>Zhijie Wang<br>
                            <small>PhD Student</small>
                        </h3>
                        <p class="collapse" id="Zhijie"> Zhijie is a PhD student in SNAC lab at Arizona State University. His research interests include wireless networking, applied cryptography, cloud computing, mobile sensing and optimization.</p>
                        <p><a class="btn" data-toggle="collapse" data-target="#Zhijie">View details &raquo;</a></p>
                        <ul class="list-inline">
                            <li><a href="#"><i class="fa fa-2x fa-facebook-square"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-2x fa-linkedin-square"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-2x fa-envelope"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-4 col-xs-6 text-center">
                <div class="thumbnail">
                    <img class="img-responsive" src="img/members/Huijun_Wu_350x300.jpg" alt="">
                    <div class="caption">
                        <h3>Huijun Wu<br>
                            <small>PhD Student</small>
                        </h3>
                        <p class="collapse" id="Huijun">Huijun is currently a Ph.D student in CIDSE at ASU. He received the B.E. and the M.E. in Electronics and Information Engineering from Huazhong University of Science & Technology in 2007 and 2009 respectively. He worked in Alcatel-Lucent Shanghai Bell as SIT engineer from July 2009 to July in 2011. His research interests are mobile cloud application, mobile cloud service framework and cloud computing</p>
                        <p><a class="btn" data-toggle="collapse" data-target="#Huijun">View details &raquo;</a></p>
                        <ul class="list-inline">
                            <li><a href="#"><i class="fa fa-2x fa-facebook-square"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-2x fa-linkedin-square"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-2x fa-envelope"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-4 col-xs-6 text-center">
                <div class="thumbnail">
                    <img class="img-responsive" src="img/members/Pankaj_Khatkar_350x300.jpg" alt="">
                    <div class="caption">
                        <h3>Pankaj Khatkar<br>
                            <small>MS Student</small>
                        </h3>
                        <p class="collapse" id="Pankaj">His research interests are in the area of network security and virtualization. User experience, web technologies and NLP catch his attention when he is busy solving problems related to vulnerability assessment.</p>
                        <p><a class="btn" data-toggle="collapse" data-target="#Pankaj">View details &raquo;</a></p>
                        <ul class="list-inline">
                            <li><a href="#"><i class="fa fa-2x fa-facebook-square"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-2x fa-linkedin-square"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-2x fa-envelope"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-4 col-xs-6 text-center">
                <div class="thumbnail">
                    <img class="img-responsive" src="img/members/Ankur_Chowdhary_350x300.jpg" alt="">
                    <div class="caption">
                        <h3>Ankur Chaudhary<br>
                            <small>MS Student</small>
                        </h3>
                        <p class="collapse" id="Ankur">MS Student in Computer Science since 2013. He earned B.Tech of Information Technology in MAIT, Delhi in 2011). He worked for CSC Pvt. Ltd. as associate software engineer from 2011 to 2013. His research interests include Mobile Security-BYOD, Virtualization, SDN, Java Application Development, and Android. He got Oracle Certified Professional,J2SE 6; Oracle Certified Expert, Web Component Developer, J2EE 6; and Red Hat Certified Engineer.</p>
                        <p><a class="btn" data-toggle="collapse" data-target="#Ankur">View details &raquo;</a></p>
                        <ul class="list-inline">
                            <li><a href="#"><i class="fa fa-2x fa-facebook-square"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-2x fa-linkedin-square"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-2x fa-envelope"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-4 col-xs-6 text-center">
                <div class="thumbnail">
                    <img class="img-responsive" src="img/members/Shilpa_Nagendra_350x300.png" alt="">
                    <div class="caption">
                        <h3>Shilpa Nagendra<br>
                            <small>MS Student</small>
                        </h3>
                        <p class="collapse" id="Shilpa">MS in Computer Science (Since Spring 2013), Arizona State University, Arizona. Bachelor of Engineering in Computer Science, Government College of Technology, Anna University, Coimbatore. (2007) Research Interests : Distributed software systems, Cloud Computing technologies and data storage in clouds. Thesis topic : yet to be decided</p>
                        <p><a class="btn" data-toggle="collapse" data-target="#Shilpa">View details &raquo;</a></p>
                        <ul class="list-inline">
                            <li><a href="#"><i class="fa fa-2x fa-facebook-square"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-2x fa-linkedin-square"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-2x fa-envelope"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-4 col-xs-6 text-center">
                <div class="thumbnail">
                    <img class="img-responsive" src="http://placehold.it/350x300" alt="">
                    <div class="caption">
                        <h3>Qingyu Li<br>
                            <small>MS Student</small>
                        </h3>
                        <p class="collapse" id="Qingyu">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste saepe et quisquam nesciunt maxime.</p>
                        <p><a class="btn" data-toggle="collapse" data-target="#Qingyu">View details &raquo;</a></p>
                        <ul class="list-inline">
                            <li><a href="#"><i class="fa fa-2x fa-facebook-square"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-2x fa-linkedin-square"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-2x fa-envelope"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-4 col-xs-6 text-center">
                <div class="thumbnail">
                    <img class="img-responsive" src="img/members/Zhiyuan_Ma_350x300.jpg" alt="">
                    <div class="caption">
                        <h3>Zhiyuan Ma<br>
                            <small>Visiting PhD Student</small>
                        </h3>
                        <p class="collapse" id="Zhiyuan">Zhiyuan received his BS in computer science in Nanjing University in 2009 and became a master student of software engineering in University of Electronic Science and Technology of China (UESCT) in 2011. In 2013, he was selected to join successive master-doctor program and became a Ph.D student of computer science in UESTC. Currently, he is at ASU as a visiting Ph.D student. He worked on cloud computing architectures and applications during 2011-2013, and now his research interests are big data mining algorithms and applications.</p>
                        <p><a class="btn" data-toggle="collapse" data-target="#Zhiyuan">View details &raquo;</a></p>
                        <ul class="list-inline">
                            <li><a href="#"><i class="fa fa-2x fa-facebook-square"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-2x fa-linkedin-square"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-2x fa-envelope"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.row -->

        <!-- Our Customers -->
        <!--        <div class="row">
                    <div class="col-lg-12">
                        <h2 class="page-header">Our Customers</h2>
                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6">
                        <img class="img-responsive customer-img" src="http://placehold.it/500x300" alt="">
                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6">
                        <img class="img-responsive customer-img" src="http://placehold.it/500x300" alt="">
                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6">
                        <img class="img-responsive customer-img" src="http://placehold.it/500x300" alt="">
                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6">
                        <img class="img-responsive customer-img" src="http://placehold.it/500x300" alt="">
                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6">
                        <img class="img-responsive customer-img" src="http://placehold.it/500x300" alt="">
                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6">
                        <img class="img-responsive customer-img" src="http://placehold.it/500x300" alt="">
                    </div>
                </div>
        -->        <!-- /.row -->

        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; Moibcloud 2014</p>
                </div>
            </div>
        </footer>

    </div>
    <!-- /.container -->

    <!-- jQuery Version 1.11.0 -->
    <script src="js/jquery-3.4.1.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.bootstrap-autohidingnavbar.min.js"></script>
    <script>
        $(".navbar-fixed-top").autoHidingNavbar();
    </script>
@stop