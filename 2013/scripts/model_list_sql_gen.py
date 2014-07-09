import sys

if len(sys.argv) != 2:
    print "Pass file"
    sys.exit(1)

file_name = sys.argv[1]

f = open(file_name)

idx = 0
#Make one pass to get number and initials, insert to PERSON
people = []

ex_nbr = None
ex_init = None


models = []
mo_nbr = None
mo_name = None
mo_breed = None
mo_gender = None
mo_div = None

for line in f:
    #
    # This part is for gathering ex numbers and initials
    #
    if line.find("NUMBER") != -1:
        if ex_nbr != None:
            print "Previous Ex:",people[-1]
            raise Exception("ex_nbr was not cleared. At line",line)

        parts = line.split(":")
        ex_nbr = parts[-1].strip()
        continue

    elif line.find("RESULTS:") != -1:
        if ex_nbr == None:
            raise Exception("Error processing initials:",ex_init)
        else:
            parts = line.split(":")
            ex_init = parts[-1].strip()

            people.append((ex_init,ex_nbr))
            ex_nbr = None
            ex_init = None


        continue
    #
    # This part is for gathering horse information
    #
    if line[0:5].isdigit():
        if mo_nbr != None:
            raise Exception("mo_nbr is not clear at",line)
        mo_nbr = line.strip()
    elif mo_nbr != None and mo_name == None:
        mo_name = line.strip().replace("'","")
    elif mo_name != None and mo_breed == None:
        mo_breed = line.strip()
    elif mo_breed != None and mo_gender == None:
        mo_gender = line.strip()
    elif mo_gender != None and mo_div == None:
        mo_div = line.strip()

        #Finished all parts of model
        models.append((mo_nbr[0:3],mo_nbr,mo_name,mo_breed,mo_gender,mo_div))

        mo_nbr = None
        mo_name = None
        mo_breed = None
        mo_gender = None
        mo_div = None

#Updaet initials
for p in people:
   ex_nbr = p[0]
   ex_init = p[1]

   query = """update PERSON set NICKNAME = '%s' where ID = %s;""" % p 
   print query
    

for m in models:
    query = """insert into PERSON_MODEL (PERSON_ID, SHOW_MODEL_ID, SHOW_MODEL_NAME, SHOW_MODEL_BREED, SHOW_MODEL_GENDER, USER_FIELD_1) values (%s,'%s','%s','%s','%s','%s');""" % m


    print query
