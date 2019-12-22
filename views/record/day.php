<?php
/**
 * Created by PhpStorm.
 * User: Semenov
 * Date: 27.11.2018
 * Time: 12:39
 */

use yii\helpers\Html;
use yii\helpers\Url;

//var_dump($days);

$this->title = 'Самостоятельная запись';
?>
<div class="container">
    <div class="jumbotron">
        <h1 class="display-4">Самозапись к специалисту</h1>
        <p class="lead">На этой странице Вы можете самостоятельно записаться на прием к врачу,
            без необходимости посещать больницу или совершать телефонные звонки для записи</p>
        <hr class="my-2">
        <?php if (isset ($patient['id'])):?>
            <p class="lead">Вы авторизованы как: <b><?= $patient['fullName']?></b></p>
        <?php else:?>
            <?php Yii::$app->response->redirect(['site/index']);?>
        <?php endif;?>
        <?php if (isset ($speciality['specId'])):?>
            <p class="lead">Выбранная специальность: <b><?= $speciality['specName']?></b></p>
        <?php else:?>
            <?php Yii::$app->response->redirect(['record/speciality']);?>
        <?php endif;?>
        <?php if (isset ($doctor['id'])):?>
            <p class="lead">Выбранный специалист: <b><?= $doctor['docName']?></b></p>
        <?php else:?>
            <?php Yii::$app->response->redirect(['record/doctor', 'specid' => $speciality['specId'], 'specname' => $speciality['specName']]);?>
        <?php endif;?>
        <hr class="my-2">
        <?php $maxKol = 30;?>
        <?php if (isset($days) && count($days) > 0): ?>
            <p class="lead">Пожалуйста, выберете удобное для Вас время</p>
            <hr class="my-2">
            <div id="container-calendar-numbs">
                <div id="my-datepicker" class="ui-widgett"></div>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 text-center"></div>
                    </div>
                    <?php foreach ($days as $key => $item): ?>
                        <?php $kol = $maxKol;?>
                        <div class="row" style="display: none" data-name="<?php echo 'd' . str_replace('.','',$key)?>">
                            <?php if (count($item['rnumbs']) > $maxKol): ?>
                                <div class="col-10">
                            <?php else:?>
                                <div class="col-12">
                            <?php endif;?>
                                    <div class="swiper-container">
                                        <div class="swiper-wrapper">
                                            <?php for ($i = 0; $i < count($item['rnumbs']); $i += $kol): ?>
                                                <div class="swiper-slide">
                                                    <?php if (($i + $kol) >= count($item['rnumbs'])): ?>
                                                        <?php $kol = count($item['rnumbs']) - $i;?>
                                                    <?php endif;?>
                                                    <?php $j = $i;?>
                                                    <?php while (($j - $i) < $kol): ?>
                                                        <?= Html::a($item['rnumbs'][$j]['dat_bgn'],
                                                                    Url::to([$url, 'rnumbId' => $item['rnumbs'][$j]['rnumb_id'],
                                                                    'rnumbInfo' => $item['rnumbs'][$j]['dd'] . ' (' . $item['rnumbs'][$j]['dw'] . '), ' . $item['rnumbs'][$j]['dat_bgn'],]),
                                                                    ['class' => 'btn btn-rnumb btn-outline-dark align-middle', 'role' => 'button', 'id' => $item['rnumbs'][$j]['rnumb_id']]);?>
                                                        <?php $j++;?>
                                                    <?php endwhile?>
                                                </div>
                                            <?php endfor;?>
                                        </div>
                                    </div>
                                </div>
                            <?php if (count($item['rnumbs']) > $maxKol): ?>
                                <div class="col-2 pagination-container">
                                    <button class="btn btn-outline-secondary button-prev">
                                        <i class="fas fa-chevron-circle-up"></i>
                                    </button>
                                <br>
                                    <button class="btn btn-outline-secondary button-next">
                                        <i class="fas fa-chevron-circle-down"></i>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else:?>
            <p class="lead"><b>По Вашему запросу ничего не найдено</b></p>
            <hr class="my-2">
                <div class="row">
                    <div class="col-3">
                        <button id="back" class="btn btn-success btn-block">Вернуться назад</button>
                    </div>
                </div>
        <?php endif;?>
    </div>
