<?php

class FB_register {
	
	
  	
  	//this function will register account with their facebook
  	//user must be a umw student.
  	//facebook api will grab student name and school to confirm umw
  	function register(){
  	
  	/*
  	*************CREATES CONNECTION TO FACEBOOK APP******************
  	*/
		// Remember to copy files from the SDK's src/ directory to a
		// directory in your application on the server, such as php-sdk/
		//this allows user to access the app via. facebook
		//if app is not public, the user must be added to developer role
		require_once('php_sdk/facebook.php');
				
		$config = array(
		'appId' => '1498679113686515',
		'secret' => 'fb792caa188b7a918f7e44891380af01',
		'allowSignedRequest' => false // optional but should be set to false for non-canvas apps
		);
				
		$facebook = new Facebook($config);
		$user_id = $facebook->getUser();
		  	
  	/*
  	****************************************************************
  	*/
  		//login through facebook
  		if($user_id) {

		      // We have a user ID(already logged in).
		      // If not, we'll get an exception, which we handle below.
		      //***check permissions to grab certain information about users***
		      try {
			//storing values in variables
		        $user_profile = $facebook->api('/me');   
		        //$user_profile holds all information associated to user
		        
		        $access_token = $facebook->getAccessToken();
		        
		        
		        
		       //catch all information you wish to have
		        $last_name = $user_profile['last_name'];
		        $first_name = $user_profile['first_name'];
		        $username = "$first_name.$last_name";
		        $password = "password";
		        
		       
        		
		       
		     	$con=mysqli_connect("testdbinstance.citdzyfi7gwk.us-east-1.rds.amazonaws.com","admin","roottoor", "KiteTest");
			// Check connection
			if (mysqli_connect_errno())
	 		 {
	 		 echo "Failed to connect to MySQL: " . mysqli_connect_error();
	 		 
	 		 }
	 		 elseif(!mysqli_connect_errno()){
	 		 echo "Connected to Mysql";
	 		 echo "<br>";
	 		 $this->db = new mysqli("testdbinstance.citdzyfi7gwk.us-east-1.rds.amazonaws.com","admin","roottoor", "KiteTest");
	 		 $this->db->autocommit(false);
	 		 
	 		 
	 		//check if name is in databases
		 	//grab information of user
	 		$stmt = $this->db->prepare('SELECT id, last_name, first_name FROM users WHERE last_name = ? AND first_name = ?');
			//"s" represents the type and the number of arguements (s = string, i = integer, d = double, b = blobs)
			$stmt->bind_param("ss", $last_name, $first_name);
		        $stmt->execute();
		        $stmt->bind_result($id, $lastname, $firstname);
		        if ($stmt->fetch()) {
		            echo "Logged into The Kyte as $lastname, $firstname.";
		            echo "<br>";
		        }
		        else{
		        
		        	$con=mysqli_connect("testdbinstance.citdzyfi7gwk.us-east-1.rds.amazonaws.com","admin","roottoor", "KiteTest");
				// Check connection
				if (mysqli_connect_errno())
				  {
				  echo "Failed to connect to MySQL: " . mysqli_connect_error();
				  }
						        	
				echo "Creating an account with facebook for";
		        	echo "$lastname, $firstname";
		        	echo "<br>";
		        	
		        	$sql="INSERT INTO users (id, last_name, first_name, username, password)
				VALUES
				(DEFAULT,'$last_name','$first_name', '$username', '$password')";
				
				if (!mysqli_query($con,$sql))
				{
				  	die('Error: ' . mysqli_error($con));
				}
					echo " was added to the system";
				
				mysqli_close($con);
		        	
		        	
		        	
		        }
		        $stmt->close();
		        }
		       
		       
		       
		
		      } catch(FacebookApiException $e) {
		        // If the user is logged out, you can have a 
		        // user ID even though the access token is invalid.
		        // In this case, we'll get an exception, so we'll
		        // just ask the user to login again here.
		        $login_url = $facebook->getLoginUrl(); 
		        echo 'Please <a href="' . $login_url . '">login.</a>';
		        error_log($e->getType());
		        error_log($e->getMessage());
		      }     
		    }
		    
		else {
		
		      // No user, print a link for the user to login
		      $login_url = $facebook->getLoginUrl();
		      echo 'Please <a href="' . $login_url . '">login.</a>';
		
		    }
	
  	}

}


// This is the first things that get called when this page is loaded
$api = new FB_register;
$api->register();









//query 'select * from users where first_name last_name = $user_profile' 
		       /*
		       	$total_friends = count($user_friends['data']); //count total friends
			echo "Name: " .$name. '<br />';
		    	echo 'Total List of Friends: '.$total_friends.'<br />';
		   	
		   	$start = 0;
		   	 while ($start < $total_friends) {
		   	     echo $user_friends['data'][$start]['name'];
		        echo '<br />';
		     	   $start++;
		   	 }
		        */
