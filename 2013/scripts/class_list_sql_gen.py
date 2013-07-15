import sys

if len(sys.argv) != 2:
    print "Pass file name"
    sys.exit(1)

in_file = sys.argv[1]
in_file = open(in_file,'r')

div_cnt = 0 
sec_cnt = 0

def get_query(div_id, div_typ, primary_div_id, name):
    query = """insert into frithi_HORSESHOW.DIVISION(ID, SHOW_ID,DIVISION_TYPE_ID,PRIMARY_DIVISION_ID,NAME,ADD_DATE) values (%s,2,%s,%s, '%s', NOW());""" % (div_id, div_typ, primary_div_id, name)
    return query

for line in in_file:
    if line[0] == 'd':
        div_cnt += 1
        print get_query(div_cnt,3,'null',line[1:].strip())
    elif line[0] == 's':
        sec_cnt += 1
        print get_query(sec_cnt,2,div_cnt,line[1:].strip())
    elif line[0] == 'c':
        c_id = line[1:4]

        print get_query(c_id,1,sec_cnt,line[5:].strip())
        

        
        
