@component('mail::message')
# Relatório Geral – {{ $date }}

Olá administrador,

Resumo de todas as vendas realizadas no dia **{{ $date }}**:

- Total geral de vendas: **R$ {{ number_format($total, 2, ',', '.') }}**
- Quantidade de vendas: **{{ $sales->count() }}**

@component('mail::table')
| ID | Vendedor | Valor (R$) | Comissão (R$) | Data |
|----|----------|------------|----------------|------|
@foreach($sales as $sale)
| {{ $sale->id }} | {{ $sale->seller->name }} | {{ number_format($sale->amount, 2, ',', '.') }} | {{ number_format($sale->commission, 2, ',', '.') }} | {{ $sale->sale_date->format('d/m/Y') }} |
@endforeach
@endcomponent

Atenciosamente,<br>
Sistema de Vendas
@endcomponent
