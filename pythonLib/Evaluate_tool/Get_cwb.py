# from CWB_module import cwbdata
import urllib.request as request
from bs4 import BeautifulSoup
import numpy as np
import csv
import datetime
import time
import calendar
import pandas as pd
import xlsxwriter
from lib.CWB_module import cwbdata



# #filename = input("請輸入要儲存檔案名稱，ex:201601.csv : ")
# start = input("請輸入起始時間，ex:2016-01-01 : ")
# end = input("請輸入結束時間，ex:2016-02-01 : ")
# output = input("請出入你所需的資料，ex:WS、WD、T2 : ")

time_one = datetime.datetime.strptime('2017-01-01', '%Y-%m-%d')
time_two = datetime.datetime.strptime('2017-01-01', '%Y-%m-%d')

while (time_one <= time_two):
    start = time_one.strftime('%Y-%m-%d')
    end   = time_one.strftime('%Y-%m-%d')
    output = 'WD'
    filename = start+'_'+output+'_obs'
    


    hrnum = ((datetime.datetime.strptime(end, '%Y-%m-%d') -
            datetime.datetime.strptime(start, '%Y-%m-%d')).days*24)+24

    starthr = str(start)+'-01'
    endhr = str(end)+'-00'

    datestarthr = datetime.datetime.strptime(starthr, '%Y-%m-%d-%H')
    dateendhr = datetime.datetime.strptime(
        endhr, '%Y-%m-%d-%H')+datetime.timedelta(days=1)

    times = []
    utc = []
    while datestarthr <= dateendhr:
        # times.append(str(datestarthr))
        times.append(datestarthr.strftime('%Y-%m-%d-%H'))
        utc.append((datestarthr-datetime.timedelta(hours=8)).strftime('%Y-%m-%d-%H'))
        datestarthr = datestarthr+datetime.timedelta(hours=1)
    times = np.array(times[:])

    # print("#馬祖")
    # T467990=CWB_WS_module.cwbdata(str('467990'),start,end)
    # print(times.shape,T467990.shape,hrnum)
    # 彭佳嶼
    # T466950=CWB_WS_module.cwbdata(str('466950'),start,end)
    print("#鞍部")
    WS466910 = cwbdata(str('466910'), start, end, output)
    print("#淡水")
    WS466900 = cwbdata(str('466900'), start, end, output)
    print("#竹子湖")
    WS466930 = cwbdata(str('466930'), start, end, output)
    print("#基隆")
    WS466940 = cwbdata(str('466940'), start, end, output)
    print("#臺北")
    WS466920 = cwbdata(str('466920'), start, end, output)
    print("#新屋")
    WS467050 = cwbdata(str('467050'), start, end, output)
    print("#板橋")
    WS466880 = cwbdata(str('466880'), start, end, output)
    print("#新竹")
    WS467571 = cwbdata(str('467571'), start, end, output)
    print("#宜蘭")
    WS467080 = cwbdata(str('467080'), start, end, output)
    print("#蘇澳")
    WS467060 = cwbdata(str('467060'), start, end, output)
    # print("#金門")
    # T467110=CWB_WS_module.cwbdata(str('467110'),start,end)
    print("#梧棲")
    WS467770 = cwbdata(str('467770'), start, end, output)
    print("#台中")
    WS467490 = cwbdata(str('467490'), start, end, output)
    print("#花蓮")
    WS466990 = cwbdata(str('466990'), start, end, output)
    print("#日月潭")
    WS467650 = cwbdata(str('467650'), start, end, output)
    # print("#澎湖")
    # T467350=CWB_WS_module.cwbdata(str('467350'),start,end)
    print("#阿里山")
    WS467530 = cwbdata(str('467530'), start, end, output)
    print("#嘉義")
    WS467480 = cwbdata(str('467480'), start, end, output)
    print("#玉山")
    WS467550 = cwbdata(str('467550'), start, end, output)
    # print("#東吉")
    # T467300=CWB_WS_module.cwbdata(str('467300'),start,end)
    # print("#七股")
    print("#成功")
    WS467610 = cwbdata(str('467610'), start, end, output)
    print("#永康")
    WS467420 = cwbdata(str('467420'), start, end, output)
    print("#台南")
    WS467410 = cwbdata(str('467410'), start, end, output)
    print("#台東")
    WS467660 = cwbdata(str('467660'), start, end, output)
    print("#高雄")
    WS467440 = cwbdata(str('467440'), start, end, output)
    print("#大武")
    WS467540 = cwbdata(str('467540'), start, end, output)
    # print("#蘭嶼")
    # T467620=CWB_WS_module.cwbdata(str('467620'),start,end)
    print("#恆春")
    WS467590 = cwbdata(str('467590'), start, end, output)

    data = {
        'LTS': times,
        'UTC': utc,
        '鞍部': WS466910,
        '淡水站': WS466900,
        '竹子湖': WS466930,
        '基隆': WS466940,
        '台北': WS466920,
        '新屋': WS467050,
        '板橋': WS466880,
        '新竹': WS467571,
        '宜蘭': WS467080,
        '蘇澳': WS467060,
        '梧棲': WS467770,
        '台中': WS467490,
        '花蓮': WS466990,
        '日月潭': WS467650,
        '阿里山': WS467530,
        '嘉義': WS467480,
        '玉山': WS467550,
        '成功': WS467610,
        '永康': WS467420,
        '台南': WS467410,
        '台東': WS467660,
        '高雄': WS467440,
        '大武': WS467540,
        '恆春': WS467590,
    }

    workbook = xlsxwriter.Workbook(filename+'.xlsx')
    # 创建工作表
    worksheet = workbook.add_worksheet('工作表1')
    # index = ['times', '馬祖', '彭佳嶼', '鞍部', '淡水站', '竹子湖', '基隆', '台北', '新屋', '板橋', '新竹', '宜蘭', '蘇澳', '金門', '梧棲',
    #             '台中', '花蓮', '日月潭', '澎湖', '阿里山', '嘉義', '玉山', '東吉島', '七股', '成功', '永康', '台南', '台東', '高雄', '大武', '蘭嶼', '恆春']
    i = 0
    for index in data:
        worksheet.write(0, i, index)
        i += 1
    for i in range(0, int(hrnum)):
        k = 0
        for j in data:
            worksheet.write(i+1, k, data[j][i])
            k += 1
    workbook.close()

    time_one += datetime.timedelta(days=1)
