import sys

if len(sys.argv) != 2:
    print "Pass file"
    sys.exit(1)

f = open(sys.argv[1])

for line in f:
    parts = line.split("`")
    nbr = parts[0].strip()
    fname = parts[1].strip().replace("'","")
    lname = parts[2].strip().replace("'","")

    query = """insert into PERSON (ID, SHOW_EXHIBITOR_ID, FIRST_NAME, LAST_NAME, ADD_DATE) values (%s,'%s','%s','%s',NOW());""" % (nbr, nbr, fname,lname)
    reg_link_query = "insert into SHOW_REGISTRATION_LINK (SHOW_ID, PERSON_ID, SHOW_REGISTRATION_STATUS_ID, EXHIBITOR_ID, DATE_ADD) values (2,%s,1,'%s',NOW());" % (nbr,nbr)
    print query
    print reg_link_query


