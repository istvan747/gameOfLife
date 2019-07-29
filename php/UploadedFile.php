<?php
class UploadedFile{
    
    private $file;
    private $uploadFolder;
    private $uniqName;
    
    function __construct(array $file ){
        $this->file = $file;
        $this->uploadFolder = "../uploads/";
    }
    
    public function getFullName():string{
        return $this->file['name'];
    }
    
    public function getName():string{
        $pos = -1;
        $result = "";
        if( ($pos = strpos($this->file['name'], '.')) ){
            $result = substr($this->file['name'], 0, $pos);
        }
        return $result;
    }
    
    public function getExtension():string{
        $pos = -1;
        $result = "";
        if( ($pos = strrpos($this->file['name'], '.')) ){
            $result = substr($this->file['name'], $pos);
        }
        return $result;
    }
    
    public function getType():string{
        return $this->file['type'];
    }
    
    public function getTmpName():string{
        return $this->file['tmp_name'];
    }
    
    public function getError():int{
        return $this->file['error'];
    }
    
    public function getSize():int{
        return $this->file['size'];
    }
    
    public function setUploadFolder(string $folder){
        $this->uploadFolder = $folder;
    }
    
    public function getUploadFolder():string{
        return $this->uploadFolder;
    }
    
    public function saveFile(){
        return move_uploaded_file($this->getTmpName(), $this->getUploadFolder() . $this->getFullName());
    }
    
    public function toString():string{
        return "{ full_name: " . $this->getFullName() . ", name: " . $this->getName()
            . ", extension: " . $this->getExtension() . ", type: " . $this->getType()
            . ", tmp_name: " . $this->getTmpName() . ", error: " . $this->getError()
            . ", size: " . $this->getSize() . " byte }";
    }
    
    
    
}

?>