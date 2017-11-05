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

get_birthdays();

sub get_birthdays
{    
  my $dsn = "DBI:mysql:database=$dbname;host=127.0.0.1;port=3306";
  my $dbh = DBI->connect($dsn, $dbusername, $dbpassword) or {email_alert("Could not connect to database: $DBI::errstr") };         
    my $sql = "SELECT
        username, 
        vtext, 
        email,
        DATE_FORMAT(dob, '%m/%d/%Y') AS dob,
        fname
      FROM users
    WHERE date_format(dob, '%m-%d') = date_format(curdate(), '%m-%d')";

    my $sth = $dbh->prepare("$sql") or
    {email_alert("Unable to run sql: ". $$dbh->errstr)};
    $sth->execute;

    my @row;
    while (@row = $sth->fetchrow_array) {  # retrieve one row
        my $to = $row[1];
        my $subject = "Happy Birthday!";
        my $body = "Happy Birthday, ".$row[4];
        vtext_notify($to, $subject, $body, $row[2]);
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
        my $cc = shift;

        $smtp = Net::SMTP->new('localhost');

        $smtp->mail('drivers@catalinacartage.com');
        $smtp->to("$toaddress");
        $smtp->cc('dispatch@catalinacartage.com');

        $smtp->data();
        $smtp->datasend("To: $toaddress\n");
        $smtp->datasend("Subject: $subject\n");
        $smtp->datasend("CC: $cc\n");
        $smtp->datasend("\n");
        $smtp->datasend("$body\n");
        $smtp->dataend();

        $smtp->quit;
}
