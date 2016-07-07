<?php
   session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="http://localhost:3000/socket.io/socket.io.js"></script>
    <!-- jQuery 2.1.4 -->
    <script src="http://localhost:8888/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.4 -->
    <link href="http://localhost:8888/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

<!-- Font Awesome Icons -->
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<!-- Ionicons -->
<link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
<!-- Theme style -->
<link href="http://localhost:8888/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
<!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
<link href="http://localhost:8888/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />

    <script>
    $(document).ready(function(){
        var socket = io.connect("localhost:3000");
        var name = "<?php echo $_SESSION['username'];?>";
        socket.emit("join", name);
        $("form").submit(function(event){
            event.preventDefault();
        });

        socket.on("update", function(msg) {
                $("#msgs").append("<li>" + msg + "</li>");
        })

        socket.on("chat", function(who, msg){
                var message_direction = '';
                if (name == who) {
                  message_direction = 'left';
                }else{
                  message_direction = 'right';
                }

                var message_text = `
                     <div class="direct-chat-msg `+message_direction+`">
                      <div class="direct-chat-info clearfix">
                        <span class="direct-chat-name pull-`+message_direction+`">`+who+`</span>

                      </div>
                      <!-- /.direct-chat-info -->
                      <img src="http://localhost:8888/dist/img/usernophoto.jpg" class="direct-chat-img" alt="User Image"><!-- /.direct-chat-img -->
                      <div class="direct-chat-text">
                      <span class="input-group-addon" style="border: 0px; background: transparent">`+msg+`</span>

                      </div>
                     `;
                $("#msgs").append(message_text);
        });

        socket.on("disconnect", function(){
            $("#msgs").append("<li><strong><span class='text-warning'>The server is not available</span></strong></li>");
            $("#msg").attr("disabled", "disabled");
            $("#send").attr("disabled", "disabled");
        });

// These two handle sending messages
        $("#send").click(function(){
            var msg = $("#msg").val();
            socket.emit("send", msg);
            $("#msg").val("");
        });

        $("#msg").keypress(function(e){
            if(e.which == 13) {
                var msg = $("#msg").val();
                socket.emit("send", msg);
                $("#msg").val("");
            }
        });
//
    });
</script>
<body>

<div class="col-md-4">
              <!-- DIRECT CHAT PRIMARY -->
              <div class="box box-primary direct-chat direct-chat-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Event Notifications</h3>
                  <div class="box-tools pull-right">
                  <!-- Total New Messages Add PHP Here Just for new messages Events today -->
                    <span data-toggle="tooltip" title="XX New Messages Today See Below" class="badge bg-light-blue">XX</span>
                    <button class="btn btn-box-tool" data-toggle="tooltip" title="FutureEvents" data-widget="chat-pane-toggle"><i class="fa fa-comments"></i></button>
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
<!--                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button> -->
                  </div>
                </div><!-- /.box-header -->



                <div class="box-body">
                  <!-- Conversations are loaded here -->
                  <div class="direct-chat-messages" id="msgs">
                    <!-- Message. Default to the left -->

                    <!-- /.direct-chat-text -->
                    </div><!-- /.direct-chat-msg -->
                    
                  
                  
                  </div><!-- /.direct-chat-pane -->
                </div><!-- /.box-body -->

                <div class="box-footer">
                  <form action="#" method="post">
                    <div class="input-group">
                      <input id="msg" type="text" name="message" placeholder="Share Message..." class="form-control">
                      <span class="input-group-btn">
                        <input type="button" name="send" id="send" value="Send" class="btn btn-success">
                      </span>
                    </div>
                  </form>
                </div><!-- /.box-footer-->

              </div><!--/.direct-chat -->
            </div>

</body>
</html>
