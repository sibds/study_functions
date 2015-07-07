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
jsFiles = []
crc = {}
counter = 10

def getLinks(site):
	r = requests.get(site)
	print "getting content of " + site
	#crc[site] = zlib.crc32(r.content)
	l = re.findall('<a href="?\'?([^"\'>]*)', r.text)
	jslinks = re.findall('/[\w.\-]*[\W]*.js', r.text)
	# reg expression for html links '<a href="?\'?([^"\'>]*)'
	# reg expression for js links '/[\w.\-]*[\W]*.js'
	global jsFiles
	jsFiles = list(set(jsFiles + jslinks))	
	rez = links or l
	return list(set(rez))

def evalLinks():
	for link in links:
		if link[0] == '/':
			link = site + link
			goods.append(link)
	global links 
	links = list(set(links))

site = 'http://sibsr.ru'
goods.append(site)

for x in range(counter):
	if x<len(goods):
		links = getLinks(goods[x])
		evalLinks()
		goods = list(set(goods))
	else:
		print "all links are visited"

goods = list(set(goods))
links = list(set(links))

jsFiles = [site + x for x in jsFiles]

for js in jsFiles:
	r = requests.get(js)
	crc[js] = zlib.crc32(r.content)

#print len(goods)
#print len(links)
print ""
for x in crc:
    print str(crc[x])  + '\t: ' + str(x)