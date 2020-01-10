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


def evaluate_WD(obsfile, simfile, workdir, start, end):
    simdata = txt_To_xlsx(filename=simfile, workdir=workdir)
    obsdata = obsfile

    new_time_data = data_change_Time(
        obsdata=obsdata, simdata=simdata, workdir=workdir, start=start, end=end)

    obs = pd.read_excel(workdir+'\\'+new_time_data['obsdata'])
    sim = pd.read_excel(workdir+'\\'+new_time_data['simdata'])

    domain = {
        '北': ['鞍部', '淡水站', '竹子湖', '基隆', '台北', '新屋', '板橋', '新竹', '宜蘭', '蘇澳'],
        '中': ['梧棲', '台中', '日月潭', '阿里山', '嘉義', '玉山'],
        '南': ['嘉義', '玉山', '永康', '台南', '高雄', '大武', '恆春'],
        '雲嘉': ['台中', '日月潭', '阿里山', '嘉義', '玉山', '永康', '台南'],
        '東部': ['台中', '花蓮', '日月潭', '阿里山', '玉山', '成功', '台東', '大武'],
        '中雲嘉': ['梧棲', '台中', '日月潭', '阿里山', '嘉義', '玉山', '永康', '台南'],
        '全台': ['鞍部', '淡水站', '竹子湖', '基隆', '台北', '新屋', '板橋', '新竹', '宜蘭', '蘇澳', '梧棲',
                    '台中', '花蓮', '日月潭', '阿里山', '嘉義', '玉山', '成功', '永康', '台南', '台東', '高雄', '大武', '恆春'],
    }

    sim_obs = {ston: 0 for ston in domain['全台']}
    abs_sim_obs = {ston: 0 for ston in domain['全台']}
    sim_hr = {ston: 0 for ston in domain['全台']}

    for ston in domain['全台']:
        sim_hr_num=0
        print('Processing'+ston)


        for i in range(0,len(sim['UTC'])):
            if (obs['UTC'][i]==sim['UTC'][i]) and (float(obs[ston][i]) < 999.) and (float(sim[ston][i]) < 999.):
                sim_hr[ston] += 1 #總共模擬的小時 
                if (float(sim[ston][i])-float(obs[ston][i])) > 180.0:
                    sim_obs[ston] += (float(sim[ston][i])-float(obs[ston][i])-360.0)
                    abs_sim_obs[ston] += abs((float(sim[ston][i])-float(obs[ston][i])-360.0))
                elif (float(sim[ston][i])-float(obs[ston][i])) < -180.0:
                    sim_obs[ston] += (float(sim[ston][i])-float(obs[ston][i])+360.0)
                    abs_sim_obs[ston] += abs((float(sim[ston][i])-float(obs[ston][i])+360.0))
                else:
                    sim_obs[ston] += (float(sim[ston][i])-float(obs[ston][i]))
                    abs_sim_obs[ston] += abs(float(sim[ston][i])-float(obs[ston][i]))
                
    WNMB_result={}
    WNME_result={}
      
    for area,stons in domain.items():

        WNMB={ston: 0 for ston in stons}
        WNMB['overal']=0

        WNME={ston: 0 for ston in stons}
        WNME['overal']=0

        sim_overal_hr = 0

        for ston in stons:
            # print('Processing'+ston)
            if (sim_hr[ston] != 0):
                WNMB[ston]= sim_obs[ston]/(360*sim_hr[ston])
                WNME[ston]= abs_sim_obs[ston]/(360*sim_hr[ston])

                WNMB['overal'] += sim_obs[ston]
                WNME['overal'] += abs_sim_obs[ston]

                sim_overal_hr += sim_hr[ston]
            else:
                WNMB[ston]=None
                WNME[ston]=None


        sim_overal_hr = sim_overal_hr/len(stons) # 把所有測站總模擬小時除上測站數就是所有測站平均模擬小時
        
        WNMB['overal'] = (WNMB['overal']/(360*sim_overal_hr*len(stons)))
        WNME['overal'] = (WNME['overal']/(360*sim_overal_hr*len(stons)))

        WNMB_result[area]=WNMB
        WNME_result[area]=WNME


    return {'WNMB': WNMB_result, 'WNME': WNME_result, 'domain': domain}
