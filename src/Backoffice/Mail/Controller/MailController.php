<?php
namespace GameShop\Site\Backoffice\Mail\Controller;


use GameShop\Site\Backoffice\Mail\Service\MailService;
use GameShop\Site\General\Renderer;
use GameShop\Site\User\Service\SessionService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;

class MailController
{
    protected $router;
    protected $renderer;
    protected $sessionService;
    protected $mailService;

    public function __construct(
        Router $router,
        Renderer $renderer,
        SessionService $sessionService,
        MailService $mailService
    ) {
        $this->router = $router;
        $this->renderer = $renderer;
        $this->sessionService = $sessionService;
        $this->mailService = $mailService;
    }

    public function mailList(Request $request): Response
    {
        $this->sessionService->requireUserId($request->getSession());

        $this->mailService->send($this->getMessage());

        return $this->renderer->getHtmlResponse(
            '/backoffice/mail/mail_list.html'
        );
    }

    protected function getMessage(): \Swift_Message
    {
        return (new \Swift_Message())
            ->setFrom(['fasderq@gmail.com' => 'Alexander Gropyanov'])
            ->setTo(['fasderq@gmail.com'])
            ->addPart('<html> 
 <head>  
  <title>Заголовок страницы</title> 
  <!-- Подключаем JavaScript\'овый скрипт с названием current_script.js --> 
  
  <!-- Подключаем main.css --> 
  <style>
  /* звездочка означает, что стиль применяется ко всем html элементам */ 
* {margin: 0; padding: 0;} 
/* для html, body  устанавливаем ширину, высоту и цвет заднего фона */ 
html, body {width: 100%; height: 100%; background-color: #f0f0f0; } 
 
#form_A { 
  /* Абсолютное позиционирование - будем перемещать его сами */ 
  position: absolute; 
  /* Перемещаем направо и вниз на половину экрана */ 
  left: 50%; top: 50%; 
  /* Выставляем ширину и высоту блока */ 
  width: 300px;  height: 150px; 
  /* Перемещаем налево на половину ширину, и вверх - на половину высоту */ 
  margin-left: -150px; margin-top: -75px; 
  /* Устанавливаем цвет заднего фона в серый */ 
  background-color: #ccc; 
   
  /* Устанавливаем бордюры */ 
  border-left: 2px solid #999; border-top: 2px solid #999; 
  border-right: 2px solid #333; border-bottom: 2px solid #333; 
  /* Благодаря ним добиваемся 3D эффекта объемности */ 
} 
 
/* кнопка с классом button в блоке id=form_A */ 
#form_A .button { 
  /* устанавливаем задний фон в белый */ 
  background-color: #fff; 
  /* цвет текста - черный */ 
  color: #000; 
  /* стиль шрифта - жирный */ 
  font-weight: bold; 
  /* бордюр вокруг на 2 пиксела темного цвета */ 
  border: 2px solid #666; 
} 
 
/* стиль для кнопки, над которым стоит мышь */ 
#form_A .button:hover { 
  /* цвет заднего фона меняем на черный */ 
  background-color: #000; 
  /* цвет текста - на белый */ 
  color: #fff; 
  /* бордюр светлый */ 
  border: 2px solid #bbb; 
} 
 
#form_A .field { 
  /* цвет заднего фона - черный */ 
  background-color: #000; 
  /* цвет текста - белый*/ 
  color: #fff; 
  /* жирный */ 
  font-weight: bold; 
  /* однопиксельный бордюр темного цвета*/ 
  border: 1px solid #333; 
} 
 
#form_A .field:hover { 
  /* цвет заднего фона меняем на белый */ 
  background-color: #fff; 
  /* цвет текста - на черный */ 
  color: #000; 
  /* бордюр делаем по-светлее*/ 
  border: 1px solid #999; 
} 
</style> 
 </head> 
 <body> 
  <div id=\'form_A\'> 
  <form action=\'user_checking.php\' method=\'POST\'> 
   <input type=\'text\' value=\'Имя\' name=\'first_name\' class=\'field\' size=\'10\' /><br /> 
   <input type=\'text\' value=\'Фамилия\' name=\'last_name\' class=\'field\' size=\'15\' /><br /> 
   <br /> 
   <!-- Кнопка отправки данных формы на сервер --> 
   <input type=\'submit\' value=\'Проверить\' class=\'button\' name=\'check_button\'  
     onmouseover="check_fields(\'first_name\', \'last_name\'); " /> 
  </form> 
  </div> 
 </body> 
</html> ', 'text/html');

    }
}
