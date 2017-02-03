#!/usr/bin/perl

## Script to auto-close tasks on their due date.

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
     my $sql = "select id from tasks where complete_user = 0 and due_date < now() - interval 1 day";

    $dbh = DBI->connect("DBI:mysql:$dbname;mysql_local_infile=1", "$dbusername", "$dbpassword") or {email_alert("Could not connect to database: $DBI::errstr")};
    my $sth = $dbh->prepare("$sql") or
      {email_alert("Unable to update database with file $file ". $$dbh->errstr)};
    $sth->execute;
    my @data;
    while(my @data = $sth->fetchrow_array)
    {
      # Sending email to $data[0] with subject $data[1]
      if ($data[3] == "1")
      {
        vtext_notify($data[0],$data[1]);
      }
      if ($data[4] == "1")
      {
        vtext_notify($data[2],$data[1]);
      }
    }
    $dbh->disconnect();

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

sub vtext_notify
{
        my $toaddress = shift;
        my $subject = shift;

        $smtp = Net::SMTP->new('localhost');

        $smtp->mail('drivers@catalinacartage.com');
        $smtp->to("$toaddress");

        $smtp->data();
        $smtp->datasend("To: $toaddress\n");
        $smtp->datasend("Subject: $subject\n");
        $smtp->datasend("reply-to: drivers\@catalinacartage.com\n");
        $smtp->datasend("\n");
        $smtp->dataend();

        $smtp->quit;
}
