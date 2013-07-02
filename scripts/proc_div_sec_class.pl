$file = $ARGV[0];

if(!(-e $file))
{
	print "file does not exist\n";
	exit(1);
}

open(FILE, $file);

@lines = <FILE>;

foreach $line (@lines)
{
	$line =~ s/\n//g;

	@fields = split('`', $line);

	$type = @fields[0];
	if($type eq "d")
	{
		$typeid = 3;
	}
	elsif($type eq "s")
	{
		$typeid = 2;
	}
	elsif($type eq "c")
	{
		$typeid = 1;
	}
	else
	{
		$typeid = "WRONG";
	}


	$parentid = @fields[1];
	$id = @fields[2];
	$name = @fields[3];
	$showid = 1;

	$query = "insert into frithi_HORSESHOW.DIVISION(ID, SHOW_ID,DIVISION_TYPE_ID,PRIMARY_DIVISION_ID,NAME,ADD_DATE) values ($id, $showid, $typeid, $parentid, '$name', NOW());";

	print $query."\n";
}