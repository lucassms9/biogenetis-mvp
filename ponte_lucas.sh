#!/bin/bash

read -p "Servidor Final  =" FServer
read -p "Servidor Porta  =" Porta
read -p "Servidor Ponte  =" Ponte

echo '--------- ssh -N -L '$Porta':'$FServer':'$Porta 'root@'$Ponte '----------'
git deploy
username: lucassms9
password: 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

// acessar maquina via bastion PROD
app: ssh -i .ssh/ssh-key-2020-08-12.key opc@172.21.0.3

pacientes: ssh -i .ssh/ssh-key-2020-08-12.key ubuntu@172.21.1.4

exames: ssh -i .ssh/ssh-key-2020-08-12.key ubuntu@172.21.1.5

maquina wagner: ssh -i .ssh/ssh-key-2020-08-12.key ubuntu@172.21.1.2

CORS_SERVER_ORIGIN=*
SERVER_PORT_LISTEN=5813

1686F7
DATABASE_DIALECT=mysql
DATABASE_HOST=172.22.2.3
DATABASE_USERNAME=paciente-user
DATABASE_PASSWORD=Bio-2020
DATABASE_NAME=biogeneticsusr


// acessar maquina via bastion DEV
app: ssh -i .ssh/ssh-key-2020-08-12.key opc@172.22.0.2

pacientes: ssh -i .ssh/ssh-key-2020-08-12.key ubuntu@172.22.1.4

exames: ssh -i .ssh/ssh-key-2020-08-12.key ubuntu@172.22.1.5

172.21.1.2
//TUNEL BANCO PROD
banco app
ssh -N -L 9998:172.21.2.2:3306 opc@168.138.149.44

banco server01 - pacientes
ssh -N -L 9999:172.21.2.3:3306 opc@168.138.133.103

banco server02 - exames
ssh -N -L 9997:172.21.2.4:3306 opc@152.67.55.26

//maquina do wagner
ssh -N -L 9999:172.21.1.2:3019 opc@152.67.55.26



//TUNEL BANCO DEV
banco app
ssh -N -L 9998:172.22.0.2:3306 opc@168.138.149.44
env
export DB_HOST = '172.22.0.2'
export DB_NAME = 'dbweb'
export DB_PASS = 'Bio-2020'
export DB_USER = 'admin'


banco server01 - pacientes
ssh -N -L 9999:172.22.2.3:3306 opc@168.138.133.103 -i /Users/lucassantos/projetos/biogenetics/app.pem

env
export DB_HOST = '172.22.2.3'
export DB_NAME = 'biogeneticsusr'
export DB_PASS = 'Bio-2020'
export DB_USER = 'root'

banco server02 - exames
ssh -N -L 9997:172.22.2.4:3306 opc@168.138.133.103 -i /Users/lucassantos/projetos/biogenetics/app.pem


//maquina servicos dev
app: ssh -i .ssh/ssh-key-2020-08-12.key ubuntu@172.22.1.2


env
export DB_HOST = '172.22.2.4'
export DB_NAME = 'biogeneticsexame_dev'
export DB_PASS = 'Bio-2020'
export DB_USER = 'root'

ssh -N -L 9998:172.22.0.2:3306 opc@168.138.149.44

//novo user
ssh opc@152.67.55.26
scp  opc@152.67.55.26:/var/www/html/* .github

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


*/1 *  *  *  *   curl http://localhost:80/api/pedidos/dispatch-emails-cron/
*/1 *  *  *  *   curl http://localhost:80/admin/pedidos/generate-file-cron/


pm2 start --name jar_sal_01_3017  jar_sal_01_3017/index.js
pm2 start --name mar_sal_01_3008  mar_sal_01_3008/index.js
pm2 start --name mar_sal_02_3009 mar_sal_02_3009/index.js
pm2 start --name mar_sal_03_3019 mar_sal_03_3019/index.js
pm2 start --name mar_sal_04_3020 mar_sal_04_3020/index.js
pm2 start --name mar_sal_05_3023 mar_sal_05_3023/index.js
pm2 start --name mar_sal_06_3024 mar_sal_06_3024/index.js
pm2 start --name mar_sal_07_3025 mar_sal_07_3025/index.js
pm2 start --name mar_sal_08_3026 mar_sal_08_3026/index.js
pm2 start --name mar_sal_09_3027 mar_sal_09_3027/index.js
pm2 start --name mar_sal_10_3028 mar_sal_10_3028/index.js
pm2 start --name mar_sal_11_3029 mar_sal_11_3029/index.js
pm2 start --name mar_sal_12_3030 mar_sal_12_3030/index.js
pm2 start --name mar_sal_13_3007 mar_sal_13_3007/index.js
pm2 start --name mar_sal_14_3021 mar_sal_14_3021/index.js
pm2 start --name mar_sal_15_3022 mar_sal_15_3022/index.js
pm2 start --name mar_sal_16_3018 mar_sal_16_3018/index.js
pm2 start --name mur_sal_01_3001 mur_sal_01_3001/index.js
pm2 start --name mur_sal_02_3002 mur_sal_02_3002/index.js
pm2 start --name mur_sal_03_3010 mur_sal_03_3010/index.js
pm2 start --name mur_sal_04_3003 mur_sal_04_3003/index.js
pm2 start --name mur_sal_05_3004 mur_sal_05_3004/index.js
pm2 start --name mur_sal_06_3015 mur_sal_06_3015/index.js
pm2 start --name mur_sal_07_3016 mur_sal_07_3016/index.js
pm2 start --name mur_sal_08_3005 mur_sal_08_3005/index.js
pm2 start --name mur_sal_09_3006 mur_sal_09_3006/index.js
pm2 start --name wag_sal_03_3011 wag_sal_03_3011/index.js
pm2 start --name wag_sal_04_3012 wag_sal_04_3012/index.js
pm2 start --name wag_sal_05_3013 wag_sal_05_3013/index.js
pm2 start --name wag_sal_06_3014 wag_sal_06_3014/index.js


