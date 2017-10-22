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

get_anniversaries();

sub get_anniversaries
{    
  my $dsn = "DBI:mysql:database=$dbname;host=127.0.0.1;port=3306";
  my $dbh = DBI->connect($dsn, $dbusername, $dbpassword) or {email_alert("Could not connect to database: $DBI::errstr") };         
    my $sql = 'select a.username, a.vtext, a.email, a.start_dt, a.anniversary,
    CASE
    WHEN a.anniversary = 90 THEN "Congratulations, you have made is past your 90 Day Evaluation Period!"
    WHEN a.anniversary = 1 THEN concat(concat("Congratulations, It is your Yearly work anniversary!  You have been here ",a.anniversary)," year.  Thank you for being part of our team.")
    ELSE concat(concat("Congratulations, It is your Yearly work anniversary!  You have been here ",a.anniversary)," years.  Thank you for being part of our team.")
    END as subject
    from (
    SELECT
        username,
        vtext,
        email,
        start_dt,
        CASE
        WHEN start_dt = curdate() - INTERVAL 90 DAY
        THEN 90
        WHEN start_dt = curdate() - INTERVAL 1 YEAR
        THEN 1
        WHEN start_dt = curdate() - INTERVAL 2 YEAR
        THEN 2
        WHEN start_dt = curdate() - INTERVAL 3 YEAR
        THEN 3
        WHEN start_dt = curdate() - INTERVAL 4 YEAR
        THEN 4
        WHEN start_dt = curdate() - INTERVAL 5 YEAR
        THEN 5
        WHEN start_dt = curdate() - INTERVAL 6 YEAR
        THEN 6
        WHEN start_dt = curdate() - INTERVAL 7 YEAR
        THEN 7
        WHEN start_dt = curdate() - INTERVAL 8 YEAR
        THEN 8
        WHEN start_dt = curdate() - INTERVAL 9 YEAR
        THEN 9
        WHEN start_dt = curdate() - INTERVAL 10 YEAR
        THEN 10
        WHEN start_dt = curdate() - INTERVAL 11 YEAR
        THEN 11
        WHEN start_dt = curdate() - INTERVAL 12 YEAR
        THEN 12
        WHEN start_dt = curdate() - INTERVAL 13 YEAR
        THEN 13
        WHEN start_dt = curdate() - INTERVAL 14 YEAR
        THEN 14
        WHEN start_dt = curdate() - INTERVAL 15 YEAR
        THEN 15
        END AS anniversary
    FROM catalina_test.users
    WHERE status = "Active"
    ) a WHERE a.anniversary IS NOT NULL';

    my $sth = $dbh->prepare("$sql") or
    {email_alert("Unable to run sql: ". $$dbh->errstr)};
    $sth->execute;

    my @row;
    while (@row = $sth->fetchrow_array) {  # retrieve one row
        my $to = $row[1];
        my $subject = "Congratulations!";
        my $body = $row[5];
        vtext_notify($to, $subject, $body);
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
