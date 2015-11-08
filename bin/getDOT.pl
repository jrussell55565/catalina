#!/usr/bin/perl

## Script to pull a cvs file from transoft and import it into the db:

use DBI;
use Getopt::Long;

# Command line variables
our ($env, $wwwroot, $ftpusername, $ftppassword, $ftphost, $dbusername, $dbpassword, $dbname);
GetOptions ("file=s" => \$filePath,
            "dbUsername=s" => \$dbusername,
            "dbPassword=s" => \$dbpassword,
            "dbName=s" => \$dbname,
           );

if (  (!$filePath) ||
      (!$dbusername) ||
      (!$dbpassword) ||
      (!$dbname) )
    {
        print "Missing argument.\n";
        exit 1;
    }

if (! -f "$filePath")
{
    print "$filePath does not exist\n";
    exit 1;
}

our $debug = 1;

update_db();

sub update_db
{

	my $file = shift;
        my $sql = "load data local infile \"$filePath\" 
                   REPLACE INTO TABLE csadata FIELDS TERMINATED BY ',' 
                   ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES
                   (\@v_date,state,number,level,placard_inspection,hm_inspection,basic,violation_group,code,description,out_of_service,convicted_diff_charge,violation_weight,time_weight,basic_violation_inspection,unit,last_name,first_name,\@v_dob,license_state,licence_number,co_driver_last_name,co_driver_first_name,co_driver_dob,co_driver_license_state,co_driver_license,veh1_type,veh1_make,veh1_license_state,veh1_license_num,veh1_vin,veh2_type,veh2_make,veh2_license_state,veh2_license_num,veh2_vin)
                   SET id=NULL, date = str_to_date(\@v_date, '%m/%d/%Y'), dob = str_to_date(\@v_dob,'%m/%d/%Y')";

	$dbh = DBI->connect("DBI:mysql:$dbname;mysql_local_infile=1", "$dbusername", "$dbpassword") or {email_alert("Could not connect to database: $DBI::errstr")};
	my $sth = $dbh->prepare("$sql") or
	{email_alert("Unable to update database with file $file ". $$dbh->errstr)};
	$sth->execute;
	$dbh->disconnect();
        unlink($filePath);
        if ($sth->err())
	{
		return 1;
	}else{
		return 0;
	}
}

sub email_alert
{
	my $error = shift;

	my $toaddress = 'jaime@catalinacartage.com';

	$smtp = Net::SMTP->new('localhost');
  
  	$smtp->mail('ftpdaemon@catalinacartage.com');
  	$smtp->to("$toaddress");
  
  	$smtp->data();
  	$smtp->datasend("To: $toaddress\n");
  	$smtp->datasend("Subject: Error connecting to catalina - import\n");
  	$smtp->datasend("CC: $ccaddress\n");
  	$smtp->datasend("\n");
  	$smtp->datasend("$error.\n");
  	$smtp->dataend();
  
  	$smtp->quit;
}

sub debugger
{
	my $message = shift;
	if ($debug == 1)
	{
		print $message."\n";
	}
}
