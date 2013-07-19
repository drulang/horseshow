import sys

if len(sys.argv) != 2:
    print "Pass file"
    sys.exit(1)

f = open(sys.argv[1])

for line in f:
    parts = line.split("`")
    nbr = parts[0].strip()

    if len(nbr) == 1:
        str_nbr = "00" + nbr
    elif len(nbr) == 2:
        str_nbr = "0" + nbr
    else:
        str_nbr = nbr


    fname = parts[1].strip().replace("'","")
    lname = parts[2].strip().replace("'","")

    query = """insert into PERSON (ID, SHOW_EXHIBITOR_ID, FIRST_NAME, LAST_NAME, ADD_DATE) values (%s,'%s','%s','%s',NOW());""" % (nbr, str_nbr, fname,lname)
    reg_link_query = "insert into SHOW_REGISTRATION_LINK (SHOW_ID, PERSON_ID, SHOW_REGISTRATION_STATUS_ID, EXHIBITOR_ID, DATE_ADD) values (2,%s,1,'%s',NOW());" % (nbr,str_nbr)
    print query
    print reg_link_query


