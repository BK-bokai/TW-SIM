import MySQLdb
import re, time
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

start = '2016-01-01'
end   = '2016-01-31'
#修改查詢條件的資料
# cur.execute("update evaluate_tasks set Finish='%d' where Time_Period = '%s'" % (now+"_"+start+"-"+end,True,start+'_'+end))
cur.execute("update met_evaluates set Finish='%d' where Time_Period = '%s'" % (True,start+'_'+end))
conn.commit() 
# # Connect MySQL
# import mysql.connector
# start = '2016-06-01'
# end   = '2016-06-300'
# conn = mysql.connector.connect(
#   host = "127.0.0.1",
#   user = "bokai",
#   password = "2841p4204",
#   database = "tw_sim_evaluate",
#   )
# cursor=conn.cursor() 
# update_users = "UPDATE test SET Finish='%s' where Time_Period = '%s'" % ('test',start+'_'+end)
# cursor.execute(update_users)
# conn.commit()

print(time.perf_counter())
print(time.clock())


