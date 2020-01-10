import numpy as np
import pandas as pd
import matplotlib
import matplotlib.pyplot as plt
import csv
import os
import re
import xlsxwriter
import datetime

# def data_change_Time(obsdata, simdata, workdir):
#     print('Processing data_change_Time')
#     # obs = pd.read_excel(workdir+'\\'+obsdata)
#     sim = pd.read_excel(workdir+'\\'+simdata)

#     # new_time_obsdate = 'new_time_'+obsdata
#     new_time_simdate = 'new_time_'+simdata

#     # if not(os.path.isfile(workdir+'\\'+new_time_obsdate)):
#     #     for i in range(0, len(obs['times'])):
#     #         # 將檔名的年份與檔案內時間結合
#     #         obs['GMT00    GMT08'][i] = obsdata[0:4] + \
#     #             obs['GMT00    GMT08'][i][0:8]
#     #         # 透過datetime轉換成時間格式再加8小時
#     #         obstime = (datetime.datetime.strptime(
#     #             obs['GMT00    GMT08'][i], '%Y%m-%d-%H')+datetime.timedelta(hours=8))
#     #         # 最後再覆蓋回檔案讓時間可以比較
#     #         obs['GMT00    GMT08'][i] = obstime

#     #     obs.to_excel(workdir+'\\'+new_time_obsdate, index=False)

#     if not(os.path.isfile(workdir+'\\'+new_time_simdate)):
#         for i in range(0, len(sim['times'])):
#             # 取出檔案內的時間，透過datetime轉換成時間格式再加8小時
#             simtime = (datetime.datetime.strptime(
#                 sim['times'][i][11:24], '%Y-%m-%d_%H')+datetime.timedelta(hours=8)).strftime('%Y-%m-%d-%H')
#             # 最後再覆蓋回檔案讓時間可以比較
#             # print(simtime)
#             sim['times'][i] = simtime

#         sim.to_excel(workdir+'\\'+new_time_simdate, index=False)

#     return {'obsdata': obsdata, 'simdata': new_time_simdate}


def data_change_Time(obsdata, simdata, workdir, start, end):
    print('Processing data_change_Time')
    obs = pd.read_excel(workdir+'\\'+obsdata)
    sim = pd.read_excel(workdir+'\\'+simdata)

    new_time_obsdate = 'new_time_'+obsdata
    new_time_simdate = 'new_time_'+simdata

    # if not(os.path.isfile(workdir+'\\'+new_time_obsdate)):
    #     for i in range(0, len(obs['times'])):
    #         # 透過datetime轉換成時間格式再加8小時
    #         obstime = (datetime.datetime.strptime(
    #             obs['times'][i], '%Y-%m-%d-%H')-datetime.timedelta(hours=8)).strftime('%Y-%m-%d-%H')
    #         # 最後再覆蓋回檔案讓時間可以比較
    #         obs['times'][i] = obstime

    #     obs.to_excel(workdir+'\\'+new_time_obsdate, index=False)

    # if not(os.path.isfile(workdir+'\\'+new_time_simdate)):
    #     for i in range(0, len(sim['times'])):
    #         # 取出檔案內的時間，透過datetime轉換成時間格式再加8小時
    #         simtime = (datetime.datetime.strptime(
    #             sim['times'][i][11:24], '%Y-%m-%d_%H')).strftime('%Y-%m-%d-%H')
    #         # 最後再覆蓋回檔案讓時間可以比較
    #         # print(simtime)
    #         sim['times'][i] = simtime

    #     sim.to_excel(workdir+'\\'+new_time_simdate, index=False)


    for i in range(0, len(sim['UTC'])):
        # 取出檔案內的時間，透過datetime轉換成時間格式再加8小時
        sim['UTC'][i] = (datetime.datetime.strptime(sim['UTC'][i][11:24], '%Y-%m-%d_%H')).strftime('%Y-%m-%d-%H')

    obs[obs["UTC"].between(start+'-00',end+'-23')].to_excel(workdir+'\\'+new_time_obsdate, index=False)
    sim[sim["UTC"].between(start+'-00',end+'-23')].to_excel(workdir+'\\'+new_time_simdate, index=False)

    return {'obsdata': new_time_obsdate, 'simdata': new_time_simdate}
