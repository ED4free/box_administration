#! /bin/bash

AlreadyConnect () {
    ping -q -w 1 -c 1 `ip r | grep default | cut -d ' ' -f 3  | sed -n '1p'` 1>/dev/null 2>/dev/null
}

PrintWifiName () {
    iwgetid -r || echo "Ethernet"
}

PrintIp () {
    IP=`ifconfig | sed -En 's/127.0.0.1//;s/.*inet (addr:)?(([0-9]*\.){3}[0-9]*).*/\2/p' | tail -n 1`

    if ! [ -z "$IP" -o "$IP" == '10.10.0.1' ];
    then
        eval "$1=$IP"
    else
	eval "$1='No connexion'"
    fi
}

PrintAvailableWifi() {
    iwlist wlan1 scan 2>/dev/null | grep ESSID | cut -d '"' -f 2 | sed -e '/EDbox/d;/^$/d'
}

AlreadyConnect &&
    (PrintIp ip_result
     echo "Connecter à: `PrintWifiName`"
     echo "Mon adress ip: $ip_result"
     exit 0) ||
	(echo "`PrintAvailableWifi`"
	 exit 1) &&
	    exit 0 ||
		exit 1
