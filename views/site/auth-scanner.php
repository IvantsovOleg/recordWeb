<?php
/**
 * Created by PhpStorm.
 * User: Semenov
 * Date: 09.04.2019
 * Time: 9:57
 */

use yii\helpers\Url;

?>

    <div class="auth-scanner">
        <div class="auth_scanner__title">
            Пожалуйста поднесите бумажный полис ОМС к считывателю или вставьте пластиковый полис ОМС в считыватель
        </div>

        <hr class="primary">

        <div class="auth_scanner__status">
            <span class="message">Ожидание полиса</span>
        </div>

    </div>

<?php
$scannerInfo      = Url::to( [ 'reader/info' ] );
$scannerStart     = Url::to( [ 'reader/start' ] );
$scannerStatus    = Url::to( [ 'reader/status' ] );
$scannerPolicy    = Url::to( [ 'reader/get-data' ] );
$setSessionPolicy = Url::to( [ 'reader/set-policy' ] );

$returnUrl = Url::to( [ 'site/index' ] );

$successUrl = Url::to( [ 'site/main' ] );

$js = <<<JS
$(document).ready(function () {
    
    const message = document.querySelector('.auth_scanner__status > span.message');
    message.innerHTML = 'Ожидание полиса';
    
    axios.get('$scannerInfo')
        .then(info => {
            if (info && info.data) {
                axios.get('$scannerStatus').then(status => {
                    if (status && status.data.status === 'OK') {
                        axios.get('$scannerStart').then(start => {
                            if (start && start.data.status === 'OK') {
                                var time = setInterval(() => {
                                    axios.get('$scannerStatus').then(scanStatus => {
                                        if (scanStatus.data.status === 'OK') {
                                            axios.get('$scannerPolicy').then(policy => {
                                                 message.innerHTML = 'Считывание полиса';
                                                if (policy && policy.data) {
                                                    const policyData = policy.data;
                                                    clearInterval(time);
                                                    
                                                    axios.post('$setSessionPolicy', policyData)
                                                      .then(response => {
                                                          if (response.data.success) {
                                                               message.innerHTML = 'Пациент найден, Вы будете перенаравлены на страницу подтверждения';
                                                               clearInterval(time);
                                                                
                                                               setTimeout(() => {
                                                                    window.location.href = '$successUrl';
                                                               }, 2000);
                                                          } else {
                                                               message.innerHTML = response.data.user_error;
                                                               clearInterval(time);
                                                               
                                                               setTimeout(() => {
                                                                   window.location.href = '$returnUrl';
                                                                }, 3000);
                                                          }
                                                      });
                                                      console.log("Пользователь считан отправляем запрос для сохранения в сессию");
                                                } else {
                                                    message.innerHTML = 'Полис не найден';
                                                    clearInterval(time);
                                                    setTimeout(() => {
                                                         window.location.href = '$returnUrl';
                                                    }, 2000);
                                                }
                                            })
                                        }
                                    })
                                }, 200);
                            }
                        })
                    }
                })
            }
        })
        .catch(error => {
            console.log(error);
        });
});
JS;
$this->registerJs( $js );