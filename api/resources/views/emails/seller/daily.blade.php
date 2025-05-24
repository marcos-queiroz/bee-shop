@component('mail::message')
# Relatório de Vendas – {{ $date }}

Olá!

Você realizou **{{ $sales->count() }} vendas** no dia **{{ $date }}**.

- Valor total das vendas: **R$ {{ number_format($total, 2, ',', '.') }}**
- Comissão: **R$ {{ number_format($commission, 2, ',', '.') }}**

@component('mail::table')
| ID | Valor (R$) | Comissão (R$) | Data |
|----|------------|----------------|------|
@foreach($sales as $sale)
| {{ $sale->id }} | {{ number_format($sale->amount, 2, ',', '.') }} | {{ number_format($sale->commission, 2, ',', '.') }} | {{ $sale->sale_date->format('d/m/Y') }} |
@endforeach
@endcomponent

Obrigado,<br>
Sistema de Vendas
@endcomponent
