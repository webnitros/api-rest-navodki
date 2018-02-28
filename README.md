# REST API  сайта NAVODKI.RU

API позволяет интегрировать данные закупок из ЕИС (zakupki.gov.ru) 

Теперь вы можете:
* получить доступ к тендер за прошедшие сутки c сайта закупок zakupki.gov.ru; 
* следить за изменение статуса по дате обновления;
* получить информацию о закупке по номеру закупки




## Получение закупок через API 

API возвращает данные в формате JSON.
Метод запроса **GET**

### Отправка запроса

Для отправки запросов на сервер вы можете использовать класс **RestClient**


|  Параметр     | Описание   | 
|:-----------------|:--------------|
|  access_token    |  API-ключ     |
|  client_secret   |  Секретный ключ     |

> для получение доступа пишете на email **info@navodki.ru**
> тестовый доступ с ограничениями уже прописан в примере

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


|  Параметр                     | Описание  | 
|:------------------------------|:--------------|
|  $results->info               | Параметры запрос и коды ответа   |
|  $results->info->http_code    | Код ответа с сервера   |
|  $results->response           | Содержит тело ответа   |




### Управление списком

В системе установлен максимальный лимит на **10000**. То есть дальше чем page=**1000** указать невозможно.
К примеру если вы установили параметры выборки где количество результов превышает 10000 то необходимо выставить больше ограничений (самое простое это ограничить период публикации закупок *published=20-02-2018,21-02-2018*).


|  Параметр     | По умолчанию | Тип данных   | Доступные значения      | Описание            |
|:--------------|:--------------|:-----------:|------------------------:|--------------------:|
|  limit        |  10           |  `int`      |  От 0 до 100            |   Количество показываемых тендеров   |
|  print        |  false        |  `int`      |  1            |   Распечатать ответ в читаемом виде   |
|  page         |  1            |  `int`      |  От 1 до 1000           | номер страницы 
|  sortby       |  updatedon    |  `string`   | *  | сортировка по полю|
|  sortdir      |  DESC         |  `int`      |  DESC,ASC      | порядок сортировки |



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

### Формат данных в ответе

В случае успешнах, код ответа будет со статусом **200 OK**.

```
https://navodki.ru/api/tenders?tid=14448486&platform_id=1,3&categories=232,232
```

Пример успешного ответа:
```json
{
    "total":"17",
    "results":[
      {
          "num": 31705899311,
          "tid": 14754476,
          "uri": "https://navodki.ru/tenders/14754476",
          "url_oos": "https://navodki.ru/tenders/14754476?redirect=oos",
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


### Управление поисковыми запросами

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



---




### Список поддерживаемых параметров

На данный момент поддерживаются следующие параметры:


|  Поле         | Тип данных        | Фильтры         | Название |
|:--------------|:------------------|:----------------|:----------------:|
|  num          |  `varchard`       | true            | [Номер закупки присвоенный ЕИС](#user-content-num)                                             | 
|  tid          |  `int`            | true            | [id тендера](#user-content-tid)                                             | 
|  uri          |  `string`         | true            | Ссылка на карточку тендера                                                       | 
|  url_oos      |  `string`         | false           | Ссылка на карточку тендера на сайте zakupki.gov.ru                                                       | 
|  name         |   `string`        | false           | Наименование закупки                                              | 
|  subject      |  `string`         | false           | Наименование темы (для 223 закона)                              |  
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

Если по каким-либо произошла ошибка, ответ будет иметь статус **400 Bad Request**, а тело ответа будет содержать следующее:

```javascript
{ "message": "Not Enough Data" }
```



## Методы для работы с типами транспорта и кузова


<a name="user-content-num"></a>
### Номер закупки присвоеный ЕИС

Выборка по уникальному номеру с гос закупок ЕИС

```php
https://navodki.ru/api/tenders?num=31705899311
```


<a name="user-content-tid"></a>
### ID тендер

Выборка по id
```php
https://navodki.ru/api/tenders?tid=14448486
```


<a name="user-content-status"></a>
### Статус

Выборка по статусу

```
https://navodki.ru/api/tenders?status=2,3,4,6
```
  
Список значений: 

```javascript
[
    { 
      id: 2,
      name: "Работа комиссии",  
    },
    { 
      id: 3,
      name: "Закупка отменена",  
    },
    { 
      id: 4,
      name: "Закупка завершена",  
    },
    { 
      id: 6,
      name: "Идет прием заявок",  
    },
]
```

<a name="user-content-platform_id"></a>
### ID закона

Выборка тендеров по закону

```
https://navodki.ru/api/tenders?platform_id=1,3
```
  
Список значений: 

```javascript
[
    { 
      id: 1,
      name: "44 закон",  
    },
    { 
      id: 3,
      name: "223 закон",  
    },
]
```


<a name="user-content-date"></a>
### Выборка дате для полей (published,updatedon,start_date,end_date)

Обе даты устанавливать обязательно

```
Формат ввода данных: "d-m-Y"
Минимальное значение: "01.01.2016"
Максимальное значение: "текущая дата"

https://navodki.ru/api/tenders?updatedon=07-02-2018,20-02-2018
```


<a name="user-content-price"></a>
### Выборка цене для полей (max_price,a_price,c_price)

Оба значения обязательны.

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

Справочник 
https://navodki.ru/api/categories?print=1
```


<a name="user-content-regions"></a>
### Регион

Выборка данных по региону закупок заказчика.

```
Разделитель: ","
Формат ввода данных: "int"

https://navodki.ru/api/tenders?regions=99,33,34,35,38,39,43,48,50

Справочник 
https://navodki.ru/api/regions?print=1
```


<a name="user-content-etp_id"></a>
### Площадка

Выборка данных по площадке размещения закупки.

```
Разделитель: ","
Формат ввода данных: "int"

https://navodki.ru/api/tenders?etp=9,7,65,121,2,63,117

Справочник 
https://navodki.ru/api/etp?print=1
```

<a name="user-content-pw"></a>
### Способу определения поставщика

Выборка данных по способу определения поставщика

```
Разделитель: ","
Формат ввода данных: "int"

https://navodki.ru/api/tenders?pw=212,3512

Справочник 
https://navodki.ru/api/pw?print=1
```
