
sub trim($);

print "starting script";

open(FILE, "quotes");

@lines = <FILE>;

foreach $line (@lines)
{
	@records = split(/`/, $line);


	$quote =  trim(@records[0]);
	$author = trim(@records[1]);

	$query = "insert ignore into HORSESHOW_WEBSITE.CONTENT values (0, 1, 4, null, null, '$quote', '$author', UTC_TIMESTAMP(), null, CURRENT_TIMESTAMP);\n";

	print $query;
}


sub trim($)
{
	my $string = shift;
	$string =~ s/^\s+//;
	$string =~ s/\s+$//;
	return $string;
}
