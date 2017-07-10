#!/usr/bin/perl

use DBI;
use Net::SMTP;
use Getopt::Long;

# Command line variables
our ($dbusername, $dbpassword, $dbname);
GetOptions ("dbUsername=s" => \$dbusername,
            "dbPassword=s" => \$dbpassword,
            "dbName=s" => \$dbname,
           );

if (  (!$dbusername) ||
      (!$dbpassword) ||
      (!$dbname) )
    {
        print "Missing argument.\n";
        exit 1;
    }

our $debug = 0;

process_tasks();

sub process_tasks
{    
  my $dsn = "DBI:mysql:database=$dbname;host=127.0.0.1;port=3306";
  my $dbh = DBI->connect($dsn, $dbusername, $dbpassword) or {email_alert("Could not connect to database: $DBI::errstr") };         
  my $sql = "SELECT id
,status
  ,real_name
  ,email
  ,username
  ,employee_id
  ,date_format(driver_license_exp,'%m/%d/%Y') as driver_license_exp
  ,date_format(med_card_exp,'%m/%d/%Y') as med_card_exp
  ,date_format(tsa_date_exp,'%m/%d/%Y') as tsa_date_exp
  ,date_format(tsa_date_change_exp,'%m/%d/%Y') as tsa_date_change_exp
  ,tsa_issue
  ,CASE
    when driver_license_exp = current_date + INTERVAL 30 DAY then 30
    when driver_license_exp = current_date + INTERVAL 15 DAY then 15
    when driver_license_exp = current_date + INTERVAL 10 DAY then 10
    when driver_license_exp = current_date + INTERVAL 5 DAY then 5
    when driver_license_exp = current_date + INTERVAL 1 DAY then 1
    when driver_license_exp < current_date then -1
  END as dl_tte,
  CASE
    when MED_CARD_EXP = current_date + INTERVAL 30 DAY then 30
    when MED_CARD_EXP = current_date + INTERVAL 15 DAY then 15
    when MED_CARD_EXP = current_date + INTERVAL 10 DAY then 10
    when MED_CARD_EXP = current_date + INTERVAL 5 DAY then 5
    when MED_CARD_EXP = current_date + INTERVAL 1 DAY then 1
    when MED_CARD_EXP < current_date then -1
  END as med_tte,
  CASE
    when tsa_date_exp = current_date + INTERVAL 30 DAY then 30
    when tsa_date_exp = current_date + INTERVAL 15 DAY then 15
    when tsa_date_exp = current_date + INTERVAL 10 DAY then 10
    when tsa_date_exp = current_date + INTERVAL 5 DAY then 5
    when tsa_date_exp = current_date + INTERVAL 1 DAY then 1
    when tsa_date_exp < current_date then -1
  END as tsa_tte,
  CASE
    when tsa_date_change_exp = current_date + INTERVAL 30 DAY then 30
    when tsa_date_change_exp = current_date + INTERVAL 15 DAY then 15
    when tsa_date_change_exp = current_date + INTERVAL 10 DAY then 10
    when tsa_date_change_exp = current_date + INTERVAL 5 DAY then 5
    when tsa_date_change_exp = current_date + INTERVAL 1 DAY then 1
    when tsa_date_change_exp < current_date then -1
  END as tsa_change_tte
,vtext
FROM (
       SELECT
         id,
         status,
         concat_ws(' ', fname, lname) AS real_name,
         email,
         USERNAME,
         EMPLOYEE_ID,
         DRIVER_LICENSE_EXP,
         NULL                         AS MED_CARD_EXP,
         NULL                         AS tsa_issue,
         NULL                         AS tsa_date_exp,
         NULL                         AS tsa_date_change_exp,
         vtext
       FROM users
       UNION
       SELECT
         id,
         status,
         concat_ws(' ', fname, lname) AS real_name,
         email,
         USERNAME,
         EMPLOYEE_ID,
         NULL                         AS DRIVER_LICENSE_EXP,
         MED_CARD_EXP,
         NULL                         AS tsa_issue,
         NULL                         AS tsa_date_exp,
         NULL                         AS tsa_date_change_exp,
         vtext
       FROM users
       UNION
       SELECT
         id,
         status,
         concat_ws(' ', fname, lname) AS real_name,
         email,
         USERNAME,
         EMPLOYEE_ID,
         NULL                         AS DRIVER_LICENSE_EXP,
         NULL                         AS MED_CARD_EXP,
         CASE WHEN tsa_date_exp IS NOT NULL
           THEN 1 END                 AS tsa_issue,
         tsa_date_exp,
         tsa_date_change_exp,
         vtext
       FROM users) root
WHERE root.status = 'Active'
      AND (driver_license_exp = current_date + INTERVAL 30 DAY OR
           driver_license_exp = current_date + INTERVAL 15 DAY OR
           driver_license_exp = current_date + INTERVAL 10 DAY OR
           driver_license_exp = current_date + INTERVAL 5 DAY OR
           driver_license_exp = current_date + INTERVAL 1 DAY OR
           driver_license_exp < current_date
           OR MED_CARD_EXP = current_date + INTERVAL 30 DAY OR
           MED_CARD_EXP = current_date + INTERVAL 15 DAY OR
           MED_CARD_EXP = current_date + INTERVAL 10 DAY OR
           MED_CARD_EXP = current_date + INTERVAL 5 DAY OR
           MED_CARD_EXP = current_date + INTERVAL 1 DAY OR
           MED_CARD_EXP < current_date OR
           (tsa_issue = 1 AND
            (tsa_date_exp = current_date + INTERVAL 30 DAY OR
             tsa_date_exp = current_date + INTERVAL 15 DAY OR
             tsa_date_exp = current_date + INTERVAL 10 DAY OR
             tsa_date_exp = current_date + INTERVAL 5 DAY OR
             tsa_date_exp = current_date + INTERVAL 1 DAY OR
             tsa_date_change_exp = current_date + INTERVAL 30 DAY OR
              tsa_date_change_exp = current_date + INTERVAL 15 DAY OR
              tsa_date_change_exp = current_date + INTERVAL 10 DAY OR
              tsa_date_change_exp = current_date + INTERVAL 5 DAY OR
              tsa_date_change_exp = current_date + INTERVAL 1 DAY OR
              tsa_date_exp < current_date OR tsa_date_change_exp < current_date)))";
# my $sql = "call expiration_reminder($_)";
my $sth = $dbh->prepare("$sql") or
  {email_alert("Unable to process expiration_reminder ". $$dbh->errstr)};
$sth->execute;
my @days = (30, 15, 10, 5, -1);
my $verbiage;
while(my @data = $sth->fetchrow_array)
{
  foreach my $date_lookup (@days) { 
    # print "looking at record " . $data[0] . " on date " . $date_lookup . "\n";          
    my $real_name = $data[2];
    my $email = $data[3];
    my $drivers_license_exp = $data[6];
    my $med_exp = $data[7];
    my $tsa_exp = $data[9];
    my $tsa_change_exp = $data[10];

    my $dl_tte = $data[11];
    my $med_tte = $data[12];
    my $tsa_tte = $data[13];
    my $tsa_change_tte = $data[14];

    my $vtext = $data[15];

    my $subject;

    # Set the verbiage    
    if ($date_lookup == -1) {            
      $verbiage = " expired prior to today.\n";
    }else{      
      $verbiage = " expires in ". $date_lookup . " days (" . $drivers_license_exp . ").\n";
    }
    
    # Process drivers license.
    if (($drivers_license_exp != '') && ($dl_tte == $date_lookup)) {
      $subject = "Drivers license for " . $real_name . $verbiage;
      $body = "Expiration data on file. $drivers_license_exp";
      vtext_notify($email,$subject,$body);    
      vtext_notify($vtext,$subject,$body);    
    }

    # Process med card.
    if (($med_exp != '') && ($med_tte == $date_lookup)) {
      $subject = "Medical card for " . $real_name . $verbiage;
      $body = "Expiration data on file. $med_exp";
      vtext_notify($email,$subject,$body);    
      vtext_notify($vtext,$subject,$body);    
    }

    # Process tsa
    if (($tsa_exp != '') && ($tsa_exp == $date_lookup)) {
      $subject = "TSA for " . $real_name . $verbiage;
      $body = "Expiration data on file. $tsa_exp";
      vtext_notify($email,$subject,$body);    
      vtext_notify($vtext,$subject,$body);    
    }

    # Process tsa_change
    if (($tsa_change_exp != '') && ($tsa_change_exp == $date_lookup)) {
      $subject = "TSA change for " . $real_name . $verbiage;
      $body = "Expiration data on file. $tsa_change_exp";
      vtext_notify($email,$subject,$body);    
      vtext_notify($vtext,$subject,$body);    
    }
  }      
}
$dbh->disconnect();
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

sub vtext_notify
{
        my $toaddress = shift;
        my $subject = shift;
        my $body = shift;

        $smtp = Net::SMTP->new('localhost');

        $smtp->mail('drivers@catalinacartage.com');
        $smtp->to("$toaddress");
        $smtp->cc('dispatch@catalinacartage.com');

        $smtp->data();
        $smtp->datasend("To: $toaddress\n");
        $smtp->datasend("Subject: $subject\n");
        $smtp->datasend("\n");
        $smtp->datasend("$body\n");
        $smtp->dataend();

        $smtp->quit;
}
