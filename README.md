# Решение первой задачи

Важно учесть что вопросы задают по очереди и на каждый вопрос отвечает каждый человек, значит лгуны будут голосовать **"ДА"** дважды. То есть если человека спрашивают любит ли он что-то он будет говорить **"ДА"**, **"НЕТ"**, **"НЕТ"** - если он из категории тех кто гвоорит правду, ну а если он из категории тех кто лжет он будет говорить примерно следующее: **"ДА"**, **"ДА"**, **"НЕТ"**. **90** это сумма голосов за макароны, пельмени и вареники, с учетом того что ни кто не врет. **110** это сумма голосов лгунов и честных голосовавших за макароны, пельмени и вареники. Вот мы из **110** (лгуны + честные) вычитаем **90** (честные) и получаем лгунов **20**.

### Есть и **визуальный** способ решения этой задачи, для этого нарисуем таблицу:

Если бы... | Макароны       | Пельмени       | Вареники       | Сумма
-----------|----------------|----------------|----------------|---------
Правду     | a + b = ?      | c + d = ?      | e + f = ?      | 90
Имеем      | a + d + f = 45 | c + b + f = 35 | e + b + d = 30 | 110

***Рассмотрим для начала первую линию, в ней лгуны не лгут, а говорят правду как и честные, но они все равно разделены.***

Где **`a`** это те кто проголосовали **"ДА"** для макарон, **`b`** кто проголосовали **"НЕТ"** для макарон (их сумма **`a + b`** как раз является настоящим показанием), **`c`**, **`d`** и **`e`**, **`f`** соотвецственно к пельменям и вареникам. А сумма всех голосов должна равняться количеству голосующих - **`90`**.

***На второй линии таблицы результат что мы имеем в задаче, когда у нас лгуны начинают врать***

Если подумать то станет понятно почему формулы записанны именно так. Ведь когда у человека спрашивают "Ваше любимое блюдо - макароны?", то если он из категории что говорят правду, скажет **"ДА"**, а на все другие вопросы скажет **"НЕТ"**, по этому голоса таких людей будут учтены (приплюсованы) только для макарон. Лгуны, которые любят макароны (**`b`**) должны будут ответить **"НЕТ"**, ведь они всегда лгут, а значит если они любят макароны то должны сказать что они их не любят (соврать), но они скажут **"ДА"** для пельменей и вареников, там мы и плюсум **`b`**. Голоса **`d`** и **`f`** приплюсованы там так как те лгуны по той эе логике должны соврать и сказать **"ДА"** для макарон. А равно это **`45`** по условию задачи. **Это мы разобрали формулу a + d + f = 45. Другие работают анологично.**

Приведем таблицу в две формулы:

**`a + b + c + d + e + f = 90`** и `a + 2b + c + 2d + e + 2f = 110`

Вычтем из нижней формулы вернюю и получим следующее:

a + 2b + c + 2d + e + 2f - a - b - c - d - e - f = `b + d + f`

110 - 90 = `20`

Значит `b + d + f = 20`, а это и есть наши лгуны.

Команда       | Описание
---------------|-----------
!hh            | Выводит список команд
!инфо          | Выводит способ связи с создателем
!переписка     | Выводит последние 10 сообщений из вк (бота). Не отображает медиа (фото, видео, голосовые, стикеры и т.д.)
!переписка     | Выводит последние 10 сообщений
* !инфоинфои   | Выводит последние 10 сообщений


Первый абзац
***
Второй абзац

h1 заголовок первого уровня
=====================

h2 заголовок второго уровня
-----------------------------------

простой текст

### h3 заголовок третьего уровня

#### h4 заголовок четвёртого уровня

##### h5 заголовок пятого уровня

###### h6 заголовок шестого уровня

[Мой сайт](https://google.com)

**Жирный шрифт**

***Наклонный жирный***

`выделенные слова`

    dir /fonts
    dir /images
    dir /js

```html
<meta name="viewport" content="width=device-width, initial-scale=1.0">
```

```scss /* или css */
@import "bower_components/tree-normalize/generic.normalize";
h1 {
 font-size:1.5em;
 font-weight: 300;
}
```

> Текст
> 
> Продолжение текста выделенного блока
> Завершение текста

Название файла        | Содержание файла
----------------------|----------------------
style.css             | Пустой файл каскадной таблицы стилей, в который производится сбока необходимых стилей
reset.css             | Reset CSS от Эрика Мейера
normlize.css          | Нормалайзер CSS от Nicolas Gallagher
block.css             | Основные стили блоков системы
addition.css          | Дополнительные стили
fontasme.css          | Стили иконочного шрифта
layout.css            | Основные стили, применительно к определённому сайту
lightox.css           | Стили лайтбокса, если таковой используется
!infewfdex.hteemlf    | Индексный файл для проверки вносимых изменений
[asd](test)           | test

* Пункт 1
* Пункт 2
* Пункт 3

1. Пункт 1
2. Пункт 2
3. Пункт 3

_наклонный_ _шрифт_ _наклонный__шрифт_

![screenshot of sample](http://webdesign.ru.net/images/Heydon_min.jpg)
