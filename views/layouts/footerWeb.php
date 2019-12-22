<footer class="container">
    <div class="row">
        <div class="col-2">
            <?php if (Yii::$app->session->get('isHome') == 1): ?>
<!--                <button id="back" type="button" class="btn btn-secondary">Вернуться</button>-->
            <?php endif; ?>
        </div>
        <div class="col-8 text-center align-self-center" style="font-weight: 200">Запись по телефону: +7 (81837) 2-82-22</div>
        <div class="col-2 text-right">
            <?php if (Yii::$app->session->get('isHome') == 1): ?>
<!--                <button id="home" type="button" class="btn btn-secondary">На главную</button>-->
            <?php endif; ?>
        </div>
    </div>
    <hr class="my-4">
    <div class="footer-info">
        <p>© 2012 <b>ГБУЗ АО "Котласская центральная городская больница <br> имени святителя Луки (В.Ф.Войно-Ясенецкого)</b></p>
        <P><b>Адрес:</b> г. Котлас, пр. Мира, д. 36
            <br>
            <b>Справочная:</b> +7 (81837) 2-58-83
        </p>
    </div>
</footer>