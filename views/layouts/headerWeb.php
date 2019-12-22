<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
    <header class="container">
        <div class="row align-items-center">
            <div class="col text-left">
                    <img class="img-fluid" src="//kotlasgb.ru/bitrix/templates/main/img/logo.png">
            </div>
            <div class="col-8 text-center">
                <div>Государственное бюджетное учреждение здравоохранения Архангельской области</div>
                <p>«Котласская центральная городская больница имени святителя<br>Луки (В.Ф. Войно-Ясенецкого)»</p>
            </div>
            <div class="col text-right">
                <div class="date-time">
                    <div id="time" class="text-nowrap"></div>
                    <div id="date" class="text-nowrap"></div>
                </div>
            </div>
        </div>
<!--        <div class="text-center">-->
<!--            <img class="img-fluid" style="width: 100%; box-shadow: 0px 0px 10px 1px rgba(0,0,0,0.5);" src="../web/images/imgWeb/foto/main-foto.jpg">-->
<!--        </div>-->
        <ul class="nav nav-tabs nav-fill">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle bg-secondary text-white" data-toggle="dropdown" href="http://www.kotlasgb.ru/" role="button" aria-haspopup="true" aria-expanded="false">О больнице</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="http://www.kotlasgb.ru/history/">История</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="http://www.kotlasgb.ru/administration/">Администрация</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="http://www.kotlasgb.ru/documents/">Документы</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="http://www.kotlasgb.ru/insurance/index.php">Страховые организации</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="http://www.kotlasgb.ru/custom/">Закупки</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="http://www.kotlasgb.ru/news/">Новости</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="http://www.kotlasgb.ru/vacancy/">Вакансии</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="http://www.kotlasgb.ru/blagodarnosti.php">Благодарности</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="http://www.kotlasgb.ru/photo/">Фотогалерея</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="http://www.aokb.ru/index.php/sotrudnikam?id=1028">Специалистам</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-dark" data-toggle="dropdown" href="http://www.kotlasgb.ru/units/" role="button" aria-haspopup="true" aria-expanded="false">Подразделения</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="http://www.kotlasgb.ru/units/hospital/">Стационар</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="http://www.kotlasgb.ru/units/adult_policlinic/">Взрослая поликлиника</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="http://www.kotlasgb.ru/units/child_policlinic/">Детская поликлиника</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="http://www.kotlasgb.ru/units/ambulance/">Отделение скорой медицинской помощи</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="http://www.kotlasgb.ru/units/family_plan_center/index.php">Акушерско-генекологический стационар и женская консультация</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="http://www.kotlasgb.ru/units/subservices/">Вспомогательные службы бальницы</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-dark" data-toggle="dropdown" href="http://www.kotlasgb.ru/services/" role="button" aria-haspopup="true" aria-expanded="false">Услуги</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="http://www.kotlasgb.ru/services/documents-paid-services/">Документы об оказании платных услуг в больнице</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="http://www.kotlasgb.ru/services/paid_services/paid_serv.pdf">Перечень платных услуг</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="http://www.kotlasgb.ru/services/price/">Прейскурант</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item disabled" href="#">Справка об оплате медуслуг</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-dark" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Пациентам</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="http://www.kotlasgb.ru/for_patients/page.php">Диспансеризация взрослого населения</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="http://www.kotlasgb.ru/for_patients/profilact/">Профилактика</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="http://www.kotlasgb.ru/for_patients/pitanie/">Питание</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="http://www.kotlasgb.ru/for_patients/medicine_today/insurance_med/">Современное здравоохранение</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="http://www.kotlasgb.ru/instructions/">Рекомендации, инструкции, документы</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="http://www.kotlasgb.ru/for_patients/medicine/index.php">Перечень лекарсмтвенных препаратов</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="http://www.kotlasgb.ru/for_patients/comments/">Отзывы пациентов</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="http://www.kotlasgb.ru/for_patients/antikorruptsionnaya-rabota.php">Антикоррупционная работа</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="http://www.kotlasgb.ru/for_patients/medicine_today/law_base/">Правовая база для пациентов</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="http://www.kotlasgb.ru/for_patients/interview/">Опрос пациентов</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="http://www.kotlasgb.ru/for_patients/zhaloba/">Куда пожаловаться</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="http://www.kotlasgb.ru/for_patients/feedback/">Задайте вопрос доктору</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="http://www.kotlasgb.ru/adult_grafik/">График работы взрослой поликлиники</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="http://www.kotlasgb.ru/adult_grafik/uchastki.php">Участки взрослой поликлиники</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="http://www.kotlasgb.ru/child_grafik/">Грфик работы детской поликлиники</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" role="button" href="http://www.kotlasgb.ru/contacts/">Контакты</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" role="button" href="https://zdrav29.ru/">Регистратура онлайн</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="<?php echo Yii::getAlias('@web');?>">Самозапись</a>
            </li>
        </ul>
    </header>