<?php

  // classes/class_imageop.inc.php
  //  Include für allgemeine Gfx-Funktionen

  class ImageOp {

      private $_error = null;

    function createThumb($IMAGE_SOURCE, $THUMB_X, $THUMB_Y, $OUTPUT_FILE, $BGCOLOR) {

        $IMAGE_PROPERTIES = getimagesize($IMAGE_SOURCE);

        switch($IMAGE_PROPERTIES[2]) {
          case 1: $SRC_IMAGE = imagecreatefromgif($IMAGE_SOURCE); break;
          default:
          case 2: $SRC_IMAGE = imagecreatefromjpeg($IMAGE_SOURCE); break;
          case 3: $SRC_IMAGE = imagecreatefrompng($IMAGE_SOURCE); break;
        }

        $SRC_X = imagesx($SRC_IMAGE);
        $SRC_Y = imagesy($SRC_IMAGE);

        if(($THUMB_Y == 0) AND ($THUMB_X == 0)) {
          return false;
        } elseif ($THUMB_Y == 0) {
          $SCALEX = $THUMB_X / ($SRC_X-1);
          $THUMB_Y = $SRC_Y * $SCALEX;
        } elseif ($THUMB_X == 0) {
          $SCALEY = $THUMB_Y / ($SRC_Y-1);
          $THUMB_X = $SRC_X * $SCALEY;
        }

        if($THUMB_X > $SRC_X) $SRC_X = $THUMB_X;
        if($THUMB_Y > $SRC_Y) $SRC_Y = $THUMB_Y;

        $THUMB_X = (int)($THUMB_X);
        $THUMB_Y = (int)($THUMB_Y);

        $DEST_IMAGE = imagecreatetruecolor(100, 100 /*$THUMB_X,$THUMB_Y*/);

        $colors = explode(",", $BGCOLOR);
        $color = imagecolorallocate($DEST_IMAGE, $colors[0], $colors[1], $colors[2]);
        imagefill($DEST_IMAGE, 0, 0, $color);

        if (!imagecopyresampled($DEST_IMAGE, $SRC_IMAGE, (int)((100-$THUMB_X)/2), (int)((100-$THUMB_Y)/2), 0, 0, $THUMB_X, $THUMB_Y, $SRC_X, $SRC_Y)) {
          imagedestroy($SRC_IMAGE);
          imagedestroy($DEST_IMAGE);
          return(3);
        } else {
          imagedestroy($SRC_IMAGE);
          if (imagejpeg($DEST_IMAGE,$OUTPUT_FILE)) {
             imagedestroy($DEST_IMAGE);
             return true;
          }
          imagedestroy($DEST_IMAGE);
        }
        return false;
    } // end createthumb

    function checkUpload() {
        if(!empty($_FILES[picfile][name]))  {
          if($_FILES[picfile][error] != 0) {
            $this->_error = "<p>Ein Serverfehler ist aufgetreten!<br>";
            switch($_FILES[picfile][error]) {
              case 1 : $this->_error .= "Die ausgw&auml;hlte Datei ist zu gross!<p>"; break;
              case 2 : $this->_error .= "Die ausgw&auml;hlte Datei ist zu gross!<p>"; break;
              case 3 : $this->_error .= "Die Datei wurde nur teilweise hochgeladen!<p>"; break;
              case 4 : $this->_error .= "Es wurde keine Datei hochgeladen!<p>"; break;
              default: $this->_error .= "Unbekannter Fehler!<p>"; break;
            }
            return false;
          }
          elseif($_FILES[picfile][type] != "image/jpeg" && $_FILES[picfile][type] != "image/jpg") {
            $this->_error .= "<p>Falscher Dateityp (kein JPEG-Bild)! (".$_FILES[picfile][type].")<p>";
            return false;
          }
          else return true;
        }
        $this->_error .= "Kein Bild ausgew&auml;hlt!";
        return false;
    }

    function getError() {
        return $this->_error;
    }

    function prepareImageForFile($data, $path = "./uploads/") {
        if(! $this->checkUpload()) return 0;

        $thumb = tempnam("/tmp", "thm");
        $bild = basename($tmp=tempnam("/tmp", "pic"));
        $file = $_FILES[picfile][tmp_name];

        list($w, $h) = getimagesize($file);
        if($h>=$w) $this->createThumb($file, 0, 100, $thumb, '0,0,0');
        else       $this->createThumb($file, 100, 0, $thumb, '0,0,0');

        $pic = array();
        $pic[filename] = $path.'/'.$bild.'.jpg';
        $pic[thumb] = $path.'/'.basename($thumb).'.jpg';
        $pic[width]    = $w;
        $pic[height]   = $h;
        @copy($file, $pic[filename]);
        @copy($thumb, $pic[thumb]);
        @unlink($file);
        @unlink($thumb);
        @unlink($tmp);

        return $pic;
    }

  } // end of class

?>
