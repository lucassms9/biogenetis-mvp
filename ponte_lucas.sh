#!/bin/bash

#read -p "Servidor Final  =" FServer
#read -p "Servidor Porta  =" Porta
#read -p "Servidor Ponte  =" Ponte

echo '--------- ssh -N -L '$Porta':'$FServer':'$Porta 'root@'$Ponte '----------'

ssh -N -L 3306:172.21.2.2:3306 root@140.238.187.25
