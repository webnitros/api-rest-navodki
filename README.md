# REST API сайта NAVODKI.RU (zakupki.gov.ru)

API позволяет интегрировать данные закупок из ЕИС (zakupki.gov.ru) 

Теперь вы можете:
* получить доступ к тендер за прошедшие сутки c сайта закупок zakupki.gov.ru; 
* следить за изменение статуса по дате обновления;
* получить информацию о закупке по номеру закупки
* использовать ключевые запрос для поиска информации

Бэта 2.0


## Демонстрация API закупок

Вернет json массив с данным по закупкам

- закупки
[https://navodki.ru/api/tenders](https://navodki.ru/api/tenders)

- контракты
[http://dev.navodki.ru/api/contracts](http://dev.navodki.ru/api/contracts)


> В тестовом доступе некоторые поля зашифрован "******"
> Список полей шифрованых полей: num - номер закупки,url_oos - ссылка на площадку, status - статус закупки 

--- 

## Содержание 
  
* [Получение закупок через API ](#user-content-service)    
* [Список поддерживаемых параметров](#user-content-params)    
* [Управление списками](#user-content-lis)    
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



<a name="user-content-service"></a>
## Получение закупок через API

API возвращает данные в формате JSON.
Метод запроса **GET**

> Для отправки запросов на сервер вы можете использовать класс **RestClient**

### Обязательные параметры 

|  Параметр     | Описание   | 
|:-----------------|:--------------|
|  access_token    |  API-ключ     |
|  client_secret   |  Секретный ключ     |


> для получения ключей свяжитесь с нами по email **info@navodki.ru**

---

### Пример 

```php
<?php
include dirname(__FILE__) . '/RestClient.class.php';

$api = new RestClient(array(
    'access_token' => 'c490a15ccdb2fec588e50cc86cea56753baf74a7',
    'client_secret' => '27918041a7dd4a9ce39a60c442400de7da9343e0',
));

$results = $api->get('tenders', 
    array(
        'print' => 1, 
        'limit' => 100, 
        'categories' => 381,382,728
    )
);
if ($results->info->http_code == 200) {
    
    foreach ($results->response as $result) {

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

#### Пример успешного ответа:

```json
{
    "total":"17",
    "results":[
      {
          "num": 31705899311,
          "tid": 14754476,
          "uri": "https://navodki.ru/tenders/14754476",
          "url_oos": "http://zakupki.gov.ru/epz/order/notice/ea44/view/common-info.html?regNumber=0137300043318000065",
          "name": "Поставка канцелярских товаров",
          "subject": "Поставка канцелярских товаров",
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
|  tid          |  `int`            | true            | [id тендера](#user-content-tid)                                             | 
|  uri          |  `string`         | true            | Ссылка на карточку тендера                                                       | 
|  url_oos      |  `string`         | false           | Ссылка на карточку тендера на сайте zakupki.gov.ru                                                       | 
|  name         |   `string`        | false           | Наименование закупки                                              | 
|  subject      |  `string`         | false           | Наименование темы для 223 закона (может быть пустым или совпадать с name)                              |  
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

> проверить как работает поискова строка проще всего на нашем сайте [https://navodki.ru/tenders/](https://navodki.ru/tenders/)




<a name="user-content-num"></a>
### Номер закупки присвоеный ЕИС

Выборка по уникальному номеру с гос закупок ЕИС

```php
https://navodki.ru/api/tenders?num=31705899311
```


<a name="user-content-tid"></a>
### ID тендер

Выборка по id. Системный индитификатор записи. Остается не изменным при изменении редакций закупок.

```php
https://navodki.ru/api/tenders?tid=14448486
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
