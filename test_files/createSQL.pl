#use Digest::SHA1  qw(sha1 sha1_hex sha1_base64);
use Digest::MD5 qw(md5 md5_hex md5_base64);

print("\n\n Starting Script \n\n");

$file = $ARGV[0];
print("Using file: $file\n\n");

open(FILE, $file);
open(OUT, ">insert.sql") or die;

@lines = <FILE>;

$rangd = 5000000;

$count = 0;
foreach $line (@lines)
{
    @strings = split(/`/,$line);
    
    $lastname = @strings[0];
    $lastname =~ s/'/\\'/g;
    
    $firstname = @strings[1];
    $firstname =~ s/'/\\'/g;    

    $addr1 = @strings[2];
    $addr1 =~ s/,//g;

    $city = @strings[3];
    $state = @strings[4];
    $zip = @strings[5];
    $phone = @strings[6];
    $phone =~ s/ //g;
    $email = @strings[7];
    @comment = @strings[8];

    #generate random number for unique id
    $random_number = int(rand($range));
    
    $data = $random_number.$firstname.$count;
    
   # $digest = sha1_base64($data);
  #  $digest = md5_base64($data);

    $digest = md5_hex($data);

   # print("$lastname   $firstname    $addr1    $city    $state    $zip    $phone    $email \n"); 
    print OUT ("insert into PERSON values \(0,'$digest','$firstname', '$lastname',null,null,null,'$addr1',null,null,'$city','$state','$zip','$email','$phone',null,null,CURDATE(),null,'$comment'\);\n");  

   $count++;
}

close FILE;
close OUT;

print("\n\n The End.\n\n");
