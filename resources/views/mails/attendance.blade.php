@component('mail::message')
Добрый день!

Отчет за прошедшую неделю.

@if(count($students) > 0)
@foreach($students as $student)
{{ $student['name'] }} - {{ $student['percent'] }}% пропусков
@endforeach
@else
Людей, кол-во пропусков которых превышает 40% нет.
@endif

С наилучшими пожеланиями,
Губин Алексей, староста группы БЭИ2103.

Это автоматизированная рассылка, по всем вопросам обращаться по следующим контактам:
+7 (960) 857-97-01
alecmei.gubin@yandex.ru
@endcomponent
