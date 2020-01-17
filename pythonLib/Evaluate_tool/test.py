import datetime
start = datetime.datetime.strptime('2016-06-01', '%Y-%m-%d')
end = datetime.datetime.strptime('2016-06-30', '%Y-%m-%d')
print(int((end-start).days)+1)


