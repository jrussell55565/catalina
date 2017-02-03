function validateRegistration()
{
  var fname = document.forms["register"]["fname"].value;
  var lname = document.forms["register"]["lname"].value;
  var username = document.forms["register"]["username"].value;
  var email = document.forms["register"]["email"].value;
  var ret_val = 0;

  if (fname == null || fname == "") {
      document.getElementById("fname_error").innerHTML = "A first name is required";
      document.getElementById("fname_error").style.display = 'block';
      ret_val = 1;
  }else{
      document.getElementById("fname_error").innerHTML = "";
      document.getElementById("fname_error").style.display = 'none';
      ret_val = 0;
  }
  if (lname == null || lname == "") {
      document.getElementById("lname_error").innerHTML = "A last name is required";
      document.getElementById("lname_error").style.display = 'block';
      ret_val = 1;
  }else{
      document.getElementById("lname_error").innerHTML = "";
      document.getElementById("lname_error").style.display = 'none';
      ret_val = 0;
  }
  if (username == null || username == "") {
      document.getElementById("username_error").innerHTML = "A username is required";
      document.getElementById("username_error").style.display = 'block';
      ret_val = 1;
  }else{
      document.getElementById("username_error").innerHTML = "";
      document.getElementById("username_error").style.display = 'none';
      ret_val = 0;
  }
  if (email == null || email == "") {
      document.getElementById("email_error").innerHTML = "An email is required";
      document.getElementById("email_error").style.display = 'block';
      ret_val = 1;
  }else{
      document.getElementById("email_error").innerHTML = "";
      document.getElementById("email_error").style.display = 'none';
      ret_val = 0;

      var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      if (! regex.test(email)) {
          document.getElementById("email_error").innerHTML = "That doesn't appear to be a valid email address";
          document.getElementById("email_error").style.display = 'block';
          ret_val = 1;
      }else{
          document.getElementById("email_error").innerHTML = "";
          document.getElementById("email_error").style.display = 'none';
          ret_val = 0;
      }
  }
  if (ret_val == 0) { return true; }else{ return false; }
}

function validateLogin()
{
  var trailer=document.forms["Driverlogin"]["LoadPosition"].value
  var truck=document.forms["Driverlogin"]["TruckID"].value
  var admin=document.forms["Driverlogin"]["AdminLogin"].checked
  var odo=document.forms["Driverlogin"]["truck_odometer"].value;

  if (admin === false)
  {
    if (truck==null || truck=="")
    {
      document.getElementById("truck_error").innerHTML = "A truck number is required";
      document.getElementById("truck_error").style.display = 'block';
      return false;
    }
    if (odo == truck)
    {
      document.getElementById("odo_error1").innerHTML = "Truck and odometers values must be different";
      document.getElementById("odo_error1").style.display = 'block';
      return false;
    }
    if (truck != null && odo == "")
    {
      document.getElementById("odo_error1").innerHTML = "You must Enter a Odometer";
      document.getElementById("odo_error1").style.display = 'block';
      return false;
    }
    if (odo == trailer)
    {
      document.getElementById("odo_error2").innerHTML = "Trailer and odometers values must be different";
      document.getElementById("odo_error2").style.display = 'block';
      return false;
    }
    if ((trailer==null || trailer=="") && (admin!=true))
    {
      if(truck>1 && truck<3999)
      {
        document.forms["Driverlogin"]["LoadPosition"].value = truck;
        return true;
      }
      if (truck>=4000 && truck<7999)
      {
          document.getElementById("trailer_error").innerHTML = "A trailer number is required";
          document.getElementById("trailer_error").style.display = 'block';
          return false;
      }
    }
  }
}

function validateForm()
{
    var x=document.forms["Driverlogin"]["LoadPosition"].value
    var z=document.forms["Driverlogin"]["TruckID"].value
    var y=document.forms["Driverlogin"]["AdminLogin"].checked
        var odo=document.forms["Driverlogin"]["truck_odometer"].value;

    if ((z==null || z=="") && (y!=true))
    {
        var r=alert("A truck number is required");
            if (r!=true)
            {
                return false;
            }
    }
        if (odo==null || odo=="")
        {
            var truckNumber = getCookie('truck_number');
            z = parseInt(z);
            if (getCookie('truck_odometer'))
            {
                // We have a cookie for truck_odometer
                // Let's compare the submitted truck number
                // with our cookie.
                if (z == truckNumber)
                {
                    // Our truck numbers match and we have a truck_odometer cookie
                    null;
                }else{
                    alert('Please note the truck odometer for truck '+z);
                    return false;
                }
            }else{
                alert('Please note the truck odometer');
                return false;
            }
        }else{
            // Set a new cookie to expire at midnight.
            var date = new Date();
            var midnight = new Date(date.getFullYear(), date.getMonth(), date.getDate(), 23, 59, 59);
            document.cookie="truck_odometer="+odo+"; expires="+midnight+";";
            document.cookie="truck_number="+z+"; expires="+midnight+";";
        }
    if ((x==null || x=="") && (y!=true))
    {
        if(z>1 && z<3999)
        {
            document.forms["Driverlogin"]["LoadPosition"].value = z;
                        return true;
        }
        if (z>=4000 && z<7999)
        {
            alert("A trailer number is required.");
            return false;
        }
        var r=confirm("Are you sure you wish to proceed without entering a trailer number?");
            if (r!=true)
            {
                return false;
            }
    }
}
function DELcheck(elem, helperMsg){
    var x=document.forms["delconfirmed"]["podName"].value.length
    var y=document.forms["delconfirmed"]["bx_localtime"].value
    var z=document.forms["delconfirmed"]["podDate"].value

    var validformat=/^(0?[1-9]|1[012])\/(0?[1-9]|[12][0-9]|3[01])\/20\d\d$/
        var validtimeformat=/^((0?[1-9])|(1[0-9])|(2[0-4]))(((:)[0-5]+[0-9]+))$/
    if (!validformat.test(z)){
        alert("POD Date format should be dd/mm/yyyy");
        document.forms["delconfirmed"]["podDate"].focus();
        return false
    }
    if (!validtimeformat.test(y)){
                alert("POD Time format should be hh:mm");
                document.forms["delconfirmed"]["bx_localtime"].focus();
                return false
        }
    if(x == 0){
                alert("You Must Enter POD Name");
        document.forms["delconfirmed"]["podName"].focus();
                return false;
        }
        return true;
}
function waitTimePU()
{
    var x=document.forms["puconfirmed"]["ShipperDuration"].value
    if (x > 30)
    {
        document.forms["puconfirmed"]["ck_waittime"].checked = true;
    }
}
function waitTimeDEL()
{
    var x=document.forms["delconfirmed"]["ShipperDuration"].value
    if (x > 30)
    {
        document.forms["delconfirmed"]["ck_waittime"].checked = true;
    }
}
function clockCheck()
{
    var x=prompt("please enter HWB #, to verify reason for clocking in or out via the web");
    var validatehawb=/^\w+|\d+$/
    if (!validatehawb.test(x)){
        alert("Hawb may only be numbers or letters and not emtpy");
        return false;
    }
    document.getElementsByName('hdn_clock')[0].value = x;
    return true;
}
