# -*- coding: utf-8 -*-

import requests
import re
import zlib

"""	
	# 	links хранит все ссылки найденные на страницах
	#	goods содержит внутренние ссылки по которые необходимо обойти
	#	crc - словарь сайт:crc
	#	counter - "глубина" обхода - количество сайтов на которых происходит поиск
"""

links = []
goods = []
cssFiles = []
crc = {}
counter = 10
site = 'http://sibsr.ru'
goods.append(site)

def getLinks(site):
	r = requests.get(site)
	print "getting content of " + site
	#crc[site] = zlib.crc32(r.content)
	l = re.findall('<a href="?\'?([^"\'>]*)', r.text)
	csslinks = re.findall('/[\w./\-]*[\W/]*\.css', r.text)
	# reg expression for html links '<a href="?\'?([^"\'>]*)'
	# reg expression for js links '/[\w.\-]*[\W]*.js'
	global cssFiles
	cssFiles = list(set(cssFiles + csslinks))	
	rez = links or l
	return list(set(rez))

def evalLinks():
	for link in links:
		if link[0] == '/':
			link = site + link
			goods.append(link)
	global links 
	links = list(set(links))

for x in range(counter):
	if x<len(goods):
		links = getLinks(goods[x])
		evalLinks()
		goods = list(set(goods))
	else:
		print "all links are visited"

goods = list(set(goods))
links = list(set(links))

cssFiles = [site + x for x in cssFiles]

for css in cssFiles:
	r = requests.get(css)
	crc[css] = zlib.crc32(r.content)

#print len(goods)
#print len(links)
print ""
for x in crc:
    print str(crc[x])  + '\t: ' + str(x)