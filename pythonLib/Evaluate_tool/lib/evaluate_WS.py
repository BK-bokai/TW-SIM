#!/usr/bin/env python
# coding=utf-8
import numpy as np
import pandas as pd
import matplotlib
import matplotlib.pyplot as plt
import csv
import os
import re
import xlsxwriter
import datetime
from .data_change_Time import data_change_Time
from .txt_To_xlsx import txt_To_xlsx


def evaluate_WS(obsfile,simfile,workdir,start,end):
    simdata = txt_To_xlsx(filename=simfile,workdir=workdir)
    obsdata = obsfile

    new_time_data = data_change_Time(obsdata=obsdata, simdata=simdata, workdir=workdir, start=start, end=end)

    obs = pd.read_excel(workdir+'\\'+new_time_data['obsdata'])
    sim = pd.read_excel(workdir+'\\'+new_time_data['simdata'])

    # print(obs['times'])
    # for i in range(0,len(obs['times'])):
    #     condition = sim['times'].isin([obs['times'][i]]) #找出與觀測資料同時間的模擬資料的位置
    #     print(isnumber(sim[condition][]))
   

    domain = {
        '北':['鞍部', '淡水站', '竹子湖', '基隆', '台北', '新屋', '板橋', '新竹', '宜蘭', '蘇澳'],
        '中':['梧棲', '台中', '日月潭', '阿里山', '嘉義', '玉山'],
        '南':['嘉義', '玉山', '永康', '台南', '高雄', '大武', '恆春'],
        '雲嘉':['台中', '日月潭', '阿里山', '嘉義', '玉山', '永康', '台南'],
        '東部':['台中', '花蓮', '日月潭', '阿里山', '玉山', '成功', '台東', '大武'],
        '中雲嘉':['梧棲', '台中', '日月潭', '阿里山', '嘉義', '玉山', '永康', '台南'],
        '全台':['鞍部', '淡水站', '竹子湖', '基隆', '台北', '新屋', '板橋', '新竹', '宜蘭', '蘇澳', '梧棲',
                    '台中', '花蓮', '日月潭', '阿里山', '嘉義', '玉山', '成功', '永康', '台南', '台東', '高雄', '大武', '恆春'],
    }


    sim_obs = {ston: 0 for ston in domain['全台']}
    square_sim_obs = {ston: 0 for ston in domain['全台']}
    sim_hr  = {ston: 0 for ston in domain['全台']}

    # local time 用 
    # for ston in domain['全台']:
    #     sim_hr_num=0
    #     print('Processing'+ston)
    #     for i in range(0,len(obs['times'])):
    #         condition = sim['times'].isin([obs['times'][i]]) #找出與觀測資料同時間的模擬資料的位置
    #         if (float(obs[ston][i]) < 999.) and (float(sim[condition][ston]) < 999.):
    #             sim_hr[ston] += 1 #總共模擬的小時 

    #             sim_obs[ston] += (float(sim[condition][ston])-float(obs[ston][i]))
    #             square_sim_obs[ston] += (float(sim[condition][ston])-float(obs[ston][i]))**2

    # UTC用 
    for ston in domain['全台']:
        sim_hr_num=0
        print('Processing'+ston)
        for i in range(0,len(sim['UTC'])):
            condition = obs['UTC'].isin([sim['UTC'][i]]) #找出與觀測資料同時間的模擬資料的位置
            if (float(obs[condition][ston]) < 999.) and (float(sim[ston][i]) < 999.):
                sim_hr[ston] += 1 #總共模擬的小時 

                sim_obs[ston] += (float(sim[ston][i])-float(obs[condition][ston]))
                square_sim_obs[ston] += (float(sim[ston][i])-float(obs[condition][ston]))**2


    MBE_result = {}
    RMSE_result = {}

    for area,stons in domain.items():

        MBE = {ston: 0 for ston in stons}
        MBE['overal'] = 0

        RMSE = {ston: 0 for ston in stons}
        RMSE['overal'] = 0 
        sim_overal_hr = 0
        for ston in stons:
            # print('Processing'+ston)
            if (sim_hr[ston] != 0):
                MBE[ston] = sim_obs[ston]/sim_hr[ston]
                RMSE[ston] = (square_sim_obs[ston]/sim_hr[ston])**0.5

                MBE['overal'] += sim_obs[ston]
                RMSE['overal'] += square_sim_obs[ston]

                sim_overal_hr += sim_hr[ston]
            else:
                MBE[ston] = None
                RMSE[ston] = None

        MBE['overal'] = MBE['overal']/(sim_overal_hr)
        RMSE['overal'] = (RMSE['overal']/(sim_overal_hr))**0.5


        MBE_result[area] = MBE
        RMSE_result[area] = RMSE

    return {'MBE':MBE_result,'RMSE':RMSE_result,'domain':domain}


    

