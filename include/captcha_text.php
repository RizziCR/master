<?php
class captcha {
 /**
  * background color for captcha chars
  *
  * @var array
  */
 var $backgroundColor = array(255, 255, 255);
 
 /**
  * text contained in captcha image
  *
  * @var string
  */
 var $string;

 /**
  * jpeg catcha image
  *
  * @var resource
  */
 var $image;
  
 /**
  * image width (depends on $fontSize)
  *
  * @var int
  */
 var $width = 200;

 /**
  * image height (depends on $fontSize)
  *
  * @var int
  */
 var $height = 150;
 
 
 var $fontFileFolder = '../include/fonts/';
 var $_fonts = array();
 /**
  * constructor, stores string which
  * will be converted into an image
  *
  * @param string $string
  */
 function captcha($string = NULL) {
  $this->string = (bool)$string ? $string : $this->makeRandomString();
  
  $this->drawCaptcha();
  
 }

 /**
  * destructor: frees image resource
  */
 function __destruct() {
  imagedestroy($this->image);
 }
 
 /**
  * make a random string
  */
 function makeRandomString () {
  $md5 = md5(microtime());
  $md5 = strtoupper($md5);

  return str_replace(
  		array('0','O','D','I','1','L'),
  		array('X','X','M','M','K'),
  		substr($md5, 0, 5));
 }

 /**
  * make a random image color
  *
  * @return resource
  */
 function getRandomColor () {
  $colors = array (
   imagecolorallocate($this->image, 0, 0, 0),
   imagecolorallocate($this->image,255,255,0),
   imagecolorallocate($this->image,0,0,255),
  );

  srand ((double)microtime()*1000000);
  $randomElement = rand(0, count($colors)-1);

  return $colors[$randomElement];
 }

 /**
  * renders captcha image
  */
 function drawCaptcha () {
  $this->image = imagecreate($this->width , $this->height);
  $bgColor = imagecolorallocate($this->image, $this->backgroundColor[0], $this->backgroundColor[1], $this->backgroundColor[2]);
  $black = imagecolorallocate($this->image,0,0,0);
  
  $this->drawDistortion($black);//imagecolorallocate($this->image,150,150,150));
  $this->putText($black,$bgColor);
  $this->morphXY();
  imagerectangle($this->image,0,0,$this->width-1,$this->height-1,$black);
 }
 
 function putText($black,$bgColor) {
 $stringLength = strlen($this->string);
   $lastX = rand(5,25);
   $lastY = 50;
   for ($i = 0; $i < $stringLength; $i++) {
	   $char = substr($this->string, $i, 1);
	   
	   $yPos = rand(40,$this->height-40);
	   $xPos = $lastX + (25+(rand(0,3)));
	   
	   $font = $this->getRandomFont();
	   
	   $return = imagefttext($this->image,20,0,$xPos,$yPos,$black,$font,$char);
	   imagefilledrectangle($this->image,$return[6],$return[7],$return[2],$return[3],$bgColor);
	   imagefttext($this->image,20,0,$xPos,$yPos,$black,$font,$char);
	   
	   $lastX = $xPos;
	   $lastY = $yPos;
	  }
 }
 
 function getRandomFont() {
 	if(!count($this->_fonts)) {
 		$dh = opendir($this->fontFileFolder);
 		while(false !== ($file = readdir($dh))) {
 			if(is_dir($this->fontFileFolder.$file) || $file == '.' || $file == '..' || strpos(strtolower($file),'.ttf')===false) {
 				continue;
 			}
 			$this->_fonts[] = $this->fontFileFolder.$file;
 		}
 		closedir($dh);
 	}
 	return $this->_fonts[rand(0,count($this->_fonts)-1)];
 }
 /**
  * renders distortion in captcha image
  */
 function drawDistortion ($color) {
    for($i=0;$i<20;$i++) {
    	imagesetthickness($this->image,rand(1,2));	
        $x1 = rand(0,$this->width);
        $y1 = rand(0,$this->height);
        $x2 = $x1 + rand(-20,20);
        $y2 = $y1 + rand(-20,20);
        
        imageline($this->image,$x1,$y1,$x2,$y2,$color);
    }
    imagesetthickness($this->image,1);
    
    for($i=0;$i<80;$i++) {
        $x1 = rand(0,$this->width);
        $y1 = rand(0,$this->height);
       	if(rand(0,1))
	       	imagefilledrectangle($this->image,$x1-2,$y1-2,$x1+2,$y1+2,$color);
       	if(rand(0,1))
	       	imagefilledrectangle($this->image,$x1-1,$y1-1,$x1+3,$y1+3,$color);
       	if(rand(0,1))
    	   	imagefilledrectangle($this->image,$x1-2,$y1-3,$x1+2,$y1-1,$color);
    }
 }
 
 function morphXY() {
 	//clean border
 	imagesetthickness($this->image,3);
    $bgColor = imagecolorallocate($this->image,$this->backgroundColor[0],$this->backgroundColor[1],$this->backgroundColor[2]);
    imagerectangle($this->image,0,0,$this->width,$this->height,$bgColor);
    
    $tempImage = imagecreate($this->width,$this->height);
	$morph_x = 0;
	for($y=0 ; $y<=$this->height; $y+=$morph_chunk)
	{
		$morph_chunk = rand(1,3);
		$morph_x += rand(-1,1);
		ImageCopy($tempImage, $this->image, $morph_x, $y, 0, $y, $this->width, $morph_chunk);

	}
	ImageCopy($this->image, $tempImage, 0, 0, 0, 0, $this->width, $this->height);
	imagedestroy($tempImage);
 }
 
 /**
  * returns jpeg catcha image
  *
  * @return resource
  */
 function fetch () {
  return $this->image;
 }

 /**
  * returns string used for catcha image
  *
  * @return string
  */
 function fetchString () {
  return $this->string;
 }

 /**
  * outputs catcha image
  */
 function show () {
  header("Content-Type: image/jpeg");
  imagejpeg($this->image, NULL, 100);
 }

 /**
  * saves a catcha image to disk
  */
 function save ($filename) {
  imagejpeg($this->image, $filename);
 }
}
?>