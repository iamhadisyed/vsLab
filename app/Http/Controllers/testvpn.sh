#!/bin/bash

echo "test data" > /tmp/vpn.log

sshpass -p 'Cloud$erver' ssh root@10.1.0.6 "\
cd /etc/openvpn/easy-rsa/; \
source ./vars; \
./build-key --batch $1; \
cd ./keys; \
cat thoth_base.ovpn > $1.ovpn; \
echo '<ca>' >> $1.ovpn; \
cat ca.crt >> $1.ovpn; \
echo '</ca>' >> $1.ovpn; \
echo '<cert>' >> $1.ovpn; \
cat $1.crt >> $1.ovpn; \
echo '</cert>' >> $1.ovpn; \
echo '<key>' >> $1.ovpn; \
cat $1.key >> $1.ovpn; \
echo '</key>' >> $1.ovpn; \
"

# sshpass -p 'Cloud$erver' scp root@10.1.0.6:/etc/openvpn/easy-rsa/keys/$1.ovpn /var/www/mobicloud/public/files

sshpass -p 'Cloud$erver' scp root@10.1.0.6:/etc/openvpn/easy-rsa/keys/$1.ovpn /tmp

#bash -x testvpn.sh name

