<?php
  abstract class AbstractClass {
    public function run() {
      return $this->getData();
    }
    
    protected abstract function getData();
  }
?>
