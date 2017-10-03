#! /bin/bash

cat /etc/wpa_supplicant/wpa_supplicant.conf | /var/edbox/script/wifi_connexion/remove_network_from_string "`iwgetid -r`" > /etc/wpa_supplicant/wpa_supplicant.conf

ifdown wlan1 --force 2>/dev/null 1>/dev/null && exit 0 & ifup wlan1 1>/dev/null
