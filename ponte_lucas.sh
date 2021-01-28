#!/bin/bash

read -p "Servidor Final  =" FServer
read -p "Servidor Porta  =" Porta
read -p "Servidor Ponte  =" Ponte

echo '--------- ssh -N -L '$Porta':'$FServer':'$Porta 'root@'$Ponte '----------'

//TUNEL BANCO
ssh -N -L 9999:172.21.2.2:3306 opc@152.67.55.26

//maquina do wagner
ssh -N -L 9999:172.21.1.2:3019 opc@152.67.55.26

//novo user
ssh opc@152.67.55.26

BANCO
database: dbweb
schema: web-app
pass: Bio-2020

IP banco dev:IP 172.22.2.2

//app
ssh root@152.67.55.26

scp root@140.238.187.25:/var/www/html/* .


scp root@152.67.55.26:/var/www/html/src/Controller/Admin/* .

nohup scp -rp * root@140.238.187.25:/var/www/html/src/Controller/Admin. &


scp * root@152.67.55.26:/var/www/html/src/Controller/Admin/.

scp * root@152.67.55.26:/var/www/html/webroot/js/amostras/.
ssh blogkotaki@40.71.43.226


scp * root@152.67.55.26:/var/www/html/src/Controller/Admin/.


scp -rp .gitattributes root@152.67.55.26:/var/www/html/.
scp -rp .gitignore root@152.67.55.26:/var/www/html/.

scp -i blogkotaki server.cer blogkotaki@40.71.43.226:/opt/bitnami/apache2/conf/bitnami/certs
scp -i blogkotaki server.key blogkotaki@40.71.43.226:/opt/bitnami/apache2/conf/bitnami/certs

db-mysql-01.subdbpriv.vcnprd.oraclevcn.com


openssl pkcs12 -in prod-kvault-ComodoSSL-Kotaki-20200929.pfx -clcerts -nokeys -out server.cer
openssl pkcs12 -in prod-kvault-ComodoSSL-Kotaki-20200929.pfx -nocerts -nodes  -out server.key
