<?php foreach ($firstcates as $firstcate):?>
    <div class="cat item1">
        <h3>
            <b>
                <a href="<?php echo Yii::getAlias('@web')?>.'/list/index?id='.<?=$firstcate->id?>"><?=$firstcate->name?></a>
           </b>
        </h3>
        <div class="cat_detail">
            <?php foreach ($firstcate->children as $secondcate):?>
                <dl class="dl_1st">
                    <dt>
                        <a href="<?php echo Yii::getAlias('@web')?>.'/list/index?id='.<?=$secondcate->id?>"><?=$secondcate->name?></a>

                    </dt>
                    <?php foreach ($secondcate->children as $thirdcate):?>
                        <dd>
                            <a href="<?php echo Yii::getAlias('@web')?>.'/list/index?id='.<?=$thirdcate->id?>"><?=$thirdcate->name?></a>

                        </dd>
                    <?php endforeach;?>
                </dl>
            <?php endforeach;?>

        </div>
    </div>
<?php endforeach;?>