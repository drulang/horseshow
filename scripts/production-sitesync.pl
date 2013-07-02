print "Starting production site sync\n\n";

$svnCmd = "svn export --force http://pixel/svn/horseshow/website/ /home/dru/website";

print "Executing command: $svnCmd\n";

system($svnCmd);

$scpCmd = "scp -P 21098 -r /home/dru/website/* frithi\@www.modelhorseshow.com:/home/frithi/www";

print("Executing command; $scpCmd\n");

system($scpCmd);

print("Done with Sync\n\n");
