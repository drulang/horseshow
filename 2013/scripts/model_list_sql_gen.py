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
            raise Exception("ex_nbr was not cleared. At line",line)

        parts = line.split(":")
        ex_nbr = parts[-1].strip()
        continue

    elif line.find("RESULTS:") != -1:
        if ex_nbr == None:
            raise Exception("Error processing initials:",ex_init)
        else:
            people.append((ex_nbr,ex_init))
            ex_nbr = None
            ex_init = None

        parts = line.split(":")
        ex_init = parts[-1].strip()

        continue
    #
    # This part is for gathering horse information
    #
    if line[0:5].isdigit():
        if mo_nbr != None:
            raise Exception("mo_nbr is not clear at",line)
        mo_nbr = line.strip()
    elif mo_nbr != None and mo_name == None:
        mo_name = line.strip()
        print mo_name
    elif mo_name != None and mo_breed == None:
        mo_breed = line.strip()
    elif mo_breed != None and mo_gender == None:
        mo_gender = line.strip()
    elif mo_gender != None and mo_div == None:
        mo_div = line.strip()
    elif mo_div != None:
        #Finished all parts of model
        models.append((mo_nbr,mo_name,mo_breed,mo_gender,mo_div))

        mo_nbr = None
        mo_name = None
        mo_breed = None
        mo_gender = None
        mo_div = None

for m in models:
    print m

