<?php
interface ProductAttribute
{
    public function errors();
    public function validate();
    public function name();
    public function identifier();
}
?>