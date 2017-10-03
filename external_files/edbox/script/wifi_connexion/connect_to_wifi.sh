#! /bin/bash

if test $# = 2
then
    ifdown --force wlan1 1>/dev/null 2>/dev/null || exit 1
    WPA_SUPPLICANT=`wpa_passphrase "$1" "$2"` || exit 1
    echo "$WPA_SUPPLICANT" | sed '/#/d' >> /etc/wpa_supplicant/wpa_supplicant.conf &&
    ifup wlan1 1>/dev/null || exit 1
    exit 0
else
    echo ko
    exit 1
fi
