@extends('adminlte::layouts.app')



@push('style')
    {{--<link rel="stylesheet" href="{{ URL::asset('packages/vis/dist/vis.css') }}" />--}}
    <link type="text/css" rel="stylesheet" href="/css/jsmind.css" />
    <style type="text/css">
        /*li{margin-top:2px; margin-bottom:2px;}*/
        /*button{width:140px;}*/
        /*select{width:140px;}*/
        /*#layout{width:2000px;}*/
        /*#jsmind_nav{width:210px;height:600px;border:solid 1px #ccc;overflow:auto;float:left;}*/
        /*.file_input{width:100px;}*/
        /*button.sub{width:100px;}*/

        #jsmind_container{
            float:left;
            width: 100%;
            height: 1000px;
            border:solid 1px #ccc;
            background:#f4f4f4;
        }
    </style>
@endpush

@section('htmlheader_title')
    Concept Map
    {{--{{ trans('message.role') }}--}}
@endsection

@section('contentheader_title')
    Concept Map
@endsection

@section('contentheader_description')

@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div id="layout" class="col-md-12">
                <div id="jsmind_nav">
                    <ul style="list-style-type: none">
                        <li>zoom</li>

                        <button id="zoom-in-button" style="width:50px" onclick="zoomIn();">
                            In
                        </button>
                        <button id="zoom-out-button" style="width:50px" onclick="zoomOut();">
                            Out
                        </button>

                    {{--<li><button onclick="screen_shot();">screenshot</button></li>--}}
                    {{--<li><button onclick="show_selected();">get the selected</button></li>--}}
                    {{--<li><button onclick="add_node();">add a node</button></li>--}}
                    {{--<li><button onclick="remove_node();">remove node</button></li>--}}


                    <!-- <li>
                                <select onchange="set_theme(this.value);">
                                    <option value="">default</option>
                                    <option value="primary">primary</option>
                                    <option value="warning">warning</option>
                                    <option value="danger">danger</option>
                                    <option value="success">success</option>
                                    <option value="info">info</option>
                                    <option value="belizehole" selected="selected">belizehole</option>
                                    <option value="nephrite">nephrite</option>
                                    <option value="belizehole">belizehole</option>
                                    <option value="wisteria">wisteria</option>
                                    <option value="asphalt">asphalt</option>
                                    <option value="orange">orange</option>
                                    <option value="pumpkin">pumpkin</option>
                                    <option value="pomegranate">pomegranate</option>
                                    <option value="clouds">clouds</option>
                                    <option value="asbestos">asbestos</option>
                                </select>
                                </li>
                                 -->


                        <li><button class="sub" onclick="expand_all();">Expand all</button></li>
                        <li><button class="sub" onclick="collapse_all();">Collapse all</button></li>
                        <input type="search" id="search" placeholder="Search for something.."><button onclick="jmsearch()">Search</button>

                    </ul>

                </div>
                <div id="jsmind_container"></div>
                <div style="display:none">
                    <input class="file" type="file" id="image-chooser" accept="image/*"/>
                </div>

            </div>

        </div>
    </div>






@endsection


@section('javascript')

@endsection

