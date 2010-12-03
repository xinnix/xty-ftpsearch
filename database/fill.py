# coding: utf-8
# 使用方法:python fill.py url(前面不加ftp://)
#

import sys
import os
from MySQLdb import *
from ftplib import FTP

conn = connect(host='localhost', user='root', passwd='xinxin', db='')
def get(url):
    try:
        ftp = FTP(url)
        ftp.connect()
        ftp.login()
    except:
        print 'something wrong happened, maybe the url is wrong!'
        return
    ftp.dir(callback)
    
def callback(eachline):
    if eachline[0] == 'd':
        pass
        
if __name__ == '__main__':
    print sys.argv[1]