wget http://vivahome.eu/ip.php -U "" -qO - | egrep -o '[[:digit:]]{1,3}\.[[:digit:]]{1,3}\.[[:digit:]]{1,3}\.[[:digit:]]{1,3}' | uniq
