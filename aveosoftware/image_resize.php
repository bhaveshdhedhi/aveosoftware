<?php
ini_set('max_execution_time', 0);

function resize($folder_path,$resize_percent)
{
	$resize_folder_path	= 'resized_images/';

	if($dir = opendir($folder_path)){
		while(($file = readdir($dir))!== false){

			$imagePath = $folder_path.$file;
			$resize_image_path = $resize_folder_path.$file;
			$flagValidImageType = @getimagesize($imagePath);

			if(file_exists($imagePath) && $flagValidImageType)
			{
				if(resizeImage($imagePath,$resize_image_path,$resize_percent))
				{
					echo $file.' resize Successfully.<br />';
				}else{
					echo $file.' resize Failed.<br />';
				}
			}
		}
		closedir($dir);
	}
}

function resizeImage($imagePath,$resize_image_path,$resize_percent)
{
   	list($imageWidth,$imageHeight,$type) = getimagesize($imagePath);
    $newImage = imagecreatetruecolor($imageWidth, $imageHeight);

	switch(strtolower(image_type_to_mime_type($type)))
	{
		case 'image/jpeg':
			$resize_image = imagecreatefromjpeg($imagePath);
			break;
		case 'image/png':
			$resize_image = imagecreatefrompng($imagePath);
			break;
		case 'image/gif':
			$resize_image = imagecreatefromgif($imagePath);
			break;
		default:
			return false;
	}

    if(imagecopyresampled($newImage, $resize_image,0, 0, 0, 0, $imageWidth, $imageHeight, $imageWidth, $imageHeight))
    {
       if(imagejpeg($newImage,$resize_image_path,$resize_percent))
        {
            imagedestroy($newImage);
            return true;
        }
    }
}

resize("images/", 30);
?>