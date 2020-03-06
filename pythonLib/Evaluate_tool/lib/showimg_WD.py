#!/usr/bin/env python
# coding=utf-8
import numpy as np
import pandas as pd
import matplotlib
import matplotlib.pyplot as plt
import csv
import datetime
import os, sys
import xlsxwriter

# obs = pd.read_csv('obs.csv')


def showimg_WD(resultDir,start, end, obsdata, simdata):

    imgdir = resultDir+"\\imgs"
    wddir  = resultDir+"\\imgs\\WD"
    if not os.path.isdir(imgdir):
        os.mkdir(imgdir)
    if not os.path.isdir(wddir):
        os.mkdir(wddir)

    start = datetime.datetime.strptime(start, '%Y-%m-%d')
    end = datetime.datetime.strptime(end, '%Y-%m-%d')
    days=int((end-start).days)+1
    hours=days*24
    # date= "201611"
    # mon = date[4:6]
    obs = pd.read_excel(resultDir+"\\"+obsdata)
    sim = pd.read_excel(resultDir+"\\"+simdata)

    obs.drop(['LTS'], axis=1) # 刪除sim沒有的資料

    x2 = sim["UTC"]
    # x2 = sim["GMT00    GMT08"]


    stons=[]
    stons_obs_avg={}
    stons_sim_avg={}

    i=0


    # plt.rcParams['font.sans-serif']=['SimHei']
    plt.rcParams['font.sans-serif'] = ['Microsoft JhengHei'] 
    # plt.rcParams['font.sans-serif']=['SimHei']
    plt.rcParams['axes.unicode_minus']=False

    for key, value in obs.items() :
        # i=i+1
        if(key=='UTC' or key=='LTS'):
            continue
        stons.append(key)

    for ston in stons:
        # print(ston)
        ston_obs_avg = np.zeros(24)
        for i in range(0, hours, 24):
            ston_obs_avg[0:24] = obs[ston][i:i+24]+ston_obs_avg[0:24]
        stons_obs_avg[ston] =ston_obs_avg[0:24]/days
        
        ston_sim_avg = np.zeros(24)
        for i in range(0, hours, 24):
            ston_sim_avg[0:24] = sim[ston][i:i+24]+ston_sim_avg[0:24]
        stons_sim_avg[ston] =ston_sim_avg[0:24]/days

        plt.close('all')
        plt.rcParams['figure.dpi'] = 500
        plt.figure(figsize=(12, 9))
        # plt.figure(figsize=(12, 12))
        plt.xticks(fontsize=20)
        plt.yticks(fontsize=20)
        plt.plot(range(0,24,1), stons_obs_avg[ston][0:24],'o',
            color='black', lw=3, label= 'obs')
        plt.plot(range(0,24,1), stons_sim_avg[ston][0:24],'o',
            color='red', lw=3, label= 'sim_ucm')

        
        my_x_ticks = np.arange(0, 24, 1)
        my_y_ticks = np.arange(0,361,45)

        plt.xticks(my_x_ticks)
        plt.yticks(my_y_ticks)



        plt.xlabel("UTC-Time", fontsize=20)
        plt.ylabel('Wind Direct', fontsize=20)  # 給左邊的Y軸說明
        plt.title( ston+"\n"+start.strftime('%Y-%m-%d')+"至"+end.strftime('%Y-%m-%d')+" \n 日平均風向", fontsize=20)
        plt.legend(loc='best', fontsize=17)
        # plt.xticks(rotation=70)
        plt.savefig(wddir+'\\'+ start.strftime('%Y-%m-%d')+"_"+end.strftime('%Y-%m-%d')+"average"+ston+".png", dpi=500, format="png")

    index= ['UTC','鞍部', '淡水站', '竹子湖', '基隆', '台北', '新屋', '板橋', '新竹', '宜蘭', '蘇澳', '梧棲',
                    '台中', '花蓮', '日月潭', '阿里山', '嘉義', '玉山', '成功', '永康', '台南', '台東', '高雄', '大武', '恆春']
    time=list(range(0,24,1))
    stons_obs_avg['UTC']=time
    stons_sim_avg['UTC']=time
    # 创建工作簿  
    workbook = xlsxwriter.Workbook(resultDir+'\\'+'AVG'+obsdata)
    # 创建工作表
    worksheet = workbook.add_worksheet('obsAvg')


    for i in range(len(index)):
        worksheet.write(0, i, index[i])
        for j in range(0,24):
            worksheet.write(j+1, i, stons_obs_avg[index[i]][j])
    workbook.close()

    # 创建工作簿  
    workbook = xlsxwriter.Workbook(resultDir+'\\'+'AVG'+simdata)
    # 创建工作表
    worksheet = workbook.add_worksheet('simAvg')

    for i in range(len(index)):
        worksheet.write(0, i, index[i])
        for j in range(0,24):
            worksheet.write(j+1, i, stons_sim_avg[index[i]][j])
    workbook.close()






# resultDir="D:\\bokai\\xampp\\htdocs\\php\\TW_SIM_Evaluate\\public\\MetData\\Evaluate\\2020-01-15-10-58-31_2016-02-01-2016-02-29\\Result"
# start = '2016-02-01'
# end   = '2016-02-29'
# obsdata='2016-02-01_2016-02-29_T2_obs.xlsx'
# simdata='2016-02-01_2016-02-29_T2_sim.xlsx'
# showimg_T2(resultDir=resultDir,start=start, end=end, obsdata=obsdata, simdata=simdata)
