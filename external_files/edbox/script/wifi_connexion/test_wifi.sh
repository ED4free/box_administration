#! /bin/bash

AlreadyConnect () {
    ping -q -w 1 -c 1 `ip r | grep default | cut -d ' ' -f 3  | sed -n '1p'` 1>/dev/null 2>/dev/null
}

PrintWifiName () {
    iwgetid -r || echo "Ethernet"
}

PrintIp () {
    INTERFACE=`route | sed '1,2d;/default/d;/wlan0/d' | rev | cut -d ' ' -f1 | rev | head -n 1`
    IP=`ifconfig "$INTERFACE" | grep 'inet ' | awk '{print $2}'`
    eval "$1=$IP"
}

PrintAvailableWifi() {
    iwlist wlan1 scan 2>/dev/null | grep ESSID | cut -d '"' -f 2 | sed -e '/EDbox/d;/^$/d'
}

AlreadyConnect &&
    (PrintIp ip_result
     echo "Connecté à: `PrintWifiName`"
     echo "Mon adresse ip: $ip_result"
     exit 0) ||
	(echo "`PrintAvailableWifi`"
	 exit 1) &&
	    exit 0 ||
		exit 1
