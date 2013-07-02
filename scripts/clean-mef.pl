#!/usr/bin/perl
sub trim($);

print "Starting Script\n\n";

$fileName = @ARGV[0];
$stageFileName = $fileName.".stage";

print "Using File: $fileName\n";
print "Using Stage File: $stageFileName\n";

$stageCommand = "catdoc $fileName > $stageFileName";

print("Executing Stage Command: $stageCommand\n");
system($stageCommand);

#TODO: Add EC for sys command

open(FILE, "$stageFileName") or die "Unable to open file\n\n";

@lines = <FILE>;

$showExNumber = "";
$showExInitials = "";

foreach $line (@lines)
{
  $line = trim($line);
  
  if($line ne "")
  {

    if($line =~ m/EXHIBITOR/g)
    {
#	$index = index($line, "::");
#        $index = int($index) + 2;
#        $showExName = substr($line, $index);
#	$showExName = trim($showExName);
#        print("Exhibitor Name: $showExName\n");
    }    
    elsif($line =~ m/NUMBER/g)
    {
       $index = index($line, "::");
       $index = int($index) + 2;
       $showExNumber = substr($line, $index); 
       $showExNumber = trim($showExNumber);
#       print("Exhibitor Name: $showExNumber\n");
    }
    elsif($line =~ m/INITIALS/g)
    {
      $index = index($line, "::");
      $index = int($index) + 2;
      $showExInitials = substr($line, $index);
      $showExInitials = trim($showExInitials);
#      print("Exhibitor Initials: $showExInitials\n");
    }
    elsif($line =~ m/\d\d\d\d\d/g)
    {
      @fields = split(/\t/, $line);

      $showHorseNumber = trim(@fields[0]);
      $showHorseName = trim(@fields[1]);
      $showHorseName =~ s/'/\'/g;
      $showBreed = trim(@fields[2]);
      $showGender = trim(@fields[3]);
      $showDivision = trim(@fields[4]);

      print($showHorseName."\n");
    }
    else
    {
      #do nothing
    }

    $query = "insert into HORSESHOW.PERSON_MODEL values (0, $showExNumber, $showExInitials, $showHorseName)";

    print "$query\n";
  }
}
    print("Moving $fileName to success direcotry\n");
    system("mv", $fileName, "../successful_completed_forms/");

    print("Deleting Stage File\n");
    system("rm", $stageFileName);


# Perl trim function to remove whitespace from the start and end of the string
sub trim($)
{
	my $string = shift;
	$string =~ s/^\s+//;
	$string =~ s/\s+$//;
	return $string;
}