</div>

<?php
$homeUrl = Url::to(['site/index']);
$js2 = <<<JS
$(document).ready(function () {

    currentDate = '$firstDate';
    $('.row div.col-12.text-center').html('Доступные номерки на ' + currentDate + 'г.');     
    currentTimesInDate = $('div[data-name = ' + 'd' + currentDate.replace(/\./g, "") + ']');
    currentTimesInDate.each(function(key, value){
        $(this).fadeIn('100');
    });    
    mySwiper = swiperInit ('div[data-name = ' + 'd' + currentDate.replace(/\./g, "") + '] .swiper-container');
    mySwiper.init(true);
    
    $('#my-datepicker').datepicker({
        minDate: new Date('$minDate'),
        maxDate: new Date('$maxDate'),
        setDate: '$minDate',
        showOtherMonths: false,
        beforeShowDay: funEnDisDate,
        onSelect: function(currentDate) {
            //Убиваем свайпер, если он есть
            mySwiper.destroy();
            
            //Скрываем все номерки
            $('div[data-name]').each(function(key, value){
                $(this).css({'display': 'none'});
            });
            
            //Показываем номерки только выбранной даты
            $('.row div.col-12.text-center').html('Доступные номерки на ' + currentDate + 'г.');
            currentTimesInDate = $('div[data-name = ' + 'd' + currentDate.replace(/\./g, "") + ']');
            currentTimesInDate.each(function() {
                $(this).fadeIn('100');
            });
            
            // Инициализируем новый свайпер
            currentSwiper = $('div[data-name = ' + 'd' + currentDate.replace(/\./g, "") + '] .swiper-container');
            mySwiper = swiperInit(currentSwiper);
            mySwiper.init(true);
        }
    });
    
    $('.btn.btn-rnumb').each(function(key, item){
        item.addEventListener('click', function(e) {
            $(".ajaxwait, .ajaxwait_image").show();
            e.preventDefault();
            sessionStorage.setItem('rnumbId', e.path[0].id);
            $.ajax({
                type: 'POST',
                data: {rnumbId: e.path[0].id},
                url: window.location.href,
            }).done(function(data) {
                $(".ajaxwait, .ajaxwait_image").hide();
                if (data.success) {
                    console.log('Номерок успешно заблокировался');
                    window.location.href = item.href;
                }
                if (!data.success) {
                    console.log('Страница перезагрузилась, так как номерок был уже занят');
                    window.location.reload();
                }
            }).fail(function() {
                $(".ajaxwait, .ajaxwait_image").hide();
                    sessionStorage.clear();
                    localStorage.clear();
                    window.location.href = '$homeUrl';
                    console.log('Ajax запрос к серверу не прошел. Приложение полностью сбросило все параметры SessionStorage и вернулось на главную страницу');
            });
        });
    });
});

    function swiperInit (currentSwiperContainer) {
        let mySwiper = new Swiper (currentSwiperContainer, {
            direction: 'vertical',
            loop: false,
            init: false,
            height: 310,
            autoHeight: true,
            slidesPerView: 1,
            navigation: {
                nextEl: '.button-next',
                prevEl: '.button-prev',
                },
        });
        return mySwiper;
    }

    var arrMyDates = new Array();
    $('div[data-name]').each(function() {
        let myDay = $(this).attr('data-name').slice(1, 3);
        let myMonth = $(this).attr('data-name').slice(3, 5);
        let myYear = $(this).attr('data-name').slice(5, 10);
        let myCurrentDate = myYear + '-' + myMonth + '-' + myDay;
        arrMyDates.push(myCurrentDate);
    });
    function funEnDisDate(d) {
        let dat = $.datepicker.formatDate('yy-mm-dd', d);
            if ($.inArray(dat, arrMyDates) != -1 ) {
                return [true];
            } else {
                return [false];
            }
        return [true];
    }
JS;
$this->registerJs($js2);
?>