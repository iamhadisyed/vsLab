1,"execCode('192.168.1.12',someUser)","OR",1.0
2,"RULE 2 (remote exploit of a server program)","AND",0.7999
3,"netAccess('192.168.1.12',tcp,'22')","OR",0.9999
4,"RULE 5 (multi-hop access)","AND",0.7627
5,"hacl('192.168.1.12','192.168.1.12',tcp,'22')","LEAF",1.0
6,"execCode('192.168.1.12',user)","OR",0.9533
7,"RULE 3 (remote exploit for a client program)","AND",0.64
8,"accessMaliciousInput('192.168.1.12','192.168.1.12_victim',internet_information_services)","OR",0.8
9,"RULE 22 (Browsing a malicious website)","AND",0.8
10,"attackerLocated(internet)","LEAF",1.0
11,"hacl('192.168.1.12',internet,httpProtocol,httpPort)","LEAF",1.0
12,"inCompetent('192.168.1.12_victim')","LEAF",1.0
13,"hasAccount('192.168.1.12_victim','192.168.1.12',user)","LEAF",1.0
14,"vulExists('192.168.1.12','CVE-2003-1567',internet_information_services,remoteClient,privEscalation)","LEAF",1.0
15,"RULE 3 (remote exploit for a client program)","AND",0.64
16,"accessMaliciousInput('192.168.1.12','192.168.1.12_victim',java_system_application_server)","OR",0.8
17,"RULE 22 (Browsing a malicious website)","AND",0.8
18,"vulExists('192.168.1.12','CVE-2010-0386',java_system_application_server,remoteClient,privEscalation)","LEAF",1.0
19,"RULE 3 (remote exploit for a client program)","AND",0.64
20,"accessMaliciousInput('192.168.1.12','192.168.1.12_victim',weblogic_server)","OR",0.8
21,"RULE 22 (Browsing a malicious website)","AND",0.8
22,"vulExists('192.168.1.12','CVE-2004-2320',weblogic_server,remoteClient,privEscalation)","LEAF",1.0
23,"RULE 5 (multi-hop access)","AND",0.768
24,"hacl('192.168.1.14','192.168.1.12',tcp,'22')","LEAF",1.0
25,"execCode('192.168.1.14',someUser)","OR",0.96
26,"RULE 2 (remote exploit of a server program)","AND",0.8
27,"netAccess('192.168.1.14',tcp,'443')","OR",1.0
28,"RULE 5 (multi-hop access)","AND",0.8
29,"hacl('192.168.1.12','192.168.1.14',tcp,'443')","LEAF",1.0
30,"RULE 5 (multi-hop access)","AND",0.7627
31,"RULE 5 (multi-hop access)","AND",0.8
32,"hacl('192.168.1.5','192.168.1.14',tcp,'443')","LEAF",1.0
33,"execCode('192.168.1.5',someUser)","OR",1.0
34,"RULE 2 (remote exploit of a server program)","AND",0.8
35,"netAccess('192.168.1.5',tcp,'22')","OR",1.0
36,"RULE 5 (multi-hop access)","AND",0.8
37,"hacl('192.168.1.12','192.168.1.5',tcp,'22')","LEAF",1.0
38,"RULE 5 (multi-hop access)","AND",0.7627
39,"RULE 5 (multi-hop access)","AND",0.8
40,"hacl('192.168.1.14','192.168.1.5',tcp,'22')","LEAF",1.0
41,"RULE 5 (multi-hop access)","AND",0.7627
42,"hacl('192.168.1.5','192.168.1.5',tcp,'22')","LEAF",1.0
43,"execCode('192.168.1.5',user)","OR",0.9533
44,"RULE 3 (remote exploit for a client program)","AND",0.64
45,"accessMaliciousInput('192.168.1.5','192.168.1.5_victim',internet_information_services)","OR",0.8
46,"RULE 22 (Browsing a malicious website)","AND",0.8
47,"hacl('192.168.1.5',internet,httpProtocol,httpPort)","LEAF",1.0
48,"inCompetent('192.168.1.5_victim')","LEAF",1.0
49,"hasAccount('192.168.1.5_victim','192.168.1.5',user)","LEAF",1.0
50,"vulExists('192.168.1.5','CVE-2003-1567',internet_information_services,remoteClient,privEscalation)","LEAF",1.0
51,"RULE 3 (remote exploit for a client program)","AND",0.64
52,"accessMaliciousInput('192.168.1.5','192.168.1.5_victim',java_system_application_server)","OR",0.8
53,"RULE 22 (Browsing a malicious website)","AND",0.8
54,"vulExists('192.168.1.5','CVE-2010-0386',java_system_application_server,remoteClient,privEscalation)","LEAF",1.0
55,"RULE 3 (remote exploit for a client program)","AND",0.64
56,"accessMaliciousInput('192.168.1.5','192.168.1.5_victim',weblogic_server)","OR",0.8
57,"RULE 22 (Browsing a malicious website)","AND",0.8
58,"vulExists('192.168.1.5','CVE-2004-2320',weblogic_server,remoteClient,privEscalation)","LEAF",1.0
59,"RULE 5 (multi-hop access)","AND",0.7936
60,"hacl('192.168.1.6','192.168.1.5',tcp,'22')","LEAF",1.0
61,"execCode('192.168.1.6',someUser)","OR",0.992
62,"RULE 2 (remote exploit of a server program)","AND",0.8
63,"netAccess('192.168.1.6',tcp,'22')","OR",1.0
64,"RULE 5 (multi-hop access)","AND",0.8
65,"hacl('192.168.1.12','192.168.1.6',tcp,'22')","LEAF",1.0
66,"RULE 5 (multi-hop access)","AND",0.7627
67,"RULE 5 (multi-hop access)","AND",0.8
68,"hacl('192.168.1.14','192.168.1.6',tcp,'22')","LEAF",1.0
69,"RULE 5 (multi-hop access)","AND",0.8
70,"hacl('192.168.1.5','192.168.1.6',tcp,'22')","LEAF",1.0
71,"RULE 5 (multi-hop access)","AND",0.7627
72,"RULE 5 (multi-hop access)","AND",0.8
73,"hacl('192.168.1.8','192.168.1.6',tcp,'22')","LEAF",1.0
74,"execCode('192.168.1.8',someUser)","OR",0.8
75,"RULE 2 (remote exploit of a server program)","AND",0.8
76,"netAccess('192.168.1.8',udp,'5355')","OR",1.0
77,"RULE 5 (multi-hop access)","AND",0.8
78,"hacl('192.168.1.12','192.168.1.8',udp,'5355')","LEAF",1.0
79,"RULE 5 (multi-hop access)","AND",0.7627
80,"RULE 5 (multi-hop access)","AND",0.8
81,"hacl('192.168.1.14','192.168.1.8',udp,'5355')","LEAF",1.0
82,"RULE 5 (multi-hop access)","AND",0.8
83,"hacl('192.168.1.5','192.168.1.8',udp,'5355')","LEAF",1.0
84,"RULE 5 (multi-hop access)","AND",0.7627
85,"RULE 5 (multi-hop access)","AND",0.8
86,"hacl('192.168.1.6','192.168.1.8',udp,'5355')","LEAF",1.0
87,"RULE 6 (direct network access)","AND",0.8
88,"hacl(internet,'192.168.1.8',udp,'5355')","LEAF",1.0
89,"networkServiceInfo('192.168.1.8',windows_2003_server,udp,'5355',someUser)","LEAF",1.0
90,"vulExists('192.168.1.8','CVE-2011-0657',windows_2003_server,remoteExploit,privEscalation)","LEAF",1.0
91,"RULE 6 (direct network access)","AND",0.8
92,"hacl(internet,'192.168.1.6',tcp,'22')","LEAF",1.0
93,"networkServiceInfo('192.168.1.6',openssh,tcp,'22',someUser)","LEAF",1.0
94,"vulExists('192.168.1.6','CVE-2008-5161',openssh,remoteExploit,privEscalation)","LEAF",1.0
95,"RULE 2 (remote exploit of a server program)","AND",0.8
96,"netAccess('192.168.1.6',tcp,'25')","OR",1.0
97,"RULE 5 (multi-hop access)","AND",0.8
98,"hacl('192.168.1.12','192.168.1.6',tcp,'25')","LEAF",1.0
99,"RULE 5 (multi-hop access)","AND",0.7627
100,"RULE 5 (multi-hop access)","AND",0.8
101,"hacl('192.168.1.14','192.168.1.6',tcp,'25')","LEAF",1.0
102,"RULE 5 (multi-hop access)","AND",0.8
103,"hacl('192.168.1.5','192.168.1.6',tcp,'25')","LEAF",1.0
104,"RULE 5 (multi-hop access)","AND",0.7627
105,"RULE 5 (multi-hop access)","AND",0.8
106,"hacl('192.168.1.8','192.168.1.6',tcp,'25')","LEAF",1.0
107,"RULE 6 (direct network access)","AND",0.8
108,"hacl(internet,'192.168.1.6',tcp,'25')","LEAF",1.0
109,"networkServiceInfo('192.168.1.6',safari,tcp,'25',someUser)","LEAF",1.0
110,"vulExists('192.168.1.6','CVE-2013-2566',safari,remoteExploit,privEscalation)","LEAF",1.0
111,"RULE 2 (remote exploit of a server program)","AND",0.8
112,"netAccess('192.168.1.6',tcp,'9090')","OR",1.0
113,"RULE 5 (multi-hop access)","AND",0.8
114,"hacl('192.168.1.12','192.168.1.6',tcp,'9090')","LEAF",1.0
115,"RULE 5 (multi-hop access)","AND",0.7627
116,"RULE 5 (multi-hop access)","AND",0.8
117,"hacl('192.168.1.14','192.168.1.6',tcp,'9090')","LEAF",1.0
118,"RULE 5 (multi-hop access)","AND",0.8
119,"hacl('192.168.1.5','192.168.1.6',tcp,'9090')","LEAF",1.0
120,"RULE 5 (multi-hop access)","AND",0.7627
121,"RULE 5 (multi-hop access)","AND",0.8
122,"hacl('192.168.1.8','192.168.1.6',tcp,'9090')","LEAF",1.0
123,"RULE 6 (direct network access)","AND",0.8
124,"hacl(internet,'192.168.1.6',tcp,'9090')","LEAF",1.0
125,"networkServiceInfo('192.168.1.6',safari,tcp,'9090',someUser)","LEAF",1.0
126,"RULE 5 (multi-hop access)","AND",0.8
127,"hacl('192.168.1.8','192.168.1.5',tcp,'22')","LEAF",1.0
128,"RULE 6 (direct network access)","AND",0.8
129,"hacl(internet,'192.168.1.5',tcp,'22')","LEAF",1.0
130,"networkServiceInfo('192.168.1.5',openssh,tcp,'22',someUser)","LEAF",1.0
131,"vulExists('192.168.1.5','CVE-2008-5161',openssh,remoteExploit,privEscalation)","LEAF",1.0
132,"RULE 2 (remote exploit of a server program)","AND",0.8
133,"networkServiceInfo('192.168.1.5',openssl,tcp,'22',someUser)","LEAF",1.0
134,"vulExists('192.168.1.5','CVE-2008-0166',openssl,remoteExploit,privEscalation)","LEAF",1.0
135,"RULE 2 (remote exploit of a server program)","AND",0.8
136,"netAccess('192.168.1.5',tcp,'25')","OR",1.0
137,"RULE 5 (multi-hop access)","AND",0.8
138,"hacl('192.168.1.12','192.168.1.5',tcp,'25')","LEAF",1.0
139,"RULE 5 (multi-hop access)","AND",0.7627
140,"RULE 5 (multi-hop access)","AND",0.8
141,"hacl('192.168.1.14','192.168.1.5',tcp,'25')","LEAF",1.0
142,"RULE 5 (multi-hop access)","AND",0.7627
143,"hacl('192.168.1.5','192.168.1.5',tcp,'25')","LEAF",1.0
144,"RULE 5 (multi-hop access)","AND",0.8
145,"hacl('192.168.1.6','192.168.1.5',tcp,'25')","LEAF",1.0
146,"RULE 5 (multi-hop access)","AND",0.8
147,"hacl('192.168.1.8','192.168.1.5',tcp,'25')","LEAF",1.0
148,"RULE 6 (direct network access)","AND",0.8
149,"hacl(internet,'192.168.1.5',tcp,'25')","LEAF",1.0
150,"networkServiceInfo('192.168.1.5',connect,tcp,'25',someUser)","LEAF",1.0
151,"vulExists('192.168.1.5','CVE-2011-1506',connect,remoteExploit,privEscalation)","LEAF",1.0
152,"RULE 2 (remote exploit of a server program)","AND",0.8
153,"networkServiceInfo('192.168.1.5',imail,tcp,'25',someUser)","LEAF",1.0
154,"vulExists('192.168.1.5','CVE-2011-1430',imail,remoteExploit,privEscalation)","LEAF",1.0
155,"RULE 2 (remote exploit of a server program)","AND",0.8
156,"networkServiceInfo('192.168.1.5',netqmail,tcp,'25',someUser)","LEAF",1.0
157,"vulExists('192.168.1.5','CVE-2011-1431',netqmail,remoteExploit,privEscalation)","LEAF",1.0
158,"RULE 2 (remote exploit of a server program)","AND",0.8
159,"networkServiceInfo('192.168.1.5',openssl,tcp,'25',someUser)","LEAF",1.0
160,"RULE 2 (remote exploit of a server program)","AND",0.8
161,"networkServiceInfo('192.168.1.5',postfix,tcp,'25',someUser)","LEAF",1.0
162,"vulExists('192.168.1.5','CVE-2011-0411',postfix,remoteExploit,privEscalation)","LEAF",1.0
163,"RULE 2 (remote exploit of a server program)","AND",0.8
164,"networkServiceInfo('192.168.1.5',safari,tcp,'25',someUser)","LEAF",1.0
165,"vulExists('192.168.1.5','CVE-2013-2566',safari,remoteExploit,privEscalation)","LEAF",1.0
166,"RULE 2 (remote exploit of a server program)","AND",0.8
167,"networkServiceInfo('192.168.1.5',scoofficeserver,tcp,'25',someUser)","LEAF",1.0
168,"vulExists('192.168.1.5','CVE-2011-1432',scoofficeserver,remoteExploit,privEscalation)","LEAF",1.0
169,"RULE 2 (remote exploit of a server program)","AND",0.8
170,"networkServiceInfo('192.168.1.5',tomcat,tcp,'25',someUser)","LEAF",1.0
171,"vulExists('192.168.1.5','CVE-2007-1858',tomcat,remoteExploit,privEscalation)","LEAF",1.0
172,"RULE 2 (remote exploit of a server program)","AND",0.8
173,"networkServiceInfo('192.168.1.5',xcs,tcp,'25',someUser)","LEAF",1.0
174,"vulExists('192.168.1.5','CVE-2011-2165',xcs,remoteExploit,privEscalation)","LEAF",1.0
175,"RULE 2 (remote exploit of a server program)","AND",0.8
176,"netAccess('192.168.1.5',tcp,'6667')","OR",1.0
177,"RULE 5 (multi-hop access)","AND",0.8
178,"hacl('192.168.1.12','192.168.1.5',tcp,'6667')","LEAF",1.0
179,"RULE 5 (multi-hop access)","AND",0.7627
180,"RULE 5 (multi-hop access)","AND",0.8
181,"hacl('192.168.1.14','192.168.1.5',tcp,'6667')","LEAF",1.0
182,"RULE 5 (multi-hop access)","AND",0.7627
183,"hacl('192.168.1.5','192.168.1.5',tcp,'6667')","LEAF",1.0
184,"RULE 5 (multi-hop access)","AND",0.8
185,"hacl('192.168.1.6','192.168.1.5',tcp,'6667')","LEAF",1.0
186,"RULE 5 (multi-hop access)","AND",0.8
187,"hacl('192.168.1.8','192.168.1.5',tcp,'6667')","LEAF",1.0
188,"RULE 6 (direct network access)","AND",0.8
189,"hacl(internet,'192.168.1.5',tcp,'6667')","LEAF",1.0
190,"networkServiceInfo('192.168.1.5',unrealircd,tcp,'6667',someUser)","LEAF",1.0
191,"vulExists('192.168.1.5','CVE-2010-2075',unrealircd,remoteExploit,privEscalation)","LEAF",1.0
192,"RULE 2 (remote exploit of a server program)","AND",0.7996
193,"netAccess('192.168.1.5',tcp,'80')","OR",0.9995
194,"RULE 5 (multi-hop access)","AND",0.8
195,"hacl('192.168.1.12','192.168.1.5',tcp,'80')","LEAF",1.0
196,"RULE 5 (multi-hop access)","AND",0.7627
197,"RULE 5 (multi-hop access)","AND",0.8
198,"hacl('192.168.1.14','192.168.1.5',tcp,'80')","LEAF",1.0
199,"RULE 5 (multi-hop access)","AND",0.7627
200,"hacl('192.168.1.5','192.168.1.5',tcp,'80')","LEAF",1.0
201,"RULE 5 (multi-hop access)","AND",0.8
202,"hacl('192.168.1.6','192.168.1.5',tcp,'80')","LEAF",1.0
203,"RULE 5 (multi-hop access)","AND",0.8
204,"hacl('192.168.1.8','192.168.1.5',tcp,'80')","LEAF",1.0
205,"RULE 6 (direct network access)","AND",0.8
206,"hacl(internet,'192.168.1.5',tcp,'80')","LEAF",1.0
207,"networkServiceInfo('192.168.1.5',http_server,tcp,'80',someUser)","LEAF",1.0
208,"vulExists('192.168.1.5','CVE-2012-0053',http_server,remoteExploit,privEscalation)","LEAF",1.0
209,"RULE 2 (remote exploit of a server program)","AND",0.8
210,"netAccess('192.168.1.5',tcp,'9090')","OR",1.0
211,"RULE 5 (multi-hop access)","AND",0.8
212,"hacl('192.168.1.12','192.168.1.5',tcp,'9090')","LEAF",1.0
213,"RULE 5 (multi-hop access)","AND",0.7627
214,"RULE 5 (multi-hop access)","AND",0.8
215,"hacl('192.168.1.14','192.168.1.5',tcp,'9090')","LEAF",1.0
216,"RULE 5 (multi-hop access)","AND",0.7627
217,"hacl('192.168.1.5','192.168.1.5',tcp,'9090')","LEAF",1.0
218,"RULE 5 (multi-hop access)","AND",0.8
219,"hacl('192.168.1.6','192.168.1.5',tcp,'9090')","LEAF",1.0
220,"RULE 5 (multi-hop access)","AND",0.8
221,"hacl('192.168.1.8','192.168.1.5',tcp,'9090')","LEAF",1.0
222,"RULE 6 (direct network access)","AND",0.8
223,"hacl(internet,'192.168.1.5',tcp,'9090')","LEAF",1.0
224,"networkServiceInfo('192.168.1.5',safari,tcp,'9090',someUser)","LEAF",1.0
225,"RULE 2 (remote exploit of a server program)","AND",0.8
226,"netAccess('192.168.1.5',udp,'2049')","OR",1.0
227,"RULE 5 (multi-hop access)","AND",0.8
228,"hacl('192.168.1.12','192.168.1.5',udp,'2049')","LEAF",1.0
229,"RULE 5 (multi-hop access)","AND",0.7627
230,"RULE 5 (multi-hop access)","AND",0.8
231,"hacl('192.168.1.14','192.168.1.5',udp,'2049')","LEAF",1.0
232,"RULE 5 (multi-hop access)","AND",0.7627
233,"hacl('192.168.1.5','192.168.1.5',udp,'2049')","LEAF",1.0
234,"RULE 5 (multi-hop access)","AND",0.8
235,"hacl('192.168.1.6','192.168.1.5',udp,'2049')","LEAF",1.0
236,"RULE 5 (multi-hop access)","AND",0.8
237,"hacl('192.168.1.8','192.168.1.5',udp,'2049')","LEAF",1.0
238,"RULE 6 (direct network access)","AND",0.8
239,"hacl(internet,'192.168.1.5',udp,'2049')","LEAF",1.0
240,"networkServiceInfo('192.168.1.5',solaris,udp,'2049',someUser)","LEAF",1.0
241,"vulExists('192.168.1.5','CVE-1999-0211',solaris,remoteExploit,privEscalation)","LEAF",1.0
242,"RULE 2 (remote exploit of a server program)","AND",0.8
243,"networkServiceInfo('192.168.1.5',ultrix,udp,'2049',someUser)","LEAF",1.0
244,"vulExists('192.168.1.5','CVE-1999-0170',ultrix,remoteExploit,privEscalation)","LEAF",1.0
245,"RULE 2 (remote exploit of a server program)","AND",0.8
246,"netAccess('192.168.1.5',udp,'53')","OR",1.0
247,"RULE 5 (multi-hop access)","AND",0.8
248,"hacl('192.168.1.12','192.168.1.5',udp,'53')","LEAF",1.0
249,"RULE 5 (multi-hop access)","AND",0.7627
250,"RULE 5 (multi-hop access)","AND",0.8
251,"hacl('192.168.1.14','192.168.1.5',udp,'53')","LEAF",1.0
252,"RULE 5 (multi-hop access)","AND",0.7627
253,"hacl('192.168.1.5','192.168.1.5',udp,'53')","LEAF",1.0
254,"RULE 5 (multi-hop access)","AND",0.8
255,"hacl('192.168.1.6','192.168.1.5',udp,'53')","LEAF",1.0
256,"RULE 5 (multi-hop access)","AND",0.8
257,"hacl('192.168.1.8','192.168.1.5',udp,'53')","LEAF",1.0
258,"RULE 6 (direct network access)","AND",0.8
259,"hacl(internet,'192.168.1.5',udp,'53')","LEAF",1.0
260,"networkServiceInfo('192.168.1.5',bind,udp,'53',someUser)","LEAF",1.0
261,"vulExists('192.168.1.5','CVE-2008-1447',bind,remoteExploit,privEscalation)","LEAF",1.0
262,"RULE 5 (multi-hop access)","AND",0.7627
263,"RULE 5 (multi-hop access)","AND",0.8
264,"hacl('192.168.1.6','192.168.1.14',tcp,'443')","LEAF",1.0
265,"RULE 5 (multi-hop access)","AND",0.8
266,"hacl('192.168.1.8','192.168.1.14',tcp,'443')","LEAF",1.0
267,"RULE 6 (direct network access)","AND",0.8
268,"hacl(internet,'192.168.1.14',tcp,'443')","LEAF",1.0
269,"networkServiceInfo('192.168.1.14',http_server,tcp,'443',someUser)","LEAF",1.0
270,"vulExists('192.168.1.14','CVE-2001-0731',http_server,remoteExploit,privEscalation)","LEAF",1.0
271,"RULE 2 (remote exploit of a server program)","AND",0.8
272,"netAccess('192.168.1.14',tcp,'80')","OR",1.0
273,"RULE 5 (multi-hop access)","AND",0.8
274,"hacl('192.168.1.12','192.168.1.14',tcp,'80')","LEAF",1.0
275,"RULE 5 (multi-hop access)","AND",0.7627
276,"RULE 5 (multi-hop access)","AND",0.8
277,"hacl('192.168.1.5','192.168.1.14',tcp,'80')","LEAF",1.0
278,"RULE 5 (multi-hop access)","AND",0.7627
279,"RULE 5 (multi-hop access)","AND",0.8
280,"hacl('192.168.1.6','192.168.1.14',tcp,'80')","LEAF",1.0
281,"RULE 5 (multi-hop access)","AND",0.8
282,"hacl('192.168.1.8','192.168.1.14',tcp,'80')","LEAF",1.0
283,"RULE 6 (direct network access)","AND",0.8
284,"hacl(internet,'192.168.1.14',tcp,'80')","LEAF",1.0
285,"networkServiceInfo('192.168.1.14',http_server,tcp,'80',someUser)","LEAF",1.0
286,"RULE 5 (multi-hop access)","AND",0.8
287,"hacl('192.168.1.5','192.168.1.12',tcp,'22')","LEAF",1.0
288,"RULE 5 (multi-hop access)","AND",0.7627
289,"RULE 5 (multi-hop access)","AND",0.8
290,"hacl('192.168.1.6','192.168.1.12',tcp,'22')","LEAF",1.0
291,"RULE 5 (multi-hop access)","AND",0.8
292,"hacl('192.168.1.8','192.168.1.12',tcp,'22')","LEAF",1.0
293,"RULE 6 (direct network access)","AND",0.8
294,"hacl(internet,'192.168.1.12',tcp,'22')","LEAF",1.0
295,"networkServiceInfo('192.168.1.12',openssh,tcp,'22',someUser)","LEAF",1.0
296,"vulExists('192.168.1.12','CVE-2008-5161',openssh,remoteExploit,privEscalation)","LEAF",1.0
297,"RULE 2 (remote exploit of a server program)","AND",0.7999
298,"networkServiceInfo('192.168.1.12',openssl,tcp,'22',someUser)","LEAF",1.0
299,"vulExists('192.168.1.12','CVE-2008-0166',openssl,remoteExploit,privEscalation)","LEAF",1.0
300,"RULE 2 (remote exploit of a server program)","AND",0.8
301,"netAccess('192.168.1.12',tcp,'25')","OR",1.0
302,"RULE 5 (multi-hop access)","AND",0.7627
303,"hacl('192.168.1.12','192.168.1.12',tcp,'25')","LEAF",1.0
304,"RULE 5 (multi-hop access)","AND",0.8
305,"hacl('192.168.1.14','192.168.1.12',tcp,'25')","LEAF",1.0
306,"RULE 5 (multi-hop access)","AND",0.8
307,"hacl('192.168.1.5','192.168.1.12',tcp,'25')","LEAF",1.0
308,"RULE 5 (multi-hop access)","AND",0.7627
309,"RULE 5 (multi-hop access)","AND",0.8
310,"hacl('192.168.1.6','192.168.1.12',tcp,'25')","LEAF",1.0
311,"RULE 5 (multi-hop access)","AND",0.8
312,"hacl('192.168.1.8','192.168.1.12',tcp,'25')","LEAF",1.0
313,"RULE 6 (direct network access)","AND",0.8
314,"hacl(internet,'192.168.1.12',tcp,'25')","LEAF",1.0
315,"networkServiceInfo('192.168.1.12',connect,tcp,'25',someUser)","LEAF",1.0
316,"vulExists('192.168.1.12','CVE-2011-1506',connect,remoteExploit,privEscalation)","LEAF",1.0
317,"RULE 2 (remote exploit of a server program)","AND",0.8
318,"networkServiceInfo('192.168.1.12',imail,tcp,'25',someUser)","LEAF",1.0
319,"vulExists('192.168.1.12','CVE-2011-1430',imail,remoteExploit,privEscalation)","LEAF",1.0
320,"RULE 2 (remote exploit of a server program)","AND",0.8
321,"networkServiceInfo('192.168.1.12',netqmail,tcp,'25',someUser)","LEAF",1.0
322,"vulExists('192.168.1.12','CVE-2011-1431',netqmail,remoteExploit,privEscalation)","LEAF",1.0
323,"RULE 2 (remote exploit of a server program)","AND",0.8
324,"networkServiceInfo('192.168.1.12',openssl,tcp,'25',someUser)","LEAF",1.0
325,"RULE 2 (remote exploit of a server program)","AND",0.8
326,"networkServiceInfo('192.168.1.12',postfix,tcp,'25',someUser)","LEAF",1.0
327,"vulExists('192.168.1.12','CVE-2011-0411',postfix,remoteExploit,privEscalation)","LEAF",1.0
328,"RULE 2 (remote exploit of a server program)","AND",0.8
329,"networkServiceInfo('192.168.1.12',safari,tcp,'25',someUser)","LEAF",1.0
330,"vulExists('192.168.1.12','CVE-2013-2566',safari,remoteExploit,privEscalation)","LEAF",1.0
331,"RULE 2 (remote exploit of a server program)","AND",0.8
332,"networkServiceInfo('192.168.1.12',scoofficeserver,tcp,'25',someUser)","LEAF",1.0
333,"vulExists('192.168.1.12','CVE-2011-1432',scoofficeserver,remoteExploit,privEscalation)","LEAF",1.0
334,"RULE 2 (remote exploit of a server program)","AND",0.8
335,"networkServiceInfo('192.168.1.12',tomcat,tcp,'25',someUser)","LEAF",1.0
336,"vulExists('192.168.1.12','CVE-2007-1858',tomcat,remoteExploit,privEscalation)","LEAF",1.0
337,"RULE 2 (remote exploit of a server program)","AND",0.8
338,"networkServiceInfo('192.168.1.12',xcs,tcp,'25',someUser)","LEAF",1.0
339,"vulExists('192.168.1.12','CVE-2011-2165',xcs,remoteExploit,privEscalation)","LEAF",1.0
340,"RULE 2 (remote exploit of a server program)","AND",0.8
341,"netAccess('192.168.1.12',tcp,'6667')","OR",1.0
342,"RULE 5 (multi-hop access)","AND",0.7627
343,"hacl('192.168.1.12','192.168.1.12',tcp,'6667')","LEAF",1.0
344,"RULE 5 (multi-hop access)","AND",0.768
345,"hacl('192.168.1.14','192.168.1.12',tcp,'6667')","LEAF",1.0
346,"RULE 5 (multi-hop access)","AND",0.8
347,"hacl('192.168.1.5','192.168.1.12',tcp,'6667')","LEAF",1.0
348,"RULE 5 (multi-hop access)","AND",0.7627
349,"RULE 5 (multi-hop access)","AND",0.7936
350,"hacl('192.168.1.6','192.168.1.12',tcp,'6667')","LEAF",1.0
351,"RULE 5 (multi-hop access)","AND",0.8
352,"hacl('192.168.1.8','192.168.1.12',tcp,'6667')","LEAF",1.0
353,"RULE 6 (direct network access)","AND",0.8
354,"hacl(internet,'192.168.1.12',tcp,'6667')","LEAF",1.0
355,"networkServiceInfo('192.168.1.12',unrealircd,tcp,'6667',someUser)","LEAF",1.0
356,"vulExists('192.168.1.12','CVE-2010-2075',unrealircd,remoteExploit,privEscalation)","LEAF",1.0
357,"RULE 2 (remote exploit of a server program)","AND",0.8
358,"netAccess('192.168.1.12',tcp,'80')","OR",1.0
359,"RULE 5 (multi-hop access)","AND",0.7627
360,"hacl('192.168.1.12','192.168.1.12',tcp,'80')","LEAF",1.0
361,"RULE 5 (multi-hop access)","AND",0.8
362,"hacl('192.168.1.14','192.168.1.12',tcp,'80')","LEAF",1.0
363,"RULE 5 (multi-hop access)","AND",0.8
364,"hacl('192.168.1.5','192.168.1.12',tcp,'80')","LEAF",1.0
365,"RULE 5 (multi-hop access)","AND",0.7627
366,"RULE 5 (multi-hop access)","AND",0.7936
367,"hacl('192.168.1.6','192.168.1.12',tcp,'80')","LEAF",1.0
368,"RULE 5 (multi-hop access)","AND",0.8
369,"hacl('192.168.1.8','192.168.1.12',tcp,'80')","LEAF",1.0
370,"RULE 6 (direct network access)","AND",0.8
371,"hacl(internet,'192.168.1.12',tcp,'80')","LEAF",1.0
372,"networkServiceInfo('192.168.1.12',http_server,tcp,'80',someUser)","LEAF",1.0
373,"vulExists('192.168.1.12','CVE-2012-0053',http_server,remoteExploit,privEscalation)","LEAF",1.0
374,"RULE 2 (remote exploit of a server program)","AND",0.8
375,"netAccess('192.168.1.12',tcp,'9090')","OR",1.0
376,"RULE 5 (multi-hop access)","AND",0.7627
377,"hacl('192.168.1.12','192.168.1.12',tcp,'9090')","LEAF",1.0
378,"RULE 5 (multi-hop access)","AND",0.8
379,"hacl('192.168.1.14','192.168.1.12',tcp,'9090')","LEAF",1.0
380,"RULE 5 (multi-hop access)","AND",0.8
381,"hacl('192.168.1.5','192.168.1.12',tcp,'9090')","LEAF",1.0
382,"RULE 5 (multi-hop access)","AND",0.7627
383,"RULE 5 (multi-hop access)","AND",0.7936
384,"hacl('192.168.1.6','192.168.1.12',tcp,'9090')","LEAF",1.0
385,"RULE 5 (multi-hop access)","AND",0.8
386,"hacl('192.168.1.8','192.168.1.12',tcp,'9090')","LEAF",1.0
387,"RULE 6 (direct network access)","AND",0.8
388,"hacl(internet,'192.168.1.12',tcp,'9090')","LEAF",1.0
389,"networkServiceInfo('192.168.1.12',safari,tcp,'9090',someUser)","LEAF",1.0
390,"RULE 2 (remote exploit of a server program)","AND",0.7999
391,"netAccess('192.168.1.12',udp,'2049')","OR",0.9999
392,"RULE 5 (multi-hop access)","AND",0.7627
393,"hacl('192.168.1.12','192.168.1.12',udp,'2049')","LEAF",1.0
394,"RULE 5 (multi-hop access)","AND",0.8
395,"hacl('192.168.1.14','192.168.1.12',udp,'2049')","LEAF",1.0
396,"RULE 5 (multi-hop access)","AND",0.8
397,"hacl('192.168.1.5','192.168.1.12',udp,'2049')","LEAF",1.0
398,"RULE 5 (multi-hop access)","AND",0.7627
399,"RULE 5 (multi-hop access)","AND",0.8
400,"hacl('192.168.1.6','192.168.1.12',udp,'2049')","LEAF",1.0
401,"RULE 5 (multi-hop access)","AND",0.8
402,"hacl('192.168.1.8','192.168.1.12',udp,'2049')","LEAF",1.0
403,"RULE 6 (direct network access)","AND",0.8
404,"hacl(internet,'192.168.1.12',udp,'2049')","LEAF",1.0
405,"networkServiceInfo('192.168.1.12',solaris,udp,'2049',someUser)","LEAF",1.0
406,"vulExists('192.168.1.12','CVE-1999-0211',solaris,remoteExploit,privEscalation)","LEAF",1.0
407,"RULE 2 (remote exploit of a server program)","AND",0.7999
408,"networkServiceInfo('192.168.1.12',ultrix,udp,'2049',someUser)","LEAF",1.0
409,"vulExists('192.168.1.12','CVE-1999-0170',ultrix,remoteExploit,privEscalation)","LEAF",1.0
410,"RULE 2 (remote exploit of a server program)","AND",0.8
411,"netAccess('192.168.1.12',udp,'53')","OR",1.0
412,"RULE 5 (multi-hop access)","AND",0.7627
413,"hacl('192.168.1.12','192.168.1.12',udp,'53')","LEAF",1.0
414,"RULE 5 (multi-hop access)","AND",0.8
415,"hacl('192.168.1.14','192.168.1.12',udp,'53')","LEAF",1.0
416,"RULE 5 (multi-hop access)","AND",0.8
417,"hacl('192.168.1.5','192.168.1.12',udp,'53')","LEAF",1.0
418,"RULE 5 (multi-hop access)","AND",0.7627
419,"RULE 5 (multi-hop access)","AND",0.7936
420,"hacl('192.168.1.6','192.168.1.12',udp,'53')","LEAF",1.0
421,"RULE 5 (multi-hop access)","AND",0.8
422,"hacl('192.168.1.8','192.168.1.12',udp,'53')","LEAF",1.0
423,"RULE 6 (direct network access)","AND",0.8
424,"hacl(internet,'192.168.1.12',udp,'53')","LEAF",1.0
425,"networkServiceInfo('192.168.1.12',bind,udp,'53',someUser)","LEAF",1.0
426,"vulExists('192.168.1.12','CVE-2008-1447',bind,remoteExploit,privEscalation)","LEAF",1.0
