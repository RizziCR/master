<?php
class captcha {
    const MIN_RADIUS = 10;
    const MAX_RADIUS = 15;
    
    protected $_targetCircleColor = array(0,0,0);
    protected $_bgColor = array(255,255,255);
    
    protected $_border  = true;
    protected $_distortion  = true;
    
    protected $_width  = 100;
    protected $_height = 100;
    
    protected $_circle = array(
                                'x' => 0,
                                'y' => 0,
                                'radius' => 0
                              );
    private $_image = null;
    
    public function __construct($x = null,$y = null,$radius = null) {
        if(!is_null($x) && !is_null($y) && !is_null($radius)) {
            $this->_circle['x'] = $x;
            $this->_circle['y'] = $y;
            $this->_circle['radius'] = $radius;
        } else {
            $this->_circle = $this->makeRandomCircle();
        }
    }
    
    
    protected function rand($from,$till) {
        srand($this->make_seed());
        return rand($from,$till);
    }
    
    public function makeRandomCircle() {
        do {
            $x = $this->rand(0,$this->_width);
        } while($x - self::MAX_RADIUS < 0 || $this->_width - self::MAX_RADIUS  < $x);
        do {
            $y = $this->rand(0,$this->_height);
        } while($y - self::MAX_RADIUS < 0 || $this->_height - self::MAX_RADIUS  < $y);
        

        $radius = $this->rand(self::MIN_RADIUS,self::MAX_RADIUS);

        $circle = array();
        $circle['x'] = $x;
        $circle['y'] = $y;
        $circle['radius'] = $radius;
        return $circle;
    }
    
    public function draw() {
        
        $this->_image = imagecreate($this->_width,$this->_height);
        imagesetthickness($this->_image,1);
        
        $bgColor = imagecolorallocate($this->_image,$this->_bgColor[0],$this->_bgColor[1],$this->_bgColor[2]);
        $targetColor = imagecolorallocate($this->_image,$this->_targetCircleColor[0],$this->_targetCircleColor[1],$this->_targetCircleColor[2]);
        $black = imagecolorallocate($this->_image,0,0,0);
        
        imageellipse(   $this->_image,
                        $this->_circle['x'],
                        $this->_circle['y'],
                        $this->_circle['radius']*2,
                        $this->_circle['radius']*2,
                        $targetColor);

        
        if($this->_distortion) {
            $this->drawDistortion($targetColor);   
            $this->morphXY();
        }
        
        if($this->_border) {
            imagerectangle($this->_image,0,0,$this->_width-1,$this->_height-1,$black);
        }
    }

    protected function make_seed()
    {
        list($usec, $sec) = explode(' ', microtime());
        return (float) $sec + ((float) $usec * 100000);
    }

	protected function drawFakeCircles($color = null) {
     	if(is_null($color)) {
            $color = imagecolorallocate($this->_image,$this->_targetCircleColor[0],$this->_targetCircleColor[1],$this->_targetCircleColor[2]);
        }
    	//and now some fake circles ;)
      	$bgColor = imagecolorallocate($this->_image,$this->_bgColor[0],$this->_bgColor[1],$this->_bgColor[2]);
        for($i=0;$i<3;$i++) {
        	$this->make_seed();
        	$circle = $this->makeRandomCircle();
        	//keep away from target circle!!
        	if($circle['x'] - 20 > $this->_circle['x'] && $this->_circle['x'] < $circle['x'] + 20) {
        		if($circle['y'] - 20 > $this->_circle['y'] && $this->_circle['y'] < $circle['y'] + 20) { 
        			$i--;
        			continue;
        		}
        	}
        	switch(rand(1,2)) {
        		case '1':
        			imagearc  ( $this->_image , $circle['x']  , $circle['y']  , 
        				$circle['radius']*2  , $circle['radius']*2, 
        				225,315 , $color );
        			imagearc  ( $this->_image , $circle['x']  , $circle['y']  , 
        				$circle['radius']*2  , $circle['radius']*2, 
        				45,135, $color );
        				break;
        		case '2':
        			imagearc  ( $this->_image , $circle['x']  , $circle['y']  , 
        				$circle['radius']*2  , $circle['radius']*2, 
        				135,225, $color );
        			imagearc  ( $this->_image , $circle['x']  , $circle['y']  , 
        				$circle['radius']*2  , $circle['radius']*2, 
        				315,45, $color );
        				break;
        	}
        				
        }
        return;
    }
    
