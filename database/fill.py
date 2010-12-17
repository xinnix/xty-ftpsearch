# coding: utf-8
# 运行环境: python 2.5以上, MySQLdb模块, mysql数据库5.0以上
# 使用方式: python fill.py [option]
# -u 更新已经索引的站点

import sys
import os
from MySQLdb import *
from ftplib import FTP


class FtpIndex:
    def __init__(self):
        self.cur = connect(host='localhost', user='root', passwd='xinxin', db='ftpsearch', charset='utf8').cursor()
        self.querycol = ('id', 'site', 'port', 'user', 'pw')
        self.firstdir = []

    def update(self):
        querystr = 'select %s, %s, %s, %s, %s from ftpinfo where indb=1'% self.querycol
        self.cur.execute(querystr)
        result = self.cur.fetchall()
        for row in result:
            self.site = dict(zip(self.querycol, row))
            self.updateonesite()

    def updateonesite(self):
        self.cur.execute('delete from files where ipid=%s'% self.site['id'])
        while(True):
            self.cur.execute('select max(pid) from cat where ipid=%s'% self.site['id'])
            maxpid = self.cur.fetchone()
            if maxpid[0] is not None:
                print 'deleting %s'% maxpid[0]
                self.cur.execute('delete from cat where pid=%s'% maxpid)
                #self.cur.execute('commit')
            else:
                self.cur.execute('delete from cat where ipid=%s'% self.site['id'])             
                break
        self.cur.execute('commit')
        self.fillonesite()
        
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
            # 先索引根目录,这样在以后的递归索引中就不用判断是否为根目录
            self.indexroot()

            for node in self.firstdir:
                self.indexsubnode(node)
            self.ftp.abort()
            self.cur.execute("update ftpinfo set indb=1 where id="+str(self.site['id'])) 
            self.cur.execute("commit")

    def indexroot(self):
        self.cur.execute("insert into cat (ipid) values (%s)" % self.site['id'])
        for node in self.ftp.nlst():
            subnode = self.ftp.nlst(node)
            self.cur.execute("select id from cat where cat is null and ipid=%s" % self.site['id'])
            pid = self.cur.fetchone()
            if len(subnode) == 0: # node为空目录
                self.cur.execute("insert into cat (cat, pid, ipid) values ('%s', %s, %s)" % \
                                 (node, pid[0], self.site['id']))

            elif len(subnode) > 1: # node为多文件目录
                self.cur.execute("insert into cat (cat, pid, ipid) values ('%s', %s, %s)" % \
                                 (node, pid[0], self.site['id']))
                self.firstdir.append(node)
                
            elif subnode[0] != node: # node为单文件目录
                self.cur.execute("insert into cat (cat, pid, ipid) values ('%s', %s, %s)" % \
                                 (node, pid[0], self.site['id']))
                self.firstdir.append(node)
                
            else: # node为文件
                if '.' in node: # 文件有后缀
                    self.cur.execute("insert into files (file, postfix, pid, ipid)\
                                    values ('%s', '%s', %s, %s)" % \
                                    (node, node.split('.')[-1], pid[0], self.site['id']))
                else: 
                    self.cur.execute("insert into files (file, pid, ipid)\
                                    values ('%s', %s, %s)" % \
                                    (node, pid[0], self.site['id']))

                        
    def indexsubnode(self, directory): # directory是绝对路径
        print 'indexing %s' % directory

        for node in self.ftp.nlst(directory):
            subnode = self.ftp.nlst(node)
            dirs = node.split('/') #切片
            self.cur.execute("select id from cat where cat='%s' and ipid=%s" % (dirs[-2], self.site['id']))
            pid = self.cur.fetchone()
            if len(subnode) == 0: # node为空目录
                self.cur.execute("insert into cat (cat, pid, ipid) values ('%s', %s, %s)" % \
                                (dirs[-1], pid[0], self.site['id']))

            elif len(subnode) > 1: # node为多文件目录
                self.cur.execute("insert into cat (cat, pid, ipid) values ('%s', %s, %s)" % \
                                (dirs[-1], pid[0], self.site['id']))
                self.indexsubnode(node)

            elif subnode[0] != node: # node为单文件目录
                self.cur.execute("insert into cat (cat, pid, ipid) values ('%s', %s, %s)" % \
                                 (dirs[-1], pid[0], self.site['id']))
                self.indexsubnode(node)

            else: # node为文件
                if '.' in node: # 文件有后缀
                    print dirs[-1]
                    self.cur.execute("insert into files (file, postfix, pid, ipid)\
                                    values ('%s', '%s', %s, %s)" % \
                                    (dirs[-1], dirs[-1].split('.')[-1], pid[0], self.site['id']))
                else: 
                    self.cur.execute("insert into files (file, pid, ipid)\
                                    values ('%s', %s, %s)" % \
                                    (dirs[-1], pid[0], self.site['id']))

if __name__ == '__main__':
    ftpindex = FtpIndex()
    if len(sys.argv) == 2:
        if sys.argv[1] == '-u':
            ftpindex.update()
    else:
        ftpindex.fill()

