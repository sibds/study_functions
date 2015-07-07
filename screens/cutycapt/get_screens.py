import sys
import os

if __name__ == "__main__":
	if len(sys.argv) < 2:
		print "usage %s name_of_list" % sys.argv[0]
	else:
		links = open(sys.argv[1], 'r').read().split()
		for link in links:
			command = 'xvfb-run --server-args="-screen 0, 1024x768x24" /usr/bin/cutycapt --url=' + link 
			os.system(command + ' --out=/home/lsd/Desktop/shield/cutycapt/%s.jpg' % link[7:])
