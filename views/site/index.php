<?php

/* @var $this yii\web\View */

//use yii\helpers\VarDumper;

$this->title = 'My Yii Application';
//$identity = Yii::$app->getUser()->getIdentity();
//if (isset($identity->profile)) {
//    $user_vk = $identity->profile;
//    $name_vk = explode(' ',$user_vk['name']);
//     var_dump($name_vk);
//    VarDumper::dump($identity->profile, 10, true);
//}
?>
<style>
    .optionGroup {
        font-weight: bold;
        font-style: italic;
    }

    .optionChild {
        padding-left: 15px;
    }
</style>


<select multiple="multiple">
    <option value="0" class="optionGroup">Parent Tag</option>
    <option value="1" class="optionChild">Child Tag</option>
    <option value="2" class="optionChild">Child Tag</option>
</select>


<form name="Sum">
    <input type="checkbox" value="1"/>
    <input type="checkbox" value="2"/>
    <input type="checkbox"/>
    <input type="checkbox" value=""/>
    <input type="checkbox" value="3"/>
    <input type="checkbox" value="4"/>
    <input type="checkbox" value="5"/>
    <output id="rezultat">Сумма: 0</output>
</form>

<script>
    var s = document.forms.Sum,
        d = s.querySelectorAll('input[type="checkbox"]:not([value]), input[type="checkbox"][value=""]');
    for (var i = 0; i < d.length; i++) // чтобы не было написано NaN, убираем в disabled пункты, где не прописаны значения
        d[i].disabled = true;
    s.onchange = function() { // начало работы функции сложения
        var n = s.querySelectorAll('[type="checkbox"]'),
            itog = 0;
        for(var j=0; j<n.length; j++)
            n[j].checked ? itog += parseFloat(n[j].value) : itog;
        document.getElementById('rezultat').innerHTML = 'Сумма: ' + itog;
    }
</script>