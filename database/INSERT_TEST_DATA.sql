/*****************************************************

    /* insert DIVISION_TYPE */
        insert ignore into frithi_HORSESHOW.DIVISION_TYPE (ID, NAME, ADD_DATE) values (1, 'CLASS', NOW());
        insert ignore into frithi_HORSESHOW.DIVISION_TYPE (ID, NAME, ADD_DATE) values (2, 'SECTION', NOW());
        insert ignore into frithi_HORSESHOW.DIVISION_TYPE (ID, NAME, ADD_DATE) values (3, 'DIVISION', NOW());
/*Class*/
insert into frithi_HORSESHOW.DIVISION(ID, SHOW_ID, PRIMARY_DIVISION_ID, DIVISION_TYPE_ID, NAME, ADD_DATE) 
 values (1, 1, null, 1, 'ORIGINAL FINISH REGULAR RUN DIVISION', NOW());

/* Section */
insert into frithi_HORSESHOW.DIVISION(ID, SHOW_ID, PRIMARY_DIVISION_ID, DIVISION_TYPE_ID, NAME, ADD_DATE)
 values (2, 1, 1, 2, 'Draft Breed & Type Section', NOW());

/* Division */
insert into frithi_HORSESHOW.DIVISION(ID, SHOW_ID, PRIMARY_DIVISION_ID, DIVISION_TYPE_ID, NAME, ADD_DATE)
 values (3, 1, 2, 3, 'BRITISH BREEDS', NOW());

/*Section*/
insert into frithi_HORSESHOW.DIVISION(ID, SHOW_ID, PRIMARY_DIVISION_ID, DIVISION_TYPE_ID, NAME, ADD_DATE)
 values (4, 1, 1, 2, 'Pony Breed & Type Section', NOW());


