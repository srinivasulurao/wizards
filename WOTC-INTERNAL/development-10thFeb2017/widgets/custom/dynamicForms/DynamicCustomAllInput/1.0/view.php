<?php /* Originating Release: November 2015 */?>
<rn:block id="top"/>
<?$initialFocus = ($this->data['attrs']['initial_focus_on_first_field']) ? 'true' : 'false';?>

<? for($i=0; $i<count($this->data['js']['fields']); $i++):?>

<?php $custom_field = $this->data['js']['fields'][$i];

$custom_field_name = explode(".", $custom_field);
$name = $custom_field_name[3];

?>

<div id="form_field_<?php echo $name; ?>" style="display:none">
<rn:widget path="input/FormInput" name="#rn:php:$this->data['js']['fields'][$i]#" />
 </div>   
<? endfor;?>