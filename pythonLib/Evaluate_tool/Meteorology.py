#!/usr/bin/env python
# coding=utf-8
import numpy as np
import pandas as pd
import matplotlib
import matplotlib.pyplot as plt
import csv
import os, sys
import re, time
import xlsxwriter
import datetime
from lib.data_change_Time import data_change_Time
from lib.txt_To_xlsx import txt_To_xlsx
from lib.evaluate_T2_V2 import evaluate_T2
from lib.evaluate_WS_V2 import evaluate_WS
from lib.evaluate_WD import evaluate_WD
from lib.MergeTxt_obs import MergeTxt_obs
from lib.MergeTxt_sim import MergeTxt_sim
import MySQLdb


t1 = time.clock()

# start = input("請輸入起始時間，ex:2016-01-01 : ")
# end = input("請輸入結束時間，ex:2016-02-01 : ")
start = sys.argv[1]
end   = sys.argv[2]
now   = sys.argv[3]
rootdir = sys.argv[4]

# rootpath = os.path.dirname(os.path.abspath(__file__))

# now = time.strftime("%Y-%m-%d-%H-%M-%S", time.localtime())
# workdir =rootpath + "\\result\\"+now+"_"+start+"-"+end
# workdir =rootpath + "\\result\\"+start+"_"+end
workdir = rootdir+"Evaluate\\"+now+"_"+start+"-"+end

if not os.path.isdir(workdir):
    os.mkdir(workdir)


obsfile_T2= MergeTxt_obs(start=start,end=end,workdir=workdir,rootdir=rootdir, var = 'T2')
simfile_T2= MergeTxt_sim(start=start,end=end,workdir=workdir,rootdir=rootdir, var = 'T2')

obsfile_WS= MergeTxt_obs(start=start,end=end,workdir=workdir,rootdir=rootdir, var = 'WS')
simfile_WS= MergeTxt_sim(start=start,end=end,workdir=workdir,rootdir=rootdir, var = 'WS')

obsfile_WD= MergeTxt_obs(start=start,end=end,workdir=workdir,rootdir=rootdir, var = 'WD')
simfile_WD= MergeTxt_sim(start=start,end=end,workdir=workdir,rootdir=rootdir, var = 'WD')

T2_eva = evaluate_T2(obsfile=obsfile_T2,simfile=simfile_T2,workdir=workdir,start=start,end=end)
WS_eva = evaluate_WS(obsfile=obsfile_WS,simfile=simfile_WS,workdir=workdir,start=start,end=end)
WD_eva = evaluate_WD(obsfile=obsfile_WD,simfile=simfile_WD,workdir=workdir,start=start,end=end)


# 创建工作簿
workbook = xlsxwriter.Workbook(workdir+'//'+start+'_'+end+'_evaluate.xlsx')
for area,stons in WD_eva['domain'].items():
    # 创建工作表
    worksheet = workbook.add_worksheet(area)
    worksheet.write(0, 1, 'MBE')
    worksheet.write(0, 2, 'MAGE')
    worksheet.write(0, 3, 'MBE')
    worksheet.write(0, 4, 'RMSE')
    worksheet.write(0, 5, 'WNMB')
    worksheet.write(0, 6, 'WNME')
    for i in range(len(stons)):
        
        MBE_item_T2 = T2_eva['MBE'][area][stons[i]]
        MAGE_item_T2 = T2_eva['MAGE'][area][stons[i]]

        MBE_item_WS = WS_eva['MBE'][area][stons[i]]
        RMSE_item_WS = WS_eva['RMSE'][area][stons[i]]

        WNMB_item_WD = WD_eva['WNMB'][area][stons[i]]
        WNME_item_WD = WD_eva['WNME'][area][stons[i]]

        worksheet.write(i+1, 0, stons[i])
        worksheet.write(i+1, 1, round(MBE_item_T2,2))  # 第一個參數是列，第二個是行
        worksheet.write(i+1, 2, round(MAGE_item_T2,2))
        worksheet.write(i+1, 3, round(MBE_item_WS,2))  # 第一個參數是列，第二個是行
        worksheet.write(i+1, 4, round(RMSE_item_WS,2))
        worksheet.write(i+1, 5, str(round(WNMB_item_WD*100,2))+'%')  # 第一個參數是列，第二個是行
        worksheet.write(i+1, 6, str(round(WNME_item_WD*100,2))+'%')

        worksheet.write(len(stons), 0, 'overal')
        worksheet.write(len(stons), 1, round(T2_eva['MBE'][area]['overal'],2))
        worksheet.write(len(stons), 2, round(T2_eva['MAGE'][area]['overal'],2))
        worksheet.write(len(stons), 3, round(WS_eva['MBE'][area]['overal'],2))
        worksheet.write(len(stons), 4, round(WS_eva['RMSE'][area]['overal'],2))
        worksheet.write(len(stons), 5, str(round(WD_eva['WNMB'][area]['overal']*100,2))+'%')
        worksheet.write(len(stons), 6, str(round(WD_eva['WNME'][area]['overal']*100,2))+'%')

workbook.close()



#connect() 方法用於建立資料庫的連線，裡面可以指定引數：使用者名稱，密碼，主機等資訊。
#這只是連線到了資料庫，要想操作資料庫需要建立遊標。
conn= MySQLdb.connect(
        host='localhost',
        port = 3306,
        user='bokai',
        passwd='2841p4204',
        db ='tw_sim_evaluate',
        )

#通過獲取到的資料庫連線conn下的cursor()方法來建立遊標。
cur = conn.cursor()

#修改查詢條件的資料
# cur.execute("update evaluate_tasks set Path='%s',Finish='%d' where Time_Period = '%s'" % (now+"_"+start+"-"+end,True,start+'_'+end))
cur.execute("update met_evaluates set Finish='%d' where Time_Period = '%s'" % (True,start+'_'+end))
conn.commit() 
 
t2 = time.clock()
print('你執行了:')
print (t2-t1)


print('finish')
