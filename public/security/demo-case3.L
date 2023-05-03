1,"execCode('192.168.1.6',someUser)","OR",0.8704
2,"RULE 2 (remote exploit of a server program)","AND",0.64
3,"netAccess('192.168.1.6',tcp,'22')","OR",0.8
4,"RULE 6 (direct network access)","AND",0.8
5,"hacl(internet,'192.168.1.6',tcp,'22')","LEAF",1.0
6,"attackerLocated(internet)","LEAF",1.0
7,"networkServiceInfo('192.168.1.6',openssh,tcp,'22',someUser)","LEAF",1.0
8,"vulExists('192.168.1.6','CVE-2008-5161',openssh,remoteExploit,privEscalation)","LEAF",1.0
9,"RULE 2 (remote exploit of a server program)","AND",0.64
10,"netAccess('192.168.1.6',tcp,'9090')","OR",0.8
11,"RULE 6 (direct network access)","AND",0.8
12,"hacl(internet,'192.168.1.6',tcp,'9090')","LEAF",1.0
13,"networkServiceInfo('192.168.1.6',safari,tcp,'9090',someUser)","LEAF",1.0
14,"vulExists('192.168.1.6','CVE-2013-2566',safari,remoteExploit,privEscalation)","LEAF",1.0
