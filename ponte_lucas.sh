#!/bin/bash

read -p "Servidor Final  =" FServer
read -p "Servidor Porta  =" Porta
read -p "Servidor Ponte  =" Ponte

echo '--------- ssh -N -L '$Porta':'$FServer':'$Porta 'root@'$Ponte '----------'

ssh -N -L $Porta:$FServer:$Porta root@$Ponte
