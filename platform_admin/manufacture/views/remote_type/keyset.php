<?php
use yii\helpers\Url;
?>
<?php include_once(NAV_DIR."/header.php");?>
<link rel="stylesheet" href="/static/css/public/select2.min.css">
<link rel="stylesheet" href="/static/css/public/department-add.css">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="/static/css/public/iCheck/all.css">

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="right-box p-b-20 row">
            <button type="button" class="btn btn-default go_back_list">
                返回
            </button>
        </div>
        <!-- 内容区域-->
        <div class="row col-md-12">
            <div class="row" style="border-bottom:1px solid #E4E4E4 ">
                <h2><?=$info['name']?></h2>
            </div>
            <form id="mymessage-form">
                <input type="hidden" name="id" value="<?=$info['id']?>">
                <input type="hidden" name="_csrf" value="<?=Yii::$app->request->csrfToken?>">
                <?php foreach ($keyset as $key=>$value):?>
                    <h3><?=$key?></h3>
                    <div class="col-md-12">
                        <div class="form-group col-md-12 input-xx">
                            <div class='radio-inline' style='flex-wrap:wrap'>
                                <?php if($value):?>
                                <?php foreach ($value as $k=>$v):?>
                                    <div class="col-md-3">
                                        <input type="checkbox" name="<?=$key?>" class="minimal" value="<?=$v?>" <?php if(in_array($v,$keysetInfo)) echo 'checked';?> />
                                        <label class='m-l-5 m-r-5 check_box_label'>
                                            <?=$v?>
                                        </label>
                                    </div>
                                <?php endforeach;?>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                <?php endforeach;?>
            </form>
        </div>
    </section>
</div>

<?php include_once(NAV_DIR."/footer.php");?>
<script src="/static/js/public/select2.full.min.js"></script>
<script src="/static/js/public/iCheck/icheck.min.js"></script>
<script type="text/javascript">
    //Initialize Select2 Elements
    $('.select2').select2();
    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass   : 'iradio_minimal-blue'
    });
    //
    $('input[type="checkbox"].minimal').on('ifChecked', function(event){
        keyset($(this).attr('name'), $(this).val(), 1);
    });
    $('input[type="checkbox"].minimal').on('ifUnchecked', function(event){
        keyset($(this).attr('name'), $(this).val());
    });
    //返回列表页面
    $('.go_back_list').unbind('click').click(function(){
        var url;
        url = '<?=Yii::$app->request->get('mid')?>' ? '<?=url::toRoute(['list', 'mid'=>Yii::$app->request->get('mid')])?>' : '<?=url::toRoute('list')?>';
        location.href = url;
//        location.href = '<?//=url::toRoute('list')?>//';
    });

    function keyset(type, key, status='') {
        var data = {};
        data.type = type;
        data.key = key;
        if (status) {
            data.checked = 1;
        }
        data.id = $("input[name='id']").val();
        data._csrf = '<?=Yii::$app->request->csrfToken?>';
//            console.log(data); return false;
        $.ajax({
            url:'<?=url::toRoute('keyset')?>',
            type:'post',
            dataType:'json',
            data:data,
            success:function (data) {
               //  console.log(data);
                if(data.code != 0){
                    fail(key+'设置失败');
                }
            }
        })
    }
</script>