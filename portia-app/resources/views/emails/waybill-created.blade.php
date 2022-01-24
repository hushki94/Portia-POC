@component('mail::message')
# Introduction

Congratulation, your order has been placed and we are processing your order.



@component('mail::button', ['url' =>  $label ])
Print Your Label
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
