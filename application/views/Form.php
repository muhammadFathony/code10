<!DOCTYPE html>
<html>
<style>
input[type=text], select {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

input[type=submit] {
  width: 100%;
  background-color: #4CAF50;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

input[type=submit]:hover {
  background-color: #45a049;
}

div {
  border-radius: 5px;
  background-color: #f2f2f2;
  padding: 20px;
}
</style>
<body>

<h3>Using CSS to style an HTML Form</h3>

<div>
  <form action="<?php echo site_url('coba/responseByid') ?>" method="post">
    <label for="fname">Title</label>
    <input type="text" id="fname" name="heading" placeholder="Title..">
    <input type="hidden" name="user_id" id="user_id">

    <label for="lname">Content</label>
    <input type="text" id="lname" name="content" placeholder="Your content..">
    <input type="hidden" id="app_id" name="app_id" value="2883dafb-ec69-40f1-beec-a6ab1b7b9d52">

    <label for="country">Country</label>
    <select id="country" name="country">
      <option value="australia">Australia</option>
      <option value="canada">Canada</option>
      <option value="usa">USA</option>
    </select>
  
    <input type="submit" value="Submit" name="notif">
  </form>
</div>
<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
<script>
  var OneSignal = window.OneSignal || [];
  OneSignal.push(function() {
    OneSignal.init({
      appId: "2883dafb-ec69-40f1-beec-a6ab1b7b9d52",
    });
  });
</script>
</body>
</html>
<script type="text/javascript">
  OneSignal.push(function() {
    /* These examples are all valid */
    var isPushSupported = OneSignal.isPushNotificationsSupported();
    if (isPushSupported) {
      // Push notifications are supported
      console.log('supported');
      OneSignal.isPushNotificationsEnabled(function(isEnabled) {
        if (isEnabled){
          console.log("Push notifications are enabled!");
             OneSignal.getUserId(function(userId) {
                console.log("OneSignal User ID:", userId);
                // (Output) OneSignal User ID: 270a35cd-4dda-4b3f-b04e-41d7463a2316 
                document.getElementById('user_id').value = userId;   
              });
        } else {
          console.log("Push notifications are not enabled yet.");
          OneSignal.push(function() {
            OneSignal.showHttpPrompt();
          });
        }    
      });
    } else {
      console.log('Not Supported');
      // Push notifications are not supported
    }
  });
</script>