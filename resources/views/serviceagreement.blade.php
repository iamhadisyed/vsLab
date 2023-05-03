@extends('layout.landing-main')
@section('title')
    ThoTh Lab
@stop
@section('content')
    <!-- Header -->
    <a name="about" xmlns="http://www.w3.org/1999/html"></a>
    <div class="intro-header">
        <div class="container">

            <div class="row">
                <div class="col-lg-12">

                        <h1>ThoThLab Service Agreement</h1>
                        <h3>November 2019</h3>
                        <h3>Athena Network Solutions LLC</h3>

                </div>
            </div>

        </div>
        <!-- /.container -->

    </div>
    <!-- /.intro-header -->

    <!-- Page Content -->

    {{--<a  name="features"></a>--}}
    {{--<div class="content-section-a">--}}

        {{--<div class="container">--}}
            {{--<div class="row">--}}
                {{--<div >--}}
                    {{--<hr class="section-heading-spacer">--}}
                    {{--<div class="clearfix"></div>--}}
                    {{--<h2 class="section-heading">ThoTh Lab Features</h2>--}}

                    {{--<p  class="lead" style="font-weight: bold">ThoTh Lab Workspace</p>--}}
                    {{--<div style="float: right">--}}
                        {{--<img class="img-responsive" width="400px" src="packages/landing-page/img/workspace.jpg" alt="Workspace">--}}
                    {{--</div>--}}
                    {{--<p class="lead">--}}
                        {{--The ThoTh Lab Workspace is a customizable hands-on experiment environment, where users can design, construct, and deploy a virtualized computing system including virtual machines and their interconnections, applications and tools running in the virtualized system, and so on.--}}
                    {{--</p>--}}
                    {{--<p class="lead">--}}
                        {{--The ThoTh Lab Workspace is a collaborative and interactive learning environment, where users can share the deployed virtual system and collaborate on hands-on experiments in real-time with interactive social networking and multimedia support.--}}
                    {{--</p>--}}
                    {{--<p class="lead">--}}
                        {{--The ThoTh Lab Workspace is a project-based learning, testing, and evaluating environment, where users develop their own problem-based virtual hands-on lab contents for assessment, tutoring, testing, and demonstration.--}}
                    {{--</p>--}}
                    {{--<p class="lead">--}}
                    {{--The <span style="font-weight: bold">main features</span> of ThoTh Lab’s workspace include:--}}
                    {{--</p>--}}
                    {{--<ul class="lead">--}}

                        {{--<li>--}}
                           {{--<span style="font-weight: bold"> Remote access:</span> Users can access the ThoTh Lab Workspace through Internet. No client applications are required to be installed. The ThoTh Lab Workspace can be accessed through a web-browser, and no plugin is required on users’ browser.--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<span style="font-weight: bold">Lab repository:</span> Predefined standardized laboratories are provided for ThoTh Lab Workspace users. Users can include one or multiple labs from the lab repository to form a course project for students. ThoTh Lab includes most popular labs (from either public open resources and private labs created by the ThoTh Lab development team). The lab repository is living resource – new labs are being added continuously.--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<span style="font-weight: bold">User-defined lab:</span> Users can design and create their own labs including both lab content and lab running environment. Users can design the lab content according to their need for teaching.--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<span style="font-weight: bold">Group-based lab management:</span> In ThoTh Lab, hands-on labs are arranged according to a teaching group (or a subgroup). A group is similar to a class in the teaching setup. Multiple roles are assigned according to a class setup: Instructor, Teaching Assistant (TA), and Student. Each role has difference access and management right in the group. An instructor can assign and deploy labs according to the group/subgroup formation, which realistically mimic the operation in a teaching setup. The group owner can make the group as either “public” or “private”, in which the public group is searchable by any ThoTh lab user for requesting to join.--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<span style="font-weight: bold">Collaborative learning:</span>  The group management features enable the collaborative learning. Users can work as a group on a hands-on project/lab. They can collaboratively work in the same project running environment to satisfy the group-based project requirements.--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<span style="font-weight: bold">Authoring:</span> ThoTh Lab allows users to create and design their own labs and publish the lab content and associated lab environment. This feature will be extended for tutoring service planned in the next release.--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<span style="font-weight: bold">IaaS & SaaS:</span> For IaaS, ThoTh Lab provides a laboratory infrastructure including virtual machines and their interconnections (i.e., virtual networks). For SaaS, ThoTh Lab provides software services such as lab management, authoring, file/data sharing, etc.--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<span style="font-weight: bold">Sharing:</span> ThoTh Lab provides a number of sharing features such as file/data sharing, lab running environment sharing, and lab content/design sharing. These features provide an attractive collaborative learning capability for users.--}}
                        {{--</li>--}}
                    {{--</ul>--}}
                    {{--<p class="lead" style="font-weight: bold">--}}
                        {{--Planned new features (the following features will be added in the following releases of ThoTh Lab):--}}
                    {{--</p>--}}
                    {{--<ul class="lead">--}}
                        {{--<li>--}}
                            {{--<span style="font-weight: bold">Openness: </span> ThoTh Lab will be an open laboratory environment that allows users to compose system-level components to form new applications based on the ThoTh Lab service platform, e.g., providing PaaS services.--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<span style="font-weight: bold">Social capability: </span> ThoTh Lab will incorporate more social networking and social media (e.g., blog) features based on current group-based lab management environment. It will support individual users to design and publish user-designed learning materials.--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<span style="font-weight: bold">Logging and Auditing: </span>ThoTh Lab will incorporate more detailed logging and auditing services to allow users to track their learning activity, learn their learning pattern, and thus improve their learning experience.--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<span style="font-weight: bold">Multi-media supported interactive learning: </span>ThoTh Lab will include multi-media support features, such as group-based video conferencing, online chatting, and bug reporting, etc.--}}
                        {{--</li>--}}

                    {{--</ul>--}}
                    {{--<p class="lead" style="font-weight: bold">--}}
                        {{--How ThoTh Lab is different from other hands-on lab solutions?--}}
                    {{--</p>--}}
                    {{--<p class="lead">--}}
                        {{--ThoTh Lab provides a virtualized laboratory that does not require students to be physically present during the lab, which eliminates the lab access scheduling issue; moreover, the virtualized lab environment can maximally improve the system resource utilization by using cloud computing technology, and pure web-based access do not requires students to setup the lab on their own computer enabling students to focus more on their lab requirements. The competitive features of the ThoTh Lab with other hands-on labs are highlighted as follows:--}}
                    {{--</p>--}}
                    {{--<table class="lead" style="border-collapse: collapse; border: 1px solid black"><tr style="border: 1px solid black">--}}
                            {{--<th style="border: 1px solid black">Representative Examples</th><th>Remote Access</th><th>Standardized Lab Repository</th><th>Customizable Lab Environment</th><th>Logging and Auditing</th><th>User Designed lab</th><th>Collaboration, Interactive Tutoring & Authoring</th><th>IaaS, PaaS, SaaS</th><th>Openness, Sharing, and Social</th>--}}
                        {{--</tr><tr style="border: 1px solid black">--}}
                            {{--<th style="border: 1px solid black">Thoth Lab</th><th style="font-weight: normal">Yes</th><th style="font-weight: normal">Yes</th><th style="font-weight: normal">Yes</th><th style="font-weight: normal">Yes</th><th style="font-weight: normal">Yes</th><th style="font-weight: normal">Yes</th><th style="font-weight: normal">IaaS, SaaS</th><th style="font-weight: normal">Limited</th>--}}
                        {{--</tr><tr style="border: 1px solid black">--}}
                            {{--<th style="border: 1px solid black">Amazon AWS</th><th style="font-weight: normal">Yes</th><th style="font-weight: normal">No</th><th style="font-weight: normal">Limited</th><th style="font-weight: normal">Yes</th><th style="font-weight: normal">No</th><th style="font-weight: normal">Limited</th><th style="font-weight: normal">IaaS, Paas</th><th  style="font-weight: normal">No</th>--}}
                        {{--</tr><tr style="border: 1px solid black">--}}
                            {{--<th style="border: 1px solid black">Hacking Lab, Hera Pen Lab</th><th style="font-weight: normal">Yes</th><th style="font-weight: normal">Limited</th><th style="font-weight: normal">No</th><th style="font-weight: normal">No</th><th style="font-weight: normal">No</th><th style="font-weight: normal">No</th><th style="font-weight: normal">No</th><th style="font-weight: normal">No</th>--}}
                        {{--</tr>--}}
                    {{--</table>--}}
                {{--</div>--}}

            {{--</div>--}}

        {{--</div>--}}
        {{--<!-- /.container -->--}}

    {{--</div>--}}
    {{--<!-- /.content-section-a -->--}}
    {{--<a  name="faq"></a>--}}
    {{--<div class="content-section-b">--}}

        {{--<div class="container">--}}

            {{--<div class="row">--}}

                {{--<div >--}}

                    {{--<div  class="clearfix"></div>--}}
                    {{--<h2 class="section-heading" style="text-align: center">ThoTh Lab FAQ</h2>--}}

                    {{--<p  class="lead" style="font-weight: bold">What is ThoTh?</p>--}}
                    {{--<div style="float:left ">--}}
                        {{--<img class="img-responsive" style= "width : 150px" src="workspace-assets/images/misc/FAQpic.png" alt="Research">British Museum<br>--}}
                    {{--</div>--}}
                    {{--<p class="lead">--}}

                        {{--Thoth (/θoʊθ/ or /toʊt/) was one of the earlier Egyptian gods. Thoth is the name given by the Greeks to the Egyptian god Djeheuty. Thoth was the god of wisdom, inventor of writing, patron of scribes and the divine mediator. He is most often represented as a man with the head of an ibis, holding a scribal palette and reed pen. He could also be shown completely as an ibis or a baboon. We use “ThoTh” as our system name, because it can help you become more knowledgeable and wise.--}}
                    {{--<br><br></p>--}}

                    {{--<p  class="lead" style="font-weight: bold">How ThoTh Lab can help you?</p>--}}
                    {{--<p class="lead">--}}
                        {{--Hands-on labs have become an indispensable part of curriculum components for science education. The main issue of providing hands-on labs for students also demands tremendous lab management overhead for instructors, and usually they cannot afford to provide practical labs due to both their time and resource constraints. ThoTh Lab is to address these issues by providing a virtualized laboratory. It provides the following benefits to Educators and students:--}}
                    {{--</p>--}}
                    {{--<ul class="lead" style="list-style-type: none">--}}
                        {{--<li>--}}
                            {{--(1) reduce lab management from a few hours to just a few minutes;--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--(2) provide standardized labs to ease the lab management and assessment;--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--(3) provide collaborative and interactive features to enable group-based project management and reduce the group-based project management overhead;--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--(4) improve students’ learn experience through a learn-by-doing-it approach, and--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--(5) integrated lab running environment and related lab content and guidance can greatly improve the learning experience.--}}
                        {{--</li>--}}

                    {{--</ul>--}}
                    {{--<p class="lead">In short, ThoTh Lab is designed to <span style="font-weight: bold">reduce lab management overhead</span> for instructors and <span style="font-weight: bold">improve learning experience</span> for students through a hands-on approach.</p>--}}
                    {{--<p  class="lead" style="font-weight: bold">Where is the ThoTh Lab come from?</p>--}}
                    {{--<p class="lead">--}}
                        {{--ThoTh Lab was initially developed from an earlier NSF CCLI project. It is designed for educators and students who want more flexible applied-learning capabilities. As right of now, ThoTh Lab focuses on hands-on experimental labs for Information technology. Unlike physical computer labs built by the institutions, and simulated labs offered by many publishers, ThoTh Lab provides a virtual, yet real, teaching and learning environment through a web-based user interface for fast development of computer-based hands-on labs including virtualized computers, their interconnections, customizable software, and standardized hands-on lab materials. ThoTh Lab provides fast deployable solutions allowing users to manage their lab content and procedure with little management overhead.--}}
                    {{--</p>--}}
                    {{--<p  class="lead" style="font-weight: bold">Who are interested in using ThoTh Lab?</p>--}}
                    {{--<p class="lead">--}}
                        {{--Current users of Thoth Lab are professors/lectures and students from universities and community colleges. Faculty whose main job function is teaching in disciplines such as CS (Computer Science), CIS (Computer Information System), IT (Information Technology) needs the ThoTh Lab solution to reduce heavy time consumption for preparing, conducting, and evaluating hands-on labs; Moreover, insufficient, inefficient, and ineffective hands-on labs were the main issues for students looking to improve their learning with real-world practical experiences.--}}
                    {{--</p>--}}
                    {{--<p class="lead">--}}
                        {{--Current users of ThoTh Lab are mainly schools, institutions, colleges, and universities (basically we are using a B2B service model). The near future plan for ThoTh Lab will include social networking features that allow individual educators to customize their knowledge and experience providing B2C and C2C service models. Besides current supported CS CIS and IT lab materials, other disciplines will be gradually added in the ThoTh Lab repository.--}}
                    {{--</p>--}}

                    {{--<p  class="lead" style="font-weight: bold">How to use ThoTh Lab?</p>--}}
                    {{--<p class="lead">--}}
                        {{--In Fall 2015, the ThoTh Lab will only provide services to invited users, which the service is free of charge. For uninvited and interested users, we will open the system in Spring 2016. In order to use the system, we would like to use the following procedure:--}}
                    {{--</p>--}}
                    {{--<ul class="lead" style="list-style-type: none">--}}
                        {{--<li>--}}
                            {{--1.       Sign the user agreement of using ThoTh Lab (<a  href="{{ URL::asset('/downloads/ThoTh_Lab_End_User_Agreement.pdf') }}">https://www.thothlab.org/downloads/ThoTh_Lab_End_User_Agreement.pdf</a>).--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--2.       Fill the resource request form (please download at <a href="{{ URL::asset('/downloads/ResourceRequest.doc') }}">https://www.thothlab.org/downloads/ResourceRequest.doc</a>) that describes the total requested system resource in terms of the number of virtual machines and their configurations. (Note that the system can automatically allocate your requested system resource. We just want to make sure that our system can satisfy your requested resource).--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--3.       Please send an electronic copy of the user agreement and the resource request form to <a href="mailto:contact@thethoth.com" target="_top">contact@thethoth.com</a>.--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--4.       Register and create a user account in ThoTh Lab (<a href="{{ URL::asset('/') }}">https://www.thothlab.org</a>). After registration, we will assign you the Instructor role and thus you can enjoy all the features in the current release.--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--5.       Practice in the ThoTh Lab system and prepare for your class in Fall 2015. (During this procedure, we will have technical support to help you to establish your desired lab setup).--}}

                        {{--</li>--}}
                        {{--<li>--}}
                            {{--6.       We will prepare a few tutorials in ThoTh Lab on how to use ThoTh Lab including how to create a user group, how to assign labs to user groups, how to deploy labs to groups of users, how to create user-design labs, etc. Please follow the tutorials to prepare your labs and enjoy.--}}
                        {{--</li>--}}
                    {{--</ul>--}}


                {{--</div>--}}

            {{--</div>--}}

        {{--</div>--}}
        {{--<!-- /.container -->--}}

    {{--</div>--}}
    <!-- /.content-section-b -->

    {{--<div class="content-section-a">--}}

        {{--<div class="container">--}}

            {{--<div class="row">--}}
                {{--<div class="col-lg-5 col-sm-6">--}}
                    {{--<hr class="section-heading-spacer">--}}
                    {{--<div class="clearfix"></div>--}}
                    {{--<h2 class="section-heading">MobiCloud Projects</h2>--}}
                    {{--<p class="lead">--}}
                        {{--An open source platform to publish research outcomes and keep track of latest research activities.--}}
                    {{--</p>--}}
                {{--</div>--}}
                {{--<div class="col-lg-5 col-lg-offset-2 col-sm-6">--}}
                    {{--<img class="img-responsive" src="packages/landing-page/img/workspace.jpg" alt="workspace">--}}
                {{--</div>--}}
            {{--</div>--}}

        {{--</div>--}}
        {{--<!-- /.container -->--}}

    {{--</div>--}}
    <!-- /.content-section-a -->

    {{--<a  name="contact"></a>--}}
    {{--<div class="banner">--}}

        {{--<div class="container">--}}

            {{--<div class="row">--}}
                {{--<div class="col-lg-6">--}}
                    {{--<h2>Other Resources:<br></h2>--}}
                {{--</div>--}}
                {{--<div class="col-lg-6">--}}
                    {{--<ul class="list-inline banner-social-buttons">--}}
                        {{--<li>--}}
                            {{--<a href="{{ URL::route('myworkspace') }}" class="btn btn-default btn-lg"><img src="workspace-assets/images/icons/icon_16_workspace.png" />&nbsp;<span class="network-name">Workspace</span></a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<a href="https://monitor.mobicloud.asu.edu/monitor" class="btn btn-default btn-lg"><i class="fa fa-line-chart"></i>&nbsp;<span class="network-name">Systen Status</span></a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<a href="{{ URL::asset('mobile') }}" class="btn btn-default btn-lg"><img src="workspace-assets/images/icons/icon_16_research.png" />&nbsp;<span class="network-name">Research</span></a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<a href="{{ URL::asset('openlabs') }}" class="btn btn-default btn-lg" target="_blank"><i class="fa fa-cloud"></i>&nbsp;<span class="network-name">Open Labs and Demos</span></a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<a href="{{ URL::asset('vlab/reports') }}" class="btn btn-default btn-lg"><img src="workspace-assets/images/icons/icon_16_page.png" />&nbsp;<span class="network-name">previous lab usage reports</span></a>--}}
                        {{--</li>--}}
                    {{--</ul>--}}
                {{--</div>--}}
            {{--</div>--}}

        {{--</div>--}}
        {{--<!-- /.container -->--}}

    {{--</div>--}}
    <!-- /.banner -->
    <div class="container">
    <div class="row">
    <div>

        <p style="font-weight: 400;">
            Athena Network Solutions LLC ("ThoTh Lab," "we," or "us") presents these terms ("Terms") cover the use of ThoTh Lab websites and services listed here (the "Services"). You ("a ThoTh Lab user")  accept these Terms by creating a ThoTh Lab account, through your use of the Services, or by continuing to use the Services after being notified of a change to these Terms.
        </p>
        <ol>
            <li>
                <span style="font-weight: 600">Your Privacy.</span>
                <span style="font-weight: 400">
            Your privacy is important to us. Please read the ThoTh Lab Privacy Statement (the "Privacy Statement") as it describes the types of data we collect from you and your devices ("Data") and how we use your Data. The Privacy Statement also describes how ThoTh Lab uses your content, which is your communications with others; postings or feedback submitted by you to ThoTh Lab via the Services; and the files, photos, documents, audio, digital works, and videos that you upload, store or share through the Services ("Your Content"). By using the Services or agreeing to these Terms, you consent to ThoTh Lab's collection, use and disclosure of Your Content and Data as described in the Privacy Statement.</span>
            </li>
            <li>
                <span style="font-weight: 600">Your Data Rights</span>
                <span style="font-weight: 400">
                ThoTh Lab follows the common practices and regulations to protect users' data. We will not purposely use, share, modify, or erase your data without your consent. In particular, ThoTh Lab is compliance with Family Educational Rights and Privacy Act (FERPA) and General Data Protection Regulation (GDPR).
                </span></li>
            <li>
                <span style="font-weight: 600">Your Content.</span>
                <span style="font-weight: 400">Many of our Services allow you to store or share Your Content or receive material from others. We do not claim ownership of Your Content. Your Content remains Your Content and you are responsible for it.
            <ol type="a">
                <li>When you share Your Content with other people, you understand that they may be able to, on a worldwide basis, use, save, record, reproduce, transmit, display Your Content without compensating you. If you do not want others to have that ability, do not use the Services to share Your Content. You represent and warrant that for the duration of these Terms, you have (and will have) all the rights necessary for Your Content that is uploaded, stored, or shared on or through the Services and that the collection, use, and retention of Your Content will not violate any law or rights of others. ThoTh Lab cannot be held responsible for Your Content or the material others upload, store or share using the Services.</li>
                <li>To the extent necessary to provide the Services to you and others, to protect you and the Services, and to improve ThoTh Lab products and services, you grant to ThoTh Lab a worldwide and royalty-free intellectual property license to use Your Content, for example, to make copies of, retain, transmit, reformat, display, and distribute via communication tools Your Content on the Services. If you publish Your Content in areas of the Service where it is available broadly online without restrictions, Your Content may appear in demonstrations or materials that promote the Service.
                </li>
            </ol>
            </span>
            </li>
            <li>
                <span style="font-weight: 600">Code of Conduct.</span>
                <span style="font-weight: 400">
                <ol type="a">
                    <li>
                    By agreeing to these Terms, you are agreeing that, when using the Services, you will follow these rules:<br>
                    1)	Do not do anything illegal.<br>
                    2)	Do not engage in any activity that exploits, harms, or threatens to harm children.<br>
                    3)	Do not send spam. Spam is unwanted or unsolicited bulk email, postings, contact requests, SMS (text messages), or instant messages.<br>
                        4)	Do not publicly display or use the Services to share inappropriate Content or material (involving, for example, nudity, bestiality, pornography, graphic violence, or criminal activity).<br>
                    5)	Do not engage in activity that is false or misleading (e.g., asking for money under false pretenses, impersonating someone else, manipulating the Services to increase play count, or affect rankings, ratings, or comments).<br>
                        6)	Do not circumvent any restrictions on access to or availability of the Services.<br>
                        7)	Do not engage in activity that is harmful to you, the Services, or others (e.g., transmitting viruses, stalking, posting terrorist content, communicating hate speech, or advocating violence against others). <br>
                        8)	Do not infringe upon the rights of others (e.g., unauthorized sharing of copyrighted music or other copyrighted material, resale or other distribution of photographs).<br>
                        9)	Do not engage in activity that violates the privacy of others.<br>
                        10)	Do not install and use illegal, unlicensed (except open source, or software authorized by its owners), or jail-broken applications. <br>
                        11)	Do not deploy malicious attacks for both internal or external users.<br>
                        12)	Do not help others break these rules.<br>

                    </li>
                    <li>
                    Enforcement. If you violate these Terms, we may stop providing Services to you or we may close your ThoTh Lab account.
                    </li>
                </ol>
            </span>
            </li>
            <li>
                <span style="font-weight: 600">Using Third-Party Apps and Services.</span>
                <span style="font-weight: 400">
            The Services may allow you to access or acquire products, services, websites, links, content, material, games or applications from third parties (companies or people who are not ThoTh Lab) ("Third-Party Apps and Services"). Many of our Services also help you find Third-Party Apps and Services, and you understand that you are directing our Services to provide Third-Party Apps and Services to you. The Third-Party Apps and Services may also allow you to store Your Content or Data with the publisher, provider, or operator of the Third-Party Apps and Services. The Third-Party Apps and Services may present you with a privacy policy or require you to accept additional terms of use before you can install or use the Third-Party App or Service. You should review any additional terms and privacy policies before acquiring or using any Third-Party Apps and Services. Any additional terms do not modify any of these Terms. You are responsible for your dealings with third parties. ThoTh Lab does not license any intellectual property to you as part of any Third-Party Apps and Services and is not responsible for information provided by third parties. </span>
            </li>
            <li>
                <span style="font-weight: 600">Service Availability</span>
                <span style="font-weight: 400">
            <ol type="a">
                <li>The Services, Third-Party Apps and Services, or material or products offered through the Services may be unavailable from time to time, may be offered for a limited time, or may vary depending on your region or device. If you change the location associated with your ThoTh Lab account, you may need to re-acquire the material or applications that were available to you and paid for in your previous region.</li>
