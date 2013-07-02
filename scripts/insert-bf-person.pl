sub trim($);

$file = $ARGV[0];
open(FILE, "$file") or die("Unable to open file\n");

@lines = <FILE>;

$queryB = "insert into frithi_HORSESHOW.PERSON (ID, SHOW_EXHIBITOR_ID, FIRST_NAME, LAST_NAME, ADDRESS_LINE_1, CITY, STATE, ZIP, PHONE_NBR_CELL, EMAIL, COMMENT, ADD_DATE, TIMESTAMP, PERSON_KEY) values ";

$personCount = 1;

foreach $line (@lines)
{
  $line = trim($line);
  
  @fields = split(/`/,$line);

  $show_id = trim(@fields[0]);
  $last_name = trim(@fields[1]);
  $first_name = trim(@fields[2]);
  $addr1 = trim(@fields[3]);
  $city = trim(@fields[4]);
  $state = trim(@fields[5]);
  $zip = trim(@fields[6]);
  $phone = trim(@fields[7]);
  $phone =~ s/ //g;
  $email = trim(@fields[8]);
  $comment = trim(@fields[9]);
  $comment =~ s/"//g;
  
  if(length($comment) < 2)
  {
    $comment = "''";
  }

  $query = $queryB."($personCount, '$show_id', $first_name, $last_name, $addr1, $city, $state, $zip, $phone, $email, $comment, UTC_TIMESTAMP(), UTC_TIMESTAMP(), null);";
  
  $linkQuery = "insert into frithi_HORSESHOW.SHOW_REGISTRATION_LINK values (0, 1, $personCount, 1, '$show_id', null, UTC_TIMESTAMP(), UTC_TIMESTAMP());";

  print("$query\n");
  print("$linkQuery\n");

  $personCount++;
}


sub trim($)
{
	my $string = shift;
	$string =~ s/^\s+//;
	$string =~ s/\s+$//;
	return $string;
}
