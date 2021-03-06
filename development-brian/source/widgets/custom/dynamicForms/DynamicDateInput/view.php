<rn:meta controller_path="custom/dynamicForms/DynamicDateInput" js_path="custom/dynamicForms/DynamicDateInput" base_css="standard/input/DateInput" presentation_css="widgetCss/DateInput.css"  compatibility_set="November '09+"/>

<? if($this->data['readOnly']):?>
<rn:widget path="output/FieldDisplay" left_justify="true"/>
<? else:?>

<div id="rn_<?=$this->instanceID;?>" class="rn_DateInput">
<fieldset>
<? if($this->data['attrs']['label_input']):?>
    <legend id="rn_<?=$this->instanceID;?>_Legend" class="rn_Label"><?=$this->data['attrs']['label_input'];?>
    <? if($this->data['attrs']['required']):?>
        <span class="rn_Required"> * </span><span class="rn_ScreenReaderOnly"><?=getMessage(REQUIRED_LBL)?></span>
    <? endif;?>
    </legend>
<? endif;?>
<? for($i = 0; $i < 3; $i++):?>

    <? /**Year*/ ?>
    <? if($this->data['yearOrder'] === $i):?>
    <select id="rn_<?=$this->instanceID;?>_<?=$this->data['js']['name'];?>_Year" <?=tabIndex($this->data['attrs']['tabindex'], 1 + $i);?>>
        <option value=''>--</option>
        <? for($j = $this->data['maxYear']; $j > 1969; $j--):?>
        <? if($this->data['defaultValue']) $selected = ($this->data['value'][2] == $j) ? 'selected="selected"' : '';?>
        <option value="<?=$j;?>" <?=$selected;?>><?=$j;?></option>
        <? endfor;?>
    </select>
    <label for="rn_<?=$this->instanceID;?>_<?=$this->data['js']['name'];?>_Year" class="rn_ScreenReaderOnly"><?=$this->data['yearLabel'];?><? if($this->data['js']['hint'] && $i===0):?> <?=$this->data['js']['hint']?><?endif?></label>

    <? /**Month*/ ?>
    <? elseif($this->data['monthOrder'] === $i):?>
    <select id="rn_<?=$this->instanceID;?>_<?=$this->data['js']['name'];?>_Month" <?=tabIndex($this->data['attrs']['tabindex'], 1 + $i);?>>
        <option value=''>--</option>
        <? for($j = 1; $j < 13; $j++):?>
        <? if($this->data['defaultValue']) $selected = ($this->data['value'][0] == $j) ? 'selected="selected"' : '';?>
        <option value="<?=$j;?>" <?=$selected;?>><?=$j;?></option>
        <? endfor;?>
    </select>
    <label for="rn_<?=$this->instanceID;?>_<?=$this->data['js']['name'];?>_Month" class="rn_ScreenReaderOnly"><?=$this->data['monthLabel'];?><? if($this->data['js']['hint'] && $i===0):?> <?=$this->data['js']['hint']?><?endif;?></label>

    <? /**Day*/ ?>
    <? else:?>
    <select id="rn_<?=$this->instanceID;?>_<?=$this->data['js']['name'];?>_Day" <?=tabIndex($this->data['attrs']['tabindex'], 1 + $i);?>>
        <option value=''>--</option>
        <? for($j = 1; $j < 32; $j++):?>
        <? if($this->data['defaultValue']) $selected = ($this->data['value'][1] == $j) ? 'selected="selected"' : '';?>
        <option value="<?=$j;?>" <?=$selected;?>><?=$j;?></option>
        <? endfor;?>
    </select>
    <label for="rn_<?=$this->instanceID;?>_<?=$this->data['js']['name'];?>_Day" class="rn_ScreenReaderOnly"><?=$this->data['dayLabel'];?><? if($this->data['js']['hint'] && $i===0):?> <?=$this->data['js']['hint']?><?endif;?></label>
    <? endif;?>
<? endfor;?>

<? if($this->field->data_type === EUF_DT_DATETIME):?>

    <? /**Hour*/ ?>
    <select id="rn_<?=$this->instanceID;?>_<?=$this->data['js']['name'];?>_Hour" <?=tabIndex($this->data['attrs']['tabindex'], 1 + $i);?>>
        <option value=''>--</option>
        <? for($j = 0; $j < 24; $j++):?>
        <? if($this->data['defaultValue']) $selected = ($this->data['value'][3] == $j) ? 'selected="selected"' : '';?>
        <option value="<?=$j;?>" <?=$selected;?>><?=$j;?></option>
        <? endfor;?>
    </select>
    <label for="rn_<?=$this->instanceID;?>_<?=$this->data['js']['name'];?>_Hour" class="rn_ScreenReaderOnly"><?=$this->data['hourLabel'];?></label>

    <? /**Minute*/ ?>
    <select id="rn_<?=$this->instanceID;?>_<?=$this->data['js']['name'];?>_Minute" <?=tabIndex($this->data['attrs']['tabindex'], 2 + $i);?>>
        <option value=''>--</option>
        <? for($j = 0; $j < 60; $j++):?>
        <? if($this->data['defaultValue']) $selected = ($this->data['value'][4] == $j) ? 'selected="selected"' : '';?>
        <option value="<?=$j;?>" <?=$selected;?>><?=$j;?></option>
        <? endfor;?>
    </select>
    <label for="rn_<?=$this->instanceID;?>_<?=$this->data['js']['name'];?>_Minute" class="rn_ScreenReaderOnly"><?=$this->data['minuteLabel'];?></label>
<? endif;?>
</fieldset>
</div>

<? endif;?>
