sub trim($);

print("\n\nStartin Script\n\n");
$file = $ARGV[0];

open(FILE, $file) or die "Unable to open file\n";
open(SCRIPT, ">breedScript.sql") or die "Unable to open file\n";

@lines = <FILE>;

$deleteQuery = "delete from horseshow.BREED;\n";
print SCRIPT ($deleteQuery);

$index = 1;
foreach $line (@lines)
{    
 #   $dashIndex = index($line, '–');
    
 #   if($dashIndex <= 1)
 #   {
 #     $dashIndex = index($line, '-');
 #   }

 #    $breed = substr($line, 0, $dashIndex);
 #    $breed = trim($breed);

    @records = split(/`/, $line);
    $breed = trim(@records[0]);
    $desc = trim(@records[1]);

    $query = "insert into horseshow.BREED values ($index,null,\'$breed\',\'$desc\',NOW());\n";

    print($query);
    print SCRIPT ($query);

   $index++;
}


close FILE;
print("\n\nThe End. \n\n");


# Perl trim function to remove whitespace from the start and end of the string
sub trim($)
{
	my $string = shift;
	$string =~ s/^\s+//;
	$string =~ s/\s+$//;
	return $string;
}
