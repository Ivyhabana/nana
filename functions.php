<?php

function check_login($con)
{

	if(isset($_SESSION['user_id']))
	{

		$id = $_SESSION['user_id'];
		$query = "select * from users where user_id = '$id' limit 1";

		$user_result = mysqli_query($con,$query);
		if($user_result && mysqli_num_rows($user_result) > 0)
		{

			$user_data = mysqli_fetch_assoc($user_result);
			return $user_data;
		}
	}

	//redirect to login
	header("Location: login.php");
	die;

}

function random_num()
{
	$EightDigitRandomNumber = mt_rand(10000000,99999999);
	return $EightDigitRandomNumber;
}