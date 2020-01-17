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
import glob
import sys

# rootpath = os.path.dirname(os.path.abspath(__file__))


def MergeTxt_obs(start, end, workdir, rootdir, var):
    start = datetime.datetime.strptime(start, '%Y-%m-%d')
    end = datetime.datetime.strptime(end, '%Y-%m-%d')
    # start -= datetime.timedelta(days=1)
    end += datetime.timedelta(days=1)
    filetimes = []
    while (start <= end):
        filetime = start.strftime('%Y-%m-%d')
        filetimes.append(filetime)
        start += datetime.timedelta(days=1)
        obs_out = pd.read_excel(
            rootdir+"Obs\\"+var+"\\"+filetimes[0]+"_"+var+"_obs.xlsx")

    for i in range(1, len(filetimes)):
        obs_in = pd.read_excel(
            rootdir+"Obs\\"+var+"\\"+filetimes[i]+"_"+var+"_obs.xlsx")
        # print(obs_in['times'][i])
        obs_out = pd.merge(obs_out, obs_in, how='outer')
    newfile = filetimes[0]+'_'+filetimes[-2]+'_'+var+'_obs.xlsx'
    obs_out.to_excel(workdir+"\\"+newfile, index=False)
    return newfile


# start = '2016-06-01'
# end = '2016-06-04'

# obs_1 = pd.read_excel("D:\\bokai\\python\\python-code\\氣象性能評估工具\\data\\obs\\2016-06-02_t2_obs.xlsx")
# obs_2 = pd.read_excel("D:\\bokai\\python\\python-code\\氣象性能評估工具\\data\\obs\\2016-06-04_t2_obs.xlsx")
# newdata = pd.merge(obs_1,obs_2,how='outer')
# newdata.to_excel('D:\\bokai\\python\\python-code\\氣象性能評估工具\\data\\newobs\\2016-06-02_2016-06-04_t2_obs.xlsx',index=False)
