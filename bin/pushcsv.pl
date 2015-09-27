#!/usr/bin/perl

## Script to pull a cvs file from transoft and import it into the db:

use Net::FTP;
use Net::SMTP;
use File::Find;
use Switch;
use Getopt::Long;

# Command line variables
our ($wwwroot, $ftpusername, $ftppassword, $ftphost, $dbusername, $dbpassword, $dbname);
GetOptions ( "wwwroot=s" => \$wwwroot,
            "ftpUsername=s" => \$ftpusername,
            "ftpPassword=s" => \$ftppassword,
            "ftpHost=s" => \$ftphost,
            "dbUsername=s" => \$dbusername,
            "dbPassword=s" => \$dbpassword,
            "dbName=s" => \$dbname,
           );

if ( (!$ftpusername) ||
      (!$ftppassword) ||
      (!$ftphost) ||
      (!$dbusername) ||
      (!$dbpassword) ||
      (!$dbname) )
    {
        print "Missing argument.\n";
        exit 1;
    }

if ($wwwroot eq '')
{
    print "Missing argument for --wwwroot.  Please specify a full local path\n";
    exit 1;
}

if (! -d $wwwroot)
{
    print "Invalid path for --wwwroot\n";
    exit 1;
}

if (! -d "$wwwroot/exports/")
{
    print "$wwwroot/exports/ does not exist\n";
    exit 1;
}

our $exportdir  = "$wwwroot/exports";
our $debug = 0;

find(\&wanted, $exportdir);

sub wanted
{
    if ($_ =~ /^\./)
    {
        return 0;
    }
    if ($_ =~ /^\+/)
    {
        return 0;
    }
    # Based on the file name, what dir is it going into?
    my $exportdest = $_;
    my $fileType;
    my $envOverride;
    $exportdest =~ /(accessorial|status)\+(\w+\d?)\+\d+/;
    $fileType = $1;
    $exportdest = $2;

    switch ($exportdest)
    {
        case "CATTUS1"     { $exportdest = "CatalinaCartage" }
        case "CATPHX1"     { $exportdest = "CatalinaCartage" }
        case "JNAIRTUS1"{ $exportdest = "CatalinaCartage" }
        case "JNAIRPHX1"{ $exportdest = "CatalinaCartage" }
        case "CATOKC1"    { $exportdest = "CatalinaCartage" }
        case "CATLAX1"    { $exportdest = "CatalinaCartage" }
        case "CATTUS2"     { $exportdest = "JNAirfreight" }
        case "CATPHX2"     { $exportdest = "JNAirfreight" }
        case "JNAIRTUS2"{ $exportdest = "JNAirfreight" }
        case "JNAIRPHX2"{ $exportdest = "JNAirfreight" }
        case "JNAIROKC2"{ $exportdest = "JNAirfreight" }
        case "JNAIRLAX2"{ $exportdest = "JNAirfreight" }
        else        { $exportdest = "CatalinaCartage" }
    }
        # Open the file and look at the hwb to determine if its prod or qa
        open FD, "< $_" or die "Unable to open file ($_) for reading: $!\n";
        foreach my $file (<FD>)
        {
            if ($file =~ /^"?QA.+/) 
            {
                $envOverride = "QA"; 
                last;
            }else{
                $envOverride = "Production";
            }
        }
    push_file($_,$fileType,$exportdest,$exportdir,$envOverride);
}

sub push_file
{
    my $filename = shift;
    my $fileType = shift;
    my $exportdest = shift;
    my $exportdir = shift;
        my $env = shift;
    my $ftpPath;

    if ($fileType =~ /accessorial/)
    {
        $ftpPath = "/DriverDispatch/CatalinaCartage/$env/Inbound/Accessorials";
    }
    if ($fileType =~ /status/)
    {
        $ftpPath = "/DriverDispatch/$exportdest/$env/Inbound";
    }

    my $ftp = Net::FTP->new("$ftphost", Debug => $debug) or {email_alert("Cannot connect to $ftphost: $@")};
    $ftp->login("$ftpusername","$ftppassword") or {email_alert("Cannot login ",$ftp->message)};
    $ftp->cwd("$ftpPath") or {email_alert("Cannot change working directory to $ftpPath", $ftp->message)};

    $ftp->put("$filename") or {email_alert("Unable to upload a file $filename ", $ftp->message)};
        $ftp->quit;

    system("/bin/mv $filename $wwwroot/export_backups");
}

sub email_alert
{
    my $error = shift;

    my $toaddress = 'mjtice@gmail.com';
    my $ccaddress = 'jaime@catalinacartage.com';

    $smtp = Net::SMTP->new('localhost');
  
      $smtp->mail('ftpdaemon@catalinacartage.net');
      $smtp->to("$toaddress");
    $smtp->cc("$ccaddress");
  
      $smtp->data();
      $smtp->datasend("To: $toaddress\n");
      $smtp->datasend("Subject: Error connecting to catalina - export\n");
      $smtp->datasend("CC: $ccaddress\n");
      $smtp->datasend("\n");
      $smtp->datasend("$error.\n");
      $smtp->dataend();
  
      $smtp->quit;

    exit;
}
