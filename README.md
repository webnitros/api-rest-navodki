# REST API сайта NAVODKI.RU (zakupki.gov.ru)

API позволяет интегрировать данные закупок из ЕИС (zakupki.gov.ru) 

Теперь вы можете:
* получить доступ к **закупкам** c сайта закупок zakupki.gov.ru; 
* получить доступ к **контрактам**  c сайта закупок zakupki.gov.ru; 
* следить за изменение статуса по дате обновления;
* получить информацию о закупке по номеру закупки
* использовать ключевые запрос для поиска информации
* используя контракты получать победителей заключивших контракт

Бэта 2.0


## Демонстрация API закупок

Доступная информаци по:

- закупки
[https://navodki.ru/api/tenders](https://navodki.ru/api/tenders)
[https://navodki.ru/api/tenders/14966149](http://dev.navodki.ru/api/tenders/14966149)

- контракты
[https://navodki.ru/api/contracts](https://navodki.ru/api/contracts)
[https://navodki.ru/api/contracts/42060683](https://navodki.ru/api/contracts/42060683)



> В тестовом режиме некоторые поля зашифрован "******".   
> Список шифрованых полей: num,number,url_tenders_oos,url_contracts_oos,status,status_name,INN.  
> [получение полного доступа](#user-content-service)

```
* Наличие ссылки на сайт navodki.ru с гиперссылкой на страницу https://navodki.ru,
не закрытой для индексации поисковыми системами, является
обязательным требованием для использования сервиса в тестовом режиме.
```


--- 

## Содержание 
  
* [Получение закупок через API ](#user-content-service)    
* [Список поддерживаемых параметров](#user-content-params)    
* [Пример ответа закупки](#user-content-tenders)    
* [Пример ответа контракты](#user-content-contracts)    
* [Управление списками](#user-content-list)    
* [Коллекция филтров](#user-content-filters)
    - [Выборка по ключевым словам](#user-content-query)   
    - [Выборка по номеру закупки](#user-content-num)    
    - [Выборка по ID](#user-content-tid)    
    - [Выборка по статусу](#user-content-status)    
    - [Выборка по цене](#user-content-price)    
    - [Выборка по дате](#user-content-date)    
    - [Выборка по заказчику](#user-content-customers)    
    - [Выборка по отрасли](#user-content-categories)    
    - [Выборка по региону](#user-content-regions)    
    - [Выборка по площадке](#user-content-etp_id)    
    - [Выборка по способу определения поставщика](#user-content-pw)    
* [Справочники](#user-content-directory)  


---

### Пример запроса через класс RestClient

```php
<?php
include dirname(__FILE__) . '/RestClient.class.php';

$api = new RestClient(array(
    'access_token' => 'c490a15ccdb2fec588e50cc86cea56753baf74a7',
    'client_secret' => '27918041a7dd4a9ce39a60c442400de7da9343e0',
));

$response = $api->get('tenders', 
    array(
        'print' => 1, 
        'limit' => 100, 
        'categories' => 381,382,728
    )
);
if ($response->info->http_code == 200) {
    $response = $response->decode_response();
    foreach ($results->results as $result) {

        echo '<pre>';
        print_r($result); die;
        
    }
} else {
    echo '<pre>'; 
    print_r($results->response);
    
    die('Error');
}
```


---


<a name="user-content-tenders"></a>
#### Пример успешного ответа по закупкам:

```json
{
    "total":"17",
    "results":[
      {
          "id": 14754476,
          "num": 31705899311,
          "uri": "https://navodki.ru/tenders/14754476",
          "url_tenders_oos": "http://zakupki.gov.ru/epz/order/notice/ea44/view/common-info.html?regNumber=0137300043318000065",
          "name": "Поставка канцелярских товаров",
          "subject_223": "Поставка канцелярских товаров",
          "status": 2,
          "status_name": "Работа комиссии",
          "platform_id": 3,
          "platform_name": "223 закон",
          "published": "12.02.2018",
          "updatedon": "19.02.2018",
          "start_date": 0,
          "end_date": "2018-02-19 14:00:00",
          "max_price": "135 000",
          "a_price": 0,
          "c_price": 0,
          "customers": [
              {
                  "name": "ОБЩЕСТВО С ОГРАНИЧЕННОЙ ОТВЕТСТВЕННОСТЬЮ ЭНЕРГОСЕРВИС КОМИ",
                  "INN": "1101068261"
              }
          ],
          "categories": [
              "152",
              "184"
          ],
          "regions": [
              {
                  "id": 12,
                  "path": "komi_resp",
                  "title": "Республика Коми"
              }
          ],
          "etp_id": 63,
          "etp_name": "РТС-тендер",
          "pw_id": 33329,
          "pw_name": "Запрос котировок в электронной форме",
          "currency_code": "RUB",
          "currency_digitalCode": "643",
          "currency_name": "Российский рубль",
          "attach": true
      }
    ]
}
```


<a name="user-content-contracts"></a>
#### Пример успешного ответа по контрактам:

```json
{
    "total":"17",
    "results":[
          {
          "id": 76023,
          "uid": 1828618,
          "num": "31503055368",
          "number": "50274149764150001220000",
          "uri": "https://navodki.ru/contracts/76023",
          "url_tenders_oos": "http://zakupki.gov.ru/223/purchase/public/purchase/info/common-info.html?regNumber=31503055368",
          "url_contracts_oos": "http://zakupki.gov.ru/epz/contract/contractCard/common-info.html?reestrNumber=3362701856316000003",
          "name": "Поставка продовольственных товаров. Конфеты в ассортименте",
          "name_contract_223": "987",
          "status": 9,
          "status_name": "Исполнение завершено",
          "platform_id": 3,
          "platform_name": "223 закон",
          "published": "30.06.2016",
          "updatedon": "22.02.2018",
          "start_date": "2015-12-29 00:00:00",
          "end_date": "2016-11-29 00:00:00",
          "max_price": "288 000,00",
          "hassub": true,
          "customers": [
              {
                  "name": "МУНИЦИПАЛЬНОЕ АВТОНОМНОЕ УЧРЕЖДЕНИЕ &quot;ЦЕНТР ДЕТСКОГО И ДИЕТИЧЕСКОГО ПИТАНИЯ&quot; ГОРОДСКОГО ОКРУГА ГОРОД УФА РЕСПУБЛИКИ БАШКОРТОСТАН",
                  "INN": "0274149764"
              }
          ],
          "suppliers": [
              {
                  "name": "Минязов Рустем Назирович",
                  "INN": "11111026412420110"
              }
          ],
          "contractDate": "22.02.2018",
          "placing_code": "400002",
          "placing_name": "Иной способ закупки, предусмотренный правовым актом заказчика, указанным в части 1 статьи 2 Федерального закона)",
          "approveDate": "22.02.2018",
          "categories": [
              "131"
          ],
          "currency": 1,
          "currency_name": "Российский рубль"
      }
    ]
}
```



#### Пример ответа с ошибкой

Тело письма всегда будет содержать такого вида ответ

```javascript
{
    "success": false,
    "message": "Unauthorized",
    "object": [],
    "code": 401
}
```

> Если использовать RestClient то можно дополнительно посмотреть код ошибки в ```$results->info->http_code```

---

<a name="user-content-list"></a>
### Управление списками

В системе установлен максимальный лимит на **10000**. То есть дальше чем page=**1000** указать невозможно.
К примеру если вы установили параметры выборки где количество результов превышает 10000 то необходимо выставить больше ограничений (самое простое это ограничить период публикации закупок *published=20-02-2018,21-02-2018*).


|  Параметр     | По умолчанию | Тип данных   | Доступные значения      | Описание            |
|:--------------|:--------------|:-----------:|------------------------:|--------------------:|
|  limit        |  10           |  `int`      |  От 0 до 100            |   Количество показываемых тендеров   |
|  print        |  0            |  `int`      |  0 или 1                |   Распечатать ответ в читаемом виде   |
|  page         |  1            |  `int`      |  От 1 до 1000           | номер страницы 
|  sortby       |  updatedon    |  `string`   | *  | сортировка по полю|
|  sortdir      |  DESC         |  `int`      |  DESC,ASC      | порядок сортировки |


*print=1 так же устанавливается если не указан access_token в заголовке*

#### Доступные поля для сортировки: 
relevance,name,published,updatedon,start_date,end_date,max_price,a_price,c_price


```
https://navodki.ru/api/tenders?sortby=updatedon
```


**relevance** - сортировка по совпадению используется в случае отправки поискового запроса в параметре **search**.

Вернет в порядке наибольшего совпадения

```
https://navodki.ru/api/tenders?search="услуги%20общественного%20питания"&sortby=relevance&sortdir=DESC&print=1
```

---


<a name="user-content-params"></a>
### Список поддерживаемых параметров

На данный момент поддерживаются следующие параметры:


|  Поле         | Тип данных        | Фильтры         | Название |
|:--------------|:------------------|:----------------|:----------------:|
|  num          |  `varchard`       | true            | [Номер закупки присвоенный ЕИС](#user-content-num)                                             | 
|  id          |  `int`            | true            | [id записи](#user-content-id)                                             | 
|  uri          |  `string`         | true            | Ссылка на карточку тендера                                                       | 
|  url_tenders_oos |  `string`      | false           | Ссылка на карточку тендера на сайте zakupki.gov.ru                                                       | 
|  name         |   `string`        | false           | Наименование закупки                                              | 
|  subject_223      |  `string`         | false           | Наименование темы для 223 закона (может быть пустым или совпадать с name)                              |  
|  status       |  `int`            | true            |[Статус](#user-content-status)                              |  
|  status_name  |  `string`         | false           |Наименование статуса                                   |  
|  platform_id  |  `int`            | true            |[Id закона](#user-content-platform_id)                 |  
|  platform_name|  `string`         | false           |Наименование закона                                  |  
|  published    |  `date`           | true            | [Дата публикации](#user-content-date)                     |  
|  updatedon    |  `date`           | true            | [Дата обновления](#user-content-date)                                 |  
|  start_date   |  `date`           | true            | [Дата начала подачи заявок](#user-content-date)                      |  
|  end_date     |  `date`           | true            | [Дата окончания подачи заявок](#user-content-date)                     |  
|  max_price    |  `float`          | true            |   [Начальная (максимальная) цена](#user-content-price)                 |  
|  a_price      |  `float`          | true            |   [Обеспечение заявки](#user-content-price)                               |  
|  c_price      |  `float`          | true            |   [Обеспечение контракта](#user-content-price)                            |  
|  customers    |  `array`          | true            |   [Заказчики](#user-content-customers)                                  |  
|  categories   |  `array`          | true            |   [Отрасли](#user-content-categories)                                  |  
|  regions      |  `array`          | true            |   [Регион](#user-content-regions)                                         |  
|  etp_id       |  `int`            | true            |   [Площадка](#user-content-etp_id)                                         |  
|  etp_name     |  `string`         | false           |   Наименование площадки                       |  
|  pw_id        |  `int`            | true            | [Способ определения поставщика](#user-content-pw_id)                      |  
|  pw_name      |  `string`         | false           |  Наименование способа определения поставщика                             | 
|  currency_name|  `int`            | false           | Наименование валюты                                    |  
|  currency_code|  `string`         | false           | Код валюты                                       |  
|  currency_digitalCode|  `int`     | false           | Цифровая валюта                           |  
|  attach       |  `boolean`        | true            |Метка о наличии документации                            |  
|  **Для контрактов**               |  
|  number       |  `string`         | true            | Номер контракта                           |  
|  name_contract_223       |  `string`  | true            | Наименование контракта (для 223)                        |  
|  hassub       |  `boolean`        | true            | Метка о том что контракт является субподрядом                           |  
|  suppliers       |  `array`       | true            | Генподрядчики заключившие контракт                          |  
|  contractDate       |  `date`     | true            | Дата заключения контракта                         |  
|  approveDate       |  `date`     | true            | Дата завершения исполнения контракта                         |  
|  placing_code       |  `string`     | true            | Код способа определения поставщика                        |  
|  placing_name       |  `string`     | true            | Наименование кода способа определения поставщика                       |  
|  url_contracts_oos      |  `string`         | false           | Ссылка на карточку контракта на сайте zakupki.gov.ru       


Расшифровка параметров:

- *total* - общее количество найденных результатов.
- *results* - массив данных


<a name="user-content-filters"></a>
## Коллекция филтров


<a name="user-content-query"></a>
### Выборка по ключевым словам

Можно использовать поисковые запросы для выборки данных параметр для поиска **query**

```
Вернет список тендоров по ключевому запросу
https://navodki.ru/api/tenders?query=услуги общественного питания&print=1

Список тендеров по точному совпадению поискового запроса и сортивровкой по наибольшим совпадениям
https://navodki.ru/api/tenders?search="услуги общественного питания"&sortby=relevance&sortdir=DESC&print=1

Минусовка слов используя "-"
https://navodki.ru/api/tenders?search="услуги -общественного питания"&sortby=relevance&sortdir=DESC&print=1

```

> Минимальная длина запрос 3 символа  
> проверить как работает поискова строка проще всего на нашем сайте [https://navodki.ru/tenders/](https://navodki.ru/tenders/)




<a name="user-content-num"></a>
### Номер закупки присвоеный ЕИС

Выборка по уникальному номеру с гос закупок ЕИС

```php
https://navodki.ru/api/tenders?num=31705899311
```


<a name="user-content-id"></a>
### ID записи

Выборка по id. Системный индитификатор записи. Остается не изменным при изменении редакций.

```php
https://navodki.ru/api/tenders/14448486
```


<a name="user-content-status"></a>
### Статус

Статус закупки определяется на основании способов определения поставщика. 

```
https://navodki.ru/api/tenders?status=2,3,4,6
```

<a name="user-content-platform_id"></a>
### ID закона

Закупки по 44 ФЗ и 223 ФЗ 

```
https://navodki.ru/api/tenders?platform_id=1,3
```


<a name="user-content-date"></a>
### Выборка по дате 

Обе даты устанавливать обязательно

> используется для полей published,updatedon,start_date,end_date

```
Разделитель: ","
Формат ввода данных: "d-m-Y"
Минимальное значение: "01-01-2016"
Максимальное значение: "текущая дата"

https://navodki.ru/api/tenders?updatedon=07-02-2018,20-02-2018
```


<a name="user-content-price"></a>
### Выборка цене для полей

Оба значения обязательны.

> используется для полей max_price,a_price,c_price

```
Разделитель: ","
Формат ввода данных: "int"
Минимальное значение: "0"
Максимальное значение: "1000000000000000"

https://navodki.ru/api/tenders?max_price=10000,10000000
```



<a name="user-content-customers"></a>
### Заказчики

У одной закупки может быть неограниченое количество заказчиков.
Для выборки заказчиков используется **ИНН организации**

```
Разделитель: ","
Формат ввода данных: "number"
Допустимые значения: "10 или 12 цифр"

https://navodki.ru/api/tenders?customers=7708697381
```

<a name="user-content-categories"></a>
### Отрасли

Выборка тендеров по отрасли закупки. Отрасль закупок привязана к справочнику ОКПД2 с помощью кода каждому тендеру назначается отрасль

```
Разделитель: ","
Формат ввода данных: "int"

https://navodki.ru/api/tenders?categories=244,380,245
```


<a name="user-content-regions"></a>
### Регион

Выборка данных по региону закупок заказчика.

```
Разделитель: ","
Формат ввода данных: "int"

https://navodki.ru/api/tenders?regions=99,33,34,35,38,39,43,48,50
```


<a name="user-content-etp_id"></a>
### Площадка

Выборка данных по площадке размещения закупки.
> только для тендеров

```
Разделитель: ","
Формат ввода данных: "int"

https://navodki.ru/api/tenders?etp=9,7,65,121,2,63,117
```

<a name="user-content-pw"></a>
### Способу определения поставщика

Выборка данных по способу определения поставщика

```
Разделитель: ","
Формат ввода данных: "int"

https://navodki.ru/api/tenders?pw=212,3512
```


<a name="user-content-directory"></a>
## Справочник

Данные для фильтрации списка тендеров
*id* справочника является значением для выборки

|  URL                                  | Описание                      |
|:--------------------------------------|:---------------------------|
|  https://navodki.ru/api/pw            |  Способы определения поставщика |
|  https://navodki.ru/api/etp           |  Площадки    |
|  https://navodki.ru/api/status        |  Статусы    |
|  https://navodki.ru/api/platform      |  Законов    |
|  https://navodki.ru/api/regions       |  Регионы    |
|  https://navodki.ru/api/categories    |  Отрасли    |




<a name="user-content-service"></a>
## Получение закупок через API

API возвращает данные в формате JSON.
Метод запроса **GET**

> Для отправки запросов на сервер вы можете использовать класс **RestClient**

### Авторизация в сервисе

Для получения полного доступа необходима авторизации с использованием **oauth2**

|  Параметр     | Описание   | 
|:-----------------|:--------------|
|  access_token    |  API-ключ     |
|  client_secret   |  Секретный ключ     |


> для получения ключей свяжитесь с нами по email **info@navodki.ru**