<li>
    ThoTh Lab provides continuous services and support for users who have sign-up with our long-term service agreement. We strive to keep the Services up and running; however, all online services suffer occasional disruptions and outages, and ThoTh Lab is not liable for any disruption or loss you may suffer as a result. In the event of an outage, you may not be able to retrieve Your Content or Data that you have stored. We recommend that you regularly backup Your Content that you store on the Services or store using Third-Party Apps and Services.
                </li>
                <li>
                    ThoTh Lab provides intermittent services for users who do not need to use their assigned resources continuously. Based on the intermittent service agreement, ThoTh Lab will monitor your assigned resource and put them in a sleep mode when there are no user-initiated activities for a given time period (or a time threshold). The current time threshold is set to 30 minutes. Entering the sleep mode, your data and application status will be maintained. You can resume the work after you wake up your allocated resources.
                </li>
            </ol>
            </span>
            </li>
            <li>
                <span style="font-weight: 600">Updates to the Services or Software, and Changes to These Terms.</span>
                <span style="font-weight: 400">
            <ol type="a">
                <li>
                    We may change these Terms at any time, and we'll tell you when we do. Using the Services after the changes become effective means you agree to the new terms. If you do not agree to the new terms, you must stop using the Services, close your ThoTh Lab account and, if you are a parent or guardian, help your minor child close his or her ThoTh Lab account.</li>
                <li>
                   Sometimes you'll need software updates to keep using the Services. We may automatically check your version of the software and download software updates or configuration changes. You may also be required to update the software to continue using the Services. Such updates are subject to these Terms unless other terms accompany the updates, in which case, those other terms apply. ThoTh Lab is not obligated to make any updates available and we do not guarantee that we will support the version of the system for which you licensed the software.</li>
                <li>