    protected function drawDistortion($color = null) {
        if(is_null($color)) {
            $color = imagecolorallocate($this->_image,$this->_targetCircleColor[0],$this->_targetCircleColor[1],$this->_targetCircleColor[2]);
        }
        $this->drawFakeCircles($color);
        
        //put some random fake circles
        
        for($i=0;$i<3;$i++){
            $this->make_seed();
            $cx = rand(self::MAX_RADIUS,$this->_width-self::MAX_RADIUS);
            $width = rand(self::MIN_RADIUS,self::MAX_RADIUS);
            $cy = rand(self::MAX_RADIUS,$this->_height-self::MAX_RADIUS);
            $height = rand(self::MIN_RADIUS,self::MAX_RADIUS);
            $phi = rand(0, pi());

            imagepolygon ( $this->_image, array(
                $cx + $width * cos($phi) - $height * sin($phi), $cy + $width * sin($phi) + $height * cos($phi),
                $cx - $width * cos($phi) - $height * sin($phi), $cy - $width * sin($phi) + $height * cos($phi),
                $cx - $width * cos($phi) + $height * sin($phi), $cy - $width * sin($phi) - $height * cos($phi),
                $cx + $width * cos($phi) + $height * sin($phi), $cy + $width * sin($phi) - $height * cos($phi),
            ), 4, $color );
        }

        for($i=0;$i<3;$i++) {
            $this->make_seed();
            $y1 = rand(0,$this->_height);
            $y2 = rand(0,$this->_height);

            imageline($this->_image,0,$y1,$this->_width,$y2,$color);
        }
    
        for($i=0;$i<3;$i++) {
            $this->make_seed();
            $x1 = rand(0,$this->_width);
            $x2 = rand(0,$this->_width);

            imageline($this->_image,$x1,0,$x2,$this->_height,$color);
        }
        
        for($i=0;$i<150;$i++) {
        	$x1 = rand(0,$this->_width);
        	$y1 = rand(0,$this->_height);
        	imagesetpixel($this->_image,$x1,$y1,$color);
        }
    }
    
    protected function morphXY() {
        //clean border
 		imagesetthickness($this->_image,4);
    	$bgColor = imagecolorallocate($this->_image,$this->_bgColor[0],$this->_bgColor[1],$this->_bgColor[2]);
    	imagerectangle($this->_image,0,0,$this->_width,$this->_height,$bgColor);
    	    
        $tempImage = imagecreate($this->_width,$this->_height);
		$morph_x = 0;
		for($y=0 ; $y<=$this->_height; $y+=$morph_chunk)
		{
		    $this->make_seed();
			$morph_chunk = rand(1,3);
			$morph_x += rand(-1,1);
			ImageCopy($tempImage, $this->_image, $morph_x, $y, 0, $y, $this->_width, $morph_chunk);

		}
		ImageCopy($this->_image, $tempImage, 0, 0, 0, 0, $this->_width, $this->_height);
		imagedestroy($tempImage);
    }
    
    public function display() {
        header("Content-Type: image/jpeg");
        imagejpeg($this->_image, NULL, 100);
    }
    
    public function checkPointInCircle($x,$y) {
           $k = sqrt(pow($this->_circle['x']-$x,2) + pow($this->_circle['y']-$y,2));
           
           if($k > $this->_circle['radius']) {
               return false;
           } else {
               return true;
           }
    }
    
    public function getCircle() {
        return $this->_circle;
    }
}
?>
