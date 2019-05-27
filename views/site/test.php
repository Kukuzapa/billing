<?php
    use yii\helpers\Html;
    use yii\widgets\Pjax;
    use yii\bootstrap\ActiveForm;
    use yii\bootstrap\Alert;

    $script = <<< JS
        //Переключение ИПП-ООО
        function trigger(){
            $('#test').change(function() {
                console.log('jj');
                if (this.checked) {
                    $('#inn1').hide();
                    $('#kpp1').show();
                } else {
                    $('#inn1').show();
                    $('#kpp1').hide();

                }
            });
        }

        $(document).on('pjax:complete', function() {
            trigger();
        });
        
        $(document).ready(function() {
            trigger();
        });
JS;

    $this->registerJs($script,yii\web\View::POS_LOAD);
?>
<div class="row">
    <?php Pjax::begin(); ?>
    
            <div class="col-sm-12">

                <?php $form = ActiveForm::begin(['id' => 'billing-form','options' => ['data-pjax' => true]]); ?>

                    <?= $form->field($model, 'email')->input('email') ?>

                    <?= $form->field($model, 'password')->passwordInput() ?>

                    <?= $form->field($model, 'name' )->textInput(['autofocus' => true]) ?>

                    <?= $form->field($model, 'isIP')->checkbox(['label' => 'Индивидуальный предприниматель', 'uncheck' => 0, 'value' => 1, 'id'=>'test' ]) ?>

                    <?php if ( $model->attributes['isIP'] ): ?>
                        <?= Html::tag('div', $form->field($model, 'kpp', ['enableClientValidation' => false] )->textInput(), ['id' => 'kpp1', 'style' => ['display' => ''] ]) ?>
                        <?= Html::tag('div', $form->field($model, 'inn', ['enableClientValidation' => false] )->textInput(), ['id' => 'inn1', 'style' => ['display' => 'none'] ]) ?>
                    <?php else: ?>
                        <?= Html::tag('div', $form->field($model, 'kpp', ['enableClientValidation' => false] )->textInput(), ['id' => 'kpp1', 'style' => ['display' => 'none'] ]) ?>
                        <?= Html::tag('div', $form->field($model, 'inn', ['enableClientValidation' => false] )->textInput(), ['id' => 'inn1', 'style' => ['display' => ''] ]) ?>
                    <?php endif; ?>

                    <div class="form-group">
                        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary btn-block']) ?>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>

        <?php if ( $message ): ?>
            <div class="col-sm-12">
                <?= Alert::widget( [ 'options' => [ 'class' => 'alert-success' ], 'body' => 'Say hello...', ]); ?>
            </div>
        <?php endif; ?>
    <?php Pjax::end(); ?>
</div>