Additionally, there may be times when we need to remove or change features or functionality of the Service or stop providing a Service or access to Third-Party Apps and Services altogether. Except to the extent required by applicable law, we have no obligation to provide a re-download or replacement of any material, Digital Goods, or applications previously purchased. We may release the Services or their features in a beta version, which may not work correctly or in the same way the final version may work.</li>
                <li>
So that you can use material protected with digital rights management (DRM), like some music, games, movies and more, DRM software may automatically contact an online rights server and download and install DRM updates.</li>
            </ol>
            </span>
            </li>
            <li>
                <span style="font-weight: 600">Software License.</span>
                <span style="font-weight: 400">
Unless accompanied by a separate ThoTh Lab license agreement, any software provided by us to you as part of the Services is subject to these Terms.
            <ol type="a">
                <li>If you comply with these Terms, we grant you the right to install and use one copy of the software per device on a worldwide basis for use by only one person at a time as part of your use of the Services. The software or website that is part of the Services may include third-party code. Any third-party scripts or code, linked to or referenced from the software or website, are licensed to you by the third parties that own such code, not by ThoTh Lab. Notices, if any, for the third-party code are included for your information only.
                </li>
                <li>
The software is licensed, not sold, and ThoTh Lab reserves all rights to the software not expressly granted by ThoTh Lab, whether by implication, estoppel, or otherwise. This license does not give you any right to, and you may not: <br>
1)  circumvent or bypass any technological protection measures in or relating to the software or Services;<br>
2)	disassemble, decompile, decrypt, hack, emulate, exploit, or reverse engineer any software or other aspect of the Services that is included in or accessible through the Services, except and only to the extent that the applicable copyright law expressly permits doing so;<br>
3)	separate components of the software or Services for use on different devices;<br>
4)	publish, copy, rent, lease, sell, export, import, distribute, or lend the software or the Services, unless ThoTh Lab expressly authorizes you to do so;<br>
5)	transfer the software, any software licenses, or any rights to access or use the Services; <br>
                    6)  use the Services in any unauthorized way that could interfere with anyone else's use of them or gain access to any service, data, account, or network;<br>
                </li>
            </ol>
            </span>
            </li>
            <li>
                <span style="font-weight: 600">Limitation of Liability.</span>
                <span style="font-weight: 400">If you have any basis for recovering damages (including breach of these Terms), you agree that your exclusive remedy is to recover, from ThoTh Lab or any affiliates, resellers, distributors, Third-Party Apps and Services providers, and vendors, direct damages up to an amount equal to your Services fee for the month during which the breach occurred (or up to $10.00 if the Services are free). You cannot recover any other damages or losses, including direct, consequential, lost profits, special, indirect, incidental, or punitive. These limitations and exclusions apply even if this remedy does not fully compensate you for any losses or fails of its essential purpose or if we knew or should have known about the possibility of the damages. To the maximum extent permitted by law, these limitations and exclusions apply to anything or any claims related to these Terms, the Services, or the software related to the Services. </span>
            </li>
        </ol>
    </div>
    </div>
    </div>
    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="list-inline">
                        {{--<li>--}}
                            {{--<a href="#">Home</a>--}}
                        {{--</li>--}}
                        <li class="footer-menu-divider">&sdot;</li>
                        {{--<li>--}}
                            {{--<a href="{{ URL::asset('about') }}">About</a>--}}
                        {{--</li>--}}
                        {{--<li class="footer-menu-divider">&sdot;</li>--}}
                        <li>
                            <a href="{{ URL::asset('openlabs') }}">Tutorial and Demos </a>
                        </li>
                        <li class="footer-menu-divider">&sdot;</li>
                        <li>
                            <a target="_blank" href="http://www.athenets.com">athenets.com</a>
                        </li>
                        <li class="footer-menu-divider">&sdot;</li>
                        <li>
                            <a target="_blank" href="https://www.thothlab.com/service-agreement">Service Agreement</a>
                        </li>
                        <li class="footer-menu-divider">&sdot;</li>
                        <li>
                            <a target="_blank" href="https://www.thothlab.com/privacy-statement">Privacy Statement</a>
                        </li>
                    </ul><br><br>
                    <ul class="list-inline">
                        <li>
                            <a target="_blank" href="http://www.nsf.gov/awardsearch/showAward?AWD_ID=1622192">
                            <img class="img-responsive" style= "width : 150px" src="img/NSF.jpg" alt="NSF SBIR I award" align="">NSF SBIR Phase I Award
                            </a>
                        </li>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <li>
                            <a target="_blank" href="http://www.reimagine-education.com/awards/reimagine-education-2016-honours-list/">
                            <img class="img-responsive" style= "width : 150px" src="img/award.png" alt="Reimagine EDU" align="middle">ENGINEERING & IT Bronze Award
                            </a>
                        </li>
                    </ul>
                    <p class="copyright text-muted small">Copyright &copy; ThoTh Lab 2015. All Rights Reserved</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- MODAL -->
    <div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="videoModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="modal-video">
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe width="640" height="360" src="" frameborder="0" class="embed-responsive-item" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--<!-- MODAL -->--}}
    {{--<div class="modal fade" id="service-agreement-Modal" tabindex="-1" role="dialog" aria-labelledby="service-agreement-Modal" aria-hidden="true" >--}}
        {{--<div class="modal-dialog" style="height: 600px; width: 700px; overflow-y: scroll">--}}
            {{--<div class="modal-content">--}}
                {{--<div class="modal-header">--}}
                    {{--<h4 style="text-align: center">ThoThLab Service Agreement</h4>--}}
                    {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true"><span aria-hidden="true">&times;</span></button>--}}
                {{--</div>--}}
                {{--<div class="modal-body" style="font-size: x-small;">--}}
                    {{--<p style="font-weight: 600;">--}}
                        {{--ThoTh Lab is compliance with Family Educational Rights and Privacy Act (FERPA).--}}
                        {{--IF YOU LIVE IN (OR YOUR PRINCIPAL PLACE OF BUSINESS IS IN) THE UNITED STATES,--}}
                        {{--PLEASE READ THE BINDING ARBITRATION CLAUSE AND CLASS ACTION WAIVER IN SECTION 15.--}}
                        {{--IT AFFECTS HOW DISPUTES ARE RESOLVED.--}}
                    {{--</p><p style="font-weight: 400;">--}}
                        {{--These terms ("Terms") cover the use of ThoThLab websites and services listed here (the "Services").--}}
                        {{--You accept these Terms by creating a ThothLab account, through your use of the Services,--}}
                        {{--or by continuing to use the Services after being notified of a change to these Terms.--}}
                    {{--</p>--}}
                    {{--<p>--}}
                        {{--<ol>--}}
                        {{--<li>--}}
                            {{--<span style="font-weight: 600">Your Privacy.</span>--}}
                            {{--<span style="font-weight: 400">--}}
                            {{--Your privacy is important to us. Please read the ThoThLab Privacy Statement (the "Privacy Statement") as it describes--}}
                                {{--the types of data we collect from you and your devices ("Data") and how we use your Data.--}}
                                {{--The Privacy Statement also describes how ThoThLab uses your content, which is your communications with others;--}}
                                {{--postings or feedback submitted by you to ThoThlab via the Services; and the files, photos, documents, audio, digital works,--}}
                                {{--and videos that you upload, store or share through the Services ("Your Content").--}}
                                {{--By using the Services or agreeing to these Terms, you consent to ThoThLab's collection,--}}
                                {{--use and disclosure of Your Content and Data as described in the Privacy Statement.</span>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<span style="font-weight: 600">Your Content.</span>--}}
                            {{--<span style="font-weight: 400">Many of our Services allow you to store or share Your Content or receive material from others.--}}
                                {{--We don't claim ownership of Your Content. Your Content remains Your Content and you are responsible for it.--}}
                            {{--<ol type="a">--}}
                                {{--<li>When you share Your Content with other people, you understand that they may be able to, on a worldwide basis, use, save, record, reproduce, transmit, display Your Content without compensating you.--}}
                                    {{--If you do not want others to have that ability, do not use the Services to share Your Content. You represent and warrant that for the duration of these Terms,--}}
                                    {{--you have (and will have) all the rights necessary for Your Content that is uploaded, stored, or shared on or through the Services and that the collection, use, and retention of--}}
                                    {{--Your Content will not violate any law or rights of others. ThoThLab cannot be held responsible for Your Content or the material others upload, store or share using the Services.--}}
                                {{--</li>--}}
                                {{--<li>To the extent necessary to provide the Services to you and others, to protect you and the Services, and to improve ThoThLab products and services,--}}
                                    {{--you grant to ThoThLab a worldwide and royalty-free intellectual property license to use Your Content,--}}
                                    {{--for example, to make copies of, retain, transmit, reformat, display, and distribute via communication tools Your Content on the Services.--}}
                                    {{--If you publish Your Content in areas of the Service where it is available broadly online without restrictions,--}}
                                    {{--Your Content may appear in demonstrations or materials that promote the Service.--}}
                                {{--</li>--}}
                            {{--</ol>--}}
                            {{--</span>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<span style="font-weight: 600">Code of Conduct.</span>--}}
                            {{--<span style="font-weight: 400">--}}
                                {{--<ol type="a">--}}
                                    {{--<li>--}}
                                    {{--By agreeing to these Terms, you're agreeing that, when using the Services, you will follow these rules:<br>--}}
                                    {{--i. Don't do anything illegal.<br>--}}
                                    {{--ii. Don't engage in any activity that exploits, harms, or threatens to harm children.<br>--}}
                                    {{--iii. Don't send spam. Spam is unwanted or unsolicited bulk email, postings, contact requests, SMS (text messages), or instant messages.<br>--}}
                                    {{--iv. Don't publicly display or use the Services to share inappropriate Content or material (involving, for example, nudity, bestiality, pornography, graphic violence, or criminal activity).<br>--}}
                                    {{--v. Don't engage in activity that is false or misleading (e.g., asking for money under false pretenses, impersonating someone else, manipulating the Services to increase play count, or affect rankings, ratings, or comments).<br>--}}
                                    {{--vi. Don't circumvent any restrictions on access to or availability of the Services.<br>--}}
                                    {{--vii. Don't engage in activity that is harmful to you, the Services, or others (e.g., transmitting viruses, stalking, posting terrorist content, communicating hate speech, or advocating violence against others).<br>--}}
                                    {{--viii. Don't infringe upon the rights of others (e.g., unauthorized sharing of copyrighted music or other copyrighted material, resale or other distribution of Bing maps, or photographs).<br>--}}
                                    {{--ix. Don't engage in activity that violates the privacy of others.<br>--}}
                                    {{--x. Don't help others break these rules.--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                    {{--Enforcement. If you violate these Terms, we may stop providing Services to you or we may close your ThoThlab accountt.--}}
                                    {{--</li>--}}
                                {{--</ol>--}}
                            {{--</span>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<span style="font-weight: 600">Using Third-Party Apps and Services.</span>--}}
                            {{--<span style="font-weight: 400">--}}
                            {{--The Services may allow you to access or acquire products, services, websites, links, content, material, games or applications from third parties (companies or people who aren't ThoThLab) ("Third-Party Apps and Services").--}}
                                {{--Many of our Services also help you find Third-Party Apps and Services, and you understand that you are directing our Services to provide Third-Party Apps and Services to you.--}}
                                {{--The Third-Party Apps and Services may also allow you to store Your Content or Data with the publisher, provider, or operator of the Third-Party Apps and Services.--}}
                                {{--The Third-Party Apps and Services may present you with a privacy policy or require you to accept additional terms of use before you can install or use the Third-Party App or Service.--}}
                                {{--You should review any additional terms and privacy policies before acquiring or using any Third-Party Apps and Services. Any additional terms do not modify any of these Terms.--}}
                                {{--You are responsible for your dealings with third parties. ThoThLab does not license any intellectual property to you as part of any Third-Party Apps and Services and is not responsible for information provided by third parties.--}}
                            {{--</span>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<span style="font-weight: 600">Service Availability</span>--}}
                            {{--<span style="font-weight: 400">--}}
                            {{--<ol type="a">--}}
                                {{--<li>--}}
                                    {{--The Services, Third-Party Apps and Services, or material or products offered through the Services may be unavailable from time to time,--}}
                                    {{--may be offered for a limited time, or may vary depending on your region or device.--}}
                                    {{--If you change the location associated with your ThoThLab account, you may need to re-acquire the material or applications that were available to you and paid for in your previous region.--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--We strive to keep the Services up and running; however, all online services suffer occasional disruptions and outages,--}}
                                    {{--and ThoThLab is not liable for any disruption or loss you may suffer as a result.--}}
                                    {{--In the event of an outage, you may not be able to retrieve Your Content or Data that you've stored.--}}
                                    {{--We recommend that you regularly backup Your Content that you store on the Services or store using Third-Party Apps and Services.--}}
                                {{--</li>--}}
                            {{--</ol>--}}
                            {{--</span>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<span style="font-weight: 600">Updates to the Services or Software, and Changes to These Terms.</span>--}}
                            {{--<span style="font-weight: 400">--}}
                            {{--<ol type="a">--}}
                                {{--<li>--}}
                                    {{--We may change these Terms at any time, and we'll tell you when we do. Using the Services after the changes become effective means you agree to the new terms.--}}
                                    {{--If you don't agree to the new terms, you must stop using the Services, close your ThoThLab account and, if you are a parent or guardian, help your minor child close his or her ThoThLab account.--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--Sometimes you'll need software updates to keep using the Services. We may automatically check your version of the software and download software updates or configuration changes.--}}
                                    {{--You may also be required to update the software to continue using the Services. Such updates are subject to these Terms unless other terms accompany the updates, in which case, those other terms apply.--}}
                                    {{--ThoThLab isn't obligated to make any updates available and we don't guarantee that we will support the version of the system for which you licensed the software.--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--Additionally, there may be times when we need to remove or change features or functionality of the Service or stop providing a Service or access to Third-Party Apps and Services altogether.--}}
                                    {{--Except to the extent required by applicable law, we have no obligation to provide a re-download or replacement of any material, Digital Goods (defined in section 14(k)), or applications previously purchased.--}}
                                    {{--We may release the Services or their features in a beta version, which may not work correctly or in the same way the final version may work.--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--So that you can use material protected with digital rights management (DRM), like some music, games, movies and more, DRM software may automatically contact an online rights server and download and install DRM updates.--}}
                                {{--</li>--}}
                            {{--</ol>--}}
                            {{--</span>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<span style="font-weight: 600">Software License.</span>--}}
                            {{--<span style="font-weight: 400">--}}
                            {{--Unless accompanied by a separate ThoThLab license agreement, any software provided by us to you as part of the Services is subject to these Terms.--}}
                            {{--<ol type="a">--}}
                                {{--<li>--}}
                                    {{--If you comply with these Terms, we grant you the right to install and use one copy of the software per device on a worldwide basis for use by only one person at a time as part of your use of the Services.--}}
                                    {{--The software or website that is part of the Services may include third-party code. Any third-party scripts or code, linked to or referenced from the software or website, are licensed to you by the third parties that own such code, not by ThoThLab.--}}
                                    {{--Notices, if any, for the third-party code are included for your information only.--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--The software is licensed, not sold, and ThoThLab reserves all rights to the software not expressly granted by ThoThLab,--}}
                                    {{--whether by implication, estoppel, or otherwise. This license does not give you any right to, and you may not: <br>--}}
                                    {{--i. circumvent or bypass any technological protection measures in or relating to the software or Services;<br>--}}
                                    {{--ii. disassemble, decompile, decrypt, hack, emulate, exploit, or reverse engineer any software or other aspect of the Services that is included in or accessible through the Services, except and only to the extent that the applicable copyright law expressly permits doing so;<br>--}}
                                    {{--iii. separate components of the software or Services for use on different devices;<br>--}}
                                    {{--iv. publish, copy, rent, lease, sell, export, import, distribute, or lend the software or the Services, unless ThoThLab expressly authorizes you to do so;<br>--}}
                                    {{--v. transfer the software, any software licenses, or any rights to access or use the Services;--}}
                                    {{--vi. use the Services in any unauthorized way that could interfere with anyone else's use of them or gain access to any service, data, account, or network;<br>--}}
                                {{--</li>--}}
                            {{--</ol>--}}
                            {{--</span>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<span style="font-weight: 600">Limitation of Liability.</span>--}}
                            {{--<span style="font-weight: 400">--}}
                                {{--If you have any basis for recovering damages (including breach of these Terms), you agree that your exclusive remedy is to recover, from ThoThLab or any affiliates, resellers, distributors, Third-Party Apps and Services providers, and vendors,--}}
                                {{--direct damages up to an amount equal to your Services fee for the month during which the breach occurred (or up to $10.00 if the Services are free).--}}
                                {{--You can't recover any other damages or losses, including direct, consequential, lost profits, special, indirect, incidental, or punitive.--}}
                                {{--These limitations and exclusions apply even if this remedy doesn't fully compensate you for any losses or fails of its essential purpose or if we knew or should have known about the possibility of the damages.--}}
                                {{--To the maximum extent permitted by law, these limitations and exclusions apply to anything or any claims related to these Terms, the Services, or the software related to the Services.--}}
                            {{--</span>--}}
                        {{--</li>--}}
                        {{--</ol>--}}
                    {{--</p>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

    <!-- jQuery Version 1.11.0 -->
    <script src="js/jquery-3.4.1.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.bootstrap-autohidingnavbar.min.js"></script>
    <script src="js/jquery.bgswitcher.js"></script>
    <script>
//        $(".navbar-fixed-top").autoHidingNavbar();
        $(".intro-header").bgswitcher({
            images: ["packages/landing-page/img/laptop_cloud_1.jpg", "packages/landing-page/img/laptop_cloud_2.jpg"],
            effect: "fade",
            interval: 15000
        });

        $('#videoLink').on('click', function(e){
            var src = "https://www.youtube.com/embed/wCi3supgX9s?autoplay=1&rel=0";
            $('#videoModal iframe').attr('src', src);
        });

        $('#videoModal button.close').on('hidden.bs.modal', function () {
            $('#videoModal iframe').removeAttr("src", $('#videoModal iframe').removeAttr("src"));
        });

        $('#videoModal').on('hidden.bs.modal', function(e) {
            $('#videoModal iframe').removeAttr("src", $('#videoModal iframe').removeAttr("src"));
        });

    </script>

    @include('sweet::alert')
@stop