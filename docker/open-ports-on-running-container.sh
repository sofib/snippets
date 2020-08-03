# only in cases it is really a menace to create the container again!!! ref - https://forums.docker.com/t/how-to-expose-port-on-running-container/3252/14

iptables -t nat -A DOCKER -p tcp --dport 1337 -j DNAT --to-destination 172.17.0.2:1337
iptables -t nat -A POSTROUTING -j MASQUERADE -p tcp --source 172.17.0.2 --destination 172.17.0.2 --dport 1337
iptables -A DOCKER -j ACCEPT -p tcp --destination 172.17.0.2 --dport 1337
