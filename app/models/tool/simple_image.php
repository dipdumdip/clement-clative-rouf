<?php 
namespace Models\Tool;

class Simple_Image {
 
   protected static  $image;
   protected static  $image_type;
 
   public static function  load($filename) {
 
	list($width, $height, $type, $attr)= getimagesize($filename);

	 static::$image_type = $type;
	
      if( static::$image_type == '2' ) {
 
         static::$image = imagecreatefromjpeg($filename);
      } elseif( static::$image_type == '1' ) {
 
         static::$image = imagecreatefromgif($filename);
      } elseif( static::$image_type == '3' ) {
 
         static::$image = imagecreatefrompng($filename);
      } elseif( static::$image_type == '6' ) {
 
         static::$image = imagecreatefromwbmp ($filename);
      }
   }
   
   public static function  save($filename, $image_type='2', $compression=75, $permissions=null) {
 
      if( $image_type == '2' ) {
         return imagejpeg(static::$image,$filename,$compression);
      } elseif( $image_type == '1' ) {
 
         return imagegif(static::$image,$filename);
      } elseif( $image_type == '3' ) {
 
        return  imagepng(static::$image,$filename);
      } elseif( $image_type == '6' ) {
 
        return  imagebmp(static::$image,$filename);
      }
      if( $permissions != null) {
 
         chmod($filename,$permissions);
      }
   }
 
 public static function  output($image_type='2') {
 
      if( $image_type == '2' ) {
        return imagejpeg(static::$image);
      } elseif( $image_type == '1' ) {
 
        return imagegif(static::$image);
      } elseif( $image_type == '3' ) {
 
       return  imagepng(static::$image);
      }elseif( $image_type == '6' ) {
 
       return  imagebmp(static::$image);
      }
   }
   
   public static function  getWidth() {
 
      return imagesx(static::$image);
   }
   
   public static function  getHeight() {
 
      return imagesy(static::$image);
   }
   
   public static function  resizeToHeight($height) {
 
      $ratio = $height / static::getHeight();
      $width = static::getWidth() * $ratio;
      static::resize($width,$height);
   }
 
   public static function  resizeToWidth($width) {
      $ratio = $width / static::getWidth();
      $height = static::getheight() * $ratio;
      static::resize($width,$height);
   }
 
   public static function  scale($scale) {
      $width = static::getWidth() * $scale/100;
      $height = static::getheight() * $scale/100;
      static::resize($width,$height);
   }
 
   public static function  resize($width,$height) {
      $new_image = imagecreatetruecolor($width, $height);
      imagecopyresampled($new_image, static::$image, 0, 0, 0, 0, $width, $height, static::getWidth(), static::getHeight());
      static::$image = $new_image;
   }      
 
}
?>