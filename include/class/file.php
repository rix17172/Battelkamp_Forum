<?php
 if(!defined("in_forum"))exit;
class image{
	
	private $image;
    private $filename;
	
    public function __construct($filename)    {
    	$this->LoadImage($filename);
    	$this->filename = $filename;
    }
    
    private function getExtension($filename)    {
        return strtolower(pathinfo($filename,PATHINFO_EXTENSION));
    }
    
   private function LoadImage($filename){
   	 switch ($this->getExtension($filename)){
   	 	case "gif":
   	 		$this->image = imagecreatefromgif($filename);
   	 	break;
   	 	case "png":
   	 		$this->image = imagecreatefrompng($filename);
   	 	break;
   	 }
   }
   
   private function ConvertHtmlColor($color){
   	$color = str_replace("#", "", $color);
   	$hexcolor = str_split($color,2);
   	$bin = array();
   	$bin[] = hexdec("0x{$hexcolor[0]}");
   	$bin[] = hexdec("0x{$hexcolor[1]}");
   	$bin[] = hexdec("0x{$hexcolor[2]}");
   	return $bin;
   }
   
   private function GetWidth(){
   	return (int)imagesx($this->image);
   }
   
   private function GetHeight(){
   	return (int)imagesy($this->image);
   }
   
   public function ResizeImage($h,$w,$background = "#DBDBC9"){
   	 $tw = $this->GetWidth();
   	 $th = $this->GetHeight();
   	 $wscale = $w / $tw;
   	 $hscale = $h / $th;
   	 $scale = min($wscale,$hscale);
   	 
   	 $nw = round($tw * $scale, 0);
   	 $nh = round($th * $scale, 0);
   	 
   	 $tmpImage = imagecreatetruecolor($nw, $nh);
   	 $col = $this->ConvertHtmlColor($background);
   	 $back = imagecolorallocate($tmpImage,$col[0],$col[1],$col[2]);
   	 imagecopyresampled($tmpImage, $this->image, 0,0,0,0,$nw,$nh,$this->GetWidth(),$this->GetHeight());
   	 imagedestroy($this->image);
   	 $this->image = $tmpImage;
   }
   
   public function ShowImage(){
   	switch ($this->getExtension($this->filename)){
   		case "gif":
   			header('Content-Type: image/gif');
   			imagegif($this->image);
   		break;
   		case "png":
   			header("Content-Type: image/png");
   			imagepng($this->image);
   		break;
   	}
   			imagedestroy($this->image);
   }
}
