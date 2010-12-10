# coding: utf-8
# 运行环境: python 2.5以上, MySQLdb模块, mysql数据库5.0以上

import sys
import os
from MySQLdb import *
from ftplib import FTP


class FtpIndex:
    def __init__(self):
        self.cur = connect(host='localhost', user='root', passwd='xinxin', db='ftpsearch', charset='utf8').cursor()
        self.querycol = ('id', 'site', 'port', 'user', 'pw')
        
    def fill(self):
        querystr = 'select %s, %s, %s, %s, %s from ftpinfo where indb=0'% self.querycol
        self.cur.execute(querystr)
        result = self.cur.fetchall()
        for row in result:
            self.site = dict(zip(self.querycol, row))
            self.fillonesite()
            
    def fillonesite(self):
        try:
            self.ftp = FTP()
            self.ftp.connect(self.site['site'], int(self.site['port']))
            self.ftp.login(self.site['user'], self.site['pw'])
        except:
            print 'something wrong happened, maybe the url is wrong!'
        else:
            self.startindex('')
            self.ftp.close()
            self.cur.execute("update ftpinfo set indb=1 where id="+str(self.site['id'])) 
            self.cur.execute("commit")
            
    def startindex(self, directory): # directory是绝对路径
        print 'indexing %s' % directory
        if not directory: #根目录
            self.cur.execute("insert into cat (ipid) values (%s)" % \
                       (self.site['id']))
        elif '/' not in directory: #第一层目录
            self.cur.execute("select id from cat where cat is null and ipid="+str(self.site['id']))
            pid = self.cur.fetchone()
            self.cur.execute("insert into cat (cat, pid, ipid) values ('%s', %s, %s)" % \
                            (directory, pid[0], self.site['id']))
        else:
            dirs = directory.split('/') #切片
            self.cur.execute("select id from cat where cat='"+dirs[-2]+"' and ipid="+str(self.site['id']))
            pid = self.cur.fetchone()
            self.cur.execute("insert into cat (cat, pid, ipid) values ('%s', %s, %s)" % \
                            (dirs[-1], pid[0], self.site['id']))
        
        for d in self.ftp.nlst(directory):    
            try:
                if d != self.ftp.nlst(d)[0]: #d是目录
                    self.startindex(d)
                else: #d是文件
                    if '/' not in d: #根目录下的文件
                        self.cur.execute("select id from cat where cat is null and ipid="+str(self.site['id']))
                        pid = self.cur.fetchone()
                        self.cur.execute("insert into files (file, postfix, pid, ipid) \
                            values ('%s', '%s', %s, %s)" % \
                            (d, d.split('.')[-1], pid[0], self.site['id']))
                    else:
                        dirs = d.split('/')
                        self.cur.execute("select id from cat where cat='"+dirs[-2]+"' and ipid="+str(self.site['id']))
                        pid = self.cur.fetchone()
                        self.cur.execute("insert into files (file, postfix, pid, ipid) \
                            values ('%s', '%s', %s, %s)" % \
                            (dirs[-1], dirs[-1].split('.')[-1], pid[0], self.site['id']))
            except: #d是空目录
                pass
   

        
if __name__ == '__main__':
    ftpindex = FtpIndex()
    ftpindex.fill()

