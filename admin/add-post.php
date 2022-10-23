<?php //include config
error_reporting(E_ALL ^ E_NOTICE);
require_once('../includes/config.php');

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin - Add Post</title>
  <link rel="stylesheet" href="../style/normalize.css">
  <link rel="stylesheet" href="../style/main.css">
  <link rel="shortcut icon" href="..\..\bararchet\images\logo.png" type="image/x-icon">
  <!-- Bootstrap CSS-->
  <link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.min.css">
  <script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>
  <script>
          tinymce.init({
              selector: "textarea",
              plugins: [
                  "advlist autolink lists link image charmap print preview anchor",
                  "searchreplace visualblocks code fullscreen",
                  "insertdatetime media table contextmenu paste"
              ],
              toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
          });
  </script>
</head>
<body>
<div class="container">
	<div class="row">
		<div class="col-md-10 col-lg-12 mx-auto">
                

	<div id="wrapper">

	<?php include('menu.php');?>
	

	<h2>Add Post</h2>

	<?php
	$name = $_FILES['file']['name'];
        $target_dir = "upload/";
        $target_file = $target_dir . basename($_FILES["file"]["name"]);

        // Select file type
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        // Valid file extensions
        $extensions_arr = array("jpg","jpeg","png","gif");

       

	//if form has been submitted process it
	if(isset($_POST['submit'])){

		$_POST = array_map( 'stripslashes', $_POST );

		//collect form data
		extract($_POST);

		//very basic validation
		if($postTitle ==''){
			$error[] = 'Please enter the title.';
		}

		if($postDesc ==''){
			$error[] = 'Please enter the description.';
		}

		if($postAuthor ==''){
			$error[] = 'Please enter the author name.';
		}

		if($postCont ==''){
			$error[] = 'Please enter the content.';
		}

		if(!isset($error)){
			// Convert to base64 
            $image_base64 = base64_encode(file_get_contents($_FILES['file']['tmp_name']) );
            $image = 'data:image/'.$imageFileType.';base64,'.$image_base64;

			try {         
         

				//insert into database
				$stmt = $db->prepare('INSERT INTO blog_posts (postTitle,postSubTitle,postDesc,postAuthor,postCont,postDate,image,name) VALUES (:postTitle, :postSubTitle, :postDesc, :postAuthor, :postCont, :postDate, :image, :name)') ;
				$stmt->execute(array(
					':postTitle' => $postTitle,
					':postSubTitle' => $postSubTitle,
					':postDesc' => $postDesc,
					':postAuthor' => $postAuthor,
					':postCont' => $postCont,
					':postDate' => date('Y-m-d H:i:s'),
					':image' => $image,
					':name' => $name,
				));

				// Upload file
            move_uploaded_file($_FILES['file']['tmp_name'],'upload/'.$name);

				//redirect to index page
				header('Location: index.php?action=added');
				exit;

			} catch(PDOException $e) {
			    echo $e->getMessage();
			}

		}

	}

	//check for any errors
	if(isset($error)){
		foreach($error as $error){
			echo '<p class="error">'.$error.'</p>';
		}
	}
	?>
</div>
	<div class="col-md-10 col-lg-12 mx-auto">	
	<form action='' method='post' enctype='multipart/form-data'>

		<p><label>Title</label><br />
		<input class='form-control' type='text' name='postTitle' value='<?php if(isset($error)){ echo $_POST['postTitle'];}?>'></p>

		<p><label>Sub Title</label><br />
		<input class='form-control' type='text' name='postSubTitle' value='<?php if(isset($error)){ echo $_POST['postSubTitle'];}?>'></p>

		<p><label>Description</label><br />
		<textarea class='form-control' name='postDesc' cols='60' rows='10'><?php if(isset($error)){ echo $_POST['postDesc'];}?></textarea></p>

		<p><label>Author Name</label><br />
		<input class='form-control' type='text' name='postAuthor' value='<?php if(isset($error)){ echo $_POST['postAuthor'];}?>'></p>

		<p><label>Content</label><br />
		<textarea class='form-control' name='postCont' cols='60' rows='10'><?php if(isset($error)){ echo $_POST['postCont'];}?></textarea></p>

		 <p><label>Post Image</label><br />
		<input class='form-control' type='file' name='file'> 
		</p>
                

		<p><input class='btn btn-success' type='submit' name='submit' value='Submit'></p>

	</form>
</div>
</div>
</div>
</div>
</body>
</html>
