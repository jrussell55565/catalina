#!/usr/bin/perl

## Script to pull a cvs file from transoft and import it into the db:

use Net::FTP;
use Net::SMTP;
use DBI;
use Getopt::Long;

# Command line variables
our ($env, $wwwroot, $ftpusername, $ftppassword, $ftphost, $dbusername, $dbpassword, $dbname);
GetOptions ("env=s" => \$env,
            "wwwroot=s" => \$wwwroot,
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

if ($env eq '')
{
    print "Missing argument for --env.  Choose either Production or QA\n";
    exit 1;
}

if ($env !~ /(Production|QA)/)
{
    print "Invalid argument for --env.  Choose either Production or QA\n";
    exit 1;
}

if ($wwwroot eq '')
{
    print "Missing argument for --wwwroot.  Please specify a full local path\n";
    exit 1;
}

if (! -d "$wwwroot/imports/")
{
    print "$wwwroot/imports/ does not exist\n";
    exit 1;
}

if (! -d $wwwroot)
{
    print "Invalid path for --wwwroot\n";
    exit 1;
}

our @ftproot	= ("CatalinaCartage","FreightServices");
our @list = ();
our $debug = 0;

get_file();

sub get_file
{
	debugger("Logging on to $ftphost");
	my $ftp = Net::FTP->new("$ftphost", Debug => $debug) or {email_alert("Cannot connect to $ftphost: $@")};
	$ftp->login("$ftpusername","$ftppassword") or {email_alert("Cannot login ",$ftp->message)};
	
	foreach my $root (@ftproot)
	{
		debugger("Changing directory to /DriverDispatch/$root/$env/Outbound");
		$ftp->cwd("/DriverDispatch/$root/$env/Outbound") or 
		{email_alert("Cannot change working directory to /DriverDispatch/$root/$env/Outbound ", $ftp->message)};
		my @fileoutput = $ftp->ls("");
		
		# If the fileoutput array is empty then there were no files and we don't need to do the rest.
		if (scalar(@fileoutput) < 1)
		{
			next;
		}

		my %seen = ();
		my @uniq = ();
	
		foreach my $csv (@fileoutput)
		{
			debugger("Proceeding to download $csv");
			$ftp->get("$csv","$wwwroot/imports/$csv") or 
			{email_alert("Unable to download a file /DriverDispatch/$root/$env/Outbound/$csv  $ftp->message")};
			my $retval = update_db($csv);
			if ($retval == 0)
			{
				$ftp->delete($csv);
			}else{
				email_alert("Unable to update the database with file $csv");
				next;
			}

			#Open the file, explode the string into an array and grab the PU/DEL driver info
			open FILE, "<", "$wwwroot/imports/$csv" or {email_alert("Cannot open file for reading ",$!)};
			while (<FILE>)
			{
				if ($_ !~ /^"Hawb Number"/)
				{
					my @splitagent = split(/,/,$_);
					my $hawb = $splitagent[0];
					$hawb =~ s/"//g;
					push(@list,$hawb);
					foreach $item (@list)
					{
    						unless ($seen{$item})
						{
        						# if we get here, we have not seen it before
        						$seen{$item} = 1;
        						push(@uniq, $item);
    						}
					}
				}
			}
			close FILE;
			sleep .5;

   			}
		# Now that we have a unique list of hawb's, Check the hawb table and send notifications if
		# the status is different
		my $proceed;
		foreach $hawb (@uniq)
		{
			$proceed = compare_status($hawb);
			if ($proceed == 1)
			{
				debugger("Proceeding to send text for hawb $hawb");
				vtext($hawb);
			}
		}
	}
}

sub update_db
{
	my $file = shift;
	$dbh = DBI->connect("DBI:mysql:$dbname;mysql_local_infile=1", "$dbusername", "$dbpassword") or {email_alert("Could not connect to database: $DBI::errstr")};
	my $sth = $dbh->prepare("load data local infile \"$wwwroot/imports/$file\" 
				REPLACE INTO TABLE dispatch FIELDS TERMINATED BY ',' 
				ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES 
                ( hawbNumber, messageDate, messageTime, status, pieces, weight, 
                  pallets, serviceCode, isPickup, isDelivery, readyTime, 
                   closeTime, shipperName, shipperCode, shipperAddress1, shipperAddress2, 
                   shipperCity, shipperState, shipperPostalCode, shipperAttention, 
                   shipperReference, shipperContact, shipperPhone, shipperAssembly, 
                   consigneeName, consigneeCode, consigneeAddress1, consigneeAddress2, 
                   consigneeCity, consigneeState, consigneePostalCode, consigneeAttention, 
                   consigneeReference, consigneeContact, consigneePhone, consigneeAssembly, 
                   appRequired, appDate, appTime, appNotes, puAgentName, puAgentCode, 
                   puAgentDriverName, puAgentDriverPhone, puZone, puRemarks, delAgentName, 
                   delAgentCode, delAgentDriverName, delAgentDriverPhone, delZone, delRemarks, 
                   hawbDate, dueDate, dueTime, loadPosition, recordID, podName, podDate, 
                   podTime, puConfirm, Origin, Destination, Control, Via, RevenueTotal, CustomerName, 
                   CustomerPhone, CustomerAddress1, CustomerAddress2, CustomerCity, CustomerState, 
                   CustomerPostalCode, BillToName, BillToPhone, BillToAddress1, BillToAddress2, 
                   BillToCity, BillToState, BillToPostalCode, \@deleted, arrivedShipperTime, 
                   arrivedConsigneeTime, insertDate, \@archived, modifiedDate)
				SET deleted = IF(\@deleted = 'T', 'T', 'F'),
                archived = IF(\@archived = 'T', 'T', 'F'),
                arrivedShipperTime = CURTIME() + 0, arrivedConsigneeTime = CURTIME() + 0,
                insertDate = DATE(NOW()), modifiedDate = CURTIME()"
                ) or 
	{email_alert("Unable to update database with file $file ". $$dbh->errstr)};
	$sth->execute;
        if ($sth->err())
	{
		return 1;
	}else{
		return 0;
	}
	$dbh->disconnect();
}

sub email_alert
{
	my $error = shift;

	my $toaddress = 'helpdesk@catalinacartage.com';

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
    exit 1;
}

sub compare_status
{

	my $hawb = shift;
	my $dispatchStatus;
	my $puAgent;
	my $delAgent;
	my $hawbDate;
	my $dueDate;
	my $hawbCount;
	my $returnHawbDate = 0;
	my $returnDueDate = 0;

        $dbh = DBI->connect("DBI:mysql:$dbname", "$dbusername", "$dbpassword") or 
	{email_alert("Could not connect to database to delete: $DBI::errstr")};

	# First, get the status,drivers,dates from the dispatch table
	$sql = "SELECT status,puAgentDriverName,delAgentDriverName,hawbDate,dueDate FROM dispatch WHERE hawbNumber = \"$hawb\"";
	$sth = $dbh->prepare($sql) or {email_alert("Unable to find status using $hwb from table: dispatch ". $$dbh->errstr)};
        $sth->execute;
        while (@results = $sth->fetchrow())
        {
		$dispatchStatus = $results[0];
		$puAgent = $results[1];
		$delAgent = $results[2];	
		$hawbDate = $results[3];
		$dueDate = $results[4];
	}

	# Now query the hawb table for the hawbNumber.  If it doesn't exist in this table then we've never seen it before
	# and we'll insert it and send a text.

	# Try this.  We need a valid hawbNumber AND both a pudriver and deldriver
	# SELECT hawb.dueDate,hawb.pudriver,hawb.deldriver FROM hawb WHERE hawb.hawbNumber = "29826989";

	$sql = "SELECT count(hawbNumber) as total FROM hawb WHERE hawbNumber = \"$hawb\"";
        $sth = $dbh->prepare($sql) or {email_alert("Unable to find status using $hwb from table: hawb ". $$dbh->errstr)};
        $sth->execute;
	while (@results = $sth->fetchrow())
	{
		$hawbCount = $results[0];	
	}

	if ($hawbCount < 1)
	{
		# We have NOT seen this hawb before.
		insert_hawb($hawb,$dispatchStatus,$puAgent,$delAgent,$hawbDate,$dueDate);
		return 1;
	}else{
		# We have seen this hawb before.  Now just compare the current hawbDate and dueDate with the existing dates.
		$returnHawbDate = find_date("hawbDate");
		$returnDueDate = find_date("dueDate");
		insert_hawb($hawb,$dispatchStatus,$puAgent,$delAgent,$hawbDate,$dueDate);
		return 1 if ($returnHawbDate == 1);
		return 1 if ($returnDueDate == 1);
	}
}

sub vtext
{
	my $hawb = shift;
	$dbh = DBI->connect("DBI:mysql:$dbname", "$dbusername", "$dbpassword") or 
	{email_alert("Could not connect to database to delete: $DBI::errstr")};
	$sql = "SELECT * FROM dispatch WHERE hawbNumber = \"$hawb\"";
	$sth = $dbh->prepare($sql) or {email_alert("Unable to find shipperName using $hwb from database ". $$dbh->errstr)};
	$sth->execute;
	while (($hawbNumber, $messageDate, $messageTime, 
		$status, $pieces, $weight, $pallets, $serviceCode, 
		$isPickup, $isDelivery, $readyTime, $closeTime, 
		$shipperName, $shipperCode, $shipperAddress1, 
		$shipperAddress2, $shipperCity, $shipperState, 
		$shipperPostalCode, $shipperAttention, $shipperReference, 
		$shipperContact, $shipperPhone, $shipperAssembly, 
		$consigneeName, $consigneeCode, $consigneeAddress1, 
		$consigneeAddress2, $consigneeCity, $consigneeState, 
		$consigneePostalCode, $consigneeAttention, $consigneeReference, 
		$consigneeContact, $consigneePhone, $consigneeAssembly, 
		$appRequired, $appDate, $appTime, $appNotes, $puAgentName, 
		$puAgentCode, $puAgentDriverName, $puAgentDriverPhone, $puZone, 
		$puRemarks, $delAgentName, $delAgentCode, $delAgentDriverName, 
		$delAgentDriverPhone, $delZone, $delRemarks, $hawbDate, $dueDate, 
		$dueTime, $loadPosition, $recordID, $podName, $podDate, $podTime, 
		$deleted, $arrivedShipperTime, $arrivedConsigneeTime, $insertDate) = $sth->fetchrow())
	{

		my %destination = ("vtext","vtextupdate","email","emailupdate");

		for my $key ( keys %destination )
		{
        		my $value = $destination{$key};

			# First process PU
      			$sql_vtext_pickup = "SELECT users.$key FROM users WHERE users.driverid = \"$puAgentDriverPhone\" AND users.$value = \"1\"";
			$sth_vtext_pickup = $dbh->prepare($sql_vtext_pickup) or {email_alert("Unable to delete $hwb from database ". $$dbh->errstr)};
			$sth_vtext_pickup->execute;
       			if ($sth_vtext_pickup->err())
			{
				email_alert("Unable to find vtext number for $hwbNumber (pickup) ". $$dbh->errstr);
			}
			while(@row = $sth_vtext_pickup->fetchrow_array)
			{
				vtext_notify(@row,$hawbNumber,"Pickup Alert\n\n$hawbDate\n$shipperName\n$shipperAddress1\n$shipperAddress2\n$shipperCity\n$shipperState\n$shipperPostalCode\n$readyTime\n$closeTime");
				#print "To: @row\n";
				#print "Subject: $hawbNumber\n";
				#print "Pickup Alert\n\nA.$shipperAddress1\nB.$shipperAddress2\nC.$shipperCity\nD.$shipperState\nE.$shipperPostalCode\n\n";
 			}
			
			# Process DEL
      			$sql_vtext_delivery = "SELECT users.$key FROM users WHERE users.driverid = \"$delAgentDriverPhone\" AND users.$value = \"1\"";
			$sth_vtext_delivery = $dbh->prepare($sql_vtext_delivery) or {email_alert("Unable to delete $hwb from database ". $$dbh->errstr)};
			$sth_vtext_delivery->execute;
       			if ($sth_vtext_delivery->err())
			{
					email_alert("Unable to find vtext number for $hwbNumber (delivery) ". $$dbh->errstr);
			}	
			while(@row = $sth_vtext_delivery->fetchrow_array)
			{
				vtext_notify(@row,$hawb,"Delivery Alert\n\n$dueDate\n$consigneeName\n$consigneeAddress1\n$consigneeAddress2\n$consigneeCity\n$consigneeState\n$consigneePostalCode\n$dueTime");
				#print "To: @row\n";
				#print "Subject: $hawbNumber\n";
				#print "Delivery Alert\n\nA.$consigneeAddress1\nB.$consigneeAddress2\nC.$consigneeCity\nD.$consigneeState\nE.$consigneePostalCode\n\n";
 			}
		}
   	}
	$dbh->disconnect();
}

sub vtext_notify
{
        my $toaddress = shift;
        my $subject = shift;
	my $body = shift;

	# Get vtext message text from file
	open FILE, "<", "/$wwwroot/vtext_message.txt" or {email_alert("Unable to open vtext_message.txt for reading")};
	while(<FILE>)
	{
		$body .= $_;
	}

        $smtp = Net::SMTP->new('localhost');

        $smtp->mail('drivers@catalinacartage.com');
        $smtp->to("$toaddress");

        $smtp->data();
        $smtp->datasend("To: $toaddress\n");
        $smtp->datasend("Subject: $subject\n");
	$smtp->datasend("reply-to: drivers\@catalinacartage.com\n");
        $smtp->datasend("\n");
        $smtp->datasend("$body.\n");
        $smtp->dataend();

        $smtp->quit;
}

sub find_date
{
	my $dateType = shift;

	my $sql = "SELECT hawb.hawbNumber,hawb.hawbDate,hawb.dueDate,hawb.pudriver,hawb.deldriver,hawb.date FROM hawb LEFT JOIN dispatch ON hawb.hawbNumber = dispatch.hawbNumber WHERE dispatch.hawbNumber = \"$hawb\" AND (hawb.dueDate = dispatch.dueDate AND hawb.hawbDate = dispatch.hawbDate AND hawb.pudriver = dispatch.puAgentDriverName AND hawb.deldriver = dispatch.delAgentDriverName) ORDER BY hawb.date DESC LIMIT 1";

         my $sth = $dbh->prepare($sql) or {email_alert("Unable to insert new hawb into table: hawb ". $$dbh->errstr)};
         $sth->execute;

	#debugger($sql);

         # If the results == 0 then the dates did NOT match

         my $foundHawbDate = 0;
	while ($sth->fetch()) 
	{
   		$foundHawbDate = 1;
	}
         if ($foundHawbDate != 0)
         {
                # The hawbDate did not change so we would NOT need to send a text.
		return 0;
         }else{
                # The hawbDate did change and we would send a text.
                return 1;
         }
}

sub insert_hawb
{
	my $hawb = shift;
	my $dispatchStatus = shift;
	my $puAgent = shift;
	my $delAgent = shift;
	my $hawbDate = shift;
	my $dueDate = shift;

	my $sql = "INSERT INTO hawb (hawbNumber,status,pudriver,deldriver,hawbDate,dueDate) 
		   VALUES (\"$hawb\",\"$dispatchStatus\",\"$puAgent\",\"$delAgent\",\"$hawbDate\",\"$dueDate\")";
       	my $sth = $dbh->prepare($sql) or {email_alert("Unable to insert new hawb into table: hawb ". $$dbh->errstr)};
       	$sth->execute;
	#debugger($sql);
}

sub debugger
{
	my $message = shift;
	if ($debug == 1)
	{
		print $message."\n";
	}
}