@push('scripts')
    <script src="{{ URL::asset('js/jsmind.js') }}"></script>
    <script src="{{ URL::asset('js/jsmind.draggable.js') }}"></script>
    <script src="{{ URL::asset('js/jsmind.screenshot.js') }}"></script>
    <script>
        var _jm = null;
        function open_empty(){
            var options = {
                container:'jsmind_container',
                theme:'belizehole',
                editable:false
            }
            setTimeout(
                function(){
                    _jm = jsMind.show(options);
                    // _jm = jsMind.show(options,mind);
                    open_json();
                    if(location.hash.substr(1)===""){
                        collapse_all();
                    }else{
                        jmsearch1(location.hash.substr(1));
                    }
                    $(document).ready(function(){
                        $('.tooltip1').tooltipster({
                            animation: 'fade',
                            delay: 200,
                            theme: 'tooltipster-punk',
                            trigger: 'click',
                            contentAsHTML: true,
                            interactive: true,
                            maxWidth: 300,
                        });
                    });
                },1000);


        }

        function open_json(){
            // var mind = {
            //     "meta":{
            //         "name":"jsMind remote",
            //     },
            //     "format":"node_tree",
            //     "data":{"id":"root","topic":"jsMind","children":[
            //         {"id":"easy","topic":"Easy","direction":"left","children":[
            //             {"id":"easy1","topic":"Easy to show"},
            //             {"id":"easy2","topic":"Easy to edit"},
            //             {"id":"easy3","topic":"Easy to store"},
            //             {"id":"easy4","topic":"Easy to embed"},
            //             {"id":"other3","background-image":"ant.png", "width": "100", "height": "100"}
            //         ]},
            //         {"id":"open","topic":"Open Source","direction":"right","children":[
            //             {"id":"open1","topic":"on GitHub", "background-color":"#eee", "foreground-color":"blue"},
            //             {"id":"open2","topic":"BSD License"}
            //         ]},
            //         {"id":"powerful","topic":"Powerful","direction":"right","children":[
            //             {"id":"powerful1","topic":"Base on Javascript"},
            //             {"id":"powerful2","topic":"Base on HTML5"},
            //             {"id":"powerful3","topic":"Depends on you"}
            //         ]},
            //         {"id":"other","topic":"test node","direction":"left","children":[
            //             {"id":"other1","topic":"I'm from local variable"},
            //             {"id":"other2","topic":"I can do everything"}
            //         ]}
            //     ]}
            // }

            var mind = {
                "meta":{
                    "name":"demo",
                    "version":"0.2",
                },
                "format":"node_array",
                "data":[
                    {"id":"root", "isroot":true, "topic":"network security"},

                    // {"id":"Terms", "parentid":"root", "topic":"Terms"},
                    // {"id":"Error_message", "parentid":"Terms", "topic":"Error_message"},
                    // {"id":"High_availability", "parentid":"Terms", "topic":"High_availability"},
                    // {"id":"OWASP", "parentid":"Terms", "topic":"OWASP"},
                    // {"id":"Indicator", "parentid":"Terms", "topic":"Indicator"},
                    // {"id":"Information_and_communications_technology", "parentid":"Terms", "topic":"Information_and_communications_technology"},
                    // {"id":"Information_exchange", "parentid":"Terms", "topic":"Information_exchange"},
                    // {"id":"Information_technology", "parentid":"Terms", "topic":"Information_technology"},
                    // {"id":"Internet_privacy", "parentid":"Terms", "topic":"Internet_privacy"},
                    // {"id":"Internet_service_provider ", "parentid":"Terms", "topic":"Internet_service_provider "},
                    // {"id":"Knowledge_management", "parentid":"Terms", "topic":"Knowledge_management"},
                    // {"id":"Outsourcing", "parentid":"Terms", "topic":"Outsourcing"},
                    // {"id":"Patch", "parentid":"Terms", "topic":"Patch"},
                    // {"id":"Plaintext", "parentid":"Terms", "topic":"Plaintext"},
                    // {"id":"Research and development", "parentid":"Terms", "topic":"Research and development"},
                    // {"id":"Sandbox", "parentid":"Terms", "topic":"Sandbox"},
                    // {"id":"Security information and event management", "parentid":"sub1", "topic":"Security information and event management"},
                    // {"id":"Technical_support", "parentid":"Terms", "topic":"Technical_support"},
                    // {"id":"System integrity", "parentid":"Terms", "topic":"System integrity"},
                    // {"id":"Confidentiality", "parentid":"Terms", "topic":"Confidentiality"},


                    // {"id":"Computer_security", "parentid":"root", "topic":"Computer_security"},
                    {"id":"information_security", "parentid":"root", "topic":"information_security"},
                    {"id":"Digital_forensics", "parentid":"root", "topic":"Digital_forensics"},
                    {"id":"Cyber_resilience", "parentid":"root", "topic":"Cyber_resilience"},
                    {"id":"Computer_security_incident_management", "parentid":"root", "topic":"Computer_security_incident_management"},
                    {"id":"Confidentiality", "parentid":"root", "topic":"Confidentiality"},

                    {"id":"Application", "parentid":"root", "topic":"Application"},
                    {"id":"Bring_your_own_device", "parentid":"Application", "topic":"Bring_your_own_device"},
                    {"id":"Cloud_computing", "parentid":"Application", "topic":"Cloud_computing"},
                    {"id":"Infrastructure_as_a_service", "parentid":"Application", "topic":"Infrastructure_as_a_service"},
                    {"id":"Wi-Fi", "parentid":"Application", "topic":"Wi-Fi"},
                    {"id":"Machine_learning", "parentid":"Application", "topic":"Machine_learning"},
                    {"id":"Network_service", "parentid":"Application", "topic":"Network_service"},
                    {"id":"OWASP", "parentid":"Application", "topic":"OWASP"},
                    {"id":"Packet_analyzer", "parentid":"Application", "topic":"Packet_analyzer"},
                    {"id":"Risk_analysis", "parentid":"Application", "topic":"Risk_analysis"},
                    {"id":"Rootkit", "parentid":"Application", "topic":"Rootkit"},
                    {"id":"Virtual private network", "parentid":"Application", "topic":"Virtual private network"},
                    {"id":"Sandbox", "parentid":"Application", "topic":"Sandbox"},
                    {"id":"SCADA", "parentid":"Application", "topic":"SCADA"},
                    {"id":"User_behavior_analytics", "parentid":"Application", "topic":"User_behavior_analytics"},


                    {"id":"Asset", "parentid":"root", "topic":"Asset"},
                    {"id":"Critical_Internet_infrastructure", "parentid":"Asset", "topic":"Critical_Internet_infrastructure"},
                    {"id":"Cyberinfrastructure", "parentid":"Asset", "topic":"Cyberinfrastructure"},
                    {"id":"Digital_asset", "parentid":"Asset", "topic":"Digital_asset"},
                    {"id":"Digital_footprint", "parentid":"Asset", "topic":"Digital_footprint"},
                    {"id":"Data_administration", "parentid":"Data", "topic":"Data_administration"},
                    {"id":"Data_aggregation", "parentid":"Data", "topic":"Data_aggregation"},
                    {"id":"Data_breach", "parentid":"Data", "topic":"Data_breach"},
                    {"id":"Data_exfiltration", "parentid":"Data", "topic":"Data_exfiltration"},
                    {"id":"Data_integrity", "parentid":"Data", "topic":"Data_integrity"},
                    {"id":"Data_loss", "parentid":"Data", "topic":"Data_loss"},
                    {"id":"Data_loss_prevention_software", "parentid":"Data", "topic":"Data_loss_prevention_software"},
                    {"id":"Data_mining", "parentid":"Data", "topic":"Data_mining"},
                    {"id":"Data_recovery", "parentid":"Data", "topic":"Data_recovery"},
                    {"id":"Data_theft", "parentid":"Data", "topic":"Data_theft"},
                    {"id":"Digital_rights_management", "parentid":"Data", "topic":"Digital_rights_management"},
                    {"id":"Personal_data", "parentid":"Data", "topic":"Personal_data"},
                    {"id":"Plaintext", "parentid":"Data", "topic":"Plaintext"},
                    {"id":"Threat_intelligence", "parentid":"Data", "topic":"Threat_intelligence"},

                    {"id":"Threat", "parentid":"root", "topic":"Digital_Threat"},
                    {"id":"Advanced_persistent_threat", "parentid":"Digital_Threat", "topic":"Advanced_persistent_threat"},
                    {"id":"Common_Vulnerabilities_and_Exposures", "parentid":"Digital_Threat", "topic":"Common_Vulnerabilities_and_Exposures"},
                    {"id":"Exploit", "parentid":"Digital_Threat", "topic":"Exploit"},
                    {"id":"Insider_threat", "parentid":"Digital_Threat", "topic":"Insider_threat"},
                    {"id":"Vulnerability", "parentid":"Digital_Threat", "topic":"Vulnerability"},
                    {"id":"Leakage", "parentid":"Digital_Threat", "topic":"Leakage"},
                    {"id":"Social engineering", "parentid":"Digital_Threat", "topic":"Social engineering"},
                    {"id":"Software_bug", "parentid":"Digital_Threat", "topic":"Software_bug"},
                    {"id":"Spamming ", "parentid":"Digital_Threat", "topic":"Spamming "},
                    {"id":"Threat actor", "parentid":"Digital_Threat", "topic":"Threat actor"},
                    {"id":"Threat_assessment", "parentid":"Digital_Threat", "topic":"Threat_assessment"},
                    {"id":"Threat_intelligence_", "parentid":"Digital_Threat", "topic":"Threat_intelligence_"},

                    {"id":"Attack", "parentid":"Digital_Threat", "topic":"Attack"},
                    {"id":"Attack_patterns", "parentid":"Attack", "topic":"Attack_patterns"},
                    {"id":"Attack_surface", "parentid":"Attack", "topic":"Attack_surface"},
                    {"id":"Attack_tree", "parentid":"Attack", "topic":"Attack_tree"},
                    {"id":"Bot", "parentid":"Attack", "topic":"Bot"},
                    {"id":"Cyber_spying", "parentid":"Attack", "topic":"Cyber_spying"},
                    {"id":"Cyberattack", "parentid":"Attack", "topic":"Cyberattack"},
                    {"id":"Distributed_denial-of-service_attacks_on_root_nameservers", "parentid":"Attack", "topic":"Distributed_denial-of-service_attacks_on_root_nameservers"},
                    {"id":"Denial-of-service_attack", "parentid":"Attack", "topic":"Denial-of-service_attack"},
                    {"id":"Disruption", "parentid":"Attack", "topic":"Disruption"},
                    {"id":"Drive-by_download", "parentid":"Attack", "topic":"Drive-by_download"},
                    {"id":"Hacker", "parentid":"Attack", "topic":"Hacker"},
                    {"id":"Hacktivism", "parentid":"Attack", "topic":"Hacktivism"},
                    {"id":"Voice phishing", "parentid":"Attack", "topic":"Voice phishing"},
                    {"id":"Keystroke_logging", "parentid":"Attack", "topic":"Keystroke_logging"},
                    {"id":"List_of_cyberattacks", "parentid":"Attack", "topic":"List_of_cyberattacks"},
                    {"id":"Logic_bomb", "parentid":"Attack", "topic":"Logic_bomb"},
                    {"id":"Macro_virus", "parentid":"Attack", "topic":"Macro_virus"},
                    {"id":"Malware", "parentid":"Attack", "topic":"Malware"},
                    {"id":"Network_eavesdropping", "parentid":"Attack", "topic":"Network_eavesdropping"},
                    {"id":"Passive_attack", "parentid":"Attack", "topic":"Passive_attack"},
                    {"id":"Phishing", "parentid":"Attack", "topic":"Phishing"},
                    {"id":"Point-of-sale_malware", "parentid":"Attack", "topic":"Point-of-sale_malware"},
                    {"id":"Ransomware", "parentid":"Attack", "topic":"Ransomware"},
                    {"id":"Rootkit", "parentid":"Attack", "topic":"Rootkit"},
                    {"id":"Sniffing attack", "parentid":"Attack", "topic":"Sniffing attack"},
                    {"id":"Social engineering", "parentid":"Attack", "topic":"Social engineering"},
                    {"id":"Software_bug", "parentid":"Attack", "topic":"Software_bug"},
                    {"id":"Trojan_horse", "parentid":"Attack", "topic":"Trojan_horse"},
                    {"id":"Social_jacking", "parentid":"Attack", "topic":"Social_jacking"},
                    {"id":"Spamming", "parentid":"Attack", "topic":"Spamming"},
                    {"id":"Spoofing attack", "parentid":"Attack", "topic":"Spoofing attack"},

                    {"id":"Bot_herder", "parentid":"Bot", "topic":"Bot_herder"},
                    {"id":"Botnet", "parentid":"Bot", "topic":"Botnet"},
                    {"id":"Zombie", "parentid":"Bot", "topic":"Zombie"},


                    {"id":"Defense1", "parentid":"Digital_Threat", "topic":"Defense"},
                    {"id":"Defense", "parentid":"root", "topic":"Defense"},
                    {"id":"Antivirus_software", "parentid":"Defense", "topic":"Antivirus_software"},
                    {"id":"Active_defense", "parentid":"Defense", "topic":"Active_defense"},
                    {"id":"Access_control", "parentid":"Defense", "topic":"Access_control"},
                    {"id":"Blacklisting", "parentid":"Defense", "topic":"Blacklisting"},
                    {"id":"Computer_forensics", "parentid":"Defense", "topic":"Computer_forensics"},
                    {"id":"Dynamic_defence", "parentid":"Defense", "topic":"Dynamic_defence"},
                    {"id":"Emergency_management", "parentid":"Defense", "topic":"Emergency_management"},
                    {"id":"Enterprise_risk_management", "parentid":"Defense", "topic":"Enterprise_risk_management"},
                    {"id":"firewall", "parentid":"Defense", "topic":"Firewall","data":{"desc":"<div>a firewall is a network security system that monitors and controls incoming and outgoing network traffic based on predetermined security rules. A firewall typically establishes a barrier between a trusted network and an untrusted network, such as the Internet.</div><br/><a target='_blank' href='https://en.wikipedia.org/wiki/Firewall_(computing)'>Wikipeida Link</a>"}},
                    {"id":"Honeypot", "parentid":"Defense", "topic":"Honeypot"},
                    {"id":"Incident_management", "parentid":"Defense", "topic":"Incident_management"},
                    {"id":"Identity_management", "parentid":"Defense", "topic":"Identity_management"},
                    {"id":"Information security management", "parentid":"Defense", "topic":"Information security management"},
                    {"id":"Information_assurance", "parentid":"Defense", "topic":"Information_assurance"},
                    {"id":"Information_security", "parentid":"Defense", "topic":"Information_security"},
                    {"id":"Information_security_operations_center", "parentid":"Defense", "topic":"Information_security_operations_center"},
                    {"id":"Integrated_Risk_Management_Services", "parentid":"Defense", "topic":"Integrated_Risk_Management_Services"},
                    {"id":"Intrusion_detection_system", "parentid":"Defense", "topic":"Intrusion_detection_system"},
                    {"id":"IT_risk_management", "parentid":"Defense", "topic":"IT_risk_management"},
                    {"id":"Vulnerability management", "parentid":"Defense", "topic":"Vulnerability management"},
                    {"id":"Mitigation", "parentid":"Defense", "topic":"Mitigation"},
                    {"id":"Multi-factor authentication", "parentid":"Defense", "topic":"Multi-factor authentication"},
                    {"id":"OWASP", "parentid":"Defense", "topic":"OWASP"},
                    {"id":"Penetration_test", "parentid":"Defense", "topic":"Penetration_test"},
                    {"id":"Patch", "parentid":"Defense", "topic":"Patch"},
                    {"id":"Redundancy", "parentid":"Defense", "topic":"Redundancy"},
                    {"id":"Risk_analysis", "parentid":"Defense", "topic":"Risk_analysis"},
                    {"id":"Risk_assessment", "parentid":"Defense", "topic":"Risk_assessment"},
                    {"id":"SCADA", "parentid":"Defense", "topic":"SCADA"},
                    {"id":"Security information and event management", "parentid":"Defense", "topic":"Security information and event management"},
                    {"id":"Threat_assessment", "parentid":"Defense", "topic":"Threat_assessment"},
                    {"id":"User_behavior_analytics", "parentid":"Defense", "topic":"User_behavior_analytics"},
                    {"id":"Software security assurance", "parentid":"Defense", "topic":"Software security assurance"},
                    {"id":"Software assurance", "parentid":"Defense", "topic":"Software assurance"},



                    {"id":"encryption", "parentid":"root", "topic":"Cryptography"},
                    {"id":"Cipher", "parentid":"Cryptography", "topic":"Cipher"},
                    {"id":"Ciphertext", "parentid":"Cryptography", "topic":"Ciphertext"},
                    {"id":"Decipherment", "parentid":"Cryptography", "topic":"Decipherment"},
                    {"id":"Digital_signature", "parentid":"Cryptography", "topic":"Digital_signature"},
                    {"id":"Electronic_signature", "parentid":"Cryptography", "topic":"Electronic_signature"},
                    {"id":"Encode", "parentid":"Cryptography", "topic":"Encode"},
                    {"id":"Encryption", "parentid":"Cryptography", "topic":"Encryption"},
                    {"id":"Hash_function", "parentid":"Cryptography", "topic":"Hash_function"},
                    {"id":"Key", "parentid":"Cryptography", "topic":"Key"},
                    {"id":"Unique_key", "parentid":"Cryptography", "topic":"Unique_key"},
                    {"id":"Public_key_certificate", "parentid":"Cryptography", "topic":"Public_key_certificate"},
                    {"id":"Password", "parentid":"Cryptography", "topic":"Password"},
                    {"id":"Public_key_infrastructure", "parentid":"Cryptography", "topic":"Public_key_infrastructure"},
                    {"id":"Public-key_cryptography", "parentid":"Cryptography", "topic":"Public-key_cryptography"},
                    {"id":"Plaintext", "parentid":"Cryptography", "topic":"Plaintext"},

                    {"id":"Network", "parentid":"root", "topic":"Network"},
                    {"id":"Network_security", "parentid":"Network", "topic":"Network_security"},
                    {"id":"Demilitarized_zone", "parentid":"Network", "topic":"Demilitarized_zone"},
                    {"id":"Firewall", "parentid":"Network", "topic":"Firewall"},
                    {"id":"Honeypot", "parentid":"Network", "topic":"Honeypot"},
                    {"id":"Local_area_network", "parentid":"Network", "topic":"Local_area_network"},
                    {"id":"Network_security", "parentid":"Network", "topic":"Network_security"},
                    {"id":"Network_service", "parentid":"Network", "topic":"Network_service"},
                    {"id":"Resilience", "parentid":"Network", "topic":"Resilience"},
                    {"id":"sub13", "parentid":"Network", "topic":"sub13"},
                    {"id":"Cipher", "parentid":"Network", "topic":"sub12"},
                    {"id":"sub13", "parentid":"Network", "topic":"sub13"},
                    {"id":"sub11", "parentid":"Network", "topic":"sub11"},
                    {"id":"sub12", "parentid":"Network", "topic":"sub12"},
                    {"id":"sub13", "parentid":"Network", "topic":"sub13"},


                ]
            };
            var cns00001 = {
                "meta":{
                    "name":"cns00001",
                    "version":"1.0",
                },
                "format":"node_array",
                "data":[
                    {"id":"root", "isroot":true, "topic":"Packet Filter Firewall Lab"},


                    {"id":"task1", "parentid":"root", "topic":"How to prepare for this Lab?"},
                    {"id":"task2", "parentid":"root", "topic":"How to test network connectivity?"},
                    {"id":"task3", "parentid":"root", "topic":"How to test installed software and service?"},
                    {"id":"task4", "parentid":"root", "topic":"How to reset firewall to whitelist?"},
                    {"id":"task5", "parentid":"root", "topic":"How to setup stateless Packet Filter Firewall?"},

                    {"id":"packet", "parentid":"task1", "topic":"packet","data":{"desc":"<div>A packet is a formatted unit of data carried by a packet-switched network. A packet consists of control information and user data; the latter is also known as the payload. Control information provides data for delivering the payload (e.g., source and destination network addresses, error detection codes, or sequencing information). Typically, control information is found in packet headers and trailers.</div><br/><a href='https://en.wikipedia.org/wiki/Network_packet' target='_blank'>Wikipeida Link</a><br/>Also appears in:<ul><li ><a href='../content/CNS-00003'>CS-CNS-00003,</a></li> <li><a href='../content/CNS-10001'>CS-CNS-10001,</a></li> <li> <a href='../content/CNS-20001'>CS-CNS-20001,</a></li> <li><a href='../content/CNS-20002'>CS-CNS-20002</a></li></ul>"}},
                    {"id":"firewall", "parentid":"task1", "topic":"firewall","data":{"desc":"<div>a firewall is a network security system that monitors and controls incoming and outgoing network traffic based on predetermined security rules. A firewall typically establishes a barrier between a trusted network and an untrusted network, such as the Internet.</div><br/><a target='_blank' href='https://en.wikipedia.org/wiki/Firewall_(computing)'>Wikipeida Link</a>"}},
                    {"id":"filter", "parentid":"task1", "topic":"filter","data":{"desc":"<div>Firewall, especially a packet filter, to control inbound and outbound network traffic at the device or local-area-network level.</div><br/><a href='https://en.wikipedia.org/wiki/Firewall_(computing)' target='_blank'>Wikipeida Link</a>"}},
                    {"id":"iptables", "parentid":"task1", "topic":"iptables","data":{"desc":"<div>iptables is a user-space utility program that allows a system administrator to configure the IP packet filter rules of the Linux kernel firewall, implemented as different Netfilter modules. The filters are organized in different tables, which contain chains of rules for how to treat network traffic packets. Different kernel modules and programs are currently used for different protocols; iptables applies to IPv4, ip6tables to IPv6, arptables to ARP, and ebtables to Ethernet frames. </div><br/><a target='_blank' href='https://en.wikipedia.org/wiki/Iptables'>Wikipeida Link</a>"}},
                    {"id":"zip", "parentid":"task1", "topic":"zip","data":{"desc":"<div></div><br/><a target='_blank' href='https://en.wikipedia.org/wiki/Iptables'>Wikipeida Link</a>"}},
                    {"id":"download", "parentid":"task1", "topic":"download","data":{"desc":"<div></div><br/><a target='_blank' href='https://en.wikipedia.org/wiki/Iptables'>Wikipeida Link</a>"}},
                    {"id":"permission", "parentid":"task1", "topic":"permission","data":{"desc":"<div></div><br/><a target='_blank' href='https://en.wikipedia.org/wiki/Iptables'>Wikipeida Link</a>"}},
                    {"id":"shell script", "parentid":"task1", "topic":"shell script","data":{"desc":"<div></div><br/><a target='_blank' href='https://en.wikipedia.org/wiki/Iptables'>Wikipeida Link</a>"}},


                    {"id":"default gateway", "parentid":"task2", "topic":"default gateway","data":{"desc":"<div></div><br/><a target='_blank' href='https://en.wikipedia.org/wiki/Iptables'>Wikipeida Link</a>"}},
                    {"id":"ping", "parentid":"task2", "topic":"ping","data":{"desc":"<div></div><br/><a target='_blank' href='https://en.wikipedia.org/wiki/Iptables'>Wikipeida Link</a>"}},
                    {"id":"VM", "parentid":"task2", "topic":"VM","data":{"desc":"<div></div><br/><a target='_blank' href='https://en.wikipedia.org/wiki/Iptables'>Wikipeida Link</a>"}},
                    {"id":"ufw", "parentid":"task2", "topic":"ufw","data":{"desc":"<div></div><br/><a target='_blank' href='https://en.wikipedia.org/wiki/Iptables'>Wikipeida Link</a>"}},
                    {"id":"packet forwarding", "parentid":"task2", "topic":"packet forwarding","data":{"desc":"<div></div><br/><a target='_blank' href='https://en.wikipedia.org/wiki/Iptables'>Wikipeida Link</a>"}},
                    {"id":"blacklist", "parentid":"task2", "topic":"blacklist","data":{"desc":"<div></div><br/><a target='_blank' href='https://en.wikipedia.org/wiki/Iptables'>Wikipeida Link</a>"}},
                    {"id":"gateway", "parentid":"task2", "topic":"gateway","data":{"desc":"<div></div><br/><a target='_blank' href='https://en.wikipedia.org/wiki/Iptables'>Wikipeida Link</a>"}},

                    {"id":"Apache2", "parentid":"task3", "topic":"Apache2","data":{"desc":"<div></div><br/><a target='_blank' href='https://en.wikipedia.org/wiki/Iptables'>Wikipeida Link</a>"}},
                    {"id":"bind9", "parentid":"task3", "topic":"bind9","data":{"desc":"<div></div><br/><a target='_blank' href='https://en.wikipedia.org/wiki/Iptables'>Wikipeida Link</a>"}},
                    {"id":"DNS", "parentid":"task3", "topic":"DNS","data":{"desc":"<div></div><br/><a target='_blank' href='https://en.wikipedia.org/wiki/Iptables'>Wikipeida Link</a>"}},
                    {"id":"SSH", "parentid":"task3", "topic":"SSH","data":{"desc":"<div></div><br/><a target='_blank' href='https://en.wikipedia.org/wiki/Iptables'>Wikipeida Link</a>"}},
                    {"id":"FTP", "parentid":"task3", "topic":"FTP","data":{"desc":"<div></div><br/><a target='_blank' href='https://en.wikipedia.org/wiki/Iptables'>Wikipeida Link</a>"}},
                    {"id":"URL", "parentid":"task3", "topic":"URL","data":{"desc":"<div></div><br/><a target='_blank' href='https://en.wikipedia.org/wiki/Iptables'>Wikipeida Link</a>"}},

                    {"id":"whitelist", "parentid":"task4", "topic":"whitelist","data":{"desc":"<div></div><br/><a target='_blank' href='https://en.wikipedia.org/wiki/Iptables'>Wikipeida Link</a>"}},

                    {"id":"DROP", "parentid":"task5", "topic":"DROP","data":{"desc":"<div></div><br/><a target='_blank' href='https://en.wikipedia.org/wiki/Iptables'>Wikipeida Link</a>"}},
                    {"id":"INPUT", "parentid":"task5", "topic":"INPUT","data":{"desc":"<div></div><br/><a target='_blank' href='https://en.wikipedia.org/wiki/Iptables'>Wikipeida Link</a>"}},
                    {"id":"OUTPUT", "parentid":"task5", "topic":"OUTPUT","data":{"desc":"<div></div><br/><a target='_blank' href='https://en.wikipedia.org/wiki/Iptables'>Wikipeida Link</a>"}},
                    {"id":"FORWARD", "parentid":"task5", "topic":"FORWARD","data":{"desc":"<div></div><br/><a target='_blank' href='https://en.wikipedia.org/wiki/Iptables'>Wikipeida Link</a>"}},
                    {"id":"NAT", "parentid":"task5", "topic":"NAT","data":{"desc":"<div></div><br/><a target='_blank' href='https://en.wikipedia.org/wiki/Iptables'>Wikipeida Link</a>"}},
                    {"id":"PREROUTING", "parentid":"task5", "topic":"PREROUTING","data":{"desc":"<div></div><br/><a target='_blank' href='https://en.wikipedia.org/wiki/Iptables'>Wikipeida Link</a>"}},
                    {"id":"POSTROUTING", "parentid":"task5", "topic":"POSTROUTING","data":{"desc":"<div></div><br/><a target='_blank' href='https://en.wikipedia.org/wiki/Iptables'>Wikipeida Link</a>"}}



                ]
            };
            _jm.show(mind);
        }

        function open_ajax(){
            var mind_url = 'data_example.json';
            jsMind.util.ajax.get(mind_url,function(mind){
                _jm.show(mind);
            });
        }

        function screen_shot(){
            _jm.screenshot.shootDownload();
        }

        function show_data(){
            var mind_data = _jm.get_data();
            var mind_string = jsMind.util.json.json2string(mind_data);
            prompt_info(mind_string);
        }

        function save_file(){
            var mind_data = _jm.get_data();
            var mind_name = mind_data.meta.name;
            var mind_str = jsMind.util.json.json2string(mind_data);
            jsMind.util.file.save(mind_str,'text/jsmind',mind_name+'.jm');
        }

        function open_file(){
            var file_input = document.getElementById('file_input');
            var files = file_input.files;
            if(files.length > 0){
                var file_data = files[0];
                jsMind.util.file.read(file_data,function(jsmind_data, jsmind_name){
                    var mind = jsMind.util.json.string2json(jsmind_data);
                    if(!!mind){
                        _jm.show(mind);
                    }else{
                        prompt_info('can not open this file as mindmap');
                    }
                });
            }else{
                prompt_info('please choose a file first')
            }
        }

        function select_node(){
            var nodeid = 'other';
            _jm.select_node(nodeid);
        }

        function show_selected(){
            var selected_node = _jm.get_selected_node();
            if(!!selected_node){
                prompt_info(selected_node.topic);
            }else{
                prompt_info('nothing');
            }
        }

        function get_selected_nodeid(){
            var selected_node = _jm.get_selected_node();
            if(!!selected_node){
                return selected_node.id;
            }else{
                return null;
            }
        }

        function add_node(){
            var selected_node = _jm.get_selected_node(); // as parent of new node
            if(!selected_node){prompt_info('please select a node first.');return;}

            var nodeid = jsMind.util.uuid.newid();
            var topic = '* Node_'+nodeid.substr(nodeid.length-6)+' *';
            var node = _jm.add_node(selected_node, nodeid, topic);
        }

        //        var imageChooser = document.getElementById('image-chooser');
        //
        //        imageChooser.addEventListener('change', function (event) {
        //            // Read file here.
        //            var reader = new FileReader();
        //            reader.onloadend = (function () {
        //                var selected_node = _jm.get_selected_node();
        //                var nodeid = jsMind.util.uuid.newid();
        //                var topic = undefined;
        //                var data = {
        //                    "background-image": reader.result,
        //                    "width": "100",
        //                    "height": "100"};
        //                var node = _jm.add_node(selected_node, nodeid, topic, data);
        //                //var node = _jm.add_image_node(selected_node, nodeid, reader.result, 100, 100);
        //                //add_image_node:function(parent_node, nodeid, image, width, height, data, idx, direction, expanded){
        //            });
        //
        //            var file = imageChooser.files[0];
        //            if (file) {
        //                reader.readAsDataURL(file);
        //            };
        //
        //        }, false);
        //
        //        function add_image_node(){
        //            var selected_node = _jm.get_selected_node(); // as parent of new node
        //            if(!selected_node){
        //                prompt_info('please select a node first.');
        //                return;
        //            }
        //
        //            imageChooser.focus();
        //            imageChooser.click();
        //        }

        function modify_node(){
            var selected_id = get_selected_nodeid();
            if(!selected_id){prompt_info('please select a node first.');return;}

            // modify the topic
            _jm.update_node(selected_id, '--- modified ---');
        }

        function move_to_first(){
            var selected_id = get_selected_nodeid();
            if(!selected_id){prompt_info('please select a node first.');return;}

            _jm.move_node(selected_id,'_first_');
        }

        function move_to_last(){
            var selected_id = get_selected_nodeid();
            if(!selected_id){prompt_info('please select a node first.');return;}

            _jm.move_node(selected_id,'_last_');
        }

        function move_node(){
            // move a node before another
            _jm.move_node('other','open');
        }

        function remove_node(){
            var selected_id = get_selected_nodeid();
            if(!selected_id){prompt_info('please select a node first.');return;}

            _jm.remove_node(selected_id);
        }

        function change_text_font(){
            var selected_id = get_selected_nodeid();
            if(!selected_id){prompt_info('please select a node first.');return;}

            _jm.set_node_font_style(selected_id, 28);
        }

        function change_text_color(){
            var selected_id = get_selected_nodeid();
            if(!selected_id){prompt_info('please select a node first.');return;}

            _jm.set_node_color(selected_id, null, '#000');
        }

        function change_background_color(){
            var selected_id = get_selected_nodeid();
            if(!selected_id){prompt_info('please select a node first.');return;}

            _jm.set_node_color(selected_id, '#eee', null);
        }

        function change_background_image(){
            var selected_id = get_selected_nodeid();
            if(!selected_id){prompt_info('please select a node first.');return;}

            _jm.set_node_background_image(selected_id, 'ant.png', 100, 100);
        }

        function set_theme(theme_name){
            _jm.set_theme(theme_name);
        }

        var zoomInButton = document.getElementById("zoom-in-button");
        var zoomOutButton = document.getElementById("zoom-out-button");

        function zoomIn() {
            if (_jm.view.zoomIn()) {
                zoomOutButton.disabled = false;
            } else {
                zoomInButton.disabled = true;
            };
        };

        function zoomOut() {
            if (_jm.view.zoomOut()) {
                zoomInButton.disabled = false;
            } else {
                zoomOutButton.disabled = true;
            };
        };

        function toggle_editable(btn){
            var editable = _jm.get_editable();
            if(editable){
                _jm.disable_edit();
                btn.innerHTML = 'enable editable';
            }else{
                _jm.enable_edit();
                btn.innerHTML = 'disable editable';
            }
        }

        // this method change size of container, perpare for adjusting jsmind
        function change_container(){
            var c = document.getElementById('jsmind_container');
            c.style.width = '1200px';
            c.style.height = '500px';
        }

        function resize_jsmind(){
            _jm.resize();
        }

        function expand(){
            var selected_id = get_selected_nodeid();
            if(!selected_id){prompt_info('please select a node first.');return;}

            _jm.expand_node(selected_id);
        }

        function collapse(){
            var selected_id = get_selected_nodeid();
            if(!selected_id){prompt_info('please select a node first.');return;}

            _jm.collapse_node(selected_id);
        }

        function toggle(){
            var selected_id = get_selected_nodeid();
            if(!selected_id){prompt_info('please select a node first.');return;}

            _jm.toggle_node(selected_id);
        }

        function expand_all(){
            _jm.expand_all();
        }

        function expand_to_level2(){
            _jm.expand_to_depth(2);
        }

        function expand_to_level3(){
            _jm.expand_to_depth(3);
        }

        function collapse_all(){
            _jm.collapse_all();
        }


        function get_nodearray_data(){
            var mind_data = _jm.get_data('node_array');
            var mind_string = jsMind.util.json.json2string(mind_data);
            prompt_info(mind_string);
        }

        function save_nodearray_file(){
            var mind_data = _jm.get_data('node_array');
            var mind_name = mind_data.meta.name;
            var mind_str = jsMind.util.json.json2string(mind_data);
            jsMind.util.file.save(mind_str,'text/jsmind',mind_name+'.jm');
        }

        function open_nodearray(){
            var file_input = document.getElementById('file_input_nodearray');
            var files = file_input.files;
            if(files.length > 0){
                var file_data = files[0];
                jsMind.util.file.read(file_data,function(jsmind_data, jsmind_name){
                    var mind = jsMind.util.json.string2json(jsmind_data);
                    if(!!mind){
                        _jm.show(mind);
                    }else{
                        prompt_info('can not open this file as mindmap');
                    }
                });
            }else{
                prompt_info('please choose a file first')
            }
        }

        function get_freemind_data(){
            var mind_data = _jm.get_data('freemind');
            var mind_string = jsMind.util.json.json2string(mind_data);
            alert(mind_string);
        }

        function save_freemind_file(){
            var mind_data = _jm.get_data('freemind');
            var mind_name = mind_data.meta.name || 'freemind';
            var mind_str = mind_data.data;
            jsMind.util.file.save(mind_str,'text/xml',mind_name+'.mm');
        }

        function open_freemind(){
            var file_input = document.getElementById('file_input_freemind');
            var files = file_input.files;
            if(files.length > 0){
                var file_data = files[0];
                jsMind.util.file.read(file_data, function(freemind_data, freemind_name){
                    if(freemind_data){
                        var mind_name = freemind_name;
                        if(/.*\.mm$/.test(mind_name)){
                            mind_name = freemind_name.substring(0,freemind_name.length-3);
                        }
                        var mind = {
                            "meta":{
                                "name":mind_name,

                            },
                            "format":"freemind",
                            "data":freemind_data
                        };
                        _jm.show(mind);
                    }else{
                        prompt_info('can not open this file as mindmap');
                    }
                });
            }else{
                prompt_info('please choose a file first')
            }
        }

        function prompt_info(msg){
            alert(msg);
        }

        function jmsearch(){
            var nodeid=$("#search").val();
            var node=document.getElementById($("#search").val());
            if(node.offsetParent===null){
                expand_all();
            }
            var topPos=node.offsetTop;
//            var leftPos=node.offsetLeft;
            _jm.select_node(nodeid,topPos);

        }

        function jmsearch1(tex){
            var nodeid=tex;
            var node=document.getElementById(tex);
            if(node.offsetParent===null){
                expand_all();
            }
            var topPos=node.offsetTop;
//            var leftPos=node.offsetLeft;
            _jm.select_node(nodeid,topPos);

        }

        open_empty();



    </script>
@endpush