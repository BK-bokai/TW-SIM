import numpy as np
import pandas as pd
import matplotlib
import matplotlib.pyplot as plt
import csv
import os
import re
import xlsxwriter
import datetime

def txt_To_xlsx(filename, workdir):
    newfilename = filename.split('.')[0]+".xlsx"
    print('Processing txt_To_xlsx')
    # 创建工作簿
    workbook = xlsxwriter.Workbook(workdir+'\\'+newfilename)
    # 创建工作表
    worksheet = workbook.add_worksheet('工作表1')
    index = ['UTC', '馬祖', '彭佳嶼', '鞍部', '淡水站', '竹子湖', '基隆', '台北', '新屋', '板橋', '新竹', '宜蘭', '蘇澳', '金門', '梧棲',
                '台中', '花蓮', '日月潭', '澎湖', '阿里山', '嘉義', '玉山', '東吉島', '七股', '成功', '永康', '台南', '台東', '高雄', '大武', '蘭嶼', '恆春']
    for i in range(len(index)):
        worksheet.write(0, i, index[i])
    x = 1
    with open (workdir+'\\'+filename, 'r') as data:
        while True:
            # 按行循环，读取文本文件
            line = data.readline()
            if not line:
                break  # 如果没有内容，则退出循环
            for i in range(len(re.split(r'\s+', line.strip(' ')))):
                item = re.split(r'\s+', line.strip(' '))[i]
                worksheet.write(x, i, item)  # x单元格经度，i 单元格纬度
            x += 1  # excel另起一行
        workbook.close()
    return  newfilename